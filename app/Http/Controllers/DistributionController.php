<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Employee;
use App\Models\Contract;
use App\Models\Tower;
use App\Helpers\AfghanCalendarHelper;
use App\Models\Product;
use Morilog\Jalali\CalendarUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    public function getAddDistributionForm(Request $request)
    {
        $distributerId = $request->input('distributer_id');

        // Fetch towers related to the distributer
        $towers = Tower::whereHas('distributers', function ($query) use ($distributerId) {
            $query->where('employee_id', $distributerId);
        })->get();

        $contracts = Contract::with(['customer','Product'])->get();
        
        // Return the form view with towers and contracts data
        return view('distribution.add-distribution-form', compact('towers', 'contracts'))->render();
    }
    public function adddestributionform(){
        $distributions = Distribution::with(['contract.customer', 'distributer', 'tower'])->get();
        // Fetch distributers and contracts for dropdowns
        $distributers = Employee::all(); // Assuming distributers are employees
        $contracts = Contract::with(['customer','Product'])->get();

        // Pass the data to the view
        return view('distribution.form', compact('distributions', 'distributers', 'contracts'));
    }


    public function index(Request $request)
    {
        if (isset($request->start_date) && isset($request->end_date)) {
            $afghaniStartDate=$request->start_date;
            $afghaniEndDate=$request->end_date;

            $start_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniStartDate)->toDateString();
            $end_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniEndDate)->toDateString();
 
        }else{

            $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
            $start_date = $monthRange['start'];
            $end_date = $monthRange['end'];   
           
        }
        // Fetch distribution data with relationships
        $distributions = Distribution::whereBetween('date', [$start_date, $end_date])
        ->with(['contract.customer', 'distributer', 'tower'])
        ->get();
        // Fetch distributers and contracts for dropdowns
        $distributers = Employee::all(); // Assuming distributers are employees
        $contracts = Contract::with(['customer','Product'])->get();
        $products = Product::all();

        // Pass the data to the view
        return view('distribution.distribution', compact('distributions', 'distributers', 'contracts','products'));
    }

    public function destroy($id)
    {
        // Find the distribution record
        $distribution = Distribution::find($id);

        // Delete the record
        $distribution->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Distribution record deleted successfully');
    }
    public function getContractsAndTowers(Request $request)
    {
        $distributerId = $request->input('distributer_id');
    
        // Fetch towers related to the distributer
        $towers = Tower::whereHas('distributers', function ($query) use ($distributerId) {
            $query->where('employee_id', $distributerId);
        })->get();
    
        // Fetch contracts related to the distributer (if applicable)
        $contracts = Contract::whereHas('distributer', function ($query) use ($distributerId) {
            $query->where('id', $distributerId);
        })->get();
    
        // Return the data as JSON
        return response()->json([
            'towers' => $towers,
            'contracts' => $contracts,
        ]);
    }
    public function addDistribution(Request $request)
    {
        $request->validate([
            'tower_id' => 'required|exists:towers,id',
            'contract_id' => 'required|exists:contracts,id',
            'distributer_id' => 'required|exists:employees,id',
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Distribution::create($request->all());

        return response()->json(['message' => 'Distribution added successfully!']);
    }
    public function getTowers(Request $request)
    {
        $distributerId = $request->input('distributer_id');
    
        // Fetch towers related to the distributer
        $towers = Tower::whereHas('distributers', function ($query) use ($distributerId) {
            $query->where('employee_id', $distributerId);
        })->get();
    
        // Fetch meter reading information and total amount for each tower (for today's date)
        $towersWithMeterReading = $towers->map(function ($tower) {
            $meterReading = $this->getMeterReadingForTower($tower->id);
    
            // Calculate total amount from the distribution table for this tower (today's date only)
            $totalAmount = Distribution::where('tower_id', $tower->id)
                ->whereDate('date', now()->toDateString()) // Filter for today's date
                ->sum('amount');
    
            $tower->meter_reading = $meterReading;
            $tower->total_amount = $totalAmount; // Add total amount to the tower object
            return $tower;
        });
    
        // Return the view with towers data
        return view('distribution.towers-list', compact('towersWithMeterReading'))->render();
    }
    public function getTodaysDistributions(Request $request)
    {
        $distributerId = $request->input('distributer_id');

        // Fetch today's distribution records for the selected distributer
        $distributions = Distribution::where('distributer_id', $distributerId)
            ->whereDate('date', now()->toDateString()) // Filter for today's date
            ->with(['contract.customer', 'distributer', 'tower'])
            ->get();

        // Return the view with today's distribution data
        return view('distribution.todays-distributions-list', compact('distributions'))->render();
    }

    private function getMeterReadingForTower($towerId)
    {
        $sql = "
            SELECT 
                serial_numbers.id AS sid,
                serial_numbers.serial AS serial_number,
                towers.serial AS tower_number,
                serial_numbers.tower_id,
                towers.name,
                towers.product_id,
                products.product_name,
                serial_numbers.date,
                (
                    SELECT SUM(amount)
                    FROM sales
                    WHERE sales.tower_id = serial_numbers.tower_id
                    AND DATE(sales.date) = DATE(serial_numbers.date)
                ) AS total_amount
            FROM serial_numbers
            INNER JOIN towers ON serial_numbers.tower_id = towers.id
            INNER JOIN products ON towers.product_id = products.id
            WHERE (
                SELECT COUNT(*)
                FROM serial_numbers AS sn2
                WHERE sn2.tower_id = serial_numbers.tower_id
                AND sn2.date >= serial_numbers.date
            ) <= 2
            AND serial_numbers.tower_id = ?
            ORDER BY serial_numbers.tower_id, serial_numbers.date DESC;
        ";

        $result = DB::select($sql, [$towerId]);

        $finalResults = collect($result)->groupBy('tower_id')->map(function ($records) {
            $lastRecord = $records->first();

            if ($records->count() == 2) {
                $secondLastRecord = $records->get(1);
                $petrolSold = $lastRecord->serial_number - $secondLastRecord->serial_number;
                $lastRecord->petrol_sold = number_format($petrolSold, 2, '.', '');
            } else {
                $lastRecord->petrol_sold = 0;
            }

            return $lastRecord;
        });

        return $finalResults->values();
    }

    public function store(Request $request)
    {
        $request->validate([
            'distributer_id' => 'required|exists:employees,id',
            'tower_id' => 'required|exists:towers,id',
            'contract_id' => 'required|exists:contracts,id',
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Distribution::create($request->all());

        return redirect()->route('distribution')->with('success', 'Distribution added successfully!');
    }

    public function getContracts(Request $request)
    {
        $productId = $request->input('product_id');

        // Fetch contracts related to the selected product
        $contracts = Contract::where('product_id', $productId)
            ->with(['customer', 'product'])
            ->get();

        return response()->json($contracts);
    }

    public function edit($id)
    {
        $distribution = Distribution::with(['contract.customer', 'distributer', 'tower'])->findOrFail($id);
        $distributers = Employee::all();
        $towers = Tower::all();
        $contracts = Contract::all();

        return view('distribution.edit-modal', compact('distribution', 'distributers', 'towers', 'contracts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'distributer_id' => 'required|exists:employees,id',
            'tower_id' => 'required|exists:towers,id',
            'contract_id' => 'required|exists:contracts,id',
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $distribution = Distribution::findOrFail($id);
        $distribution->update($request->all());

        return redirect()->route('distribution.index')->with('success', 'Distribution updated successfully!');
    }
}