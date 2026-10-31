<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Supplier extends Model
{
    protected $connection = 'mongodb';
    protected $table = "suppliers";
    protected $fillable = [
        "name",
        "code",
        "email",
        "address",
        "website",
    ];

    public function product()
    {
        return $this->hasMany(Product::class, "supplier_id", "_id");
    }
}
