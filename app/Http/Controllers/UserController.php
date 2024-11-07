<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Request;

class UserController extends Controller
{
    public function reservations()
    {
        $reservations = auth()->user()->reservations()
            ->with('table')
            ->latest()
            ->get();

        return inertia('User/Reservations', [
            'reservations' => $reservations,
        ]);
    }

    public function orders()
    {
       $orders = auth()->user()->orders()
            ->with('items.food')
            ->latest()
            ->get();

        return inertia('User/Orders', [
            'orders' => $orders
        ]);
    }
}
