<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'booking' => new BookingResource($this->whenLoaded('booking')),
            'ticketType' => new TicketTypeResource($this->whenLoaded('ticketType')),
            'quantity' => $this->quantity
        ];
    }
}
