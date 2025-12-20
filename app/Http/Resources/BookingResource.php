<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'screening_id' => $this->screening_id,
            'profile_id' => $this->profile_id,
            'email' => $this->email,
            'booking_fee' => $this->booking_fee,
            'status' => $this->status
        ];
    }
}
