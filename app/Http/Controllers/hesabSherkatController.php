<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class hesabSherkatController extends Controller
{
    function index()
    {
        return view('hesabSherkat.hesabSherkat_purchase');
    }

    function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        // Process the payment here

        return redirect()->route('hesabSherkat_purchase')->with('success', 'Purchase processed successfully!');
    }

    function destroy($id)
    {
        // Find the resource by ID
        // $resource = ResourceModel::find($id);

        // Delete the resource
        // $resource->delete();

        // Redirect with success message
        return redirect()->route('hesabSherkat_purchase')->with('success', 'Purchase deleted successfully!');
    }




    function index_payment()
    {
        return view('hesabSherkat.hesabSherkat_payment');
    }

    function store_payment(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        // Process the payment here

        return redirect()->route('hesabSherkat_payment')->with('success', 'Payment processed successfully!');
    }

    function destroy_payment($id)
    {
        // Find the resource by ID
        // $resource = ResourceModel::find($id);

        // Delete the resource
        // $resource->delete();

        // Redirect with success message
        return redirect()->route('hesabSherkat_payment')->with('success', 'Payment deleted successfully!');
    }
}
