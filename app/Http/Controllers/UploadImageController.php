<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UploadImageController extends Controller
{
    public function update(Request $request)
    {
        // upload from filepond
        if ($request->has('task')) {
            $task = Task::findOrFail($request->task);
            $media = $task->addMediaFromRequest('image')
                ->toMediaCollection();

            return $media->getFullUrl();
        }

        // upload from the ckeditor
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();
        $file->storeAs('', $fileName, ['disk' => 'public']);
        return Storage::url($fileName);
    }

    public function destroy(Request $request)
    {
        $path = pathinfo($request->fileUrl);
        $pathElements =  explode('/', $path['dirname']);
        $id = end($pathElements);
        if (!is_numeric($id)) {
            return 'false';
        }

        return Media::find($id)->delete();

        //$task = Task::findOrFail($request->task);
        //return $task->getMedia()->where('file_name', $path['basename'])->first()->delete();
    }
}
