<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseTrait;
    public function index() {
        $order = Order::with('orderDetail')->get();

        return $this->apiResponseSuccess($order, 'Get Order SSuccessfully', 200);
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'paid_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'remark' => 'nullable|string',
            'payment_method' => 'required|string',
            'detail' => 'required|array',
            'detail.*.price' => 'required|numeric|min:0',
            'detail.*.qty' => 'required|integer|min:1',
            'detail.*.discount' => 'required|numeric|min:0',
            'detail.*.total' => 'required|numeric|min:0',
            'detail.*.product_id' => 'required|exists:products,id',
        ]);

        $orderNo = null;
        // Generate order_no
        $lastOrder = Order::orderBy('_id', 'desc')->first();
        if ($lastOrder) {
            $lastOrderNo = (int) preg_replace('/\D+/', '', $lastOrder->order_no);
            $orderNo = 'ORD' . str_pad($lastOrderNo + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $orderNo = 'ORD00001';
        }

        // create order
        $order = Order::create([
            'order_no' => $orderNo,
            'paid_amount' => (float) $validate['paid_amount'],
            'total_amount' => (float) $validate['total_amount'],
            'remark' => $validate['remark'] ?? null,
            'payment_method' => $validate['payment_method'],
        ]);

        // create order detail
        if ($order) {
            foreach ($validate['detail'] as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'price' => (float) $item['price'],
                    'qty' => (int) $item['qty'],
                    'discount' => (float) $item['discount'],
                    'total' => (float) $item['total'],
                ]);

                // stock
                $product = Product::find($item['product_id']);
                $currentQty = (int) $product->qty;
                $orderQty = (int) $item['qty'];
                $newQty = max(0, $currentQty - $orderQty);
                $product->update(['qty' => $newQty]);
            }

            return $this->apiResponseSuccess($order->load('orderDetail'), 'Create Order SSuccessfully', 201);
        }
    }

    public function show(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->apiNotFound();
        } else {
            return $this->apiResponseSuccess($order->load('orderDetail'), 'Get Order SSuccessfully', 201);
        }
    }

    public function update(Request $request, string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->apiNotFound();
        }

        $order->update($request->only(['order_no', 'paid_amount', 'total_amount', 'remark', 'payment_method']));

        return $this->apiResponseSuccess($order->load('orderDetail'), 'Update Order SSuccessfully', 202);
    }

    public function destroy(string $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return $this->apiNotFound();
        }

        $order->delete();
        $order->orderDetail()->delete();

        return $this->apiResponseSuccess($order->load('orderDetail'), 'Delete Order SSuccessfully', 202);
    }
}
