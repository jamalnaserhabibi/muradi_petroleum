<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;
use App\Helpers\AfghanCalendarHelper;
use App\Models\hesabSherkat_payment;
use App\Models\hesabSherkat_Purchase;
use App\Models\Product;
use Morilog\Jalali\Jalalian;

class hesabSherkatController extends Controller
{
    function index(Request $request)
    {   
        if (isset($request->start_date) && isset($request->end_date)) {
            $afghaniStartDate = $request->start_date;
            $afghaniEndDate = $request->end_date;
            $start_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniStartDate)->toDateString();
            $end_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniEndDate)->toDateString();
        } else {
            $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
            $start_date = $monthRange['start'];
            $end_date = $monthRange['end'];
            $afghaniStartDate = AfghanCalendarHelper::toAfghanDateFormat($start_date);
            $afghaniEndDate =  AfghanCalendarHelper::toAfghanDateFormat($end_date);

    }
    $products = Product::select('id', 'product_name')
    ->whereNotIn('id', [13, 14, 15])
    ->orderBy('product_name', 'asc')
    ->get();

    // Fetch distribution data with relationships
         $hesabSherkat_Purchase = hesabSherkat_Purchase::whereBetween('date', [$start_date, $end_date])
        ->orderBy('date', 'asc')->get();
        $afghancurrentdate = AfghanCalendarHelper::getCurrentShamsiDate();

    return view('hesabSherkat.hesabSherkat_purchase', compact('hesabSherkat_Purchase','products', 'afghaniStartDate', 'afghaniEndDate','afghancurrentdate'));
    }

    function store(Request $request)
    {
            $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'heaviness' => 'required|numeric|min:700|max:2500',  
            'amount' => 'required|numeric|min:0',
            'rate' => 'required|numeric|min:0',
            'submitted_to' => 'nullable|string|max:255',
            'date' => 'required',
            'details' => 'nullable|string|max:255',
            'supplier' => 'required|string|max:255', // New supplier field
        ]);
        
        $jalaliDate = $request->input('date'); 
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $jalaliDate)->toCarbon();
        $formattedDate = $gregorianDate->format('Y-m-d');
        // Process the payment here

        hesabSherkat_Purchase::create([
            'product_id'    => $validatedData['product_id'],
            'heaviness'     => $validatedData['heaviness'],
            'amount'        => $validatedData['amount'],
            'rate'          => $validatedData['rate'],
            'submitted_to'  => $validatedData['submitted_to'],
            'date'          => $formattedDate,  
            'details'       => $validatedData['details'],
            'supplier'      => $validatedData['supplier'],
        ]);
        return redirect()->route('hesabSherkat_purchase')->with('success', 'Purchase processed successfully!');
    }

    function destroy($id)
    {
        // Find the resource by ID
        $resource = hesabSherkat_Purchase::find($id);

        // Delete the resource
        $resource->delete();

        // Redirect with success message
        return redirect()->route('hesabSherkat_purchase')->with('success', 'Purchase deleted successfully!');
    }




    public function index_payment(Request $request)
    {
        // Handle date filtering
        if (isset($request->start_date) && isset($request->end_date)) {
                $afghaniStartDate = $request->start_date;
                $afghaniEndDate = $request->end_date;
                $start_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniStartDate)->toDateString();
                $end_date = CalendarUtils::createCarbonFromFormat('Y/m/d', $afghaniEndDate)->toDateString();
            } else {
                $monthRange = AfghanCalendarHelper::getCurrentShamsiMonthRange();
                $start_date = $monthRange['start'];
                $end_date = $monthRange['end'];
                $afghaniStartDate = AfghanCalendarHelper::toAfghanDateFormat($start_date);
                $afghaniEndDate =  AfghanCalendarHelper::toAfghanDateFormat($end_date);
        }
        // Fetch distribution data with relationships
             $hesabSherkat_payment = hesabSherkat_payment::whereBetween('date', [$start_date, $end_date])
            ->orderBy('date', 'asc')->get();
            $afghancurrentdate = AfghanCalendarHelper::getCurrentShamsiDate();
    
            $suppliers = hesabSherkat_Purchase::select('supplier')->distinct()->get();

        return view('hesabSherkat.hesabSherkat_payment', compact('hesabSherkat_payment','suppliers', 'afghaniStartDate', 'afghaniEndDate','afghancurrentdate'));
    }

    function store_payment(Request $request)
    {
        $validatedData = $request->validate([
            'supplier' => 'required|string|max:255',  
            'fromPerson' => 'required|string|max:255', 
            'fromChannel' => 'required|string|max:255', 
            'amount' => 'required|numeric|min:0',
            'date' => 'required',
            'details' => 'nullable|string|max:255',
        ]);

        $jalaliDate = $request->input('date'); 
        $gregorianDate = Jalalian::fromFormat('Y/m/d', $jalaliDate)->toCarbon();
        $formattedDate = $gregorianDate->format('Y-m-d');
        
        hesabSherkat_payment::create([
            'supplier' => $validatedData['supplier'],
            'fromPerson' => $validatedData['fromPerson'],
            'fromChannel' => $validatedData['fromChannel'],
            'amount' => $validatedData['amount'],
            'date' => $formattedDate,  
            'details' => $validatedData['details'],
        ]);


        return redirect()->route('hesabSherkat_payment')->with('success', 'Payment processed successfully!');
    }

    function destroy_payment($id)
    {
        // Find the resource by ID
        $resource = hesabSherkat_payment::find($id);

        // Delete the resource
        $resource->delete();

        // Redirect with success message
        return redirect()->route('hesabSherkat_payment')->with('success', 'Payment deleted successfully!');
    }
}
