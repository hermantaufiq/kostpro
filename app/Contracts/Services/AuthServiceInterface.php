<?php

namespace App\Contracts\Services;

interface AuthServiceInterface
{
    public function register(\App\DTOs\Auth\RegisterDTO $dto);
    public function login(\App\DTOs\Auth\LoginDTO $dto): bool;
    public function logout(): void;
}
