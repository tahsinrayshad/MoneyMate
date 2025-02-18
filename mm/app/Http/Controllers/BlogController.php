<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Summary of create
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $userid = auth()->user()->id;

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->user_id = $userid;

        $blog->save();

        return response()->json([
            'message' => 'Successfully created blog!',
            'blog' => $blog,
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
            'title' => 'required|max:255',
            'content' => 'required',
            'id' => 'required'
        ]);

        $userid = auth()->user()->id;

        $id = $request->id;

        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found!',
            ], 404);
        }

        if ($blog->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to edit this blog!',
            ], 401);
        }

        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->user_id = $userid;

        $blog->save();

        return response()->json([
            'message' => 'Successfully updated blog!',
            'blog' => $blog,
            'user' => auth()->user()
        ], 201);
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

        $id = $request->id;

        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found!',
            ], 404);
        }

        if ($blog->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not authorized to delete this blog!',
            ], 401);
        }

        $blog->delete();

        return response()->json([
            'message' => 'Successfully deleted blog!',
            'blog' => $blog,
            'user' => auth()->user()
        ], 201);
    }

    /**
     * Summary of getAll
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        $blogs = Blog::with(['user:id,username'])->withCount(['comments'])->get();

        return response()->json([
            'message' => 'Successfully fetched all blogs!',
            'blogs' => $blogs
        ], 201);
    }


    /**
     * Summary of getSingleBlog
     * @param mixed $id
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getSingleBlog($id){
        $blog = Blog::where('id', $id)
                    ->with(relations: ['user:id,username','comments'])
                    ->withCount(['comments'])
                    ->first();

        if (!$blog) {
            return response()->json([
                'message' => 'Blog not found!',
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetched blog!',
            'blog' => $blog
        ], 201);
    }
}
