<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tower extends Model
{
    protected $fillable = [
        'serial',
        'name',
        'product_id',
        'details'
    ];

    /**
     * Get the product associated with the tower.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function Serial_Numbers()
    {
        return $this->hasMany(Serial_Numbers::class, 'tower_id', 'id');
    }
    public function sales()
    {
        return $this->hasMany(Sales::class, 'tower_id', 'id');
    }
}
