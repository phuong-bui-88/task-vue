<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;

class FavoriteController extends Controller
{
    public function store(Task $task)
    {
        $user = auth()->user();
        $user->favorites()->attach($task);
        info("Favorite task: {$task->title} and user: {$user->name}", $task->toArray());
        return response()->json(['message' => 'Task favorited']);
    }

    public function destroy(Task $task)
    {
        $user = auth()->user();
        $user->favorites()->detach($task);
        info("Unfavorite task: {$task->title} and user: {$user->name}", $task->toArray());
        return response()->json(['message' => 'Task unfavorited']);
    }
}
