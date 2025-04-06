<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\PriceResource;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    
    public function getPricesBySeason(Request $request){

        $price = Price::select('*')
        ->selectRaw( '(valid_from <= NOW() AND valid_until >= NOW()) AS is_current')
        ->with(['dailyPrices'])
        ->orderByDesc('is_default') 
        ->get();

        return PriceResource::collection($price);
    }
}
