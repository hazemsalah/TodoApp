<?php

namespace Tests\Feature;


use App\Comment;
use App\Task;
use App\User;
use App\Mention;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class CommentTest
 * @package Tests\Feature
 */
class CommentTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;
    /** @test */
    public function aUserCanCreateACommentOnATask()
    {
        // Given a task, an authenticated User and comment params
        //An authenticated user
        $user = factory(User::class)->create();
        $this->actingAs($user);
        // A task
        $task = factory(Task::class)->create();

        // When
        // A comments data
        $commentData = factory(Comment::class)->make(['user_id' => $user->id, 'task_id' => $task->id]);
        $response = $this->post('/api/addComment', $commentData->toArray());
        $response->assertStatus(200);
        // I see the newly created comment in the database
        $this->assertDatabaseHas('comments', $commentData->toArray());
        $comment = Comment::first();
        // This comment should have a task
        $this->assertNotNull($comment->task);
        // This comment should be related to the task
        $this->assertEquals($task->id, $comment->task->id);
    }
    /** @test */
    public function mentionAUserInAComment(){
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $user1 = factory(User::class)->create();
        $task = factory(Task::class)->create();
        $commentData = factory(Comment::class)->make(['user_id' => $user->id, 'task_id' => $task->id,
            'mentioned_user_id' => [$user1->id]]);
        $response = $this->post('/api/addComment', $commentData->toArray());
        $response->assertStatus(200);
        $mention = Mention::first();
        $comment = Comment::first();
        $this->assertNotNull($mention);
        $this->assertNotNull($comment);
        $this->assertEquals($mention->comment_id, $comment->id);
    }
}
