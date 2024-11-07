<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __invoke()
    {
        $menu = Menu::where('active', true)
            ->with('foods.category')
            ->first();

        $categories = collect($menu?->foods)
            ->groupBy('category.name')
            ->toArray();

        return inertia('Menu', [
            'categories' => $categories,
        ]);
    }
}
