<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function filterdate(Request $request)
    {
        $query = Expense::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $expenses = $query->get();

        return view('expenses.expenses', compact('expenses'));
    }

    public function expenses()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
    
        $expenses = Expense::whereBetween('date', [$startOfMonth, $endOfMonth])->get();
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
            'description' => 'nullable|max:255',
            'category' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,png,doc,docx|max:2048',

        ]);
        $data = $request->all();
        if ($request->hasFile('document')) {
            $data['document'] = $request->file('document')->store('documents', 'public'); // Store file in 'storage/app/public/documents'
        }
        Expense::create($data);
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

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

       $request->validate([
        'item' => 'required|string|max:255',
        'amount' => 'required|numeric',
        'category' => 'required|string',
        'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Add file validation
        'description' => 'nullable|string',
    
    ]);

    if ($request->hasFile('document')) {
        // Delete old file if it exists
        if ($expense->document && Storage::exists('public/' . $expense->document)) {
            Storage::delete('public/' . $expense->document);
        }

        // Store the new file
        $filePath = $request->file('document')->store('documents', 'public');
        $expense->document = $filePath;
    }

    // Update other fields
    $expense->item = $request->item;
    $expense->amount = $request->amount;
    $expense->category = $request->category;
    $expense->description = $request->description;
    $expense->save();
        return redirect()->route('expenses')->with('success', 'Expense updated successfully!');
    }
    public function destroy(Expense $expense)
    {
        if ($expense->document && Storage::exists('public/' . $expense->document)) {
            Storage::delete('public/' . $expense->document);
        }
        $expense->delete();

        return redirect()->route('expenses')->with('success', 'Expense deleted successfully.');
    }
}
