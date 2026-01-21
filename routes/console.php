<?php

use App\Models\Booking;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::call(function () {     
//     $expiredBookings = Booking::where('status', 'pending')
//         ->where('booking_time', '<', now()->subMinutes(10))
//         ->get();

//     foreach ($expiredBookings as $booking) {        
//         $booking->update(['status' => 'expired']);        
//         $booking->Delete();
//     }
// })->everyMinute()->name('expire-pending-bookings')->timezone('Europe/Budapest');
