<?php

namespace App\Http\Resources;

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
        $path = data_get($poster, 'path');

        if (!$path) {
            return [
                'text' => 'Default Avatar',
                'url' => null,
            ];
        }

        if($path && (Str::startsWith($path, 'http://') || Str::startsWith($path, 'https://') || Str::startsWith($path, 'data:'))) {
            $url = $path;
        } else {
            $url = asset('storage/' . $path);
        }        
         return [
                    'text' => data_get($poster, 'text', 'Avatar'),
                    'url' => $url,
                ];
    }
}
