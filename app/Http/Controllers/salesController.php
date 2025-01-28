<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Sales;
use App\Helpers\AfghanCalendarHelper;
use Morilog\Jalali\Jalalian;
use App\Models\Tower;
use Illuminate\Http\Request;
class salesController extends Controller
{
    public function singlecustomersalescustomer($id)
        {
            $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
            $startOfMonth = $monthRange['start'];
            $endOfMonth = $monthRange['end'];    

            $sales = Sales::with(['tower', 'contract.customer', 'contract.product']) // Include related data
                        ->whereHas('contract', function ($query) use ($id) {
                            $query->where('contract_id', $id); // Filter by customer ID
                        })
                        ->whereBetween('date', [$startOfMonth, $endOfMonth]) // Filter by Gregorian start and end dates
                        ->get();

            return view('sales.sales', compact('sales'));
        }

    public function sales()
    {
        $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
        $startOfMonth = $monthRange['start'];
        $endOfMonth = $monthRange['end'];    
        $sales = Sales::with(['tower', 'contract.customer', 'contract.product']) // Include related data
                    ->whereBetween('date', [$startOfMonth, $endOfMonth]) // Filter by Gregorian start and end dates
                    ->get();
        return view('sales.sales', compact('sales'));
    }
    
    public function salesform()
    {
        $customers = Customers::whereHas('contract', function ($query) {
            $query->where('isActive', 1);
        })->with(['contract.product'])->get();
    
        $towers = Tower::with('product')->get();
        
        return view('sales.form', compact('towers', 'customers'));
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'date' => 'required',
            'contract_id' => 'required|exists:contracts,id', // Validate that the contract_id exists in the 'contracts' table
            'tower_id' => 'required|exists:towers,id', // Validate that the tower_id exists in the 'towers' table
            'amount' => 'required|numeric|min:0.1', // Validate that the amount is a numeric value and at least 0.1
            'details' => 'nullable|string', // Validate that details is a string and nullable
            'rate' => 'required|numeric|min:0.1', // Validate the rate, ensure it's a numeric value and at least 0.1
        ]);
        $jalaliDate = $request->input('date'); 
        $dateTimeParts = explode(' ', $jalaliDate); 
        $timeParts = date('H:i', strtotime($dateTimeParts[1] . ' ' . $dateTimeParts[2])); // Convert time to 24-hour format
        $jalaliDateIn24HourFormat = $dateTimeParts[0] . ' ' . $timeParts;
    
        $gregorianDate = Jalalian::fromFormat('Y/m/d H:i', $jalaliDateIn24HourFormat)->toCarbon();
        $formattedDate = $gregorianDate->format('Y-m-d H:i:s');
        Sales::create([
            'date' => $formattedDate,
            'contract_id' => $validated['contract_id'],
            'tower_id' => $validated['tower_id'],
            'amount' => $validated['amount'],
            'details' => $validated['details'],
            'rate' => $validated['rate'],
        ]);
        return redirect()->route('sales')->with('success', 'Sale Added Successfully!');
    }
    public function delete($request){

        // dd($request);
        $sale = Sales::findOrFail($request);
        $sale->delete();
        return redirect()->route('sales')->with('success', 'Sale deleted successfully.');
    }

    public function singlecustomerinfo(Request $request, $id)
    {
        $customer = Customers::where('id', $id)
        ->with([
            'contract.product',
            'contract.sales', // No date filter
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
        return view('customers.customersinfo', compact('customer','types'));
    }
}
