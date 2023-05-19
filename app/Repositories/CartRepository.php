<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\RemovedItem;
use Illuminate\Support\Carbon;

class CartRepository
{
    private $cartModel;
    private $cartItemModel;
    private $removedItemModel;

    public function __construct(Cart $cartModel, CartItem $cartItemModel, RemovedItem $removedItemModel)
    {
        $this->cartModel = $cartModel;
        $this->cartItemModel = $cartItemModel;
        $this->removedItemModel = $removedItemModel;
    }

    /**
     * Find a cart by user ID or session ID.
     *
     * @param int|null    $userId
     * @param string|null $sessionId
     * @return Cart|null
     */
    public function findCartByUserIdOrSessionId(?int $userId, ?string $sessionId): ?Cart
    {
        return $this->cartModel->when($userId !== null, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
            ->orWhere('session_id', $sessionId)
            ->first();
    }

    /**
     * Find a cart by session ID.
     *
     * @param string $sessionId
     * @return Cart|null
     */
    public function findCartBySessionId(string $sessionId): ?Cart
    {
        return $this->cartModel
            ->where('session_id', $sessionId)
            ->first();
    }

    /**
     * Find a cart item by cart ID and product ID.
     *
     * @param int $cartId
     * @param int $productId
     * @return CartItem|null
     */
    public function findCartItemByProduct(int $cartId, int $productId): ?CartItem
    {
        return $this->cartItemModel
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }

    /**
     * Find a cart by user ID or session ID with associated items.
     *
     * @param int|null    $userId
     * @param string|null $sessionId
     * @return Collection|null
     */
    public function findCartItemsByUserIdOrSessionId(?int $userId, ?string $sessionId): ?Collection
    {
        return $this->cartModel->when($userId !== null, function ($query) use ($userId) {
            return $query->where('user_id', $userId);
        })
            ->orWhere('session_id', $sessionId)
            ->with('cartItems.product')
            ->get();
    }

    /**
     * Create a new cart.
     *
     * @param string $sessionId
     * @return Cart 
     */
    public function createCart(string $sessionId, string $ipAddress, string $userAgent): Cart
    {

        return $this->cartModel->create([
            'session_id' => $sessionId,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'last_activity' => now(),
        ]);
    }

    /**
     * Create a new cart item.
     *
     * @param int $cartId
     * @param int $productId
     * @return CartItem
     */
    public function createCartItem(int $cartId, int $productId): CartItem
    {
        return $this->cartItemModel::create([
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => 1,
        ]);
    }

    /**
     * Create a new removed item record.
     *
     * @param int|null    $userId
     * @param string|null $sessionId
     * @param int         $productId
     * @return RemovedItem
     */
    public function createRemovedItem(?int $userId, ?string $sessionId, int $productId): RemovedItem
    {
        return $this->removedItemModel::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'product_id' => $productId,
            'removed_at' => now(),
        ]);
    }

    /**
     * Update the cart's session ID and user ID.
     *
     * @param string $newSessionId
     * @param string $oldSessionId
     * @param int    $userId
     * @return int
     */
    public function updateCartSessionAndUserId(string $newSessionId, string $oldSessionId, int $userId): int
    {
        return $this->cartModel::where('session_id', $oldSessionId)
            ->update(['user_id' => $userId, 'session_id' => $newSessionId]);
    }

    /**
     * Update the cart's session ID.
     *
     * @param string $newSessionId
     * @param string $oldSessionId
     * @param int    $userId
     * @return int
     */
    public function updateCartSessionId(string $newSessionId, string $oldSessionId): int
    {
        return $this->cartModel::where('session_id', $oldSessionId)
            ->update(['session_id' => $newSessionId]);
    }

    /**
     * Update the quantity of a cart item.
     *
     * @param int $cartItemId
     * @param int $quantity
     * @return int
     */
    public function updateCartItemQuantity(int $cartItemId, int $quantity): int
    {
        return $this->cartItemModel::where('id', $cartItemId)
            ->update(['quantity' => $quantity]);
    }

    /**
     * Delete a cart item.
     *
     * @param int|null    $userId
     * @param string|null $sessionId
     * @param int         $productId
     * @return int
     */
    public function deleteCartItem(?int $userId, ?string $sessionId, int $productId): int
    {
        return $this->cartItemModel::where(function ($query) use ($userId, $sessionId) {
            $query->whereHas('cart', function ($query) use ($userId, $sessionId) {
                $query->where('user_id', $userId)
                    ->orWhere('session_id', $sessionId);
            });
        })
            ->where('product_id', $productId)
            ->delete();
    }

    /**
     * Merge the old cart into the new cart.
     *
     * @param Cart $oldCart
     * @param Cart $newCart
     * @return void
     */
    public function mergeCarts(Cart $oldCart, Cart $newCart): void
    {
        $oldCartItems = $oldCart->cartItems()->get();

        foreach ($oldCartItems as $oldCartItem) {
            $newCartItem = $newCart->cartItems()->where('product_id', $oldCartItem->product_id)->first();

            if ($newCartItem) {
                // Cart item already exists in the new cart, update the quantity
                $newCartItem->update(['quantity' => $newCartItem->quantity + $oldCartItem->quantity]);
            } else {
                // Cart item doesn't exist in the new cart, create a new cart item
                $newCart->cartItems()->create([
                    'product_id' => $oldCartItem->product_id,
                    'quantity' => $oldCartItem->quantity,
                ]);
            }

            $oldCartItem->delete();
        }

        $oldCart->delete();
    }
}
