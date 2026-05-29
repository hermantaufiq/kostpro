<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Contracts\Services\AuthServiceInterface;
use App\DTOs\Auth\LoginDTO;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    public function show()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $dto = new LoginDTO(
            email: $request->email,
            password: $request->password,
            remember: $request->boolean('remember')
        );

        if ($this->authService->login($dto)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        $this->authService->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
