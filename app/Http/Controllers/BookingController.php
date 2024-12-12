<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\BookingStoreRequest;
use App\Models\Booking;
use App\Models\Table;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function store(Table $table, BookingStoreRequest $request)
    {
        auth()->user()->reservations()->create(
            $request->validated() + [
            'table_id' => $table->id,
            ]
        );

        return to_route('home')->with('success', 'Rezerwacja została zapisana');
    }

    public function cancel(Booking $booking)
    {
//        $this->authorize('cancel', $booking);

        $booking->update(['status' => StatusEnum::CANCELLED->value]);

        return back()->with('success', 'Rezerwacja została anulowana');
    }
}
