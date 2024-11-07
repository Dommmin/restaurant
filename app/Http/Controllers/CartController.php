<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $userId = auth()->check() ? auth()->user()->id : request()->ip();

        $cartItems = CartItem::where('user_id', $userId)
            ->with('food')
            ->get();

        $table = Booking::where('user_id', $userId)
            ->where('date', now()->format('Y-m-d'))
            ->whereBetween('time', [now()->format('H:i'), now()->addHour()->format('H:i')])
            ->first();

        return inertia('Cart', [
            'cartItems' => $cartItems,
            'tableId' => $table?->id,
        ]);
    }

    public function store(Request $request)
    {
        $userId = auth()->check() ? auth()->user()->id : $request->ip();

        $cartItem = CartItem::where('user_id', $userId)
            ->where('food_id', $request->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => $userId,
                'food_id' => $request->id,
            ]);
        }

        return back()->with('success', 'Dodano do koszyka');
    }

    public function increment(CartItem $cartItem)
    {
        $cartItem->increment('quantity');


    }

    public function decrement(CartItem $cartItem)
    {
        $cartItem->decrement('quantity');
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();

        return back()->with('success', 'Usunięto z koszyka');
    }
}
