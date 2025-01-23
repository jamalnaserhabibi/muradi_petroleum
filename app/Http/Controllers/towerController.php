<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\Tower;
use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;

class towerController extends Controller
{
    public function seeksale($id){
        $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
        $startOfMonth = $monthRange['start'];
        $endOfMonth = $monthRange['end'];    
        $tower = Sales::with(['tower', 'contract.customer', 'contract.product']) // Include related data
                    ->where('tower_id', $id) // Add condition to match tower_id
                    ->whereBetween('date', [$startOfMonth, $endOfMonth]) // Filter by Gregorian start and end dates
                    ->get();
        return view('towers.seeksale', compact('tower'));
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
