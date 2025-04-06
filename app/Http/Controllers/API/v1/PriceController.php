<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\PriceResource;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    
    public function getPrices(Request $request){

        $price = Price::with(['dailyPrices'])->get();

        return PriceResource::collection($price);
    }
}
