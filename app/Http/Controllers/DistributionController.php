<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\Employee;
use App\Models\Contract;
use App\Models\Tower;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function index()
    {
        // Fetch distribution data with relationships
        $distributions = Distribution::with(['contract.customer', 'distributer', 'tower'])->get();
        // Fetch distributers and contracts for dropdowns
        $distributers = Employee::all(); // Assuming distributers are employees

         $contracts = Contract::with(['customer','Product'])->get();

        // Pass the data to the view
        return view('distribution.distribution', compact('distributions', 'distributers', 'contracts'));
    }

    public function destroy($id)
    {
        // Find the distribution record
        $distribution = Distribution::find($id);

        // Delete the record
        $distribution->delete();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Distribution record deleted successfully');
    }
    public function getTowers(Request $request)
    {
        $distributerId = $request->input('distributer_id');
    
        // Fetch towers related to the distributer and include the product relationship
        $towers = Tower::whereHas('distributers', function ($query) use ($distributerId) {
            $query->where('employee_id', $distributerId);
        })
        ->with('product') // Eager load the product relationship
        ->get();
    
        return response()->json($towers);
    }
    public function store(Request $request)
    {
        $request->validate([
            'distributer_id' => 'required|exists:employees,id',
            'tower_id' => 'required|exists:towers,id',
            'contract_id' => 'required|exists:contracts,id',
            'rate' => 'required|numeric',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Distribution::create($request->all());

        return redirect()->route('distribution')->with('success', 'Distribution added successfully!');
    }
    public function getContracts(Request $request)
    {
        $productId = $request->input('product_id');
    
        // Fetch contracts related to the selected product
        $contracts = Contract::where('product_id', $productId)
            ->with(['customer', 'product']) // Eager load customer and product relationships
            ->get();
    
        return response()->json($contracts);
    }

    public function edit($id)
        {
            $distribution = Distribution::with(['contract.customer', 'distributer', 'tower'])->findOrFail($id);
            $distributers = Employee::all(); // Fetch all distributers for the dropdown
            $towers = Tower::all(); // Fetch all towers for the dropdown
            $contracts = Contract::all(); // Fetch all contracts for the dropdown

            return view('distribution.edit-modal', compact('distribution', 'distributers', 'towers', 'contracts'));
        }

        public function update(Request $request, $id)
{
    $request->validate([
        'distributer_id' => 'required|exists:employees,id',
        'tower_id' => 'required|exists:towers,id',
        'contract_id' => 'required|exists:contracts,id',
        'rate' => 'required|numeric',
        'amount' => 'required|numeric',
        'description' => 'nullable|string',
    ]);

    $distribution = Distribution::findOrFail($id);
    $distribution->update($request->all());

    return redirect()->route('distribution.index')->with('success', 'Distribution updated successfully!');
}
}
