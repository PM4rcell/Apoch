<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class BookingCheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $bookingTickets = $this->resource->bookingTickets()->with('ticketType')->get();
        $bookingSeats = $this->resource->bookingSeats()->with('seat')->get();     

        return [
            'id' => $this->id,
            'barcode' => 'EPCH' . str_pad($this->id, 6, '0', STR_PAD_LEFT),
            'status' => $this->status,
            'total' => $this->payment?->amount ?? 0,  
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'screening' => [
                'movie_title' => $this->screening->movie->title,
                'movie_poster' => $this->screening->movie->poster?->url,
                'date' => $this->screening->start_time->format('l, F j, Y'),
                'time' => $this->screening->start_time->format('g:i A'),
                'auditorium' => $this->screening->auditorium->name,
                'format' => $this->screening->format
            ],
           'tickets' => collect($this->bookingTickets)->map(function ($ticket, $index) {
                $seat = collect($this->bookingSeats)[$index]?->seat ?? null; 
    
                return [
                    'ticket_type' => $ticket->ticketType->name,
                    'price' => $ticket->ticketType->price,
                    'row' => $seat?->row,
                    'seat_number' => $seat?->number
                ];
            })->values()
        ];
    }
}
