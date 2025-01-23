<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;
class PurchaseController extends Controller
{   
    public function purchaseedit($id)
    {
           
        $products = Product::select('id', 'product_name')
        ->orderBy('product_name', 'asc')
        ->get();
        
        $purchase = Purchase::findOrFail($id);
        return view('purchase.form',compact('products','purchase'));
    }

    public function purchasedelete($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();
        return redirect()->route('purchase')->with('success', 'Purchase deleted successfully.');
    }

    public function purchaseupdate(Request $request, $id)
    {   
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',  
            'heaviness' => 'required|numeric|min:700|max:2500',  
            'amount' => 'required|numeric|min:0', 
            'rate' => 'required|numeric|min:0',  
            'details' => 'nullable|string|max:255',  
        ]);

    
        $purchase = Purchase::findOrFail($id);

        $purchase->update([
            'product_id' => $validatedData['product_id'],
            'amount' => $validatedData['amount'],
            'heaviness' => $validatedData['heaviness'],
            'rate' => $validatedData['rate'],
            'details' => $validatedData['details'] ?? null,
        ]);

        return redirect()->route('purchase')->with('success', 'Purchase updated successfully.');
    }

    public function purchaseadd(Request $request)
    {   
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id', // Ensure product exists
            'heaviness' => 'required|numeric|min:700|max:2500',  
            'amount' => 'required|numeric|min:0', // Weight constraints
            'rate' => 'required|numeric|min:0', // Ensure rate is a positive number
            'details' => 'nullable|string|max:255', // Optional description
        ]);
    
        // Store the purchase
        $purchase = Purchase::create([
            'product_id' => $validatedData['product_id'],
            'amount' => $validatedData['amount'],
            'heaviness' => $validatedData['heaviness'],
            'rate' => $validatedData['rate'],
            'details' => $validatedData['details'] ?? null,
        ]);
        return redirect()->route('purchase')->with('success', 'Purchase Added successfully.');

         
    }

    public function purchaseform()
    {
           
        $products = Product::select('id', 'product_name')
        ->orderBy('product_name', 'asc')
        ->get();

        return view('purchase.form',compact('products'));
    }

    public function purchase()
    {
        $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
        $startOfMonth = $monthRange['start'];
        $endOfMonth = $monthRange['end'];   

        // Fetch purchases within the current month, including related product data
        $purchases = Purchase::with('product')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        // Fetch unique products for the dropdown
        $products = Purchase::with('product')
            ->get()
            ->pluck('product')
            ->unique('id');

        // Pass the fetched data to the view
        return view('purchase.purchase', compact('purchases', 'products'));
    }

    public function filter(Request $request)
    {
    
        $products = Purchase::with('product')
        ->get()
        ->pluck('product')
        ->unique('id');

        $query = Purchase::query();

        try {
        $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
        $startOfMonth = $monthRange['start'];
        $endOfMonth = $monthRange['end'];   

        $afghaniStartDate=$request->start_date;
        $afghaniEndDate=$request->end_date;

        $start_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniStartDate)->toDateString();
        $end_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniEndDate)->toDateString();
        } catch (\Throwable $th) {
       
        }



        if (!$request->filled('start_date') && !$request->filled('end_date')) {
            // Default to the current month's date range
        
            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
        } else {
            // If date range is provided, filter accordingly
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [ $start_date, $end_date]);
            }
        }

        // Apply product filter if provided
        if ($request->filled('product_id')) {
            // Ensure product_id is treated as an array for multiple selections
            $productIds = $request->input('product_id', []);
            $query->whereIn('product_id', $productIds);
        }

        // Fetch purchases (all if no filters applied)
        $purchases = $query->with('product')->get();

        // Pass data to the view
        return view('purchase.purchase', compact('purchases', 'products','afghaniStartDate','afghaniEndDate'));
    }
}
