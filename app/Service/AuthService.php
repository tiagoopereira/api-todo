<?php

namespace App\Service;

use App\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findBy('email', $email);

        if (is_null($user) || !Hash::check($password, $user->password)) {
            throw new AuthenticationException('Wrong credentials.');
        }

        $token = $user->createToken('users');

        return [
            'access_token' => $token->accessToken,
            'expires_at' => $token->token->expires_at
        ];
    }
}