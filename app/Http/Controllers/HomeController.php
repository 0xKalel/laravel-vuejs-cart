<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CartItemResource;
use App\Models\Product;
use App\Services\CartService;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $cartService = app(CartService::class);
        $userId = auth()->id();
        $sessionId = session()->getId();
        $cartItems = $cartService->getCartItems($userId, $sessionId);
        $cartItemResources = CartItemResource::collection($cartItems);
        $productsResources = ProductResource::collection(Product::all());
        return Inertia::render('Home', [
            'products' => $productsResources,
            'cartItems' => $cartItemResources,
        ]);
    }
}
