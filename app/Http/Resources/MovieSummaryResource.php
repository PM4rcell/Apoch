<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieSummaryResource extends JsonResource
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
            'release_date' => $this->release_date,
            'vote_avg' => $this->vote_avg,
            'runtime_min' => $this->runtime_min,
            'slug' => $this->slug,
            'poster' => PosterResource::make($this->whenLoaded('poster')),
        ];
    }
}
