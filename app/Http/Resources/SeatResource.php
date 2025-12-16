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
            'auditorium_id' => $this->auditorium_id,
            'seat_type_id' => $this->seat_type_id,
            'row' => $this->row,
            'number' => $this->number,
        ];
    }
}
