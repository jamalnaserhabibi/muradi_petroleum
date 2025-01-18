<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\CustomerType;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    
    public function customeraddform()
    {
        $customerTypes = CustomerType::select('id', 'customer_type')->get();
        return view('customers.form',compact('customerTypes'));
        
    }
    public function customer()
    {
        $customer = Customers::with(['contract.product'])->get();
        return view('customers.customers',compact('customer'));
    }
    public function edit(Customers $customer)
    {
        $customerTypes = CustomerType::select('id', 'customer_type')->get();

        return view('customers.form',compact('customer','customerTypes'));
    }
    public function store(Request $request)
    {   
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'customer_type' =>'required|exists:customer_types,id',
            'created_by' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
     
        Customers::create($request->all());
        return redirect()->route('CustomerContract')->with('success','Customer added successfully');
    }

    public function update(Request $request, Customers $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'customer_type' =>'required|exists:customer_types,id',
            'created_by' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customers $customer)
    {
        $customer->delete();

        return redirect()->route('customers')->with('success', 'Customer Deleted successfully.');
    }
}
