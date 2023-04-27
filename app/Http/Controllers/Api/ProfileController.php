<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllPostCollection;
use App\Http\Resources\UsersCollection;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
   

    public function show(string $id): JsonResponse
    {
        try {
            
            $posts = Post::query()
                    ->where('user_id', $id)
                    ->orderBy('created_at', 'desc')
                    ->get();

            $user = User::query()
                    ->where('id', $id)
                    ->get();

            return [
                'posts' => new AllPostCollection($posts),
                'user' => new UsersCollection($user)
            ];
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

   
}
