<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        return [
            'user' => $user,
            'token' => JWTAuth::fromUser($user)
        ];
    }

    public function checkCredentials(array $credentials): array
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials']);
        }

        return [
            'user' => auth()->user(),
            'token' => $token
        ];
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refreshToken(): array
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());

        return [
            'token' => $newToken
        ];
    }
}