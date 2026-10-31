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
        Schema::create('product_details', function (Blueprint $collection) {
            $collection->id();

            $collection->string('barcode')->unique();
            $collection->string('color');
            $collection->string('make_in');

            // One-to-One relationship
            $collection->foreignId('product_id')->unique();
            $collection->index('product_id');

            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
