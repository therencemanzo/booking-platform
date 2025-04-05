<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Booking;

class UpdateBookParkingSpaceRequest extends FormRequest
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
            'parking_space_id' => ['required', 'exists:parking_spaces,id'],
            'date_from' => ['required', 'date','after_or_equal:today'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
        ];

    }
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $parkingSpaceId = $this->parking_space_id;
            $dateFrom = $this->date_from;
            $dateTo = $this->date_to;
            $bookingId = $this->route('booking')->id; // expects route like /bookings/{booking}

            if ($parkingSpaceId && $dateFrom && $dateTo) {
                $overlappingBooking = Booking::where('parking_space_id', $parkingSpaceId)
                    ->where('status', 'active')
                    ->when($bookingId, function ($query) use ($bookingId) {
                        $query->where('id', '!=', $bookingId);
                    })
                    ->where(function ($query) use ($dateFrom, $dateTo) {
                        $query->whereBetween('date_from', [$dateFrom, $dateTo])
                              ->orWhereBetween('date_to', [$dateFrom, $dateTo])
                              ->orWhere(function ($query) use ($dateFrom, $dateTo) {
                                  $query->where('date_from', '<=', $dateFrom)
                                        ->where('date_to', '>=', $dateTo);
                              });
                    })
                    ->exists();

                if ($overlappingBooking) {
                    $validator->errors()->add('parking_space_id', 'This parking space is already booked for the selected date range.');
                }
            }
        });
    }
}
