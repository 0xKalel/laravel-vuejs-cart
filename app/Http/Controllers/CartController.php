<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Services\CartService;
use Illuminate\Http\Request;
use Exception;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Add items to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $productId = $request->id;

        try {
            $this->cartService->addToCart($productId, $request->ip(), $request->userAgent());
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Item added to cart successfully']);
    }

    /**
     * Remove an item from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItem(Request $request)
    {
        $inputData = [
            'product_id' => $request->product_id,
        ];

        $validator = Validator::make($inputData, [
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $removed = $this->cartService->removeItem($request->product_id);

            if ($removed) {
                return response()->json(['message' => 'Item removed from cart successfully']);
            } else {
                return response()->json(['error' => 'Item not found in cart'], 404);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Increment the quantity of an item from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function increment(Request $request)
    {
        $inputData = [
            'product_id' => $request->product_id,
        ];

        $validator = Validator::make($inputData, [
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $this->cartService->incrementCartItemQuantity($request->product_id);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * decrement the quantity of an item from the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function decrement(Request $request)
    {
        $inputData = [
            'product_id' => $request->product_id,
        ];

        $validator = Validator::make($inputData, [
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $this->cartService->decrementCartItemQuantity($request->product_id);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
