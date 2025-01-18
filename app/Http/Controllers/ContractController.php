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
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'rate' => 'nullable',
            'details' => 'nullable|string|max:500',
        ]);
    
        // Remove 'rate' from the request data if it's null
        $data = $request->all();
        if ($request->input('rate') === null) {
            unset($data['rate']);
        }
    
        Contract::create($data);
    
        return redirect()->route('customers')->with('Success', 'Customer added successfully');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'rate' => 'nullable',
            'details' => 'nullable|string|max:500',
        ]);
    
        $contract = Contract::findOrFail($request->id);
    
        // Filter the request data to exclude 'rate' if it's null
        $data = $request->all();
        if ($request->input('rate') === null) {
            unset($data['rate']);
        }
    
        $contract->update($data);
    
        return redirect()->route('customers')->with('Success', 'Customer updated successfully');
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
    public function updateStatus(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'customer_id' => 'required|exists:contracts,id',
            'is_active' => 'required|boolean',
        ]);
    
        // Find the customer's contract and update the status
        $contract = Contract::find($request->customer_id);
        
        if ($contract) {
            $contract->isActive = $request->is_active;
            $contract->save();
    
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Contract status updated successfully.']);
        }
    
        // If contract wasn't found, return error response
        return response()->json(['success' => false, 'message' => 'Failed to update contract status.']);
    }
    
}