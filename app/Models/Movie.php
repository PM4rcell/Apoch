<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    /** @use HasFactory<\Database\Factories\MovieFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'release_year',
        'director_id',
        'era_id',
        'vote_avg',
        'imdb_id',
        'omdb_category',
        'age_rating',
        'release_date',
        'runtime_min',
        'is_featured',
        'slug',
        'trailer_link',
    ];

    public function era()
    {
        return $this->belongsTo(Era::class);
    }
    public function director()
    {
        return $this->belongsTo(Director::class);
    }
    public function poster()
    {
        return $this->hasOne(Media::class, 'connected_id', 'id')
            ->where('connected_table', 'movies')
            ->where('media_type', 'poster');
    }
    public function gallery()
    {
        return $this->hasMany(Media::class, 'connected_id', 'id')
            ->where('connected_table', 'movies');            
    }
    public function cast()
    {
        return $this->belongsToMany(CastMember::class, 'movie_casts');
    }
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }
    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }

}
