<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Jobs\ProcessCalendarTask;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $showData = [
            Task::ALL => false,
            Task::REMAIN => false,
            Task::OVER_DATE => false,
            Task::FAVORITE => false,
        ];

        if ($request->has('status')) {
            $showData[$request->status] = true;
        }

        $hasIndex = $request->has('index') ? $request->index : true;
        $user = $request->user();

        $tasks = [];
        $remainCount = $this->searchTasks($tasks, '>', $showData[Task::REMAIN], $hasIndex, $user);
        $allCount = $this->searchTasks($tasks, null, $showData[Task::ALL], $hasIndex, $user);
        $overDateCount = $this->searchTasks($tasks, '<=', $showData[Task::OVER_DATE], $hasIndex, $user);
        $favoriteCount = $this->searchTasks($tasks, null, $showData[Task::FAVORITE], $hasIndex, $user, true);

        return TaskResource::collection($tasks)
            ->additional(compact('remainCount', 'allCount', 'overDateCount', 'favoriteCount'));
    }

    public function searchTasks(&$tasks, $operator, $data, $hasIndex, $user, $isFavorite = false)
    {
        if (!$hasIndex) {
            $query = ($operator)
                ? Task::where('start_date', $operator, now())
                : Task::whereNotNull('start_date');
            $query->where('user_id', $user->id);
        }
        else {
            $query = Task::search('', function ($meiliSearch, string $query, array $options) use ($isFavorite, $operator, $user) {
                ($operator)
                && $options['filter'][] = sprintf('start_date_timestamp %s %s ', $operator, now()->timestamp);

                if ($isFavorite) {
                    $favoriteTaskIds = auth()->user()->favorites->pluck('id')->all();
                    ($favoriteTaskIds)
                        ? $options['filter'][] = sprintf('id = %s', implode(' OR id = ', $favoriteTaskIds))
                        : $options['filter'][] = sprintf('id IS NULL');
                }

                $options['filter'][] = sprintf('user_id %s %s', '=', $user->id);

                return $meiliSearch->search($query, $options);
            });
        }

        ($data) && $tasks = $query->get();

        return $query->count();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreTaskRequest  $request
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all() + ['user_id' => $request->user()->id]);

        ProcessCalendarTask::dispatch($task, ProcessCalendarTask::CREATE);
        info("Created task: {$task->title}", $task->toArray());
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
     * @param  Task  $task
     * @param  StoreTaskRequest  $request
     *
     * @return \Illuminate\Http\Response|string
     */
    public function update(Task $task, StoreTaskRequest $request)
    {
        info("Updated task: {$task->title}", $task->toArray());
        $task->update($request->all());
        ProcessCalendarTask::dispatchIf(isset($task->calendar_id),
            $task, ProcessCalendarTask::UPDATE
        );

        return 'ok';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response|string
     */
    public function destroy(Task $task)
    {
        info("Deleted task: {$task->title}", $task->toArray());
        ProcessCalendarTask::dispatchIf(isset($task->calendar_id),
            null, ProcessCalendarTask::DELETE, $task->calendar_id
        );

        $task->delete();
        return 'ok';
    }

}
