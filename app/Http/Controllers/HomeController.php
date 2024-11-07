<?php

namespace App\Http\Controllers;

use App\Enums\PlaceEnum;
use App\Models\Table;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $tables = Table::query()
            ->when($request->input('place'), function ($query) use ($request) {
                return $query->where('places', $request->get('place'));
            })
            ->get();

        $filters = $request->only(['place', 'date']);

        return inertia('Home', [
            'tables' => $tables,
            'places' => PlaceEnum::values(),
            'filters' => $filters,
        ]);
    }
}
