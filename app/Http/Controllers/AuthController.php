<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\AuditService;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            AuditService::log('LOGIN', 'User', (string)$user->id, null, ['email' => $user->email, 'role' => $user->role]);

            return redirect()->intended(route('dashboard'))
                ->with('success', "مرحباً بك مجدداً، {$user->name_ar}! تم تسجيل الدخول بنجاح.");
        }

        return back()->withErrors([
            'email' => 'بيانات الدخول غير صحيحة، يرجى التأكد من البريد الإلكتروني وكلمة المرور.',
        ])->onlyInput('email');
    }

    public function quickLogin(string $role)
    {
        $roleMap = [
            'admin' => 'admin@naturalsnacks.com',
            'production' => 'production@naturalsnacks.com',
            'qc' => 'qc@naturalsnacks.com',
            'warehouse' => 'warehouse@naturalsnacks.com',
            'auditor' => 'auditor@naturalsnacks.com',
        ];

        $email = $roleMap[$role] ?? 'admin@naturalsnacks.com';
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();
            AuditService::log('QUICK_LOGIN', 'User', (string)$user->id, null, ['role' => $user->role]);

            return redirect()->route('dashboard')
                ->with('success', "تم الدخول السريع بحساب: {$user->name_ar} ({$user->role_title})");
        }

        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            AuditService::log('LOGOUT', 'User', (string)$user->id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'تم تسجيل الخروج بنجاح. نتمنى لك يوماً سعيداً!');
    }
}
