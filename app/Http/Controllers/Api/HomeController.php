<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllPostCollection;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{

    public function index(): JsonResponse
    {
        try {
            
            $posts = Post::query()
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json(new AllPostCollection($posts), 200);    

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

  
}
