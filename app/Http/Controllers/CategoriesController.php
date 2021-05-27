<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoriesController extends Controller
{

    public function index()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if ($user['role'] != 'admin') {
            return response(['message' => 'User is not admin'], 403);
        }
        return Category::create($request->all());
    }

    public function show($id)
    {
        if (Category::find($id) == null) {
            return response(['message' => 'Category does not exist'], 404);
        }

        return Category::find($id);
    }

    public function update(Request $request, $id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if ($user['role'] != 'admin') {
            return response(['message' => 'User is not admin'], 403);
        } else if (!$data = Category::find($id)) {
            return response(['message' => 'Category does not exist'], 404);
        }
        $data->update($request->all());

        return $data;
    }

    public function destroy($id)
    {
        try {
            $user = JWTAuth::toUser(JWTAuth::getToken());
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], 404);
        }

        if ($user['role'] != 'admin') {
            return response(['message' => 'User is not admin'], 403);
        } else if (Category::find($id) === null) {
            return response(['message' => 'Category does not exist'], 404);
        }

        return Category::destroy($id);
    }

    public function posts($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response(['message' => 'Category does not exist'], 404);
        }
        $posts = Post::get();
        $resPosts = [];

        foreach ($posts as $post) {
            if ($post["category_id"] == $category["id"]) {
                array_push($resPosts, $post);
            }
        }
        return response()->json($resPosts, 200);
    }
}
