<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Customers;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class paymentController extends Controller
{
    
    public function addpaymentform(){
        $customers = Customers::whereHas('contract', function ($query) {
            $query->where('isActive', 1);
        })->with(['contract.product'])->get();
        return view('payment/form', compact('customers'));
    }

    public function payment(){
        $customers = Customers::whereHas('contract', function ($query) {
            $query->where('isActive', 1);
        })->with(['contract.product'])->get();
        
        $balances = Contract::join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->leftJoin('sales', 'contracts.id', '=', 'sales.contract_id')
            ->leftJoin('payment', 'contracts.id', '=', 'payment.contract_id')
            ->select(
                'contracts.id',
                'customers.name as customer_name',
                'customers.company as customer_company',
                'contracts.date as contract_date',
                DB::raw('COALESCE(SUM(sales.amount), 0) as total_sales'),
                DB::raw('COALESCE(SUM(payment.amount), 0) as total_payments'),
                DB::raw('(COALESCE(SUM(payment.amount), 0) - COALESCE(SUM(sales.amount), 0)) as balance'),
                //DB::raw('MAX(payment.date) as last_payment_date'),
                //DB::raw('MAX(sales.date) as last_sales_date')
            )
            ->where('contracts.isActive', 1) 
            ->groupBy('contracts.id', 'customers.name', 'customers.company', 'contracts.date')
            ->get();      

            return view('payment/payment',compact('balances','customers'));
    }

    public function singlecustomerinfo($id){
        $payments = Payment::where('contract_id', $id)->get();
        dd($payments);
        return view('payment/customerpayment', compact('payments'));
    }
    public function filtercustomer(Request $request)
    {
        // Get selected customer IDs from the request
        $customerIds = $request->input('product_id', []); // Default to an empty array if not set
    
        // Fetch customers with active contracts and their products
        $customers = Customers::whereHas('contract', function ($query) {
            $query->where('isActive', 1);
        })->with(['contract.product'])->get();
    
        // Fetch contract balances for selected customers
        $balances = Contract::join('customers', 'contracts.customer_id', '=', 'customers.id')
            ->leftJoin('sales', 'contracts.id', '=', 'sales.contract_id')
            ->leftJoin('payment', 'contracts.id', '=', 'payment.contract_id')
            ->select(
                'contracts.id',
                'customers.name as customer_name',
                'customers.company as customer_company',
                // 'contracts.date as contract_date',
                DB::raw('COALESCE(SUM(sales.amount), 0) as total_sales'),
                DB::raw('COALESCE(SUM(payment.amount), 0) as total_payments'),
                DB::raw('(COALESCE(SUM(payment.amount), 0) - COALESCE(SUM(sales.amount), 0)) as balance')
            )
            ->where('contracts.isActive', 1)
            ->when(!empty($customerIds), function ($query) use ($customerIds) {
                return $query->whereIn('contracts.customer_id', $customerIds);
            })
            ->groupBy('contracts.id', 'customers.name', 'customers.company', 'contracts.date')
            ->get();
    
        // Return the view with data
        return view('payment.payment', compact('balances', 'customers'));
    }
    
    public function store(Request $request)
    {
     
        // Validate the request data
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id', // Validate that the contract_id exists in the 'contracts' table
            'amount' => 'required|numeric|min:0.1', // Validate that the amount is a numeric value and at least 0.1
            'details' => 'nullable|string', // Validate that details is a string and nullable
        ]);
    
        Payment::create([
            'contract_id' => $validated['contract_id'],
            'amount' => $validated['amount'],
            'details' => $validated['details'],
        ]);
        return redirect()->route('payment')->with('success', 'Payment Added Successfully!');
    }

    public function editpayment(){
        return view('payment/form');
    }

    public function updatepayment(){
        // return view('payment/form');
    }
    public function delete(){
        // return view('payment/form');
    }
}
