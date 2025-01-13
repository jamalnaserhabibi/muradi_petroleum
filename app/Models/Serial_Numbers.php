<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Serial_Numbers extends Model
{
    protected $fillable = ['tower_id', 'serial', 'date'];
    
    public function tower()
    {
        return $this->belongsTo(Tower::class, 'tower_id', 'id');
    }
}
