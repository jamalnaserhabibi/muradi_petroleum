<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Tower;
use Illuminate\Http\Request;

class salesController extends Controller
{
    
    public function saleform()
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
        return view('sales.form', [
            'towers' => $towers,
            'finalResults' => $flatResults
        ]);
    }
    

    public function update(){

        return redirect('sales');
        
    }
    public function store(Request $request){

 
    }
    public function destroy(){

        return redirect('sales');
    }
}
