<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductDetail extends Model
{
    protected $connection = "mongodb";
    protected $table = "product_details"; // table = collection
    protected $fillable = [
        "barcode",
        "color",
        "make_in",
        "product_id"
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id", "_id");
    }
}
