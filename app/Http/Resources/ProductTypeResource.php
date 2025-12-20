<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductTypeResource extends JsonResource
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
            'name' =>$this->name,
            'price' => $this->price,
            'point_price' => $this->point_price,
            'era_id' => $this->era_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'poster' => new PosterResource($this->whenLoaded('poster'))
        ];
    }
}
