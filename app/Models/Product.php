<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_name', 'details'];


    public function sale()
    {
        return $this->hasMany(Sales::class);
    }
    public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }
    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
    public function towers()
    {
        return $this->hasMany(Tower::class);
    }
}
