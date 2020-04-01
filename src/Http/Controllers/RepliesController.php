<?php

namespace Plmrlnsnts\Commentator\Http\Controllers;

use Illuminate\Http\Request;
use Plmrlnsnts\Commentator\Comment;

class RepliesController
{
    /**
     * Show all replies for a comment.
     *
     * @param \Plmrlnsnts\Commentator\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function index(Comment $comment)
    {
        return $comment->replies()
            ->with('author')
            ->when(request('sort') === 'latest', function ($query) {
                $query->orderByDesc('created_at');
            })
            ->paginate(request('perPage', 15));
    }

    /**
     * Create a new reply to a comment.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Plmrlnsnts\Commentator\Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Comment $comment)
    {
        $attributes = $request->validate([
            'body' => ['required', 'string'],
            'media' => ['nullable', 'url'],
        ]);

        $reply = $comment->addReply($attributes);

        return $reply;
    }
}
