<?php

namespace App\Http\Controllers;

use App\Models\Tower;
use Illuminate\Http\Request;

class salesController extends Controller
{
    public function saleslist()
    {
        $towers = Tower::with('product')->get();
       return view('sales.sales',compact('towers'));
    }
    public function saleform(){
        
        $towers = Tower::with('product')->get();
       return view('sales.form',compact('towers'));
    }
    public function update(){

        return redirect('sales');
        
    }
    public function store(){

        return redirect('sales');
    }
    public function destroy(){

        return redirect('sales');
    }
}
