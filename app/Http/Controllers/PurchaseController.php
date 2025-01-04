<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;

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
            'heaviness' => 'required|numeric|min:700|max:1000',  
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
            'heaviness' => 'required|numeric|min:700|max:1000',  
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

        // $startOfMonth = now()->startOfMonth();
        // $endOfMonth = now()->endOfMonth();

        // Fetch purchases within the current month, including related product data
        // $purchases = Purchase::with('product')
        //     ->whereBetween('date', [$startOfMonth, $endOfMonth])
        //     ->get();

        // // Fetch unique products for the dropdown
        // $products = Purchase::with('product')
        //     ->get()
        //     ->pluck('product')
        //     ->unique('id');

        // Pass the fetched data to the view
        // return view('purchase.purchase', compact('purchases', 'products'));
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
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

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
        // Fetch unique products for the dropdown
        $products = Purchase::with('product')
            ->get()
            ->pluck('product')
            ->unique('id');
    
        // Initialize query for filtering purchases
        $query = Purchase::query();
    
        // Apply date filter if provided
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
            // Set the date range to this month's start and end
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
            $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
        } else {
            // If date range is provided, filter accordingly
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }
        }
    
        // Apply product filter if provided
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
    
        // Fetch purchases (all if no filters applied)
        $purchases = $query->with('product')->get();
    
        // Pass data to the view
        return view('purchase.purchase', compact('purchases', 'products'));
    }
}
