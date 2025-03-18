<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\Customers;
use App\Models\CustomerType;
use Illuminate\Http\Request;
class CustomerController extends Controller
{
   
    
    public function customeraddform()
    {
        return view('customers.form');
        
    }
    public function customer()
    {
        $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
        $startOfMonth = $monthRange['start'];
        $endOfMonth = $monthRange['end'];   
        $customer = Customers::whereHas('contract', function ($query) {
            $query->where('isActive', 1);
        })
        ->with([
            'contract.product',
            'contract.distribution' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }
        ])
        ->get()
        ->map(function ($customer) {
            // Check if the customer has contracts
            $customer->current_month_sales_total = $customer->contract 
                ? $customer->contract->distribution->sum('amount') 
                : 0;
            return $customer;
        });
        
      
        

        return view('customers.customers', compact('customer'));
    }
   
    public function customer0()
    {
        $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
        $startOfMonth = $monthRange['start'];
        $endOfMonth = $monthRange['end'];   
        $customer = Customers::whereHas('contract', function ($query) {
            $query->where('isActive', 0);
        })
        ->with([
            'contract.product',
            'contract.distribution' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }
        ])
        ->get()
        ->map(function ($customer) {
            // Check if the customer has contracts
            $customer->current_month_sales_total = $customer->contract 
                ? $customer->contract->distribution->sum('amount') 
                : 0;
            return $customer;
        });
        
      

        return view('customers.customers', compact('customer'));
    }

    public function edit(Customers $customer)
    {

        return view('customers.form',compact('customer'));
    }
    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'contact' => 'required|string|max:25',
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
            'contact' => 'required|numeric|max:25',
            'created_by' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        // dd($payment);
        $customer->update($request->all());

        return redirect()->route('customers')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customers $customer)
    {
        $customer->delete();

        return redirect()->route('customers')->with('success', 'Customer Deleted successfully.');
    }
}
