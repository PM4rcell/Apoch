<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatResource extends JsonResource
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
            'auditorium' => new AuditoriumResource($this->whenLoaded('auditorium')),            
            'seatType' => new SeatTypeResource($this->whenLoaded('seatType')),
            'row' => $this->row,
            'number' => $this->number,            
            'state' => $this->state
        ];
    }
}
