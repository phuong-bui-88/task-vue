<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Jobs\ProcessCalendarTask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index()
    {
        return TaskResource::collection(Task::select('id', 'title', 'created_at')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all());

        ProcessCalendarTask::dispatch($task, ProcessCalendarTask::CREATE);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return TaskResource
     */
    public function show($id)
    {
        $task = Task::find($id);

        return (new TaskResource($task));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response|string
     */
    public function update(Task $task, StoreTaskRequest $request)
    {
        $task->update($request->all());
        ProcessCalendarTask::dispatchIf(isset($task->calendar_id),
            $task, ProcessCalendarTask::UPDATE
        );

        return 'ok';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|string
     */
    public function destroy(Task $task)
    {
        ProcessCalendarTask::dispatchIf(isset($task->calendar_id),
            null, ProcessCalendarTask::DELETE, $task->calendar_id
        );

        $task->delete();
        return 'ok';
    }

}
