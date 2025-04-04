<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sarafi_payments extends Model
{
    protected $table = 'sarafi_payments';
    protected $fillable = [
        'amount_afghani',
        'equivalent_dollar',
        'amount_dollar',
        'moaadil_afghani',
        'date',
        'az_darak',
        'details',
    ];
}
