<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingSeatResource extends JsonResource
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
            'row' => $this->whenLoaded('seat', fn() => $this->seat->row),
            'number' => $this->whenLoaded('seat', fn() => $this->seat->number),
            'seatType' => $this->seat->seatType->name,
            'auditorium' => $this->seat->auditorium->name,            
        ];
    }
}
