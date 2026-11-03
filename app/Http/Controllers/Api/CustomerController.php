<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Customer::with('order')->get()], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'tel' => 'nullable|string|max:30',
        ]);

        return response()->json([
            'data' => Customer::create($validated),
            'message' => 'Customer created successfully',
        ], 201);
    }

    public function show(string $id)
    {
        $customer = Customer::with('order')->find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        return response()->json(['data' => $customer], 200);
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->_id,
            'tel' => 'nullable|string|max:30',
        ]);

        $customer->update($validated);
        return response()->json(['data' => $customer->load('order'), 'message' => 'Customer updated successfully'], 200);
    }

    public function destroy(string $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $customer->delete();
        return response()->json(['id' => $id, 'message' => 'Customer deleted successfully'], 200);
    }
}
