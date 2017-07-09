<?php

namespace Tests\Feature;


use App\Task;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function aUserCanCreateTask()
    {
        // Given: I have an array of task data and a logged in user
        $taskData = factory(Task::class)->make(['user_id' => $this->user->id]);


        $this->actingAs($this->user);

        // When: I hit 'api/createTask'
        $response = $this->post('api/addTask', $taskData->toArray());

        // The response should be 200
        $response->assertStatus(200);

        // I should see this task inside the database
        $dbTask = Task::whereUserId($this->user->id)->first();
        $this->assertNotNull($dbTask);

        // Assert that the retrieved task is the same as the created
        $this->assertEquals($taskData->body, $dbTask->body);

        $response->assertJson([
            'result' => [
                'body' => $taskData->body,
                'user_id' => $this->user->id,
                'private' => $taskData->private
            ]

        ]);

    }

}