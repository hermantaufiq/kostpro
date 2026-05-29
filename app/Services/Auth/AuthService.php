<?php

namespace App\Services\Auth;

use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Repositories\UserRepositoryInterface;
use App\DTOs\Auth\RegisterDTO;
use App\DTOs\Auth\LoginDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function register(RegisterDTO $dto)
    {
        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'phone' => $dto->phone,
            'nik' => $dto->nik,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

    public function login(LoginDTO $dto): bool
    {
        return Auth::attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ], $dto->remember);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
