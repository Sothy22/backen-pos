<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function index()
    {
        $productDetail = ProductDetail::with("product")->get();

        return response()->json([
            "data" => $productDetail,
            "message" => "Get Product successfully"
        ], 200);
    }

    public function store(request $req)
    {

        // H

        // ====== Validation =====
        $validate = $req->validate([
            "barcode" => "required|string|min:3|max:255|unique:products_details,barcode",
            "color" => "nullable|string|min:3|max:255",
            "make_in" => "required",
            "product_id" => "required|exists:products,_id",
        ]);

        // create new product into DB
        $productDetail = new ProductDetail();

        $productDetail->fill($validate);
        $productDetail->save();

        return response()->json([
            "data" => $productDetail,
            "message" => "Create Product successfully"
        ], 201);
    }

    public function show($id)
    {
        $productDetail = ProductDetail::find($id);
        if (!$productDetail) {
            return response()->json([
                "message" => "Product is not found",
            ], 404);
        }

        return response()->json([
            "data" => $productDetail,
            "message" => "Get Product successfully"
        ], 200);
    }
}
