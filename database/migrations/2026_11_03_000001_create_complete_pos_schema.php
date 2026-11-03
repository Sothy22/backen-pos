<?php

use Illuminate\Database\Migrations\Migration;
use MongoDB\Laravel\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasCollection('customers')) {
            Schema::create('customers', function (Blueprint $collection) {
                $collection->id();
                $collection->string('name');
                $collection->string('address')->nullable();
                $collection->string('email')->nullable()->index();
                $collection->string('tel')->nullable()->index();
                $collection->timestamps();
            });
        }

        if (!Schema::hasCollection('orders')) {
            Schema::create('orders', function (Blueprint $collection) {
                $collection->id();
                $collection->string('order_no')->unique();
                $collection->decimal('paid_amount', 12, 2)->default(0);
                $collection->decimal('total_amount', 12, 2)->default(0);
                $collection->text('remark')->nullable();
                $collection->string('payment_method');
                $collection->string('customer_id')->nullable()->index();
                $collection->timestamps();
            });
        }

        if (!Schema::hasCollection('order_detail')) {
            Schema::create('order_detail', function (Blueprint $collection) {
                $collection->id();
                $collection->decimal('price', 12, 2)->default(0);
                $collection->unsignedInteger('qty')->default(1);
                $collection->decimal('discount', 12, 2)->default(0);
                $collection->decimal('total', 12, 2)->default(0);
                $collection->string('order_id')->index();
                $collection->string('product_id')->index();
                $collection->timestamps();
            });
        }

        if (!Schema::hasCollection('suppliers')) {
            Schema::create('suppliers', function (Blueprint $collection) {
                $collection->id();
                $collection->string('name');
                $collection->string('code')->nullable()->unique();
                $collection->string('address')->nullable();
                $collection->string('email')->nullable()->index();
                $collection->string('website')->nullable();
                $collection->timestamps();
            });
        }

        if (!Schema::hasCollection('purchases')) {
            Schema::create('purchases', function (Blueprint $collection) {
                $collection->id();
                $collection->string('supplier_id')->index();
                $collection->decimal('shipping_cost', 12, 2)->default(0);
                $collection->decimal('paid', 12, 2)->default(0);
                $collection->date('paid_date')->nullable();
                $collection->boolean('status')->default(true);
                $collection->timestamps();
            });
        }

        if (!Schema::hasCollection('purchase_product')) {
            Schema::create('purchase_product', function (Blueprint $collection) {
                $collection->id();
                $collection->string('purchase_id')->index();
                $collection->string('product_id')->index();
                $collection->decimal('cost', 12, 2)->default(0);
                $collection->unsignedInteger('qty')->default(1);
                $collection->decimal('total_price', 12, 2)->default(0);
                $collection->decimal('discount', 12, 2)->default(0);
                $collection->boolean('status')->default(true);
                $collection->text('remark')->nullable();
                $collection->string('ref')->nullable()->index();
                $collection->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_product');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('order_detail');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
    }
};
