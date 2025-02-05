<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class paymentController extends Controller
{
    public function payment(){
        


        return view('payment/payment');
    }
}
