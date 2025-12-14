<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EraResource extends JsonResource
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
            'startYear' => $this->startYear,
            'endYear' => $this->endYear,
            'description' => $this->description,
            // 'poster_path' => asset('storage/images/era/' . 'era2000s_poster.jpg'),
            'poster'=> PosterResource::make($this->whenLoaded('poster')),
        ];
    }
}
