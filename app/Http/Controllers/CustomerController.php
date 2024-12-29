<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function customeraddform()
    {
        return view('customers.form');
    }
    public function customer()
    {
        $customer = Customers::all();
        return view('customers.customers',compact('customer'));
    }
    public function edit(Customers $customer)
    {
        // dd($customer);
        return view('customers.form',compact('customer'));
    }
    public function store(Request $request)
    {   
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'created_by' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
     
        Customers::create($request->all());
        return redirect()->route('customers')->with('success','Customer added successfully');
    }

    public function update(Request $request, Customers $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'date' => 'nullable|date',
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
