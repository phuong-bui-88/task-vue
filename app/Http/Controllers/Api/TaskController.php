<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Jobs\ProcessCalendarTask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use MeiliSearch\Endpoints\Indexes;

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
            Task::OVER_DATE => false
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

        return TaskResource::collection($tasks)
            ->additional(compact('remainCount', 'allCount', 'overDateCount'));
    }

    public function searchTasks(&$tasks, $operator, $data, $hasIndex, $user)
    {
        if (!$hasIndex) {
            $query = ($operator)
                ? Task::where('start_date', $operator, now())
                : Task::whereNotNull('start_date');

            $query->where('user_id', $user->id);
        }
        else {
            $query = Task::search('', function ($meiliSearch, string $query, array $options) use ($operator, $user) {
                ($operator)
                && $options['filter'][] = sprintf('start_date_timestamp %s %s ', $operator, now()->timestamp);

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
     * @param  \Illuminate\Http\Request  $request
     * @return TaskResource
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all() + ['user_id' => $request->user()->id]);

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
