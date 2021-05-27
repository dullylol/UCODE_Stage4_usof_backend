<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'login' => 'required|string|unique:users,login|max:20|min:3',
            'name' => 'required|max:100|min:3',
            'email' => 'required|email|unique:users,email|max:100|min:3',
            'password' => 'required|min:8|max:50',
        ];
    }
}
