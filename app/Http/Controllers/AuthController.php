<?php

namespace App\Http\Controllers;

use App\Models\Hte;
use App\Models\User;
use App\Models\Admin;
use App\Models\Intern;
use App\Models\Coordinator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // For sending emails
use Illuminate\Support\Str;       // For generating tokens
use App\Mail\CoordinatorSetupMail; // Your custom mail class
use Illuminate\Support\Facades\DB; // For database operations

class AuthController extends Controller
{
    public function adminLogin()
    {
        return view('auth.login-admin');
    }

    public function coordinatorLogin()
    {
        return view('auth.login-coordinator');
    }

    public function internLogin()
    {
        return view('auth.login-intern');
    }

    public function hteLogin()
    {
        return view('auth.login-hte');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function adminAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'faculty_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Eager load the user relationship
        $admin = Admin::with('user')->where('faculty_id', $credentials['faculty_id'])->first();

        if (!$admin || !$admin->user) {
            return back()->withErrors([
                'faculty_id' => 'The provided credentials do not match our records.',
            ])->onlyInput('faculty_id');
        }

        // Attempt authentication
        if (Auth::attempt([
            'email' => $admin->user->email,
            'password' => $credentials['password']
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('admin/dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('faculty_id');
    }

    public function coordinatorAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'faculty_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $coordinator = Coordinator::with('user')->where('faculty_id', $credentials['faculty_id'])->first();

        if (!$coordinator || !$coordinator->user) {
            return back()->withErrors([
                'faculty_id' => 'The provided credentials do not match our records.',
            ])->onlyInput('faculty_id');
        }

        if (Auth::attempt([
            'email' => $coordinator->user->email,
            'password' => $credentials['password']
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('coordinator/dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('faculty_id');
    }

    public function internAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find intern by student_id with user relationship
        $intern = Intern::with('user')->where('student_id', $credentials['student_id'])->first();

        if (!$intern || !$intern->user) {
            return back()->withErrors([
                'student_id' => 'The provided credentials do not match our records.',
            ])->onlyInput('student_id');
        }

        // Attempt login using the associated user's email
        if (Auth::attempt([
            'email' => $intern->user->email,
            'password' => $credentials['password']
        ], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('intern/dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('student_id');
    }

    public function hteAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find HTE by email with user relationship
        $hte = Hte::with('user')->whereHas('user', function($query) use ($credentials) {
            $query->where('email', $credentials['email']);
        })->first();

        if (!$hte) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Attempt login
        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ], $request->remember)) {
            // Check if user is actually an HTE
            if (!auth()->user()->hte) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'This account is not registered as an HTE.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended('hte/dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('email');
    }

public function showSetupForm($token, $role)
{
    // Validate the role first
    if (!in_array($role, ['intern', 'coordinator', 'hte'])) {
        return redirect()->route('login')
            ->with('error', 'Invalid account type.');
    }

    $tokenData = DB::table('password_setup_tokens')
        ->where('token', $token)
        ->where('created_at', '>', now()->subHours(24))
        ->first();

    if (!$tokenData) {
        return redirect()->route('login')
            ->with('error', 'Invalid or expired activation link.');
    }

    // Check if user exists and has the correct role
    $user = User::where('email', $tokenData->email)->first();
    if (!$user) {
        return redirect()->route('login')
            ->with('error', 'User account not found.');
    }

    return view('auth.setup-password', [
        'token' => $token,
        'email' => $tokenData->email,
        'role' => $role
    ]);
}

    public function processSetup(Request $request, $token, $role)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $tokenData = DB::table('password_setup_tokens')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$tokenData) {
            return back()->with('error', 'Invalid or expired activation link.');
        }

        $user = User::where('email', $tokenData->email)->firstOrFail();
        
        // Optional role verification
        if ($role === 'intern' && !$user->intern) {
            return back()->with('error', 'This account is not an intern.');
        }
        
        if ($role === 'coordinator' && !$user->coordinator) {
            return back()->with('error', 'This account is not a coordinator.');
        }

        $user->update([
                'password' => Hash::make($request->password),
                'temp_password' => false
            ]);

            // Explicitly specify the guard
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            DB::table('password_setup_tokens')->where('token', $token)->delete();

            return redirect()->route("{$role}.dashboard")
                ->with('success', 'Password set successfully!');
    }
}
