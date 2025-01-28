<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\Tower;
use App\Models\Product;
use App\Models\Sales;
use Morilog\Jalali\CalendarUtils;
use Illuminate\Http\Request;

class towerController extends Controller
{
    public function seeksale(Request $request, $id)
    {
        $astart_date = $request->start_date;
        $aend_date = $request->end_date;
        // Default values for the date range
        $start_date = null;
        $end_date = null;
        $tower_id = $id; // Use the $id from the route as the fallback tower ID
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Convert start and end dates to Gregorian format
            $start_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->start_date)->toDateString();
            $end_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->end_date)->toDateString();
    
            // Override tower_id from the form request if it exists
            $tower_id = $request->tower_id ?? $id; // Fallback to $id if not provided
        } else {
            // Get the current month range
            $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
            $start_date = $monthRange['start'];
            $end_date = $monthRange['end'];
        }
    
        // Fetch sales data based on tower_id and date range
        $tower = Sales::with(['tower', 'contract.customer', 'contract.product'])
            ->where('tower_id', $tower_id) // Ensure tower_id is used
            ->whereBetween('date', [$start_date, $end_date])
            ->get();
    
        // Pass $id to the view explicitly
        
        return view('towers.seeksale', compact('tower', 'id', 'astart_date', 'aend_date'));
    }
    
    public function towerform()
    {
           
        $products = Product::select('id', 'product_name')
        ->orderBy('product_name', 'asc')
        ->get();

        return view('towers.form',compact('products'));
    }

    public function towers()
    {
        // Get the current month and year
        $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
        $startOfMonth = $monthRange['start'];
        $endOfMonth = $monthRange['end'];   
    
        // Fetch towers with their total sales for the current month
        $towers = Tower::with('sales') // Ensure you define the 'sales' relationship in the Tower model
        ->withSum(['sales' => function ($query) use ($startOfMonth, $endOfMonth) {
            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
        }], 'amount')
        ->get();
        // Pass the data to the view
        // dd($towers);
        return view('towers.tower', compact('towers'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial' => 'required|numeric|unique:towers,serial',
            'product_id' => 'required|exists:products,id',
            'details' => 'nullable|string|max:255'
        ]);
        Tower::create($request->all());
        return redirect()->route('towers')->with('success', 'Tower Added successfully');

    }

    public function edit($id)
    {
        $products = Product::select('id', 'product_name')
        ->orderBy('product_name', 'asc')
        ->get();

        $towers = Tower::findOrFail($id);
        return view('towers.form',compact('towers','products'));
    }
    
    public function destroy($id)
    {
        $tower = Tower::find($id);
        if ($tower) {
            $tower->delete();
        }
        return redirect()->route('towers')->with('success', 'Tower deleted successfully');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial' => 'required|numeric|unique:towers,serial,' . $id,
            'product_id' => 'required|exists:products,id',
            'details' => 'nullable|string|max:255'
        ]);

        $tower = Tower::findOrFail($id);

        $tower->update($request->all());
        return redirect()->route('towers')->with('success', 'Tower Updated successfully');
    }
}
