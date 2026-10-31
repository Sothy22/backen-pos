<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class OrderDetail extends Model
{
    protected $connection = 'mongodb';
    protected $table = "order_detail";
    protected $fillable = [
        "price",
        "qty",
        "discount",
        "total",
        "order_id",
        "total_id",
        "product_id",
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }
    public function order() {
        return $this->belongsTo(Order::class, 'order_id', '_id');
    }
}
