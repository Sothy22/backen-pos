<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'address',
        'email',
        'tel',
    ];

    public function order()
    {
        return $this->hasMany(Order::class, 'customer_id', '_id');
    }
}
