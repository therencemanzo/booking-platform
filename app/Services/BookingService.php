<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Booking;
use App\Models\ParkingSpace;
use Illuminate\Support\Facades\DB;
use Exception;
class BookingService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function book($user, $data)
    {

        //lock to fix the race condition
        $lockKey = 'booking_lock:' . $data['parking_space_id'] . ':' . $data['date_from'] . ':' . $data['date_to'];

        $lock = Cache::lock($lockKey, 10);

        if ($lock->get()) {
            try {
                
                $booking =  DB::transaction(function () use ($user, $data) {
                    // Proceed to create the booking after acquiring the lock
                    $parkingSpace = ParkingSpace::findOrFail($data['parking_space_id']);

                    $price = $parkingSpace->calculatePriceForPeriod($data['date_from'], $data['date_to']);
            
                    return $user->bookings()->create([
                        'total_price' => $price['total'],
                        'parking_space_id' => $data['parking_space_id'],
                        'date_from' => $data['date_from'],
                        'date_to' => $data['date_to'],
                        'details' => json_encode($price['details']),
                    ]);
                });

                return $booking;
            } catch (Exception $e) {
                // Handle any exception (e.g., log it or return an error message)
                throw new Exception('Error while creating booking: ' . $e->getMessage());
            } finally {
                // Release the lock after the process is done
                $lock->release();
            }
        }

        // If the lock is not acquired, return an error or a message
        throw new Exception('This parking space is currently being booked by another user. Please try again.');

    }

    public function update(Booking $booking, $data){

       
        //lock to fix the race condition
        $lockKey = 'booking_lock:' . $data['parking_space_id'] . ':' . $data['date_from'] . ':' . $data['date_to'];

        $lock = Cache::lock($lockKey, 10);

        if ($lock->get()) {
            try {
                
                $booking =  DB::transaction(function () use ($booking, $data) {
                    // Proceed to create the booking after acquiring the lock
                    $parkingSpace = ParkingSpace::findOrFail($data['parking_space_id']);

                    $price = $parkingSpace->calculatePriceForPeriod($data['date_from'], $data['date_to']);
            
                    $booking->update([
                        'total_price' => $price['total'],
                        'parking_space_id' => $data['parking_space_id'],
                        'date_from' => $data['date_from'],
                        'date_to' => $data['date_to'],
                        'details' => json_encode($price['details']),
                    ]);

                    return $booking->refresh();
                });

                return $booking;

            } catch (Exception $e) {
                // Handle any exception (e.g., log it or return an error message)
                throw new Exception('Error while creating booking: ' . $e->getMessage());
            } finally {
                // Release the lock after the process is done
                $lock->release();
            }
        }

        // If the lock is not acquired, return an error or a message
        throw new Exception('This parking space is currently being booked by another user. Please try again.');
    }

    public function cancel(Booking $booking){

        $booking->update([
            'status' => 'cancelled'
        ]);

        return true;
    }
}
