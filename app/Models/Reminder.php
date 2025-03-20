<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'note',
        'reminder_date',
        'created_by',
    ];

    protected $casts = [
        'reminder_date' => 'date', // Cast reminder_date to a Carbon instance
    ];

    // Optional: Define relationships or custom methods here
}