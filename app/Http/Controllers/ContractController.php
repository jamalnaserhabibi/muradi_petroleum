<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customers;
use App\Models\Product;
use Illuminate\Http\Request;
class ContractController extends Controller
{
    public function findCustomer()
    {   //products info
        $products = Product::select('id', 'product_name')->get();
        // Fetch the most recently added customer
        $latestCustomer = Customers::select('id','name','company')
        ->latest()
        ->first();
        return view('customers/CustomerContractForm', compact('latestCustomer','products'));
    }
    public function store(Request $request){
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'rate' => 'required|numeric|min:1',
            'details' => 'nullable|string|max:500',
        ]);
        Contract::create($request->all());
        return redirect()->route('customers')->with('Success','Customer added successfully');
    }
    public function update(Request $request){
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'rate' => 'required|numeric|min:1',
            'details' => 'nullable|string|max:500',
        ]);
        $contract = Contract::findOrFail($request->id);
        $contract->update($request->all());
        return redirect()->route('customers')->with('Success','Customer updated successfully');
    }
    public function contractedit(Request $request){
        $latestCustomer = Customers::findOrFail($request->id);

        // Fetch the contract associated with the customer (if exists)
        $contract = Contract::with('customer', 'product')->where('customer_id', $latestCustomer->id)->first();
    
        // Fetch all products to populate the product dropdown
        $products = Product::all();
    
        // Pass data to the view
        return view('customers/CustomerContractForm', compact('latestCustomer', 'contract', 'products'));
 
    }

}
