<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Contracts\Services\AuthServiceInterface;
use App\DTOs\Auth\RegisterDTO;

class RegisterController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    public function show()
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request)
    {
        $dto = new RegisterDTO(
            name: $request->name,
            email: $request->email,
            password: $request->password,
            phone: $request->phone,
            nik: $request->nik
        );

        $this->authService->register($dto);

        return redirect()->route('dashboard');
    }
}
