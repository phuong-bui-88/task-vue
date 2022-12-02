<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function update(Request $request)
    {
        // upload from filepond
        if ($request->has('task')) {
            $task = Task::findOrFail($request->task);
            $task->addMediaFromRequest('image')
                ->toMediaCollection();

            return $task->getFirstMediaUrl();
        }

        // upload from the ckeditor
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();
        $file->storeAs('', $fileName, ['disk' => 'public']);
        return Storage::url($fileName);
    }
}
