<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request)
{
    return $request->user();
});

// Authentification +
Route::prefix('auth')->group(function () {
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
    Route::get('/refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('/password-reset', 'App\Http\Controllers\UsersController@sendToEmail');
    Route::post('/password-reset/{confirm_token}', 'App\Http\Controllers\UsersController@resetPasswordWithToken');
});

// Users +
Route::get('users', 'App\Http\Controllers\UsersController@index');
Route::post('users', 'App\Http\Controllers\UsersController@store');
Route::prefix('users')->group(function () {
    Route::get('/{user_id}', 'App\Http\Controllers\UsersController@show');
    Route::get('/{user_id}/posts', 'App\Http\Controllers\UsersController@posts');
    Route::patch('/{user_id}', 'App\Http\Controllers\UsersController@update');
    Route::delete('/{user_id}', 'App\Http\Controllers\UsersController@destroy');
    Route::post('/avatar', 'App\Http\Controllers\UsersController@uploadAvatar');
});

// Posts +
Route::get('posts', 'App\Http\Controllers\PostsController@index');
Route::post('posts', 'App\Http\Controllers\PostsController@store');
Route::prefix('posts')->group(function () {
    Route::get('/{post_id}', 'App\Http\Controllers\PostsController@show');
    Route::get('/{post_id}/comments', 'App\Http\Controllers\PostsController@comments');
    Route::post('/{post_id}/comments', 'App\Http\Controllers\PostsController@createComment');
    Route::get('/{post_id}/category', 'App\Http\Controllers\PostsController@category');
    Route::get('/{post_id}/likes', 'App\Http\Controllers\PostsController@likes');
    Route::get('/{post_id}/dislikes', 'App\Http\Controllers\PostsController@dislikes');
    Route::post('/{post_id}/likes', 'App\Http\Controllers\PostsController@addLike');
    Route::post('/{post_id}/dislikes', 'App\Http\Controllers\PostsController@addDislike');
    Route::patch('/{post_id}', 'App\Http\Controllers\PostsController@update');
    Route::delete('/{post_id}', 'App\Http\Controllers\PostsController@destroy');
});

// Categories +
Route::get('categories', 'App\Http\Controllers\CategoriesController@index');
Route::post('categories', 'App\Http\Controllers\CategoriesController@store');
Route::prefix('categories')->group(function () {
    Route::get('/{category_id}', 'App\Http\Controllers\CategoriesController@show');
    Route::get('/{category_id}/posts', 'App\Http\Controllers\CategoriesController@posts');
    Route::patch('/{category_id}', 'App\Http\Controllers\CategoriesController@update');
    Route::delete('/{category_id}', 'App\Http\Controllers\CategoriesController@destroy');
});

// Comments +
Route::get('comments', 'App\Http\Controllers\CommentsController@index');
Route::prefix('comments')->group(function () {
    Route::get('/{comment_id}', 'App\Http\Controllers\CommentsController@show');
    Route::post('/{comment_id}/create-comment', 'App\Http\Controllers\CommentsController@createComment');
    Route::get('/{category_id}/posts', 'App\Http\Controllers\CommentsController@posts');
    Route::get('/{comment_id}/likes', 'App\Http\Controllers\CommentsController@likes');
    Route::post('/{comment_id}/likes', 'App\Http\Controllers\CommentsController@addLike');
    Route::get('/{comment_id}/dislikes', 'App\Http\Controllers\CommentsController@dislikes');
    Route::post('/{comment_id}/dislikes', 'App\Http\Controllers\CommentsController@addDislike');
    Route::patch('/{comment_id}', 'App\Http\Controllers\CommentsController@update');
    Route::delete('/{comment_id}', 'App\Http\Controllers\CommentsController@destroy');
});
