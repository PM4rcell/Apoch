<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Movie;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::paginate(15);
        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Movie $movie)
    {
        $data = $request->validated();

        $comment = $movie->comments()->create([
            'user_id' => $request->user()->id,
            'text' => $data['text'],
            'rating' => $data['rating']
        ]);

        $comment->load('user', 'movie');
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment->load('user', 'movie');
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $data = $request->validated();
        $comment->update($data);

        $comment->load('user', "movie");
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }
}
