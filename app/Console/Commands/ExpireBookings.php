<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending bookings older than 10 minutes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {        
        $count = Booking::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(10))
            ->get()
            ->each(function ($booking) {
                $booking->update(['status' => 'expired']);
                $booking->seats()->delete();
                $booking->tickets()->delete();
                $booking->delete();
            })
            ->count();

        $this->info("Expired {$count} bookings");
    }
}
