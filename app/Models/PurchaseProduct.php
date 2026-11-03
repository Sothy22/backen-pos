<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PurchaseProduct extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'purchase_product';

    protected $fillable = [
        'purchase_id',
        'product_id',
        'cost',
        'qty',
        'total_price',
        'discount',
        'status',
        'remark',
        'ref',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', '_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id');
    }
}
