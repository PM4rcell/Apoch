<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditoriumResource extends JsonResource
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
            'name' => $this->name,
            'cinema' => new CinemaResource($this->whenLoaded('cinema')),            
            'capacity' => (int) $this->capacity,
            'seats' => SeatResource::collection($this->whenLoaded('seats')),
            'screenings' => ScreeningResource::collection($this->whenLoaded('screenings'))
        ];
    }
}
