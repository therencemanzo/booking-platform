<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\NewBooking;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendAdminNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public Booking $booking)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin){
            $admin->notify(new NewBooking($this->booking));
        }
    }
}
