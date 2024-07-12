<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PharIo\Manifest\Email;

class AuthController extends Controller
{
    public function register(RegisterRequest $registerRequest)
    {
        $user = User::create($registerRequest->all())
            ->sendEmailVerificationNotification();

        return Response::successResponse('Verification Link Sent!', []);
    }

    public function verify(VerifyRequest $verifyRequest)
    {
        $userId = $verifyRequest->route('id');

        $user = User::find($userId);

        if (!hash_equals((string) $verifyRequest->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->markEmailAsVerified())
            event(new Verified($user));

        return Response::successResponse('Email Verification Success', []);
    }

    public function login(LoginRequest $loginRequest)
    {
        $email = $loginRequest->input('email');
        $password = $loginRequest->input('password');

        $user = User::where('email', $email)
            ->first();
        if (is_null($user->email_verified_at)) {
            return Response::errorResponse('Please Check Your Email For Email Verification Link.', [], 401);
        }

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $responseData = ['token' => $user->createToken('fancy-todo-app')->plainTextToken];

        return Response::successResponse('You are logged in', $responseData);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return Response::successResponse('Logged out!', []);
    }
}
