<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class PostsController extends Controller
{

    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        $post = Post::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id'),
            'user_id' => $user['id'],
        ]);
        
        $user->update(['rating' => $user['rating'] + 5]);

        return $post;
    }

    public function show($id)
    {
        if (Post::find($id) == null) {
            return response(['message' => 'Post does not exist'], 404);
        }

        return Post::find($id);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if (!$post = Post::find($id)) {
            return response(['message' => 'Post does not exist'], 404);
        } else if ($post['user_id'] != $user['id'] && $user['role'] != 'admin') {
            return response(['message' => 'It is not user`s post'], 403);
        }

        if ($request->input('title')) {
            $post->update([
                'title' => $request->input('title'),
            ]);
        }
        if ($request->input('content')) {
            $post->update([
                'content' => $request->input('content'),
            ]);
        }
        if ($request->input('category_id')) {
            $post->update([
                'category_id' => $request->input('category_id'),
            ]);
        }
        if ($request->input('status')) {
            $post->update([
                'status' => $request->input('status'),
            ]);
        }

        return $post;
    }

    public function destroy($id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if (!$post = Post::find($id)) {
            return response(['message' => 'Post does not exist'], 404);
        } else if ($post['user_id'] != $user['id']) {
            return response(['message' => 'It is not user`s post'], 403);
        }

        return Post::destroy($id);
    }

    public function likes($id)
    {
        if (!$comment = Post::find($id)) {
            return response(['message' => 'Post does not exist'], 404);
        }
        $likes = Like::all();

        $commentLikes = [];
        foreach ($likes as $like) {
            if ($like['post_id'] == $comment['id'] && $like['type'] == 'like') {
                array_push($commentLikes, $like);
            }
        }

        return $commentLikes;
    }

    public function dislikes($id)
    {
        if (!$comment = Post::find($id)) {
            return response(['message' => 'Post does not exist'], 404);
        }
        $likes = Like::all();

        $commentLikes = [];
        foreach ($likes as $like) {
            if ($like['post_id'] == $comment['id'] && $like['type'] == 'dislike') {
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

        if (!$post = Post::find($id)) {
            return response(['message' => 'Post does not exist'], 404);
        }
        $likes = Like::all();

        foreach ($likes as $like) {
            if ($like['user_id'] == $user['id'] &&
                $like['post_id'] == $id
                && $like['type'] == 'like') {

                return response(['message' => 'Like already exist'], 404);

            }
        }
        $newLike = Like::create(['type' => 'like', 'post_id' => $id, 'user_id' => $user['id']]);

        $likedUser = User::find($post['user_id']);
        $likedUser->update(['rating' => $$likedUser['rating'] + 2]);

        return $newLike;
    }

    public function addDislike(Request $request, $id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if (!$post = Post::find($id)) {
            return response(['message' => 'Post does not exist'], 404);
        }
        $likes = Like::all();

        foreach ($likes as $like) {
            if ($like['user_id'] == $user['id'] &&
                $like['post_id'] == $id
                && $like['type'] == 'dislike') {

                return response(['message' => 'Dislike already exist'], 404);

            }
        }
        $newDislike = Like::create(['type' => 'dislike', 'post_id' => $id, 'user_id' => $user['id']]);

        $dislikedUser = User::find($post['user_id']);
        $dislikedUser->update(['rating' => $$dislikedUser['rating'] - 2]);

        return $newDislike;
    }

    public function comments($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response(['message' => 'Post does not exist'], 404);
        }
        $comments = Comment::get();
        $postComments = [];

        foreach ($comments as $comment) {
            if ($comment["post_id"] == $post["id"]) {
                array_push($postComments, $comment);
            }
        }
        return response()->json($postComments, 200);
    }

    public function createComment(Request $request, $id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if (!Post::find($id)) {
            return response(['message' => 'Post does not exist'], 404);
        }

        $comment = response(Comment::create([
            'content' => $request->input('content'),
            'post_id' => $id,
            'user_id' => $user['id']]
        ), 200);

        $user->update(['rating' => $user['rating'] + 2]);

        return $comment;

    }

    public function category($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response(['message' => 'Post does not exist'], 404);
        }
        $categories = Category::all();

        foreach ($categories as $category) {
            if ($category["id"] == $post["category_id"]) {
                return response($category, 200);
            }
        }
        return response(['message' => 'No category'], 200);
    }

}
