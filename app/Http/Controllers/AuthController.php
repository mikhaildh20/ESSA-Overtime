<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\Karyawan;
use App\Models\Sso;

class AuthController extends Controller
{
    // Get the rate limiter key for the login attempt
    protected function getLoginRateLimiterKey(Request $request)
    {
        return 'login:' . $request->ip(); // Use IP address as the key for rate limiting
    }

    // Increment the login attempts counter
    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit($this->getLoginRateLimiterKey($request));
    }

     // Rate limiting to prevent brute force attacks
    protected function checkLoginAttempts(Request $request)
    {
        $key = $this->getLoginRateLimiterKey($request);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['username' => 'Terlalu banyak upaya masuk. Coba lagi nanti.']);
        }
    }

    public function showLoginForm(Request $request)
    {
        return view('layouts.pages.login');
    }

    // Handle login
    public function login(Request $request)
    {
        if(Auth::check())
        {
            $this->logout();
        }

        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

         // Apply rate limiting on login attempts to prevent brute force attacks
        $this->checkLoginAttempts($request);

        $karyawan = Karyawan::where('kry_username', $request->username)->first();

        if (!$karyawan || !Hash::check($request->password, $karyawan->kry_password)) {
            $this->incrementLoginAttempts($request);
            return back()->with('error', 'Kredensial tidak valid.');
        }

        $sso = Sso::where('kry_id', $karyawan->kry_id)->pluck('sso_level')->toArray();
        if(count($sso) == 0){
            return back()->with('error','User ini belum mempunyai hak akses.');
        }

        session([
            'kry_id' => $karyawan->kry_id_alternative,
            'roles' => $sso,
            'kry_name' => $karyawan->kry_name
        ]);

        return redirect('login');
    }

    public function authenticate(Request $request)
    {
        if(Auth::check())
        {
            $this->logout();
        }

        $role = $request->input('role');
        if(in_array($role, session('roles',[])))
        {
            Auth::loginUsingId(session('kry_id'));
            session(['role' => $role]);
            session()->regenerate();
            return redirect('/');
        }

        return back()->with('error', 'Tindakan dilarang.');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
