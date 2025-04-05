<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sarafi_pickup extends Model
{
    protected $table = 'sarafi_pickup';
    protected $fillable = [
        'amount',
        'toAccount',
        'date',
        'az_darak',
        'details',
    ];
}
