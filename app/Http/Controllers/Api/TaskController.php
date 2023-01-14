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
            Task::ALL => true,
            Task::REMAIN => false,
            Task::OVER_DATE => false
        ];

        if ($status = $request->status) {
            if (Task::REMAIN == $request->status) {
                $showData[Task::REMAIN] = true;
            }
            else {
                $showData[Task::OVER_DATE] = true;
            }
            $showData[Task::ALL] = false;
        }

        $tasks = [];
        $remainCount = $this->searchTasks($tasks, '>', $showData[Task::REMAIN]);
        $allCount = $this->searchTasks($tasks, null, $showData[Task::ALL]);
        $overDateCount = $this->searchTasks($tasks, '<', $showData[Task::OVER_DATE]);

        return TaskResource::collection($tasks)
            ->additional(compact('remainCount', 'allCount', 'overDateCount'));
    }

    public function searchTasks(&$tasks, $operator, $data)
    {
        $query = Task::search('', function ($meiliSearch, string $query, array $options ) use ( $operator ) {
            if ($operator) {
                $options['filter'] = sprintf('start_date_timestamp %s %s', $operator, now()->timestamp);
            }
            return $meiliSearch->search( $query, $options );
        });

        if ($data) {
            $tasks = $query->get();
        }

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
