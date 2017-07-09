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
}
