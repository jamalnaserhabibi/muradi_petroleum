<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'description',
        'amount',
        'item',
        'category',
    ];
}
