<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    
    public function dashboard()
{
    $sarafiPayments = DB::table('sarafi_payments')
        ->select(
            DB::raw('COALESCE(SUM(equivalent_dollar), 0) as total_equivalent_dollar'),
            DB::raw('COALESCE(SUM(amount_dollar), 0) as total_amount_dollar'),
            DB::raw('MAX(created_at) as latest_payment_date')
        )
        ->first();

    $sarafiPickups = DB::table('sarafi_pickup')
        ->select(
            DB::raw('COALESCE(SUM(amount), 0) as total_amount'),
            DB::raw('MAX(created_at) as latest_pickup_date')
        )
        ->first();

    // Calculate the balance
    $balance = ($sarafiPayments->total_equivalent_dollar + $sarafiPayments->total_amount_dollar) - $sarafiPickups->total_amount;

    return view('admin.dashboard', compact('sarafiPayments', 'sarafiPickups', 'balance'));
}
  
    public function useraccounts()
    {
        $users = User::all();
        return view('admin.useraccounts',compact('users'));
    }

    public function table()
    {
        $type = type::all();
        return view('admin.table',compact('type'));
    }
}
