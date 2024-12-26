<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\type;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }
    public function dashboard()
    {
        return view('admin.dashboard');
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
