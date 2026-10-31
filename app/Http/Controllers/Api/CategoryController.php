<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function search (Request $req) {
        $query = $req->query('query');
        $category = Category::where('name', 'like', '%' . $query . '%')->get();

        return response()->json([
            'Query' => $query,
            'data' => $category,
            'message' => 'Search Category Successfully'
        ], 200);
    }

    public function index()
    {
        // $category = Category::with(['products'])->get();
        $category = Category::with(['product'])->get();
        return response()->json([
            "data" => $category,
            "message" => "Get category successfully",
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|min:3|unique:categories,name',
            'description' => 'nullable|string|max:255|min:3',
            'status' => 'required|boolean',
        ]);

        // create new category
        $category = Category::create($validate);
        return response()->json([
            "data" => $category->load(['product']),
            "message" => "Category created successfully",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category not found",
            ], 404);
        } else {
            return response()->json([
                "data" => $category->load(['product']),
                "message" => "Get one category successfully",
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category not found",
            ], 404);
        }

        $validate = $request->validate([
            'name' => 'required|string|max:255|min:3|unique:categories,name,' .$category->_id,
            'description' => 'nullable|string|max:255|min:3',
            'status' => 'required|boolean',
        ]);

        // New data for Update
        $category->update($validate);

        return response()->json([
            "data" => $category->load(['product']),
            "message" => "Category Update successfully",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $category = Category::find($id);
        if (!$category) {
            return response()->json([
                "message" => "Category not found",
            ], 404);
        }

        // New data for Update
        $category->delete();

        return response()->json([
            "id" => $id,
            "data" => $category->load(['product']),
            "message" => "Category Deleted successfully",
        ], 200);
    }
}
