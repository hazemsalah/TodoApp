<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Vote;

use Illuminate\Http\Request;

class VoteController extends Controller
{
    /**
     *UpVoting on a comment
     *
     * @return Comment $comment
     */

    public function upVote(){

        $this->validate(request(),[
            'voteable_id'=>'required',
        ]);

        $record = Vote::where([
            ['voteable_id', request('voteable_id')],
            ['user_id', auth()->id()]
        ])->first();
        $comment = Comment::where('id', request('voteable_id'))->first();
        if (!($record)) {
            /** @var Comment $comment */
            //what if el voteable_id da5ly le reply ?
            $comment = Comment::find(request('voteable_id'));
            $comment->update([
                'votes' => $comment->votes + 1
            ]);
            $comment->hasVotes()->updateOrCreate([
                'user_id' => auth()->id(),
                'state' => "1"
            ]);
            return response()->json($comment);
        } else {
            $state = $record->state;
            if ($state == 1) {
                return response()->json('You have already upVoted this comment');
            } else {
                $comment = Comment::find(request('voteable_id'));
                $comment->update([
                    'votes' => $comment->votes + 1
                ]);
                $comment->hasVotes()->update([
                    'user_id' => auth()->id(),
                    'state' => "1"
                ]);
                return response()->json($comment);
            }
        }
    }

    /**
     *Voting down on a comment.
     *
     * @return Comment $comment
     */
    public function downVote()
    {
        $this->validate(request(), [
            'voteable_id' => 'required',
        ]);

        $record = Vote::where([
            ['voteable_id', request('voteable_id')],
            ['user_id', auth()->id()]
        ])->first();
        $comment = Comment::where('id', request('voteable_id'))->first();
        if (!($record)) {
            /** @var Comment $comment */
            $comment = Comment::find(request('voteable_id'));
            $comment->update([
                'votes' => $comment->votes - 1
            ]);

            $comment->hasVotes()->updateOrCreate([
                'user_id' => auth()->id(),
                'state' => "0"
            ]);
            return response()->json($comment);
        } else {
            $state = $record->state;
            if ($state == 0) {
                return response()->json('You have already downVoted this comment');
            } else {
                $comment = Comment::find(request('voteable_id'));
                $comment->update([
                    'votes' => $comment->votes - 1
                ]);
                $comment->hasVotes()->update([
                    'user_id' => auth()->id(),
                    'state' => "0"
                ]);
                return response()->json($comment);

            }
        }
    }
}
