<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductDetailController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PurchaseController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

/// ============= API endpoint =============

// Authentication
Route::post("register", [AuthController::class, 'register']);
Route::post("login", [AuthController::class, 'login']);

Route::prefix('auth')->middleware('auth:sanctum')->group(function () {
    Route::post("logout", [AuthController::class, 'logout']);
    Route::get("me", [AuthController::class, 'me']);
});

// Product
Route::prefix("product")->group(function () {
    Route::get("/", [ProductController::class, "index"]);
    Route::get("/{id}", [ProductController::class, "getOne"]);
    Route::post("/", [ProductController::class, "store"]);
    Route::put("/{id}", [ProductController::class, "update"]);
    Route::delete("/{id}", [ProductController::class, "destroy"]);
});

Route::prefix("product_detail")->group(function () {
    Route::get("/", [ProductDetailController::class, "index"]);
    Route::get("/{id}", [ProductDetailController::class, "show"]);
    Route::post("/", [ProductDetailController::class, "store"]);
    Route::put("/{id}", [ProductDetailController::class, "update"]);
    Route::delete("/{id}", [ProductDetailController::class, "destroy"]);
});

Route::prefix("category")->group(function () {
    Route::get("/", [CategoryController::class, "index"]);
    Route::get("/search", [CategoryController::class, "search"]);
    Route::get("/{id}", [CategoryController::class, "show"]);
    Route::post("/", [CategoryController::class, "store"]);
    Route::put("/{id}", [CategoryController::class, "update"]);
    Route::delete("/{id}", [CategoryController::class, "destroy"]);
});

Route::prefix("brand")->group(function () {
    Route::get("/", [BrandController::class, "index"]);
    Route::get("/search", [BrandController::class, "search"]);
    Route::get("/{id}", [BrandController::class, "show"]);
    Route::post("/", [BrandController::class, "store"]);
    Route::put("/{id}", [BrandController::class, "update"]);
    Route::delete("/{id}", [BrandController::class, "destroy"]);
});

Route::prefix("order")->group(function () {
    Route::get("/", [OrderController::class, "index"]);
    Route::get("/{id}", [OrderController::class, "show"]);
    Route::post("/", [OrderController::class, "store"]);
    Route::put("/{id}", [OrderController::class, "update"]);
    Route::delete("/{id}", [OrderController::class, "destroy"]);
});

Route::prefix("supplier")->group(function () {
    Route::get("/search", [SupplierController::class, "search"]);
    Route::get("/", [SupplierController::class, "index"]);
    Route::get("/{id}", [SupplierController::class, "show"]);
    Route::post("/", [SupplierController::class, "store"]);
    Route::post("/bluk", [SupplierController::class, "storeBluk"]);
    Route::put("/{id}", [SupplierController::class, "update"]);
    Route::delete("/{id}", [SupplierController::class, "destroy"]);
});

Route::prefix("customer")->group(function () {
    Route::get("/", [CustomerController::class, "index"]);
    Route::get("/{id}", [CustomerController::class, "show"]);
    Route::post("/", [CustomerController::class, "store"]);
    Route::put("/{id}", [CustomerController::class, "update"]);
    Route::delete("/{id}", [CustomerController::class, "destroy"]);
});

Route::prefix("purchase")->group(function () {
    Route::get("/", [PurchaseController::class, "index"]);
    Route::get("/{id}", [PurchaseController::class, "show"]);
    Route::post("/", [PurchaseController::class, "store"]);
    Route::delete("/{id}", [PurchaseController::class, "destroy"]);
});
