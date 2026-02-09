<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class Cart extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    public function render()
    {
        return view('livewire.cart');
    }

    public function addToCart(int $productId): void
    {
        // Add functionality if needed inside cart (e.g. + button) but parent handles primary add
        // Reusing logic:
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }
        session()->put('cart', $cart);
        $this->dispatch('cart-updated'); // Notify parent for badge
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
            $this->dispatch('cart-updated'); // Notify parent for badge
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

    public function getCartCountProperty(): int
    {
        return count(session()->get('cart', []));
    }
}
