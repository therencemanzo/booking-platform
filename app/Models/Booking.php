<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        'parking_space_id',
        'customer_id',
        'date_from',
        'date_to',
        'total_price',
        'status',
        'details',
    ];

    public function parkingSpace(){
        return $this->belongsTo(ParkingSpace::class);
    }

    public function customer(){
        return $this->belongsTo(User::class)->where('role', 'customer');
    }

    private function generateUniqueReference()
    {
        do {
            $reference = 'MNCHTR-' . Str::upper(Str::random(10));
        } while (self::where('reference', $reference)->exists());

        return $reference;
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            $booking->reference = $booking->generateUniqueReference();
        });
    }
}
