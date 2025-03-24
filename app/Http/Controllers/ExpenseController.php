<?php

namespace App\Http\Controllers;

use App\Helpers\AfghanCalendarHelper;
use App\Models\Distribution;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;
use App\Models\Expense;
use App\Models\Contract;

class ExpenseController extends Controller
{
    public function filterdate(Request $request)
    {
        $query = Expense::query();
        try{
            $afghaniStartDate=$request->start_date;
            $afghaniEndDate=$request->end_date;

            $start_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniStartDate)->toDateString();
            $end_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniEndDate)->toDateString();
            // dd($start_date,$end_date);
        } catch (\Throwable $th) {
        
        }
        if ($afghaniStartDate &&  $afghaniEndDate) {
            $query->whereBetween('date', [$start_date, $end_date]);
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $expenses = $query->get();

        return view('expenses.expenses', compact('expenses','afghaniStartDate','afghaniEndDate'));
    }

    public function expenses(Request $request)
    {
        if ($request->has('start_date') && $request->has('end_date')) {
            $startOfMonth = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->start_date)->toDateString();
            $endOfMonth = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->end_date)->toDateString();
            $afghaniStartDate = $request->start_date;
            $afghaniEndDate = $request->end_date;
        }else{
            $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
            $startOfMonth = $monthRange['start'];
            $endOfMonth = $monthRange['end'];   
            $afghaniStartDate = AfghanCalendarHelper::toAfghanDateFormat($startOfMonth);
            $afghaniEndDate =  AfghanCalendarHelper::toAfghanDateFormat($endOfMonth);
        }
        if ($request->has('contract_id')) {
            $expenses = Distribution::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('contract_id', $request->contract_id)
                ->whereHas('contract.product', function ($query) {
                    $query->where('id', 15);
                })
                ->with(['contract.customer', 'distributer', 'tower'])
                ->orderBy('date', 'asc')
                ->get();
        } else {
            $expenses = Distribution::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->whereHas('contract.product', function ($query) {
                    $query->where('id', 15);
                })
                ->with(['contract.customer', 'distributer', 'tower'])
                ->orderBy('date', 'asc')
                ->get();
        }
        
       

        $contracts = Contract::with(['customer', 'product'])
            ->whereHas('product', function ($query) {
            $query->whereIn('id', [15]);
            })
            ->get();

        return view('expenses.expenses', compact('expenses','afghaniStartDate', 'afghaniEndDate', 'contracts'));
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
