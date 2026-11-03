<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Purchase::with(['supplier', 'purchaseProduct.product'])->get(),
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,_id',
            'shipping_cost' => 'nullable|numeric|min:0',
            'paid' => 'nullable|numeric|min:0',
            'paid_date' => 'nullable|date',
            'status' => 'nullable|boolean',
            'detail' => 'required|array|min:1',
            'detail.*.product_id' => 'required|exists:products,_id',
            'detail.*.cost' => 'required|numeric|min:0',
            'detail.*.qty' => 'required|integer|min:1',
            'detail.*.discount' => 'nullable|numeric|min:0',
            'detail.*.remark' => 'nullable|string',
            'detail.*.ref' => 'nullable|string',
        ]);

        $purchase = Purchase::create([
            'supplier_id' => $validated['supplier_id'],
            'shipping_cost' => (float) ($validated['shipping_cost'] ?? 0),
            'paid' => (float) ($validated['paid'] ?? 0),
            'paid_date' => $validated['paid_date'] ?? null,
            'status' => $validated['status'] ?? true,
        ]);

        foreach ($validated['detail'] as $item) {
            $qty = (int) $item['qty'];
            $cost = (float) $item['cost'];
            $discount = (float) ($item['discount'] ?? 0);
            $totalPrice = max(0, ($cost * $qty) - $discount);

            PurchaseProduct::create([
                'purchase_id' => $purchase->_id,
                'product_id' => $item['product_id'],
                'cost' => $cost,
                'qty' => $qty,
                'total_price' => $totalPrice,
                'discount' => $discount,
                'status' => true,
                'remark' => $item['remark'] ?? null,
                'ref' => $item['ref'] ?? null,
            ]);

            $product = Product::find($item['product_id']);
            if ($product) {
                $product->update(['qty' => (int) $product->qty + $qty]);
            }
        }

        return response()->json([
            'data' => $purchase->load(['supplier', 'purchaseProduct.product']),
            'message' => 'Purchase created successfully',
        ], 201);
    }

    public function show(string $id)
    {
        $purchase = Purchase::with(['supplier', 'purchaseProduct.product'])->find($id);
        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found'], 404);
        }

        return response()->json(['data' => $purchase], 200);
    }

    public function destroy(string $id)
    {
        $purchase = Purchase::find($id);
        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found'], 404);
        }

        $details = PurchaseProduct::where('purchase_id', $id)->get();
        foreach ($details as $detail) {
            $product = Product::find($detail->product_id);
            if ($product) {
                $product->update(['qty' => max(0, (int) $product->qty - (int) $detail->qty)]);
            }
        }

        $purchase->purchaseProduct()->delete();
        $purchase->delete();

        return response()->json(['id' => $id, 'message' => 'Purchase deleted successfully'], 200);
    }
}
