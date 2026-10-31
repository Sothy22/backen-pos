<?php

namespace App\Models;

use App\Models\Product;
use MongoDB\Laravel\Eloquent\Model;

class Brand extends Model
{
    protected $connection = 'mongodb';
    protected $collection = "brands";
    protected $fillable =[
        "name",
        "status",
        "description",
    ];

    public function product(){
        return $this->hasMany(Product::class, "brand_id", "_id");
    }
}
