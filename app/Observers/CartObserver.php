<?php

namespace App\Observers;

use App\Models\cart;
use Illuminate\Support\str;

class CartObserver
{
    /**
     * Handle the cart "created" event.
     */
    public function created(cart $cart): void
    {
        $cart->id=str::uuid();

    }

    /**
     * Handle the cart "updated" event.
     */
    public function updated(cart $cart): void
    {
        //
    }

    /**
     * Handle the cart "deleted" event.
     */
    public function deleted(cart $cart): void
    {
        //
    }

    /**
     * Handle the cart "restored" event.
     */
    public function restored(cart $cart): void
    {
        //
    }

    /**
     * Handle the cart "force deleted" event.
     */
    public function forceDeleted(cart $cart): void
    {
        //
    }
}
