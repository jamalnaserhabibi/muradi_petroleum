<?php

namespace App\Http\Controllers;

use App\Models\CustomerTypes;
use Illuminate\Http\Request;

class customer_typeController extends Controller
{
    public function customer_types(){

        return view('purchase.form',compact('products'));
    }
}
