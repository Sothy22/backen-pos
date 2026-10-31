<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            // Relationships One-To-Many (Eloquent Relationships)
            $collection->foreignId('category_id');
            $collection->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            $collection->dropForeign(['category_id']);
            $collection->dropIndex(['category_id']);
        });
    }
};
