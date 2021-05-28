<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\like;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentsController extends Controller
{
    public function index()
    {
        return Comment::all();
    }

    public function createComment(Request $request, $id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if (!Comment::find($id)) {
            return response(['message' => 'Comment does not exist'], 404);
        }

        return Comment::create([
            'content' => $request->input('content'),
            'user_id' => $user['id'],
            'comment_id' => $id,
        ]);
    }

    public function show($id)
    {
        if (!Comment::find($id)) {
            return response(['message' => 'Comment does not exist'], 404);
        }

        return Comment::find($id);
    }

    public function update(Request $request, $id)
    {
        JWTAuth::toUser(JWTAuth::getToken());

        if (!$comment = Comment::find($id)) {
            return response(['message' => 'Comment does not exist'], 404);
        }
        $comment->update([
            'content' => $request->input('content'),
        ]);

        return $comment;
    }

    public function destroy($id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if (!$comment = Comment::find($id)) {
            return response(['message' => 'Comment does not exist'], 404);
        } else if ($user['id'] != $comment['user_id'] && $user['role'] != 'admin') {
            return response(['message' => 'It is not this user`s comment'], 403);
        }
        return Comment::destroy($id);
    }

    public function likes($id)
    {
        if (!$comment = Comment::find($id)) {
            return response(['message' => 'Comment does not exist'], 404);
        }
        $likes = Like::all();

        $commentLikes = [];
        foreach ($likes as $like) {
            if ($like['comment_id'] == $comment['id'] && $like['type'] == 'like') {
                array_push($commentLikes, $like);
            }
        }

        return $commentLikes;
    }

    public function dislikes($id)
    {
        if (!$comment = Comment::find($id)) {
            return response(['message' => 'Comment does not exist'], 404);
        }
        $likes = Like::all();

        $commentLikes = [];
        foreach ($likes as $like) {
            if ($like['comment_id'] == $comment['id'] && $like['type'] == 'dislike') {
                array_push($commentLikes, $like);
            }
        }

        return $commentLikes;
    }

    public function addLike(Request $request, $id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if (!$comment = Comment::find($id)) {
            return response(['message' => 'Comment does not exist'], 404);
        }
        $likes = Like::all();
        foreach ($likes as $like) {
            if ($like['user_id'] == $user['id'] &&
                $like['comment_id'] == $id
                && $like['type'] == 'like') {

                return response(['message' => 'Like already exist'], 404);

            }
        }
        $newLike = Like::create(['type' => 'like', 'comment_id' => $id, 'user_id' => $user['id']]);

        $likedUser = User::find($comment['user_id']);
        $likedUser->update(['rating' => $likedUser['rating'] + 1]);

        return $newLike;
    }

    public function addDislike(Request $request, $id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if (!$comment = Comment::find($id)) {
            return response(['message' => 'Comment does not exist'], 404);
        }
        $likes = Like::all();

        foreach ($likes as $like) {
            if ($like['user_id'] == $user['id'] &&
                $like['comment_id'] == $id
                && $like['type'] == 'dislike') {

                return response(['message' => 'Dislike already exist'], 404);

            }
        }
        $newDislike = Like::create(['type' => 'dislike', 'comment_id' => $id, 'user_id' => $user['id']]);
        
        $dislikedUser = User::find($comment['user_id']);
        $dislikedUser->update(['rating' => $dislikedUser['rating'] - 1]);

        return $newDislike;
    }

}
