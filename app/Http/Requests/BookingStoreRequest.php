<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $date
 * @property string $time
 * @property string $notes
 */
class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i', Rule::unique('bookings')->where(function ($query) {
                return $query->where('date', $this->date)->where('time', $this->time);
            })],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'time.unique' => __('You already have a booking at this time.'),
        ];
    }
}
