<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\Comment;
use App\Vote;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VotesTest extends TestCase
{
   use DatabaseTransactions, DatabaseMigrations;
    /** @test */
   public function aUserCanUpVoteOnAComment(){
       // Given a comment, an authenticated User and vote params
       //An authenticated user
       $user = factory(User::class)->create();
       $this->actingAs($user);
       // A comment
       $comment = factory(Comment::class)->create();
       //vote params
       $voteData = factory(Vote::class)->make(['user_id' => $user->id, 'voteable_id' => $comment->id]);

       // When
       $response = $this->post('/api/upVote', $voteData->toArray());

       // Then
       // The response should be 200
       //dd($response);
       $response->assertStatus(200);

       // I see the newly created vote in the database
       $this->assertDatabaseHas('votes', $voteData->toArray());
       $vote = Vote::first();

       // This vote should be related to the comment
       $this->assertEquals($vote->voteable_id, $comment->id);
   }


}
