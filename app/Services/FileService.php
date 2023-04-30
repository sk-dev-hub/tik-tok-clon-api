<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;

class FileService {

    public function updateImage($model, Request $request): mixed
    {
        $image = Image::make($request->file('image'));

        if(!empty($model->image)) {
            $currentImage = public_path() . $model->image;

            if (file_exists($currentImage) && $currentImage != public_path() . '/user-placeholder.png' ) {
                unlink($currentImage);
            }
        }

        $file = $request->file('image');
        $extension = $file->getClientOriginalExtension();

        $image->crop(
            $request->width,
            $request->height,
            $request->left,
            $request->top
        );

        $name = time() . '.'  . $extension;

        $filePath = '/images/' . $name;



        $image->save(public_path('storage') . $filePath);
       
        $model->image = '/storage' . $filePath;

        return $model;
    }

    public function addVideo($model, $request)
    {
        $video = $request->file('video');


        $extension = $video->getClientOriginalExtension();

        $name = time() . '.' . $extension;

        $filePath = '/videos/' . $name;

        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($video));
        
       // $video->move(public_path() . '/files/' . $name);

        $model->video = '/storage' . $filePath;

        return $model;
    }


}