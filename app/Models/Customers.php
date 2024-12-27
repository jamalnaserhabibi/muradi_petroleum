<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public $timestamps = true;
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'company',
        'date',
        'contact',
        'created_by',
        'document',
        'description',
    ];
    protected $casts = [
        'date' => 'date',
    ];
}
