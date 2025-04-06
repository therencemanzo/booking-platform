<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\JsonResponse;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking)
    {   

        if ($user->id !== $booking->customer_id) {
            return Response::deny('You do not own this booking.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking)
    {
        if ($user->id !== $booking->customer_id) {
            return Response::deny('You do not own this booking.');
        }

        if($booking->status !== 'active'){
            return Response::deny('Booking was already cancelled.');
        }

        // Get the current time and the booking creation time
        $currentTime = Carbon::now();
        $createdAt = Carbon::parse($booking->created_at);

        if ($createdAt->diffInMinutes($currentTime) > 60) {
            // If the booking's date_from is today or in the past, disallow update
            if (Carbon::parse($booking->date_from)->isToday() || Carbon::parse($booking->date_from)->isPast()) {
                return Response::deny('Cannot update Booking update/cancel grace period is finished and booking was already started.');
            }
        }
        
        return Response::allow();
    }

    public function cancel(User $user, Booking $booking)
    {
       return $this->update($user, $booking);
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        return false;
    }
}
