<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Tower;
use Illuminate\Http\Request;

use function Pest\Laravel\get;

class DistributerController extends Controller
{
    public function index()
        {
            // Get employees who are linked to towers (those with an existing relationship in the distributer table)
            $employees = Employee::whereHas('towers')->get();  // This will get employees with assigned towers
            $allemployees = Employee::all();
            // Get all towers
            $towers = Tower::all();
            // Get the towers that are not assigned to any employee
            $assignedTowerIds = Employee::has('towers')->with('towers')->get()->pluck('towers.*.id')->flatten();
            // Retrieve towers that are not in the list of assigned towers
            $availableTowers = Tower::with('product')
                ->whereNotIn('id', $assignedTowerIds)
                ->orWhere(function ($query) {
                        $query->whereIn('product_id', [13, 14, 15]);
                    })->get();
                

            return view('distributers.distributers', compact('employees', 'availableTowers','allemployees'));
        }

    
    // public function assign(Request $request)
    // {
    //     $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'tower_id' => 'required|array', // Ensure tower_id is an array
    //         'tower_id.*' => 'exists:towers,id', // Ensure each tower_id exists in the towers table
    //     ]);
         

    //     $employee = Employee::find($request->employee_id);
    //     // Sync the selected towers with the employee
    //     $employee->towers()->sync($request->tower_id);
    //     return redirect()->route('distributers')->with('success', 'Towers Assigned Successfully!');
    // }
    public function assign(Request $request)
        {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'tower_id' => 'required|array', // Ensure tower_id is an array
                'tower_id.*' => 'exists:towers,id', // Ensure each tower_id exists in the towers table
            ]);

            $employee = Employee::find($request->employee_id);
            // Attach the selected towers to the employee without removing existing ones
            $employee->towers()->attach($request->tower_id);
            return redirect()->route('distributers')->with('success', 'Towers Assigned Successfully!');
        }
    // Assign Distributor to Tower
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'tower_id' => 'required|exists:towers,id',
            'assigned_date' => 'required|date',
        ]);

        $employee = Employee::find($request->employee_id);
        $employee->towers()->attach($request->tower_id, [
            'assigned_date' => $request->assigned_date,
        ]);

        return redirect()->back()->with('success', 'Distributor assigned successfully');
    }

       // Delete Distributor Assignment
    public function destroy($employee_id, $tower_id)
    {
        $employee = Employee::find($employee_id);
    
        // Check if the employee exists
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found');
        }
    
        // Detach the specified tower from the employee
        $employee->towers()->detach($tower_id);
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Tower removed successfully');
    }
    
}
