<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Support\Facades\DB;

use App\Models\Tower;
use Illuminate\Http\Request;
class salesController extends Controller
{
    public function sales()
    {
        $towers = Tower::with('product')->get();
       return view('sales.sales', compact('towers'));
    }
    public function salesform()
    {
        $customers = Customers::with(['contract.product'])->get();
        $towers = Tower::with('product')->get();
       return view('sales.form', compact('towers','customers'));
    }
    
   
    
    public function store(Request $request){

    }
    public function destroy(){

        return redirect('sales');
    }
}
