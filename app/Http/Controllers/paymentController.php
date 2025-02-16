<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\Contract;
use App\Models\Customers;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\CalendarUtils;

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
        ->leftJoinSub(
            DB::table('sales')
                ->select('contract_id', DB::raw('SUM(amount * rate) as total_sales'))
                ->groupBy('contract_id'),
            'sales_summary',
            'contracts.id',
            '=', 'sales_summary.contract_id'
        )
        ->leftJoinSub(
            DB::table('payment')
                ->select('contract_id', DB::raw('SUM(amount) as total_payments'))
                ->groupBy('contract_id'),
            'payments_summary',
            'contracts.id',
            '=', 'payments_summary.contract_id'
        )
        ->select(
            'contracts.id',
            'customers.name as customer_name',
            'customers.company as customer_company',
            'contracts.date as contract_date',
            DB::raw('COALESCE(sales_summary.total_sales, 0) as total_sales'),
            DB::raw('COALESCE(payments_summary.total_payments, 0) as total_payments'),
            DB::raw('(COALESCE(payments_summary.total_payments, 0) - COALESCE(sales_summary.total_sales, 0)) as balance')
        )
        ->where('contracts.isActive', 1)
        ->groupBy(
            'contracts.id',
            'customers.name',
            'customers.company',
            'contracts.date',
            'sales_summary.total_sales', // Add this to the GROUP BY
            'payments_summary.total_payments' // Add this to the GROUP BY
        )
        ->get();
    

            return view('payment/payment',compact('balances','customers'));
    }

    public function singlecustomerpayments(Request $request, $id){

        
        $astart = $request->start_date;
        $aend = $request->end_date;
        $contractId = $request->contractId ?? $id;
        // $contractId = $request->contractId;

        $start = null;
        $end = null;

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->start_date)->toDateString();
            $end = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->end_date)->toDateString();
        } else {
            $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
            $start = $monthRange['start'];
            $end = $monthRange['end'];
        }
        
        $payments = Payment::with(['contract' => function ($query) {
            $query->select('id', 'customer_id')->with(['customer' => function ($query) {
                $query->select('id', 'name', 'company');
            }]);
        }])
        ->where('contract_id', $contractId)
        ->whereBetween('date', [$start, $end])  // Adding the date range filter
        ->get();

        return view('payment/customerpayment', compact('payments','astart','aend','contractId'));
        
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
            ->leftJoinSub(
                DB::table('sales')
                    ->select('contract_id', DB::raw('SUM(amount * rate) as total_sales'))
                    ->groupBy('contract_id'),
                'sales_summary',
                'contracts.id',
                '=',
                'sales_summary.contract_id'
            )
            ->leftJoinSub(
                DB::table('payment')
                    ->select('contract_id', DB::raw('SUM(amount) as total_payments'))
                    ->groupBy('contract_id'),
                'payments_summary',
                'contracts.id',
                '=',
                'payments_summary.contract_id'
            )
            ->select(
                'contracts.id',
                'customers.name as customer_name',
                'customers.company as customer_company',
                DB::raw('COALESCE(sales_summary.total_sales, 0) as total_sales'),
                DB::raw('COALESCE(payments_summary.total_payments, 0) as total_payments'),
                DB::raw('(COALESCE(payments_summary.total_payments, 0) - COALESCE(sales_summary.total_sales, 0)) as balance')
            )
            ->where('contracts.isActive', 1)
            ->when(!empty($customerIds), function ($query) use ($customerIds) {
        return $query->whereIn('contracts.customer_id', $customerIds);
        })
            ->groupBy(
                'contracts.id', 
                'customers.name', 
                'customers.company', 
                'contracts.date', 
                'sales_summary.total_sales',  // Added to group by to avoid MySQL error
                'payments_summary.total_payments'  // Added to group by to avoid MySQL error
            )
        ->get();

    
        // Return the view with data
        return view('payment.payment', compact('balances', 'customers'));
    }
    
    public function filterpaymentdate(Request $request){
       
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

    public function editpayment($id){
        $payment = Payment::with(['contract' => function ($query) {
            $query->select('id', 'customer_id')->with(['customer' => function ($query) {
                $query->select('id', 'name', 'company');
            }]);
        }])->findOrFail($id);
        return view('payment/form', compact('payment'));
    }

    public function updatepayment(){
        // return view('payment/form');
    }
    public function delete($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('payment')->with('success', 'Payment deleted successfully.');

    }
}
