<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class ProductController extends Controller
{
    private function cloudinary(): Cloudinary
    {
        return new Cloudinary(config('cloudinary.url'));
    }

    // ============ CRUD Operation ============

    // Read Data
    public function index()
    {
        $product = Product::with(['productDetail','category','brand'])->get();

        return response()->json([
            "data" => $product,
            "message" => "Get Product successfully"
        ], 200);
    }

    // Store new data
    public function store(Request $req)
    {

        // ====== NO Validation =====
        // $inputData = [
        //     "name" => $req->input("name"),
        //     "description" => $req->input("description"),
        //     "price" => $req->input("price"),
        //     "qty" => $req->input("qty"),
        //     "discount" => $req->input("discount"),
        //     "status" => $req->input("status"),
        // ];

        // ====== Validation =====
        $validate = $req->validate([
            "name" => "required|string|min:3|max:255|unique:products,name",
            "description" => "nullable",
            "price" => "required|integer",
            "qty" => "required|integer|min:1",
            "discount" => "required|integer|min:0",
            "status" => "required|boolean",
            "category_id" => "required|exists:categories,_id",
            "brand_id" => "required|exists:brands,_id",
            "image" => "nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048" //2M = 2048 (1M = 1024)
        ]);

        // // Handle inmage upload
        // if ($req->hasFile("image")) {
        //     $validate['image'] = $req->file('image')->store('porducts', 'public');
        // }
        if ($req->hasFile('image')) {
            // Upload new image
            $uploaded = $this->cloudinary()->uploadApi()->upload(
                $req->file('image')->getRealPath(),
                ['folder' => config('cloudinary.upload_folder', 'img_pos_system')]
            );
            $validate['image_url'] = $uploaded['secure_url'];
            $validate['image_public_id'] = $uploaded['public_id'];
        }
        unset($validate['image']);


        // create new product into DB
        $product = new Product();

        // $product->fill($inputData); // for use NO validation
        $product->fill($validate); // for use validation
        $product->save();

        return response()->json([
            "data" => $product->load(['productDetail','category','brand']),
            "message" => "Create Product successfully"
        ], 201);
    }


    /// Get One
    public function getOne($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                "message" => "Product is not found",
            ], 404);
        }

        return response()->json([
            "data" => $product->load(['productDetail','category','brand']),
            "message" => "Get Product successfully"
        ], 200);
    }

    // Update Data
    public function update(Request $req, $id)
    {
        // find Product
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                "message" => "Product is not found",
            ], 404);
        }

        // ====== NO Validation =====
        // // new data for update
        // $inputData = [
        //     "name" => $req->input("name"),
        //     "description" => $req->input("description"),
        //     "price" => $req->input("price"),
        //     "qty" => $req->input("qty"),
        //     "discount" => $req->input("discount"),
        //     "status" => $req->input("status"),
        // ];

        // ====== Validation =====
        $validate = $req->validate([
            "name" => "sometimes|required|string|min:3|max:255|unique:products,name," . $product->_id,
            "description" => "sometimes|nullable",
            "price" => "sometimes|required|integer",
            "qty" => "sometimes|required|integer|min:1",
            "discount" => "sometimes|required|integer|min:0",
            "status" => "sometimes|required|boolean",
            "category_id" => "sometimes|required|exists:categories,_id",
            "brand_id" => "sometimes|required|exists:brands,_id",
            "image" => "sometimes|nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048" //2M = 2048 (1M = 1024)
        ]);

        //  // Handle inmage upload
        // if ($req->hasFile("image")) {
        //     // Delete old image
        //     if ($product->image && Storage::disk('public')->exists($product->image)) {
        //         Storage::disk('public')->delete($product->image);
        //     }

        //     // New image
        //     $validate['image'] = $req->file('image')->store('porducts', 'public');
        // }

        if ($req->hasFile('image')) {    // Delete old image
            if (!empty($product->image_public_id)) {
                $this->cloudinary()->uploadApi()->destroy($product->image_public_id);
            }
            // Upload new image
            $uploaded = $this->cloudinary()->uploadApi()->upload(
                $req->file('image')->getRealPath(),
                ['folder' => config('cloudinary.upload_folder', 'img_pos_system')]
            );
            $validate['image_url'] = $uploaded['secure_url'];
            $validate['image_public_id'] = $uploaded['public_id'];
        }
        unset($validate['image']);

        // Update new product into DB
        // $product->fill($inputData); // for use NO validation
        $product->fill($validate); // for use validation
        $product->save();

        return response()->json([
            "data" => $product->load(['productDetail','category','brand']),
            "message" => "Update Product successfully"
        ], 200);
    }

    // Delete data
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                "message" => "Product is not found",
            ], 404);
        }

        // Delete image
        if (!empty($product->image_public_id)) {
            $this->cloudinary()->uploadApi()->destroy($product->image_public_id);
        }

        $product->delete();

        return response()->json([
            "data" => $product->load(['productDetail','category','brand']),
            "message" => "Delete Product successfully"
        ], 200);
    }
}
