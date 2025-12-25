<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = $request->createRegisterDTO();
        $result = $this->authService->register($dto);

        return $this->created($result, 'messages.user_registered');
    }

    /**
     * Login a user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $dto = $request->createLoginDTO();
        $result = $this->authService->login($dto);

        return $this->success($result, 'messages.user_logged_in');
    }

    /**
     * Logout a user.
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout(auth()->user());

        return $this->success([], 'messages.user_logged_out');
    }

    /**
     * Get authenticated user.
     */
    public function me(): JsonResponse
    {
        return $this->success(
            ['user' => auth()->user()->load('brand')],
            'messages.user_retrieved'
        );
    }
}
