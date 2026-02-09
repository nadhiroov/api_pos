<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class CheckoutCart extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    public function render()
    {
        return view('livewire.checkout-cart');
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            if ($quantity > 0) {
                $cart[$productId] = $quantity;
            } else {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
            $this->dispatch('cart-updated');
        }
    }

    public function removeFromCart(int $productId): void
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            $this->dispatch('cart-updated');
        }
    }

    public function getCartItemsProperty()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return collect();
        }

        $products = Product::whereIn('id', array_keys($cart))->get();

        return $products->map(function ($product) use ($cart) {
            $product->quantity = $cart[$product->id];
            $product->total_price = $product->price * $product->quantity;
            return $product;
        });
    }

    public function getCartTotalProperty(): float
    {
        return $this->cartItems->sum('total_price');
    }
}
