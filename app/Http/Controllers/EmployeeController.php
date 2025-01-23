<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
class EmployeeController extends Controller
{
    public function employees()
    {

        $employees = Employee::all();

        $totalSalaries = $employees->sum('salary');
        return view('employees.employees', compact('employees','totalSalaries'));
    }

    public function addemployee()
    {
        return view('employees.form');
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);

        return view('employees.form', compact('employee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['fullname', 'salary', 'description']);

        // Handle file upload if present
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees')->with('success', 'Employee added successfully!');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->photo && file_exists(storage_path('app/public/' . $employee->photo))) {
            unlink(storage_path('app/public/' . $employee->photo));
        }

        $employee->delete();

        return redirect()->route('employees')->with('success', 'Employee Deleted successfully.');
    }
    
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'fullname' => 'required|string|max:255',
            'salary' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['fullname', 'salary', 'description']);

        // Handle file upload if present
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($employee->photo && file_exists(storage_path('app/public/' . $employee->photo))) {
                unlink(storage_path('app/public/' . $employee->photo));
            }

            $data['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($data);

        return redirect()->route('employees')->with('success', 'Employee updated successfully!');
    }
}