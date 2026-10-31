<?php

namespace App\Models;

use App\Models\Product;
use MongoDB\Laravel\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'categories';
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function product(){
        return $this->hasMany(Product::class, "category_id", "_id");
    }
}
