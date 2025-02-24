<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(CreateUserRequest $request)
    {
        $validated = $request->validated();

        $data = $this->authService->register($validated);

        return $this->success(
            'user register successfully',
            [
                'user' => $data['user'],
                'token' => $data['token']
            ],
            201
        );
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $loginRequest)
    {
        $validated = $loginRequest->validated();

        $data = $this->authService->checkCredentials($validated);

        return $this->success(
            'Login successful',
            [
                'access_token' => $data['token'],
            ]);
    }

    public function logout()
    {
        $this->authService->logout();

        return $this->success(
            'Logout successful'
        );
    }

    public function refresh()
    {
        try {
            $data = $this->authService->refreshToken();

            return $this->success(
                'Token refreshed successfully',
                [
                    'token' => $data['token'],
                ]
            );
        } catch (\Exception $e) {
            return $this->error(
                'Failed to refresh token',
                [
                    'error' => $e->getMessage()
                ],
                401
            );
        }

        
    }
}
