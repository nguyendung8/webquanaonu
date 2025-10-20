<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => ['required','string','max:50','unique:users,username'],
            'email' => ['required','email','max:100','unique:users,email'],
            'password' => ['required','min:6','confirmed'],
        ]);

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect($this->redirectPathFor($user));
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => ['required','string'],
            'password' => ['required','string'],
        ]);

        $identifier = $request->input('identifier');
        $credentials = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? ['email' => $identifier, 'password' => $request->password]
            : ['username' => $identifier, 'password' => $request->password];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['identifier' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.'])->onlyInput('identifier');
            }

            $request->session()->regenerate();
            return redirect($this->redirectPathFor($user));
        }

        return back()->withErrors(['identifier' => 'Thông tin đăng nhập không đúng.'])->onlyInput('identifier');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    protected function redirectPathFor(User $user): string
    {
        return $user->role === 'admin' ? route('admin.dashboard') : route('home');
    }
}


