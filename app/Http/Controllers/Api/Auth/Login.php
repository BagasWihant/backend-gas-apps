<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\Respons;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Login extends Controller
{
    public function login(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'email'  => 'required|email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return new Respons(false, 'Validation Failed', $validator->errors());
        }


        if (!Auth::attempt($req->only(['email', 'password']))) {
            return new Respons(false, 'Email / Password salah', ['fail'=>true]);
        }

        $user = User::where('email', $req->email)->first();
        $user->tokens()->delete();

        $success['token'] =  $user->createToken($user->name)->plainTextToken;
        return new Respons(true, 'Success Login', $success);
    }
}
