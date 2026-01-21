<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node\Expr\Cast;

class MovieDetailResource extends JsonResource
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
            'description' => $this->description,            
            'poster' => PosterResource::make($this->whenLoaded('poster')),
            'cast' => MovieCastResource::collection($this->whenLoaded('cast')),
            'genres' => GenreResource::collection($this->whenLoaded('genres')),            
            'director' => new DirectorResource($this->whenLoaded('director')),
            'era' => new EraResource($this->whenLoaded('era')),
            'gallery' => PosterResource::collection($this->whenLoaded('gallery')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),        
        ];
    }
}
