<?php

use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint; for MySQl
use MongoDB\Laravel\Schema\Blueprint;  // For MongoDB
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $collection) {
            $collection->id();
            $collection->string("name", 255);
            $collection->text("description")->nullable();
            $collection->decimal("price", 10, 2)->default(0);
            $collection->unsignedInteger("qty")->default(1);
            $collection->decimal("discount", 8, 2)->default(0);
            $collection->boolean("status")->default(true);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
