<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller
{
    public function __construct()
    {
    }

    public function create()
    {
        // $model::comments()->create()
        $this->validate(request(), [
            'body' => 'required',
            'commentable_id' => 'required',
            'commentable_type' => 'required'
        ]);
        Comment::create([
            'user_id' => auth()->id(),
            'body' => request('body'),
            'commentable_id' => request('commentable_id'),
            'commentable_type' => request('commentable_type')
        ]);

        return response()->json('Your Comment has been successfully posted :)');
    }

    public function update()
    {

        $this->validate(request(), [

            'comment_id' => 'required',
            'body' => 'required'
        ]);

        $comment = Comment::find(request('comment_id'));

        if (\Gate::denies('commentOwner', $comment)) {
            return response()->json("Comment doesn't Belong To you");

        }
        $comment->body=request('body');
        $comment->save();
        return response()->json("Your Comment has been successfully edited :)");
    }


    public function destroy()
    {
        $this->validate(request(), [
            'comment_id' => 'required'
        ]);
        $comment = Comment::find(request('comment_id'));
        if (\Gate::denies('commentOwner', $comment)) {
            return response()->json("Comment doesn't Belong To you");

        }
        $comment->delete();
        return response()->json('Comment Deleted Successfully');
    }
    public function show()
    {
        $this->validate(request(),[
            'commentable_type' => 'required',
           'commentable_id' => 'required'
        ]);

        $comments = Comment::where(['commentable_type'=>request('commentable_type')],['commentable_id'=>request('commentable_id')])->get();

        return response()->json($comments);

    }
}
