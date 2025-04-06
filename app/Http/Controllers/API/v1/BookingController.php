<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\BookParkingSpaceRequest;
use App\Http\Requests\API\UpdateBookParkingSpaceRequest;
use App\Http\Resources\API\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
class BookingController extends Controller
{
    
    public function __construct(private BookingService $bookingService){}

    public function getBookings(Request $request)
    {
        // Fetch bookings for the authenticated user
        $bookings = $this->bookingService->getUserBookings($request->user());

        return BookingResource::collection($bookings);
    }

    public function getBookingDetails(Request $request, Booking $booking){

        return BookingResource::make($booking);
    }
    public function bookParkingSpace(BookParkingSpaceRequest $request)
    {

        try {
            // Call the service to create the booking (this will use the cache lock)
            $booking = $this->bookingService->book($request->user(), $request->validated());

            return response()->json(BookingResource::make($booking), status: 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

       
    }

    public function updateBooking(UpdateBookParkingSpaceRequest $request, Booking $booking)
    {

        try {
            // Call the service to create the booking (this will use the cache lock)
            $booking = $this->bookingService->update($booking, $request->validated());

            return response()->json(BookingResource::make($booking), status: 201);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

      
    }

    public function cancelBooking(Booking $booking)
    {
       
        $booking = $this->bookingService->cancel($booking);

        return response()->json(['message' => 'Booking has been cancelled'], status: 201);

        
       
    }
}
