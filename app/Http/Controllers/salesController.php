<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Sales;
use App\Helpers\AfghanCalendarHelper;
use App\Models\Contract;
use App\Models\CustomerType;
use Morilog\Jalali\Jalalian;

use App\Models\Tower;
use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;


class salesController extends Controller
{

    public function singlecustomersalescustomer($id)
    {
        $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
        $startOfMonth = $monthRange['start'];
        $endOfMonth = $monthRange['end'];    

        $sales = Sales::with(['tower', 'contract.customer', 'contract.product']) // Include related data
                    ->whereHas('contract', function ($query) use ($id) {
                        $query->where('contract_id', $id); // Filter by customer ID
                    })
                    ->whereBetween('date', [$startOfMonth, $endOfMonth]) // Filter by Gregorian start and end dates
                    ->get();

        return view('sales.sales', compact('sales'));
    }

    public function sales(Request $request)
    {
        $astart = $request->start_date;
        $aend = $request->end_date;
        $products = $request->input('product_id', []); // Retrieve selected product IDs (if any)
    
        // Initialize variables for start and end dates
        $start = null;
        $end = null;
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->start_date)->toDateString();
            $end = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->end_date)->toDateString();
        } else {
            $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
            $start = $monthRange['start'];
            $end = $monthRange['end'];
        }
    
        $sales = Sales::with(['tower', 'contract.customer', 'contract.product'])
            ->whereBetween('date', [$start, $end]); // Filter by date range
    
        if (!empty($products)) {
            $sales->whereHas('contract.product', function ($query) use ($products) {
                $query->whereIn('id', $products);
            });
        }
    
        $sales = $sales->get(); 
    
        $products = Contract::with('product')
            ->get()
            ->pluck('product')
            ->unique('id');
    
        return view('sales.sales', compact('sales', 'products', 'astart', 'aend'));
    }
           
    public function salesform()
    {
        $customers = Customers::whereHas('contract', function ($query) {
            $query->where('isActive', 1);
        })->with(['contract.product'])->get();
    
        $towers = Tower::with('product')->get();
        
        return view('sales.form', compact('towers', 'customers'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'date' => 'required',
            'contract_id' => 'required|exists:contracts,id', // Validate that the contract_id exists in the 'contracts' table
            'tower_id' => 'required|exists:towers,id', // Validate that the tower_id exists in the 'towers' table
            'amount' => 'required|numeric|min:0.1', // Validate that the amount is a numeric value and at least 0.1
            'details' => 'nullable|string', // Validate that details is a string and nullable
            'rate' => 'required|numeric|min:0.1', // Validate the rate, ensure it's a numeric value and at least 0.1
        ]);
        $jalaliDate = $request->input('date'); 
        $dateTimeParts = explode(' ', $jalaliDate); 
        $timeParts = date('H:i', strtotime($dateTimeParts[1] . ' ' . $dateTimeParts[2])); // Convert time to 24-hour format
        $jalaliDateIn24HourFormat = $dateTimeParts[0] . ' ' . $timeParts;
    
        $gregorianDate = Jalalian::fromFormat('Y/m/d H:i', $jalaliDateIn24HourFormat)->toCarbon();
        $formattedDate = $gregorianDate->format('Y-m-d H:i:s');
        Sales::create([
            'date' => $formattedDate,
            'contract_id' => $validated['contract_id'],
            'tower_id' => $validated['tower_id'],
            'amount' => $validated['amount'],
            'details' => $validated['details'],
            'rate' => $validated['rate'],
        ]);
        return redirect()->route('sales')->with('success', 'Sale Added Successfully!');
    }

    public function delete($request)
    {

        // dd($request);
        $sale = Sales::findOrFail($request);
        $sale->delete();
        return redirect()->route('sales')->with('success', 'Sale deleted successfully.');
    }

    public function singlecustomerinfo(Request $request, $id)
    {
        $customer = Customers::where('id', $id)
        ->with([
            'contract.product',
        ])->get();

        $types = CustomerType::all();
        return view('customers.customersinfo', compact('customer','types'));
    }
}
