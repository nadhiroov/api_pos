<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthWeb extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    function loginPage() {
        if (Auth::check()) return back();
        return view('auth.login');
    }
    
    public function login(LoginRequest $request) {
        $data = $request->validated();
        $loginResult = $this->authService->login($data);

        if ($loginResult['status']) {
            // Login menggunakan Auth facade
            Auth::login($loginResult['dataUser']);

            // Regenerate session
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'invalid' => $loginResult['message'] ?? 'Login failed'
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
