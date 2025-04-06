<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class ParkingSpacePriceDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price = $this->calculatePriceForPeriod($request->date_from, $request->date_to);
        return [
            "id"=> $this->id,
            "name"=> $this->name,
            "is_covered"=> $this->is_active,
            "has_ev_charger"=> $this->is_inactive,
            "notes"=> $this->is_active,
            "length"=> $this->length,
            "width"=> $this->width,
            'price' => Number::currency($price['total'], 'GBP'),
            'price_details' => $price['details'],
            'booked_dates' => $this->bookings->map(function($booking){
                return [
                    'date_from'=> $booking->date_from,
                    'date_to'=> $booking->date_to,
                ];
            }),
        ];
    }
}
