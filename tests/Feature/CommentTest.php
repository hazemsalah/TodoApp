<?php

namespace Tests\Feature;


use App\Comment;
use App\Task;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

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

        $comment = factory(Comment::class)->create();


        // When
        // A comments data
       // $commentData = factory(Comment::class)->make(['user_id' => $user->id, 'commentable_id' => $task->id]);
        $commentData = factory(Comment::class)->states('ReplyId')->make(['user_id' => $user->id, 'commentable_id' => $comment->id]);
       // dd($commentData);
//        $this->disableExceptionHandling();
        $response = $this->post('/api/addComment', $commentData->toArray());

        // Then
        // The response should be 200
       //dd($response->getContent());
        $response->assertStatus(200);
        // I see the newly created comment in the database
        $this->assertDatabaseHas('comments', $commentData->toArray());
        $comment = Comment::first();
        // This comment should have a task
        $this->assertNotNull($comment->commentable_id);
        // This comment should be related to the task
        $this->assertEquals($task->id, $comment->commentable_id);


    }
    /** @test */
    public function aUserCanUpdateAComment()
    {
        // given an authenticated user, comment params to be updated
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $comment = factory(Comment::class)->create(['user_id'=>$user->id]);
        $response = $this->post('/api/editComment', ['comment_id'=>$comment->id,'body'=>'update Test']);

        $response->assertStatus(200);
        //dd($comment->body);
        $this->assertDatabaseHas('comments',['body'=>'update Test','user_id'=>$user->id,'votes'=>0,'commentable_id'=>$comment->commentable_id,
            'commentable_type'=>$comment->commentable_type]);

    }
    /** @test */
    public function aUserCanDeleteAComment()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $comment = factory(Comment::class)->create(['user_id'=>$user->id]);

        $response = $this->post('/api/deleteComment', ['comment_id'=>$comment->id]);

        $response->assertStatus(200);

        $this->assertNotNull($comment->id);
    }
    /** @test */
    public function aUserCanSeeAllTheCommentsRelatedToAPost()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);

        $task = factory(Task::class)->create();

        $comment = factory(Comment::class)->states('ReplyId')->create(['user_id'=>$user->id,'body'=>'show']);

        $response = $this->post('/api/getComments', ['commentable_id'=>$task->id,'commentable_type'=>'task']);
        //dd($comment);
        //dd($response);
        $response->assertStatus(200);
    }
}