<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyPrice extends Model
{
    /** @use HasFactory<\Database\Factories\DailyPriceFactory> */
    use HasFactory;

    protected $fillable = [
        'price_id',
        'date',
        'price',
    ];

    public function price()
    {
        return $this->belongsTo(Price::class);
    }
}
