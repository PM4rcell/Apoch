<?php

namespace App\Observers;

use App\Models\Comment;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $this->updateMovieRating($comment);
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        if ($comment->wasChanged(['rating', 'movie_id'])) {
            $this->updateMovieRating($comment);
        }
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        $this->updateMovieRating($comment);
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }

    private function updateMovieRating(Comment $comment)
    {
        $movie = $comment->movie;

        if(!$movie){
            return;
        }

        $avg = $movie->comments()->avg('rating') ?? 0;
        $movie->vote_avg = $avg;
        $movie->saveQuietly();        
    }
}
