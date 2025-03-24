<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributer extends Model
{
    protected $table = 'distributer';
     // Relationship with Employee model
     public function employee()
     {
         return $this->belongsTo(Employee::class, 'employee_id');
     }
}
