<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            "status" => true,
            "message" => "success",
            "statusCode" => 200,
            "data" => [
                "orders" => $request->user()->orders
            ]
        ]);
    }

    public function show(Order $userOrder)
    {
        return response()->json([
            "status" => true,
            "message" => "success",
            "statusCode" => 200,
            "data" => [
                "order" => $userOrder
            ]
        ]);
    }

    public function update(Order $userOrder, Request $request)
    {
        $validated = $request->validate([
            "order_code" => "required|string|max:50",
            "product_id" => "required|numeric|exists:products,id",
            "quantity" => "required|numeric",
            "address" => "required|string|max:255",
        ]);

        if ($userOrder->shipping_at) {
            throw new UnprocessableEntityHttpException("Cannot to be change if product was shipped");
        }

        $userOrder->update($validated);

        return response()->json([
            "status" => true,
            "message" => "success",
            "statusCode" => 200,
            "data" => [
                "order" => $userOrder
            ]
        ]);
    }
}
