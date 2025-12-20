<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [            
            'booking' => new BookingResource($this->whenLoaded('booking')),
            'producttType' => new ProductTypeResource($this->whenLoaded('productType')),
            'quantity' => $this->quantity
        ];
    }
}
