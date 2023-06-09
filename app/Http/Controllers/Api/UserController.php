<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UsersCollection;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function loggedInUser(): JsonResponse
    {
        try {
            
            $user = User::query()
                ->where('id', auth()->user()->id)
                ->get();

            return response()->json(new UsersCollection($user), 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }



    public function updateUserImage(Request $request): JsonResponse
    {
        $request->validate(['image' => 'required|mimes:png,jpg,jpeg']);
        if (
            $request->height === '' 
            || $request->width === '' 
            || $request->height === ''
            || $request->top === ''
            ) {
                return response()->json(['error' => 'The dimension are incomplete'], 400);
            }

        try {

            $user = (new FileService)->updateImage(auth()->user(), $request);
            $user->save();

            return response()->json(['success' => 'OK'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function getUser(string $id): JsonResponse
    {
        try {
            
            $user = User::query()
                ->findOrFail($id);

            return response()->json([
                'success' => 'OK',
                'user' => $user,
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function updateUser(Request $request)
    {
        $request->validate(['name' => 'required']);

        try {
            
            $user = User::query()
                ->findOrFail(auth()->user()->id);

            $user->name = $request->input('name');
            $user->bio = $request->input('bio');

            $user->save();

            return response()->json(['success' => 'OK'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}