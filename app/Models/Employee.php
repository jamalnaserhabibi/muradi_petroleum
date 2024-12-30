<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'fullname',
        'photo',
        'salary',
        'description',
    ];
    protected $casts = [
        'date' => 'date',
    ];
}
