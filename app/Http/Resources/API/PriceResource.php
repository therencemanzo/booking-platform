<?php

namespace App\Http\Resources\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'from' => $this->valid_from ? Carbon::parse($this->valid_from)->format('d/m/Y') : null,
            'until' => $this->valid_until ? Carbon::parse($this->valid_until)->format('d/m/Y') : null,
            'daily_prices' => DailyPriceResource::collection($this->whenLoaded('dailyPrices')),
        ];
    }
}
