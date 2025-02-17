<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Payment extends Model
{
    protected $table = 'payment';
    protected $fillable = ['contract_id','amount','details'];
    
    protected $casts = [
        'date' => 'date',
    ];


    public function customer()
    {
        return $this->belongsTo(Customers::class);
    }


    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
