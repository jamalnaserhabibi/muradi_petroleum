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
        'customer_type', // Ensure this field is fillable if it's used in forms
        'contact',
        'created_by',
        'document',
        'description',
    ];
    protected $casts = [
        'date' => 'date',
    ];

    // Define the relationship with the CustomerTypes model
    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'customer_type', 'id');
    }
    public function contract()
    {
        return $this->hasOne(Contract::class,'customer_id');
    }
    public function sale()
    {
        return $this->hasMany(Sales::class);
    }
}
