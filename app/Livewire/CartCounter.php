<?php

namespace App\Livewire;

use Livewire\Component;

class CartCounter extends Component
{
    protected $listeners = ['cart-updated' => '$refresh'];

    public function render()
    {
        return view('livewire.cart-counter');
    }

    public function getCartCountProperty(): int
    {
        return count(session()->get('cart', []));
    }
}
