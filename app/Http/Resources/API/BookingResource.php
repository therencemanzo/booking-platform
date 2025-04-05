<?php

namespace App\Http\Resources\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Number;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'reference_id'=> $this->reference,
            'parking_space'=> [
                'name'=> $this->parkingSpace->name
            ],
            'status'=> $this->status,
            'date_from'=> Carbon::parse($this->date_from)->format('d/m/Y'),
            'date_to'=> Carbon::parse($this->date_to)->format('d/m/Y'),
            'total_price'=> Number::currency($this->total_price, 'GBP'),
            'details'=> json_decode($this->details),
        ];
    }
}
