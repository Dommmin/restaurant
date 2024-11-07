<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Models\Booking;
use App\Models\Table;
use App\Models\WorkingHour;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TableController extends Controller
{
    public function __invoke(Request $request, Table $table)
    {
        $date = Carbon::parse($request->get('date', Carbon::now()));
        $dayOfWeek = strtolower($date->format('l'));

        $workingHours = Cache::remember("working_hours_{$dayOfWeek}", now()->endOfDay(), function() use ($dayOfWeek) {
            return WorkingHour::where('day_of_week', $dayOfWeek)->first();
        });

        // Tworzenie slotów czasu
        $openTime = Carbon::parse($workingHours->open_time);
        $closeTime = Carbon::parse($workingHours->close_time);
        $availableSlots = collect(range(0, $openTime->diffInHours($closeTime->addSecond()) - 1))
            ->map(fn($i) => $openTime->copy()->addHours($i))
            ->filter(function($slot) use ($date) {
                if ($date->isToday()) {
                    return $slot->greaterThan(Carbon::now()->addHour()->startOfHour());
                }

                return true;
            })
            ->map(fn($slot) => $slot->format('H:i'))
            ->toArray();

        // Pobieranie zarezerwowanych slotów
        $bookedSlots = Booking::where('table_id', $table->id)
            ->where('date', $date->format('Y-m-d'))
            ->where('status', StatusEnum::CONFIRMED->value)
            ->pluck('time')
            ->toArray();

        return inertia('Table/Show', [
            'table' => $table,
            'date' => $date->format('Y-m-d'),
            'availableSlots' => array_values(array_diff($availableSlots, $bookedSlots)),
        ]);
    }
}
