<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hesabSherkat_payment extends Model
{
    protected $table = 'hesabSherkat_payment';
    protected $fillable = [
        'fromPerson',
        'fromChannel',
        'supplier',
        'amount',
        'date',
        'details',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
