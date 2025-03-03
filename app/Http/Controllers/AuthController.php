<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Services\AuthService;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    public function __construct(protected AuthService $authService)
    {}

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $params = $request->validated();

        return $this->sendResponse($this->authService->register($params));
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException|AuthenticationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        return $this->sendResponse($this->authService->login($credentials));
    }
}
