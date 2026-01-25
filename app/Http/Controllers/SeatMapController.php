<?php

namespace App\Http\Controllers;

use App\Http\Resources\SeatResource;
use App\Models\BookingSeat;
use App\Models\Screening;
use Illuminate\Http\Request;

class SeatMapController extends Controller
{
    public function index(Screening $screening){
        $lockedSeatIDs = BookingSeat::query()
                        ->whereHas('booking', function ($q) use ($screening){
                            $q->where('screening_id', $screening->id)
                            ->whereIn('status', ['pending', 'paid'])
                            ->where(function ($q){
                                $q->where('deleted_at', null)
                                ->orWhere('deleted_at', '>', now()->subMinutes(10));
                            });
                        })
                        ->pluck('seat_id')
                        ->toArray();
        $seats = $screening->auditorium->seats->map(function ($seat) use($lockedSeatIDs){
            $seat->state = in_array($seat->id, $lockedSeatIDs)
                ? 'unavailable'
                : 'available';
            return $seat;
        });
        
        return SeatResource::collection($seats);
    }
}
