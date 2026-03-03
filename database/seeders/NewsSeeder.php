<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\News;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news = News::query()->updateOrCreate(
            [
                'title' => 'mbappe to watch premiere',
                'slug' => 'assumenda-porro-reprehenderit-nemo-aliquid',
                'body' => 'Natus et quasi repellendus impedit voluptatum. Molestias id nobis saepe debitis. Cum distinctio temporibus temporibus delectus quia eaque aut.',
                'category' => 'Behind the scenes',
                'excerp' => 'Porro quae et sit et cumque.',
                'read_time_min' => 3,
                'external_link' => null,
                'user_id' => 1,
            ]
        );

        Media::query()->updateOrCreate(
            [
                'connected_type' => News::class,
                'connected_id' => $news->id,
                'media_type' => 'poster',
            ],
            [
                'text' => 'mbappe to watch premiere Poster.jpg',
                'path' => 'https://image.blikk.hu/1/NKQk9kpTURBXy9iZjI3MDYwMTZlYjdhZGM2ZWY2MDY1ZTY1ZGUwNzRiOC5qcGeTlQMAIM0EAM0CQJMJpjFlYTVmNAaTBc0DMM0CZN4AAaEwAQ/kylian-mbappe-ismet-megserult-foto-getty-images',
            ]
        );
    }
}
