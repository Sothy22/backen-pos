<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $table = "orders";
    protected $fillable = [
        "order_no",
        "paid_amount",
        "total_amount",
        "remark",
        "payment_method",
        "customer_id",
    ];

    public function orderDetail() {
        return $this->hasMany(OrderDetail::class, 'order_id', '_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', '_id');
    }
}
