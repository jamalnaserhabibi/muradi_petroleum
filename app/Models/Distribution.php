<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $table = 'distribution';

    protected $fillable = [
        'contract_id',
        'distributer_id',
        'tower_id',
        'rate',
        'amount',
        'description',
    ];

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
        return $this->belongsTo(Tower::class, 'tower_id', 'id');
    }
}