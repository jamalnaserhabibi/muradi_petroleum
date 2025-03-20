<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index(){
        return view('reminder.reminder');
    }
}
