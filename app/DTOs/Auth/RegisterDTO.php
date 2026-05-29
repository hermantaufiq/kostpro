<?php

namespace App\DTOs\Auth;

readonly class RegisterDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $phone = null,
        public ?string $nik = null,
    ) {}
}
