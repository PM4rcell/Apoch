<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,            
            'category' => $this->category,
            'excerp' => $this->excerp,
            'read_time_min' => $this->read_time_min,
            'external_link' => $this->external_link,
            'user' => new UserFullResource($this->whenLoaded("user")),            
            'poster' => new PosterResource($this->whenLoaded('poster'))
        ];
    }
}
