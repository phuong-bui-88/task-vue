<?php

namespace App\Jobs;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\GoogleCalendar\Event;

class ProcessCalendarTask implements ShouldQueue, JobConst
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const STATUS_CANCELLED = 'cancelled';
    public ?Task $task;
    public $method = '';
    public $calendarId = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(?Task $task = null, $method = null, $calendarId = null)
    {
        $this->task = $task;
        $this->method = $method;
        $this->calendarId = (self::DELETE == $this->method) ? $calendarId : null;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (self::CREATE == $this->method) && $this->processCreate();
        (self::UPDATE == $this->method) && $this->processUpdate();
        (self::DELETE == $this->method) && $this->processDelete();
    }

    protected function processCreate()
    {
        $event = Event::create([
           'name' => $this->task->title,
           'startDate' => Carbon::now(),
           'endDate' => Carbon::now(),
        ]);

        $this->task->calendar_id = $event->id;
        $this->task->save();
    }

    protected function processUpdate()
    {
        if (!$this->task->calendar_id) {
            return;
        }

        $event = Event::find($this->task->calendar_id);
        $event->name = $this->task->title;
        $event->startDate = Carbon::parse($this->task->start_date);
        $event->endDate = Carbon::parse($this->task->start_date);
        $event->save();
    }

    protected function processDelete()
    {
        $e = Event::find($this->calendarId);

        if (!$event = Event::find($this->calendarId)) {
            return;
        }
        if (self::STATUS_CANCELLED == $event->googleEvent->getStatus()) {
            return;
        }
        $event->delete();
    }
}
