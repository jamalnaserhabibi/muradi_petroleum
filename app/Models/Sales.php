<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = ['contract_id', 'tower_id', 'amount', 'rate','date', 'description'];

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function tower()
    {
        return $this->belongsTo(Tower::class, 'tower_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }
    protected $casts = [
        'date' => 'datetime',
    ];
}
