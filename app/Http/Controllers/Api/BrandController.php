<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Models\Brand;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    use ApiResponseTrait;

    public function search (Request $req) {
        $query = $req->query('query');
        $brand = Brand::where('name', 'like', '%' . $query . '%')->get();

        return response()->json([
            'Query' => $query,
            'data' => $brand,
            'message' => 'Search Brand Successfully'
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brand = Brand::with(["product"])->get();

        // return response()->json([
        //     "data" => $brand,
        //     "message" => "Get Brand successfully",
        // ], 200);

        return $this->apiResponseSuccess($brand, 'Get Brand successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        // $validate = $request->validate([
        //     'name' => 'required|string|min:3|max:255|unique:category,name',
        //     'description' => 'nullable|string|min:3|max:255',
        // ]);

        $validate = $request->validated();

        $brand = Brand::create($validate);

        return response()->json([
            "data" => $brand->load(["product"]),
            "message" => "Brand created successfully",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $brand = Brand::find($id);

        if (!$brand) {
            return response()->json([
                "message" => "Brand not found",
            ], 404);
        } else {
            return response()->json([
                "data" => $brand->load(["product"]),
                "message" => "Get one Brand successfully",
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBrandRequest $request, string $id)
    {
        $brand = Brand::find($id);

        if(!$brand) {
            return response()->json([
                "message" => "Brand not found",
            ],404);
        }

        // $validate = $request->validate([
        //     "name" => "required|string|min:3|max:255|unique:brands,name,".$brand->_id,
        //     "description" => "nullable|string|min:3|max:255",
        // ]);
        $validate = $request->validated();

        $brand->update($validate);

        return response()->json([
            "data" => $brand->load(["product"]),
            "message" => "Brand updated successfully",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);

        if(!$brand) {
            return response()->json([
                "message" => "Brand not found",
            ],404);
        }

        $brand->delete();

        return response()->json([
            "id" => $id,
            "data" => $brand->load(["product"]),
            "message" => "Brand deleted successfully",
        ], 200);
    }
}
