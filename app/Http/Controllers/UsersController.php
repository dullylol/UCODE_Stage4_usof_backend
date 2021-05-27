<?php

namespace App\Http\Controllers;

use App\Http\Requests\AvatarRequest;
use App\Http\Requests\MailRequest;
use App\Models\PasswordResets;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{

    public function index()
    {
        return User::all();
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

        return User::create($request->all());
    }

    public function show($id)
    {
        if (User::find($id) == null) {
            return response(['message' => 'User does not exist'], 404);
        }

        return User::find($id);
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
        } else if (!$data = User::find($id)) {
            return response(['message' => 'User does not exist'], 404);
        } else {
            $rating = $user['rating'];
            $data->update($request->all());
            $data->update(['rating' => $rating]);
        }

        $rating = $user['rating'];
        $user->update($request->all());
        $user->update(['rating' => $rating]);

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
        } else if (User::find($id) == null) {
            return response(['message' => 'User does not exist'], 404);
        }

        return User::destroy($id);
    }

    public function uploadAvatar(AvatarRequest $request)
    {
        if ($request->file('image')) {
            try {
                $user = JWTAuth::toUser(JWTAuth::getToken());
            } catch (Exception $exception) {
                return response(['message' => $exception->getMessage()], 404);
            }

            if ($user->image != 'avatars/default.jpeg') {
                Storage::delete('public/' . $user->image);
            }

            $user->update([
                'image' => $image = $request->file('image')->storeAs('avatar', $user->id . $request->file('image')->getClientOriginalName(), 'public'),
            ]);

            return response([
                "message" => "Avatar uploaded",
                "image" => $image,
            ]);
        }
    }

    public function sendToEmail(MailRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        $token = Str::random(50);
        try {
            if ($user && PasswordResets::where('email', $user->email)->first()) {
                PasswordResets::where('email', $user->email)->update(['token' => $token]);
            } else {
                PasswordResets::create([
                    'email' => $user->email,
                    'token' => $token,
                ]);
            }
            $data = [
                'login' => $user->login,
                'name' => $user->name,
                'role' => $user->role,
                'resetLink' => URL::current() . '/' . $token,
            ];
            Mail::send('reset_password', $data, function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Password reset');
            });

            return response([
                'message' => 'Password reset sent to ' . $user->email . '!',
            ]);
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
            ], 400);
        }
    }

    public function passwordResetWithToken(MailRequest $request, $token)
    {
        try {
            if (!$data = PasswordResets::where('token', $token)->first()) {
                return response([
                    'message' => 'Invalid token!',
                ], 400);
            }
            if (!$user = User::where('email', $data->email)->first()) {
                return response([
                    'message' => 'User does not exist!',
                ], 404);
            }
            $user->password = Hash::make($request->input('password'));
            $user->save();

            PasswordResets::where('email', $data->email)->delete();
        } catch (Exception $exception) {
            return response([
                'message' => $exception->getMessage(),
            ], 400);
        }

        return response([
            'message' => 'Password reseted',
        ]);
    }
}
