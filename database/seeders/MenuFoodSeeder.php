<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuFoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = Menu::inRandomOrder()->first();

        $menu->update([
            'active' => true,
        ]);

        $menu->foods()->attach(Food::inRandomOrder()->pluck('id')->toArray());
    }
}
