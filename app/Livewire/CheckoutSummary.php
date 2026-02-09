<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class CheckoutSummary extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    public function render()
    {
        return view('livewire.checkout-summary');
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
