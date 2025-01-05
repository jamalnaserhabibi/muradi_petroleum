<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    public $timestamps = true;
    protected $table = 'customer_types';
    protected $fillable = ['customer_type'];

    // Define reverse relationship (optional)
    public function customers()
    {
        return $this->hasMany(Customers::class, 'customer_type', 'id');
    }
}
