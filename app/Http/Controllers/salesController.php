<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Tower;
use Illuminate\Http\Request;
class salesController extends Controller
{
    public function sales()
    {
        $towers = Tower::with('product')->get();
       return view('sales.sales', compact('towers'));
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
        $formattedDate = Carbon::createFromFormat('Y-m-d h:i A', $request->input('date'))->format('Y-m-d H:i:s');

        $purchase = Sales::create([
            'date' => $formattedDate,
            'contract_id' => $validated['contract_id'],
            'tower_id' => $validated['tower_id'],
            'amount' => $validated['amount'],
            'details' => $validated['details'],
            'rate' => $validated['rate'],
        ]);
        return redirect()->route('sales')->with('success', 'Sale Added Successfully!');
    }
   
    
    
    public function destroy(){

        return redirect('sales');
    }
}
