<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\Customers;
use App\Models\CustomerType;
use Illuminate\Http\Request;
class CustomerController extends Controller
{
    public function typefilter(Request $request){
    // Fetch customers where customer_type matches requested IDs and has active contracts
        if ($request->filled('type_id')) {
            $customer = Customers::whereIn('customer_type', $request->input('type_id', []))
            ->whereHas('contract', function ($query) {
                $query->where('isActive', 1);
            })
            ->with(['contract.product'])             ->get();
        }else {
            $customer = Customers::whereHas('contract', function ($query) {
                $query->where('isActive', 1);
            })->with(['contract.product'])->get();
            $types = Customers::with('customerType:id,customer_type')
            ->select('customer_type')
            ->distinct()
            ->get();
        }


        $types = Customers::with('customerType:id,customer_type') // Fetch related customer types
        ->select('customer_type') // Only fetch distinct customer_type IDs from customers table
        ->distinct()
        ->get();
        return view('customers.customers', compact('customer','types'));
        }
    
    public function customeraddform()
    {
        $customerTypes = CustomerType::select('id', 'customer_type')->get();
        return view('customers.form',compact('customerTypes'));
        
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
            'contract.sales' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }
        ])
        ->get()
        ->map(function ($customer) {
            // Check if the customer has contracts
            $customer->current_month_sales_total = $customer->contract 
                ? $customer->contract->sales->sum('amount') 
                : 0;
            return $customer;
        });
        
        $types = Customers::with('customerType:id,customer_type') // Fetch related customer types
            ->select('customer_type') // Only fetch distinct customer_type IDs from customers table
            ->distinct()
            ->get();
        

        return view('customers.customers', compact('customer','types'));
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
            'contract.sales' => function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
            }
        ])
        ->get()
        ->map(function ($customer) {
            // Check if the customer has contracts
            $customer->current_month_sales_total = $customer->contract 
                ? $customer->contract->sales->sum('amount') 
                : 0;
            return $customer;
        });
        
        $types = Customers::with('customerType:id,customer_type') // Fetch related customer types
            ->select('customer_type') // Only fetch distinct customer_type IDs from customers table
            ->distinct()
            ->get();
        

        return view('customers.customers', compact('customer','types'));
    }

    public function edit(Customers $customer)
    {
        $customerTypes = CustomerType::select('id', 'customer_type')->get();

        return view('customers.form',compact('customer','customerTypes'));
    }
    public function store(Request $request)
    {   
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'contact' => 'required|string|max:25',
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
            'contact' => 'required|numeric|max:25',
            'customer_type' =>'required|exists:customer_types,id',
            'created_by' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        dd($payment);
        $customer->update($request->all());

        return redirect()->route('customers')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customers $customer)
    {
        $customer->delete();

        return redirect()->route('customers')->with('success', 'Customer Deleted successfully.');
    }
}
