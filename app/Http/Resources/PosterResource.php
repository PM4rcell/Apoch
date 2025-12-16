<?php

namespace App\Http\Resources;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PosterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {        
        $poster = $this->resource;
        $path = $poster?->path;
        if($path && (Str::startsWith($path, 'http://') || Str::startsWith($path, 'https://'))) {
            $url = $path;
        } else {
            $url = asset('storage/' . $poster->path);
        }        
         return [
                    'text' => $this->text,
                    'url' => $url,
                ];
    }
}
