<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFullResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            "points" => $this->points,
            'poster' => new PosterResource($this->whenLoaded('poster')),
            "achievements" => AchievementResource::collection($this->whenLoaded('achievements')),
            "watchlist" => WatchlistResource::collection($this->whenLoaded('watchlist')),
            "comments" => CommentResource::collection($this->whenLoaded('comments')),
            "bookings" => BookingResource::collection($this->whenLoaded('bookings'))
        ];
    }
}
