<?php

namespace Tests\Unit\Controllers\Api;

use App\Models\User;
use App\Models\Task;
use App\Http\Controllers\Api\FavoriteController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase; // Reset the database after each test


    public function testStoreMethodFavoritingTask()
    {
        // Create a user and a task
        list($user, $task) = $this->createUserAndTask();

        // Call the store method
        $response = $this->actingAs($user)->post("/api/favorite/{$task->id}");

        // Assert that the response is a JSON response
        $this->assertEquals(200, $response->getStatusCode());

        // Assert that the response contains the 'message' key with the expected value
        $this->assertEquals(['message' => 'Task favorited'], $response->getData(true));

        // Assert that the user has favorited the task
        $this->assertTrue($user->favorites->contains($task));
    }

    public function testDestroyMethodUnfavoritingTask()
    {
        // Create a user and a task
        list($user, $task) = $this->createUserAndTask();

        // Attach the task to the user as a favorite
        $user->favorites()->attach($task);

        // Create an instance of the FavoriteController
        $response = $this->actingAs($user)->delete("/api/favorite/{$task->id}");
        // Call the destroy method

        // Assert that the response is a JSON response
        $this->assertEquals(200, $response->getStatusCode());

        // Assert that the response contains the 'message' key with the expected value
        $this->assertEquals(['message' => 'Task unfavorited'], $response->getData(true));

        // Assert that the user has unfavorited the task
        $this->assertFalse($user->favorites->contains($task));
    }
}
