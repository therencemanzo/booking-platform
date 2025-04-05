<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    /** @use HasFactory<\Database\Factories\PriceFactory> */
    use HasFactory;

    protected $fillable = [ 
        'title',
        'valid_from',
        'valid_until',
    ];

    public function parkingSpaces()
    {
        return $this->belongsToMany(ParkingSpace::class)->using(ParkingSpacePrice::class);
    }

    public function dailyPrices()
    {
        return $this->hasMany(DailyPrice::class);
    }
    
}
