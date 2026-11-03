<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // for MongoDB

class Product extends Model
{
    //
    protected $connection = 'mongodb';
    protected $table = "products"; // table = collection
    protected $fillable = [
        "name",
        "description",
        "price",
        "qty",
        "discount",
        "status",
        "category_id",
        "brand_id",
        "image",
        "image_url",
        "image_public_id",
        "supplier_id"
    ];

    public function productDetail()
    {
        return $this->hasOne(ProductDetail::class, "product_id", "_id");
    }

    public function category(){
        return $this->belongsTo(Category::class, "category_id", "_id");
    }

    public function brand(){
        return $this->belongsTo(Brand::class, "brand_id", "_id");
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, "supplier_id", "_id");
    }

    public function orderDetail() {
        return $this->hasMany(OrderDetail::class, 'product_id', '_id');
    }
}
