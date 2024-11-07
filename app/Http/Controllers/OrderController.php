<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Http\Requests\OrderStoreRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Payment;
use Devpark\Transfers24\Exceptions\RequestException;
use Devpark\Transfers24\Exceptions\RequestExecutionException;
use Devpark\Transfers24\Requests\Transfers24;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Termwind\render;

class OrderController extends Controller
{
    public function __construct(private Transfers24 $transfers24)
    {
    }

    /**
     * @param OrderStoreRequest $request
     * @return void
     */
    public function store(OrderStoreRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $userId = auth()->check() ? auth()->user()->id : request()->ip();

            $cartItems = CartItem::where('user_id', $userId)->with('food')->get();

            $order = Order::create([
                'user_id' => $userId,
                'table_id' => $validated['table_id'],
            ]);

            $items = [];

            foreach ($cartItems as $cartItem) {
                $items[] = [
                    'food_id' => $cartItem->food_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->food->price,
                ];
            }

            $order->items()->createMany($items);

            CartItem::where('user_id', $userId)->delete();

            return $this->processPayment($order, $validated);
        });
    }

    public function confirmation()
    {
        $query = auth()->check()
            ? auth()->user()->orders()
            : Order::where('user_id', request()->ip());

        $order = $query->with('items.food')->latest()->first();

        return inertia('Order/Confirmation', [
            'order' => $order,
        ]);
    }

    public function status(Request $request)
    {
        $response = $this->transfers24->receive($request);
        $payment = Payment::where('session_id', $response->getSessionId())->first();

        if ($response->isSuccess()) {
            $payment->update([
                'status' => PaymentStatus::SUCCESS,
            ]);
        } else {
            $payment->update([
                'status' => PaymentStatus::FAIL,
                'error_code' => $response->getErrorCode(),
                'error_description' => $response->getErrorDescription(),
            ]);
        }
    }

    /**
     * @throws RequestException
     * @throws RequestExecutionException
     */
    private function processPayment(Order $order, $validated): RedirectResponse
    {
        $this->transfers24->setEmail($validated['email'])->setAmount(100);
        $response = $this->transfers24->init();

        if ($response->isSuccess()) {
            Payment::create([
                'order_id' => $order->id,
                'session_id' => $response->getSessionId(),
            ]);

            $redirectUrl = $this->transfers24->execute($response->getToken());

            return back()->with('info', $redirectUrl);
        }

        if ($response->getErrorCode() === 401) {
            Payment::create([
                'order_id' => $order->id,
                'session_id' => $response->getSessionId(),
                'error_code' => $response->getErrorCode(),
                'error_description' => $response->getErrorDescription(),
            ]);

            return back()->with('error', 'Błąd uwierzytelniania');
        }

        return back()->with('error', 'Błąd transakcji');
    }
}
