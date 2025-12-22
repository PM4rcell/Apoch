<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'text' => $this->text,
            'rating' => $this->rating,
            'user' => new UserResource($this->whenLoaded('user')),
            'movie' => new MovieSummaryResource($this->whenLoaded('movie'))
        ];
    }
}
