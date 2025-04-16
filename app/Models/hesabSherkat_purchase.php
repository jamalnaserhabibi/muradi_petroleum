<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hesabSherkat_purchase extends Model

{
    protected $table = 'hesabSherkat_purchase';
    protected $fillable = ['product_id',   'submitted_to','supplier', 'amount', 'heaviness', 'rate', 'date', 'details'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    protected $casts = [
        'date' => 'date',
    ];
}
