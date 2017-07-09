<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Comment;
use App\User;
use App\Task;
use App\Mention;


class CommentTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;
    public $user;

    public function setUp()
    {
        parent::setUp();


    }
    /** @test */
public function aUserCanUploadPicWithComment(){
    //given a comment and an authenticated user
    $this->user = factory(User::class)->create();
    $this->actingAs($this->user);
    $comment=  factory(Comment::class)->create();
    //When I upload a file
    Storage::fake('local');
   $file= UploadedFile::fake()->image('file.jpg',1500, 800);
    $response = $this->post('api/uploadFile', [
        'file'=>$file,
        'comment_id'=>$comment->id
    ]
    );
    //then
    $response->assertStatus(200);
    Storage::disk('local')->assertExists('/uploads/file.jpg');
    $this->assertDatabaseHas('files', ['comment_id'=>$comment->id,'path'=>'uploads/file.jpg']);
    $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix()."uploads/file.jpg";
    $uF = new \Symfony\Component\HttpFoundation\File\UploadedFile($storagePath,'file.jpg');
    $this->assertEquals(getimagesize($uF)[0],800);
    $this->assertEquals(getimagesize($uF)[1],800);


}
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
        // When

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



