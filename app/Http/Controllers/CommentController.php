<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Mention;
use App\User;
use App\Notifications\MentionInComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{


    public function store ()
    {
        $this->validate(request(), [
            'body' => 'required',
            'task_id' => 'required',
            'mentioned_users_ids' => 'array',
            'mentioned_users_ids.*'=>'integer|exists:users,id'

        ]);
        $comment = Comment::create([
            'user_id' => auth()->id(),
            'body' => request('body'),
            'task_id' => request('task_id')
        ]);
        $comment = $comment->fresh();
        $mentioned_users_ids = \request()->mentioned_user_id;
        if($mentioned_users_ids) {
            foreach ($mentioned_users_ids as $mentioned_user_id) {
                Mention::create([
                    'comment_id' => $comment->id,
                    'mentioned_user_id' => $mentioned_user_id
                ]);
                $mentioned_user = User::find($mentioned_user_id);
                \Notification::send($mentioned_user, new MentionInComment($comment));
            }
        }


    }

}
