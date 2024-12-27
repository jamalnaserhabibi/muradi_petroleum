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
    public function store(Request $request)
    {   
        dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'created_by' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            // as
           
        ]);
     
        Customers::create($request->all());
        return redirect()->route('customers')->with('success','Customer added successfully');
    }
}
