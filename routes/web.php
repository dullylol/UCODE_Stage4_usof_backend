<?php

use Illuminate\Support\Facades\Route;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', function() {
    try {
        $user = JWTAuth::toUser(JWTAuth::getToken());
    } catch (Exception $exception) {
        return view('admin_panel/errors/not_login_in');
    }

    if ($user['role'] == 'user') {
        return view('admin_panel/errors/access_denied');
    } else if ($user['role'] == 'admin') {
        return view('admin_panel/start_page');
    }
});
