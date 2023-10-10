<?php

namespace Tests\Feature\Api;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Jobs\ProcessCalendarTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Queue;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexTask()
    {
        // Create a user
        $user = User::factory()->create(['id' => rand(250, 260)]);
        // Create some tasks associated with the user

        Task::factory(3)->create(['user_id' => $user->id]);

        sleep(1);
        // Make a request to the 'index' method
        $response = $this->actingAs($user)->get('/api/tasks?status=' . Task::ALL);

        // Assert that the response is successful (HTTP status code 200)
        $response->assertStatus(200);
        // Assert that the response contains the expected data (customize this according to your actual response structure)
        $response->assertJsonStructure([
            'data',
            'remainCount',
            'allCount',
            'overDateCount',
        ]);

        $content = json_decode($response->content(), true);
        $this->assertEquals(3, count($content['data']));
        $this->assertEquals(3, $content['allCount']);
        $this->assertEquals(3, $content['overDateCount']);

        $response = $this->actingAs($user)->get('/api/tasks?status=' . Task::OVER_DATE);

        // Assert that the response is successful (HTTP status code 200)
        $response->assertStatus(200);
        // Assert that the response contains the expected data (customize this according to your actual response structure)
        $response->assertJsonStructure([
            'data',
            'remainCount',
            'allCount',
            'overDateCount',
        ]);

        $content = json_decode($response->content(), true);
        $this->assertEquals(3, count($content['data']));
        $this->assertEquals(3, $content['allCount']);
        $this->assertEquals(3, $content['overDateCount']);
    }

    public function testStoreTask()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a task data array
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task.',
        ];

        // Mock the ProcessCalendarTask job
        Queue::fake();

        // Create a task record in the database
        //$task = Task::create($taskData + ['user_id' => $user->id]);

        // Make a request to the 'store' method
        $response = $this->actingAs($user)->post('/api/tasks', $taskData);

        // Assert that ProcessCalendarTask::dispatch was called
        Queue::assertPushed(ProcessCalendarTask::class, function ($job) use ($taskData) {
            return $job->task->title === $taskData['title'];
        });

        // Assert that the response is successful (HTTP status code 200)
        $response->assertStatus(201);

        // Assert that the response contains the created task data (customize this according to your actual response structure)
        $response->assertJson([
            'data' => $taskData,
        ]);

        // Assert that the task is stored in the database
        $this->assertDatabaseHas('tasks', $taskData);
    }

    public function testShowTask()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a task data array
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Make a request to the 'show' method with the task's ID
        $response = $this->actingAs($user)->get("/api/tasks/{$task->id}");

        // Assert that the response is successful (HTTP status code 200)
        $response->assertStatus(200);
        $content = json_decode($response->content(), true);

        $this->assertEquals($content['data']['title'], $task->title);
    }

    public function testUpdateTask()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a task data array
        $task = Task::factory()->create(['user_id' => $user->id]);

        $request = new StoreTaskRequest([
            'title' => 'Updated Task Title',
        ]);

        // Call the update method
        $response = $this->actingAs($user)->put("api/tasks/{$task->id}", $request->toArray());

        // Assert the response
        $response->assertStatus(200); // Assuming 'ok' returns a 200 status

        // You can also assert that the task was updated as expected
        $this->assertEquals('Updated Task Title', $task->fresh()->title);

        // You can also assert that the dispatchIf method was called as expected
        // Has CalendarId
        // Mock the ProcessCalendarTask job
        Queue::fake();
        // Create a task data array
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'calendar_id' => 'something',
        ]);
        // Call the update method
        $response = $this->actingAs($user)->put("api/tasks/{$task->id}", $request->toArray());
        // Assert the response
        $response->assertStatus(200); // Assuming 'ok' returns a 200 status
        Queue::assertPushed(ProcessCalendarTask::class, function ($job) use ($task) {
            return $job->task->id === $task->id;
        });
    }

    public function testDestroyTask()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a task data array
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Call the destroy method with the task ID
        $response = $this->actingAs($user)->delete("api/tasks/{$task->id}");

        // Assert the response
        $response->assertStatus(200); // Assuming 'ok' returns a 200 status

        // Verify that the task was deleted
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
