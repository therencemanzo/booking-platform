<?php

namespace App\Http\Controllers;

use App\Http\Resources\API\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    
    public function index(Request $request){

        $bookings = Booking::all();
        //dd($bookings);
        return Inertia::render('Dashboard', ['bookings'=> BookingResource::collection($bookings)]);
    }
}
