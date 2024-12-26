<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
class ExpenseController extends Controller
{
    public function expenses()
    {
        $expenses = Expense::all();
        return view('expenses.expenses', compact('expenses'));
    }
    public function create()
    {
        return view('expenses.form');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'item' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'description' => 'string|max:255',
            'category' => 'required|string|max:255',
        ]);

        Expense::create([
            'item' => $request->input('item'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
        ]);
    
        return redirect()->route('expenses')->with('success', 'Expense Added successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('expenses.form', compact('expense'));
    }
    public function expenseadd()
    {
        return view('expenses.form');
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'item' => 'string|max:255',
            'category' => 'required|string|max:255',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses')->with('success', 'Expense updated successfully!');
    }
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses')->with('success', 'Expense deleted successfully.');
    }
}
