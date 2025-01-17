<?php

namespace App\Http\Controllers;
use App\Models\Serial_Numbers;
use App\Models\Tower;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class serial_numbersController extends Controller
{
    public function meter_reading()
    {
        $sql = "
            WITH ranked_records AS (
                SELECT
                    serial_numbers.id AS sid,
                    serial_numbers.serial AS serial_number, -- Alias for serial from serial_numbers
                    towers.serial AS tower_number,         -- Alias for serial from towers
                    serial_numbers.tower_id,
                    towers.name,
                    towers.product_id,
                    products.product_name,
                    serial_numbers.date,
                    ROW_NUMBER() OVER (PARTITION BY serial_numbers.tower_id ORDER BY serial_numbers.date DESC) AS row_num
                FROM
                    serial_numbers
                INNER JOIN
                    towers ON serial_numbers.tower_id = towers.id
                INNER JOIN
                    products ON towers.product_id = products.id
            )
            SELECT
                sid,
                serial_number,
                tower_number,
                tower_id,
                name,
                product_id,
                product_name,
                date
            FROM
                ranked_records
            WHERE
                row_num <= 2
            ORDER BY
                tower_id,
                date DESC;
        ";
    
        // Step 2: Execute the raw SQL query using DB::select()
        $result = DB::select($sql);
    
        // Step 3: Group the results by tower_id and ensure only the latest record is displayed
        $finalResults = collect($result)->groupBy('tower_id')->map(function ($records) {
            // Select the most recent record (last inserted)
            $lastRecord = $records->first(); // The first record in descending order of date
    
            // Calculate petrol sold if there are exactly two records
            if ($records->count() == 2) {
                $secondLastRecord = $records->get(1); // The second record
                $lastRecord->petrol_sold = $lastRecord->serial_number - $secondLastRecord->serial_number;
            } else {
                $lastRecord->petrol_sold = 0; // No petrol sold if only one record exists
            }
    
            return $lastRecord; // Return only the last inserted record
        });
    
        // Step 4: Flatten the grouped results for easier view rendering
        $flatResults = $finalResults->values();
    
        // Load towers with products to pass to the view
        $towers = Tower::with('product')->get();
    
        // Return the view with the results
        return view('meter_reading.form', [
            'towers' => $towers,
            'finalResults' => $flatResults
        ]);
    }
    public function destroy($id)
    {
        $serialNumber = Serial_Numbers::find($id);

        if ($serialNumber) {
            $serialNumber->delete();
            return redirect()->route('meter_reading')->with('success', 'Serial Number deleted successfully.');
        } else {
            return redirect()->route('meter_reading')->with('error', 'Serial Number not found.');
        }
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

        return redirect()->route('meter_reading')->with('success', 'Tower Meter Added successfully.');

    }
    public function edit($id){
        $sql = "
        WITH ranked_records AS (
            SELECT
                serial_numbers.id AS sid,
                serial_numbers.serial AS serial_number, -- Alias for serial from serial_numbers
                towers.serial AS tower_number,         -- Alias for serial from towers
                serial_numbers.tower_id,
                towers.name,
                towers.product_id,
                products.product_name,
                serial_numbers.date,
                ROW_NUMBER() OVER (PARTITION BY serial_numbers.tower_id ORDER BY serial_numbers.date DESC) AS row_num
            FROM
                serial_numbers
            INNER JOIN
                towers ON serial_numbers.tower_id = towers.id
            INNER JOIN
                products ON towers.product_id = products.id
        )
        SELECT
            sid,
            serial_number,
            tower_number,
            tower_id,
            name,
            product_id,
            product_name,
            date
        FROM
            ranked_records
        WHERE
            row_num <= 2
        ORDER BY
            tower_id,
            date DESC;
        ";

        // Step 2: Execute the raw SQL query using DB::select()
        $result = DB::select($sql);

        // Step 3: Group the results by tower_id and ensure only the latest record is displayed
        $finalResults = collect($result)->groupBy('tower_id')->map(function ($records) {
            // Select the most recent record (last inserted)
        $lastRecord = $records->first(); // The first record in descending order of date

        // Calculate petrol sold if there are exactly two records
        if ($records->count() == 2) {
            $secondLastRecord = $records->get(1); // The second record
            $lastRecord->petrol_sold = $lastRecord->serial_number - $secondLastRecord->serial_number;
        } else {
            $lastRecord->petrol_sold = 0; // No petrol sold if only one record exists
        }

        return $lastRecord; // Return only the last inserted record
    });

    // Step 4: Flatten the grouped results for easier view rendering
    $flatResults = $finalResults->values();

    // Load towers with products to pass to the view
    $towers = Tower::with('product')->get();
    $serialNumber = Serial_Numbers::find($id);

    // Return the view with the results
    return view('meter_reading.form', [
        'towers' => $towers,
        'finalResults' => $flatResults,
        'serialNumber' =>$serialNumber
    ]);
    }
    
    public function update(Request $request, $id)
    {
        $serialNumber = Serial_Numbers::find($id);
        $request->validate([
            'tower_id' => 'required|exists:towers,id',
            'serial' => 'required|numeric|min:0',
            'date' => 'required', // Validate the date format
        ]);

        // Format the date to match MySQL datetime format
        $formattedDate = Carbon::createFromFormat('Y-m-d h:i A', $request->input('date'))->format('Y-m-d H:i:s');

        // Update the record
        $serialNumber->update([
            'tower_id' => $request->input('tower_id'),
            'serial' => $request->input('serial'),
            'date' => $formattedDate, // Use the formatted date
        ]);

        return redirect()->route('meter_reading')->with('success', 'Serial Number updated successfully.');
    }
}
