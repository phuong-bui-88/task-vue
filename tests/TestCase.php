<?php

namespace Tests;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    protected function createUser()
    {
        return User::factory()->create();
    }

    protected function createTask()
    {
        return Task::factory()->create();
    }

    protected function createUserAndTask()
    {
        $user = $this->createUser();
        $task = Task::factory()->create(['user_id' => $user->id]);

        return [$user, $task];
    }
}
