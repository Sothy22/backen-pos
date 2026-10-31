<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    public function search (Request $req) {
        $query = $req->query('query');
        $supplier = Supplier::where('name', 'like', '%' . $query . '%')->get();

        return response()->json([
            'Query' => $query,
            'data' => $supplier,
            'message' => 'Search Supplier Successfully'
        ], 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::query()->get();

        // return response()->json([
        //     "data" => $brand,
        //     "message" => "Get Supplier successfully",
        // ], 200);

        return $this->apiResponseSuccess($supplier, 'Get Supplier successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'website' => 'required|string|max:255',
        ]);

        $supplier = Supplier::create($validate);

        return response()->json([
            "data" => $supplier->load(["product"]),
            "message" => "Spplier created successfully",
        ], 201);
    }


    public function storeBluk(Request $request)
    {
        $validate = $request->validate([
            'supplier' => 'required|array|max:255',
            'supplier.*.name' => 'required|string|max:255',
            'supplier.*.code' => 'required|string|max:255',
            'supplier.*.email' => 'required|string|max:255',
            'supplier.*.address' => 'required|string|max:255',
            'supplier.*.website' => 'required|string|max:255',
        ]);

        foreach($validate['supplier'] as $item){
            $supplier = Supplier::create($item);
        }


        return response()->json([
            "data" => $supplier,
            "message" => "Import Supplier Successfully",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                "message" => "supplier not found",
            ], 404);
        } else {
            return response()->json([
                "data" => $supplier->load(["product"]),
                "message" => "Get one supplier successfully",
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);

        if(!$supplier) {
            return response()->json([
                "message" => "supplier not found",
            ],404);
        }

        $validate = $request->validate([
            'name' => 'string|max:255',
            'code' => 'string|max:255',
            'email' => 'string|max:255',
            'address' => 'string|max:255',
            'website' => 'string|max:255',
        ]);

        $supplier->update($validate);

        return response()->json([
            "data" => $supplier->load(["product"]),
            "message" => "Supplier updated successfully",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);

        if(!$supplier) {
            return response()->json([
                "message" => "supplier not found",
            ],404);
        }

        $supplier->delete();

        return response()->json([
            "id" => $id,
            "data" => $supplier->load(["product"]),
            "message" => "Supplier deleted successfully",
        ], 200);
    }
}
