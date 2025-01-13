<?php

namespace App\Http\Controllers;

use App\Models\Tower;
use App\Models\Product;

use Illuminate\Http\Request;

class towerController extends Controller
{
    public function towerform()
    {
           
        $products = Product::select('id', 'product_name')
        ->orderBy('product_name', 'asc')
        ->get();

        return view('towers.form',compact('products'));
    }
    public function towers()
    {
        $towers = Tower::with('product')->get();
        return view('towers.tower',compact('towers'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial' => 'required|numeric|unique:towers,serial',
            'product_id' => 'required|exists:products,id',
            'details' => 'nullable|string|max:255'
        ]);
        Tower::create($request->all());
        return redirect()->route('towers')->with('success', 'Tower Added successfully');

    }
    public function edit($id)
    {
        $products = Product::select('id', 'product_name')
        ->orderBy('product_name', 'asc')
        ->get();

        $towers = Tower::findOrFail($id);
        return view('towers.form',compact('towers','products'));
    }
    public function destroy($id)
    {
        $tower = Tower::find($id);
        if ($tower) {
            $tower->delete();
        }
        return redirect()->route('towers')->with('success', 'Tower deleted successfully');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'serial' => 'required|numeric|unique:towers,serial,' . $id,
            'product_id' => 'required|exists:products,id',
            'details' => 'nullable|string|max:255'
        ]);

        $tower = Tower::findOrFail($id);

        $tower->update($request->all());
        return redirect()->route('towers')->with('success', 'Tower Updated successfully');
    }
}
