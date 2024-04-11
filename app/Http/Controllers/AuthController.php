<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        event(new Registered($user));
        return new LoginResource($user);
    }

    public function login(LoginRequest $request)
    {

        if (auth()->attempt($request->validated())) {
            return new LoginResource(auth()->user());
        }

        return response()->json(['message' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();
    }

    function sendEmailVerificationNotification(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
    }
}
