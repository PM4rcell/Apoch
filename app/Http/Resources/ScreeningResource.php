<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScreeningResource extends JsonResource
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
            'auditorium' => new AuditoriumResource($this->whenLoaded('auditorium')),
            'movie' => new MovieSummaryResource($this->whenLoaded('movie')),
            'language' => new LanguageResource($this->whenLoaded('language')),
            'start_time' => $this->start_time->toDateTimeString(),
            'start_date' => $this->start_time->toDateString(),
            'start_day' => $this->start_time->format('l')
        ];
    }
}
