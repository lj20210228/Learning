<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class PostContoller extends Controller
{
    public function addNewPost(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',


        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }
        try {
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = auth()->user()->id;
            $post->save();
            return response()->json([
                'message' => 'post added successfully',
                'post_data' => $post

            ], 200);
        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 403);
        }
    }
    public function editPost(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
            'post_id' => 'required|integer'


        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }
        try {
            $post_data = Post::find($request->post_id);
            $updated_post = $post_data->update([
                'title' => $request->title,
                'content' => $request->content,

            ]);
            return response()->json([
                'message' => 'post updated successfully',
                'updated_post' => $updated_post
            ], 200);
        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 403);
        }
    }
    public function getAllPosts()
    {
        try {
            $posts = Post::all();
            return response()->json([
                'posts' => $posts
            ]);
        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 403);
        }
    }
    public function editPost2(Request $request, $post_id)
    {
        $validated = Validator::make($request->all(), [
            'title' => 'required|string',
            'content' => 'required|string',
        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }
        try {
            $post_data = Post::find($post_id);
            $updated_post = $post_data->update([
                'title' => $request->title,
                'content' => $request->content,

            ]);
            return response()->json([
                'message' => 'post updated successfully',
                'updated_post' => $updated_post
            ], 200);
        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 403);
        }
    }
    public function getPost($post_id)
    {
        try {
            $post = Post::with('user')->where('id', $post_id)->first();
            return response()->json([
                'post' => $post
            ], 200);
        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 403);
        }
    }
    public function deletePost(Request $request, $post_id)
    {
        try {
            $post = Post::find($post_id);
            $post->delete();
            return response()->json([
                'message' => 'Post deleted successfully'
            ], 200);
        } catch (\Exception $th) {
            return response()->json(['error' => $th->getMessage()], 403);
        }
    }
}
