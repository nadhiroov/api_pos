<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

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
            Auth::login($loginResult['dataUser']);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'invalid' => $loginResult['message'] ?? 'Login failed'
        ])->onlyInput('username');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        if (User::where('username', $data['username'])->count() == 1) {
            return back()->withErrors([
                'invalid' => $loginResult['message'] ?? 'Username already registered'
            ])->withInput();
        }
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();
        if ($request['code'] != null) {
            $shop = Shop::where('code', $request['code'])->first();
            if ($shop) {
                $staffIds = $shop->staff_id ?? [];
                if (!in_array($user->id, $staffIds)) {
                    array_push($staffIds, $user->id);
                    Arr::sort($staffIds);
                }
                $shop->update(['staff_id' => $staffIds]);
            }
        }
        return redirect('/login')->with('success', 'Registration successful, please login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
