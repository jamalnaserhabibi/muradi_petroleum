<?php

namespace App\Http\Controllers;
use App\Models\Reminder;
use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;

class ReminderController extends Controller
{
    public function index(){
        $reminder = Reminder::orderBy('reminder_date', 'asc')->get();
        return view('reminder.reminder',compact('reminder'));
    }
    public function destroy($id){
        $reminder = Reminder::find($id);
        if (!$reminder) {
        return redirect()->route('reminders')->with('success', 'Reminder Deleted Successfully!');
        }
        $reminder->delete();
        return redirect()->route('reminders')->with('success', 'Reminder Deleted Successfully!');

    }
    public function create(Request $request)
    {  
        $date = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->remind_date)->toDateString();
        // Validate the request
        $request->validate([
            'note' => 'required|string',
            'created_by' => 'required|string',
        ]);

        // Create the reminder
        Reminder::create([
            'note' => $request->input('note'),
            'reminder_date' => $date,
            'created_by' => $request->input('created_by'),
        ]);

        return redirect()->route('reminders')->with('success', 'Reminder Added Successfully!');
    }
    public function update(Request $request)
        {
        $date = CalendarUtils::createCarbonFromFormat('Y/m/d', $request->remind_date)->toDateString();

            $request->validate([
                'id' => 'required|exists:reminders,id',
                'note' => 'required|string',
            ]);

            $reminder = Reminder::find($request->id);
            $reminder->note = $request->note;
            $reminder->reminder_date = $date;
            $reminder->save();

            return redirect()->back()->with('success', 'Reminder updated successfully!');
        }
}
