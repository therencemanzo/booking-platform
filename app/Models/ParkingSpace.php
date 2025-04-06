<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Database\Factories\ParkingSpaceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSpace extends Model
{
    /** @use HasFactory<\Database\Factories\ParkingSpaceFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'length',
        'width',
        'is_covered',
        'has_ev_charger',
        'notes',
    ];
    protected $casts = [
        'is_covered' => 'boolean',
        'has_ev_charger' => 'boolean',
    ];
    public function bookings()
    {
        return $this->hasMany(Booking::class)->where('status', 'active');
    }
    public function prices()
    {
        return $this->belongsToMany(Price::class)->using(ParkingSpacePrice::class);
    }

    /**
     * Calculate total price for a given date range
     */
    public function calculatePriceForPeriod($from, $to)
    {
        $start = Carbon::parse($from);
        $end = Carbon::parse($to);
        $period = CarbonPeriod::create($start, $end);
        $systemDefaultPrice = 4.00;
        
        // Retrieve all applicable prices with their daily prices
        $details = [];
        $prices = $this->prices()
            ->with('dailyPrices')
            ->where(function ($query) use ($start, $end) {
                $query->where(function ($q) use ($start, $end) {
                    $q->where('is_default', false)
                    ->whereDate('valid_from', '<=', $end)
                    ->whereDate('valid_until', '>=', $start);
                })->orWhere(function ($q) {
                    $q->where('is_default', true)
                    ->whereNull('valid_from')
                    ->whereNull('valid_until');
                });
            })
            ->orderBy('is_default') // Non-default first
            ->orderByDesc('valid_from') // Most recent first
            ->get();

        if ($prices->isEmpty()) {
            throw new \Exception("No prices found for the parking space.");
        }


        $total = 0;

        foreach ($period as $date) {
            $dayOfWeek = strtolower($date->format('l'));
            $applicablePrice = null;
    
            // Check non-default prices
            foreach ($prices as $price) {
                if ($price->is_default) {
                    continue;
                }
                $validFrom = Carbon::parse($price->valid_from);
                $validUntil = Carbon::parse($price->valid_until);
                if ($date->between($validFrom, $validUntil)) {
                    $applicablePrice = $price;
                    break;
                }
            }
    
            // Fallback to default price
            

            if (!$applicablePrice) {
                $applicablePrice = $prices->firstWhere('is_default', true);
            }
    
            $dailyPrice = $applicablePrice->dailyPrices->firstWhere('day', $dayOfWeek);

            $details [] = [
                'date' => $date->toDateString(),
                'day' => $dayOfWeek,
                'price' => $dailyPrice->price ?? $systemDefaultPrice,
                'season' => $applicablePrice->title,
            ];
            
            $total += $dailyPrice->price ?? $systemDefaultPrice;
        }

        return [
            'total' => $total,
            'details' => $details,
        ];
    }

    
}
