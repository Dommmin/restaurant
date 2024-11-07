<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

class BookingService
{
    public function getAvailableSlots(Carbon $startDate, Carbon $endDate, int $intervalMinutes = 30)
    {
        // Pobierz wszystkie rezerwacje w danym zakresie
        $bookings = Booking::where('start_datetime', '>=', $startDate)
            ->where('end_datetime', '<=', $endDate)
            ->where('status', 'confirmed')
            ->get();

        // Utwórz tablicę wszystkich możliwych slotów
        $slots = [];
        $period = CarbonPeriod::create($startDate, $intervalMinutes . ' minutes', $endDate);

        foreach ($period as $date) {
            $slots[$date->format('Y-m-d')][$date->format('H:i')] = true;
        }

        // Oznacz zajęte sloty
        foreach ($bookings as $booking) {
            $bookingPeriod = CarbonPeriod::create(
                $booking->start_datetime,
                $intervalMinutes . ' minutes',
                $booking->end_datetime
            );

            foreach ($bookingPeriod as $date) {
                $slots[$date->format('Y-m-d')][$date->format('H:i')] = false;
            }
        }

        return $slots;
    }

    public function isSlotAvailable(Carbon $startDateTime, Carbon $endDateTime): bool
    {
        return Booking::where(function ($query) use ($startDateTime, $endDateTime) {
            $query->whereBetween('start_datetime', [$startDateTime, $endDateTime])
                ->orWhereBetween('end_datetime', [$startDateTime, $endDateTime])
                ->orWhere(function ($q) use ($startDateTime, $endDateTime) {
                    $q->where('start_datetime', '<=', $startDateTime)
                        ->where('end_datetime', '>=', $endDateTime);
                });
        })
            ->where('status', 'confirmed')
            ->doesntExist();
    }
}
