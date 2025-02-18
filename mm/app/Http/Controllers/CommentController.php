<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Blog;

class CommentController extends Controller
{
    /**
     * Summary of create
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'blog_id' => 'required'
            
        ]);

        $userid = auth()->user()->id;

        $blog = Blog::find($request->blog_id);
        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found!',
            ], 404);
        }

        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = $userid;
        $comment->blog_id = $request->blog_id;

        if ($request->has('parent_id')) {
            $comment->parent_id = $request->parent_id;
        }

        $comment->save();

        return response()->json([
            'message' => 'Successfully created comment!',
            'comment' => $comment,
            'user' => auth()->user()
        ], 201);
    }

    /**
     * Summary of edit
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'id' => 'required'
        ]);

        $userid = auth()->user()->id;

        $id = $request->id;

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found!',
            ], 404);
        }

        if ($comment->user_id != $userid) {
            return response()->json([
                'message' => 'Unauthorized!',
            ], 401);
        }

        $comment->content = $request->content;

        $comment->save();

        return response()->json([
            'message' => 'Successfully edited comment!',
            'comment' => $comment,
            'user' => auth()->user()
        ], 200);
    }

    /**
     * Summary of delete
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $userid = auth()->user()->id;

        $id = $request->id;

        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found!',
            ], 404);
        }

        if ($comment->user_id != $userid) {
            return response()->json([
                'message' => 'Unauthorized!',
            ], 401);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Successfully deleted comment!',
            'user' => auth()->user()
        ], 200);
    }
}
