<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
class MediaService
{
    /**
     * Create a new class instance.
    */
    // public function __construct()
    // {
    //     //
    // }

    public function storePoster($model, ?string $omdbPosterUrl = null, ?UploadedFile $uploadedFile = null): void
    {
        $model->poster()->delete();

        if($omdbPosterUrl){
            $this->storeExternalPoster($model, $omdbPosterUrl);
        }
        if($uploadedFile){
            $this->storeUploadedPoster($model, $uploadedFile);
        }
    }

    private function storeExternalPoster($model, string $omdbPosterUrl): void
    {
        
        $model->poster()->create([
                'text' => $this->getPosterText($model, 'jpg'),
                'media_type' => 'poster',
                'path' => $omdbPosterUrl,
            ]);
    }

    private function storeUploadedPoster($model, UploadedFile $uploadedFile): void
    {
        $originalName = $uploadedFile->getClientOriginalName();
        $sanitizedName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_',
        pathinfo($originalName, PATHINFO_FILENAME));
        $extension = $uploadedFile->getClientOriginalExtension();
        $safeName = $sanitizedName . '_' . uniqid() . '.' . $extension;

        $path = $uploadedFile->storeAs('images/'. $this->getTableFolder($model), $safeName, 'public');
                
        $model->poster()->create([
            'text' => $this->getPosterText($model, $extension),
            'media_type' => "poster",
            'path' => $path,
        ]);
    }

    private function getTableFolder($model): string
    {
          $table = $model->getTable();
          return match($table) {
              'movies' => 'movies/'. $model->slug,
              'cast_members','profiles','news','directors' => $table. '/'. $model->slug,              
                default => $table,
          };
    }

    private function getPosterText($model, string $extension): string
    {
        $name = $model->slug ?? $model->name ?? $model->title ?? 'Item';
        return $name . ' Poster.' . $extension;
    }
}
