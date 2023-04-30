<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'post_id' => 'required',
            'comment' => 'required'
        ]);
        
        try {
            
            $comment = New Comment;

            $comment->post_id = $request->input('post_id');
            $comment->text = $request->input('comment');
            $comment->user_id = auth()->user()->id;

            $comment->save();

            return response()->json(['success' => 'OK'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function destroy($id)
    {
        try {
            
            $comment = Comment::find($id);
            $comment->delete();

            return response()->json(['success' => 'OK'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
