<?php

namespace App\Services;

use App\Repositories\CartRepository;
use Exception;


class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addToCart(int $productId, string $ipAddress, string $userAgent)
    {
        try {
            $cart = $this->getCurrentCart();
            if (!$cart) {
                $cart = $this->cartRepository->createCart($this->getSessionId(), $ipAddress, $userAgent);
            }
        } catch (Exception $e) {
            throw new Exception('Failed to create cart');
        }
        try {
            $existingCartItem = $this->cartRepository->findCartItemByProduct($cart->id, $productId);

            if ($existingCartItem) {
                $this->cartRepository->updateCartItemQuantity($existingCartItem->id, $existingCartItem->quantity + 1);
            } else {
                $this->cartRepository->createCartItem($cart->id, $productId);
            }
        } catch (Exception $e) {
            throw new Exception('Failed to add item to cart');
        }
    }

    public function removeItem(int $productId)
    {
        try {
            $removed = $this->cartRepository->deleteCartItem($this->getUserId(), $this->getSessionId(), $productId);

            if ($removed) {
                $this->cartRepository->createRemovedItem($this->getUserId(), $this->getSessionId(), $productId);
            }

            return $removed;
        } catch (Exception $e) {
            throw new Exception('Failed to remove item from cart');
        }
    }

    public function synchronizeCart(string $oldSessionId)
    {
        $userId = $this->getUserId();
        $newSessionId = $this->getSessionId();

        try {
            $oldCart = $this->cartRepository->findCartBySessionId($oldSessionId);
            $newCart = $this->cartRepository->findCartBySessionId($newSessionId);

            if ($oldCart && $newCart) {
                $this->cartRepository->mergeCarts($oldCart, $newCart);
            }
            $this->cartRepository->updateCartSessionAndUserId($newSessionId, $oldSessionId, $userId);
        } catch (Exception $e) {
            $this->logError('Failed to synchronize cart: ' . $e->getMessage());
        }
    }

    public function incrementCartItemQuantity(int $productId)
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartRepository->findCartItemByProduct($cart->id, $productId);

        if (!$cartItem) {
            throw new Exception('Cart item not found');
        }

        try {
            $this->cartRepository->updateCartItemQuantity($cartItem->id, $cartItem->quantity + 1);
        } catch (Exception $e) {
            throw new Exception('Failed to increment cart item quantity');
        }
    }

    public function setSessionId(string $oldSessionId)
    {
        try {
            $this->cartRepository->updateCartSessionId($this->getSessionId(), $oldSessionId);
        } catch (Exception $e) {
            throw new Exception('Failed to update the cart session id');
        }
    }

    public function decrementCartItemQuantity(int $productId)
    {
        $cart = $this->getCurrentCart();
        $cartItem = $this->cartRepository->findCartItemByProduct($cart->id, $productId);

        if (!$cartItem) {
            throw new Exception('Cart item not found');
        }

        try {
            $this->cartRepository->updateCartItemQuantity($cartItem->id, $cartItem->quantity - 1);
        } catch (Exception $e) {
            throw new Exception('Failed to decrement cart item quantity');
        }
    }

    protected function getCurrentCart()
    {
        $userId = $this->getUserId();
        $sessionId = $this->getSessionId();

        return $this->cartRepository->findCartByUserIdOrSessionId($userId, $sessionId);
    }

    protected function createCart(string $sessionId, string $ipAddress, string $userAgent)
    {
        return $this->cartRepository->createCart($sessionId, $ipAddress, $userAgent);
    }

    protected function getUserId()
    {
        return auth()->id() ?? null;
    }

    protected function getSessionId()
    {
        return session()->getId();
    }

    protected function logError(string $message)
    {
        logger()->error($message);
    }

    public function getCartItems()
    {
        $userId = $this->getUserId();
        $sessionId = $this->getSessionId();
        try {
            $cart = $this->cartRepository->findCartItemsByUserIdOrSessionId($userId, $sessionId);

            if ($cart->isEmpty()) {
                return [];
            }

            return $cart->flatMap(function ($cart) {
                return $cart->cartItems;
            });
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}
