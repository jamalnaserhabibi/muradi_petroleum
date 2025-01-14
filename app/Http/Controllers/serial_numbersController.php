<?php

namespace App\Http\Controllers;
use App\Models\Serial_Numbers;
use App\Models\Tower;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class serial_numbersController extends Controller
{
    public function destroy($id)
    {
        $serialNumber = Serial_Numbers::find($id);

        if ($serialNumber) {
            $serialNumber->delete();
            return redirect()->route('addsaleinfoform')->with('success', 'Serial Number deleted successfully.');
        } else {
            return redirect()->route('addsaleinfoform')->with('error', 'Serial Number not found.');
        }
    }
    public function tower_serials()
{
    
    

  
        $towers = Tower::with('product')->get();
       return view('sales.sales', compact('towers'));
    
}

    
   
    public function store(Request $request){
        // dd($request);
        $request->validate([
            'tower_id' => 'required|exists:towers,id',
            'serial' => 'required|numeric|min:0', 
            'date' => 'required', // Validate the date format
        ]);
    
        // Format the date to match MySQL datetime format
        $formattedDate = Carbon::createFromFormat('Y-m-d h:i A', $request->input('date'))->format('Y-m-d H:i:s');
    
        // Create the record
        Serial_Numbers::create([
            'tower_id' => $request->input('tower_id'),
            'serial' => $request->input('serial'),
            'date' => $formattedDate, // Use the formatted date
        ]);

        return redirect()->route('addsaleinfoform')->with('success', 'Tower Meter Added successfully.');

    }
}
