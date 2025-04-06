<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model

{
    protected $table = 'purchase';
    protected $fillable = ['product_id',   'document','supplier', 'amount', 'heaviness', 'rate', 'date', 'details'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    protected $casts = [
        'date' => 'date',
    ];
}
