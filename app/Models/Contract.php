<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customers;
class Contract extends Model
{
    
    protected $fillable = [
        'customer_id',
        'product_id',
        'rate',
        'details',
    ];
    public function customer()
    {
        return $this->belongsTo(Customers::class,'customer_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
