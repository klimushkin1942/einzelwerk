<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService extends BaseService
{
    /**
     * @param array $params
     * @return User
     */
    public function register(array $params): User
    {
        return User::create(
            [
                'email' => $params['email'],
                'password' => $params['password'],
            ]
        );
    }

    /**
     * @param array $credentials
     * @return array
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function login(array $credentials): array
    {
        if (!isset($credentials['email']) || !isset($credentials['password'])) {
            throw new \InvalidArgumentException('Email and password are required.');
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ])->status(Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
