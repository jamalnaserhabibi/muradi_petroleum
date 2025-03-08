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

    public function towers()
    {
        return $this->belongsToMany(Tower::class, 'distributer')->withTimestamps();
    }

    // Relationship with Employee model
    public function distributer()
    {
        return $this->belongsTo(Employee::class, 'distributer_id');
    }

    // Relationship with Contract model
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    // Relationship with Tower model
    public function tower()
    {
        return $this->belongsTo(Tower::class);
    }
    
}
