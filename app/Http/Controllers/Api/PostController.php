<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllPostCollection;
use App\Models\Post;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'video' =>'required|mimes:mp4',
            'text' => 'required'
        ]);

        try {

            $post = new Post;
            $post = (new FileService)->addVideo($post, $request);

            $post->user_id = auth()->user()->id;
            $post->text = $request->input('text');

            $post->save();

            return response()->json(['success' => 'OK'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function show($id): JsonResponse
    {
        try {
            
            $post = Post::query()
                ->where('id', $id)
                ->get();
            
            $posts = Post::query()
                ->where('user_id', $post[0]->user_id)
                ->get();

            $ids = $posts->map(function ($post) {
                return $post->id;
            });

            return response()->json([
                'post' => new AllPostCollection($post),
                'ids' => $ids
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function destroy($id)
    {
        try {
           
            $post = Post::query()
                ->findOrFail($id);

            if (!is_null($post->video) && file_exists(public_path() . $post->video)) {
                unlink(public_path() . $post->video);
            }   

            $post->delete();

            return response()->json(['success' => 'OK'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
