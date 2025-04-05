<?php

namespace App\View\Components;

use app\Facade\Cart;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CartMenu extends Component
{

    public $cartItems;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->cartItems = Cart::get();
        $this->cartItems = Cart::total();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cart-menu');
    }
}
