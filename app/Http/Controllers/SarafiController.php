<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;
use App\Helpers\AfghanCalendarHelper;
use App\Models\Sarafi_payments;
use Morilog\Jalali\Jalalian;

use function Pest\Laravel\get;

class SarafiController extends Controller
{
    public function index(Request $request)
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
        $sarafiPayments = Sarafi_payments::whereBetween('date', [$start_date, $end_date])
            ->orderBy('date', 'asc')->get();
            $afghancurrentdate = AfghanCalendarHelper::getCurrentShamsiDate();
    
        return view('sarafi.sarafi_payment', compact('sarafiPayments', 'afghaniStartDate', 'afghaniEndDate','afghancurrentdate'));
    }

    public function store(Request $request){
        $data = $request->validate([
            'amount_afghani' => 'required|numeric',
            'equivalent_dollar' => 'required|numeric',
            'amount_dollar' => 'required|numeric',
            'moaadil_afghani' => 'required|numeric',
            'date' => 'required',
            'az_darak' => 'required|string|max:255',
            'details' => 'nullable|string|max:255',
            'date' => 'required|date_format:Y/m/d',
        ]);
       
        $jalaliDate = $request->input('date'); 

        $gregorianDate = Jalalian::fromFormat('Y/m/d', $jalaliDate)->toCarbon();
        $formattedDate = $gregorianDate->format('Y-m-d');

        // Save the data to the database
        Sarafi_payments::create([
            'amount_afghani' => $data['amount_afghani'],
            'equivalent_dollar' => $data['equivalent_dollar'],
            'amount_dollar' => $data['amount_dollar'],
            'moaadil_afghani' => $data['moaadil_afghani'],
            'date' => $data['date'],
            'az_darak' => $data['az_darak'],
            'details' => $data['details'],
            'date' => $formattedDate,
        ]);

        // Redirect back to the index page with a success message
        return redirect()->route('sarafipayments')->with('success', 'Payment recorded successfully.');
    }
}
