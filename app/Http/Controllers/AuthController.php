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

    public function showLoginForm()
    {
        $roles = $request->session()->get('roles', []);
        $karyawan = $request->session()->get('karyawan', null);

        return view('login', compact('roles', 'karyawan'));
    }

    // Handle login
    public function login(Request $request)
    {
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

        $roles = Sso::where('kry_id', $karyawan->kry_id)->get();
        $request->session()->put('roles',$roles);
        $request->session()->put('karyawan',$karyawan);

        return view('login');
    }

    public function authenticate(Request $request)
    {
        $karyawan = session('karyawan');

        if(!$karyawan)
        {
            return redirect()->route('login')->with('error', 'Sesi telah berakhir, silahkan login kembali!');
        }

        $role = $request->input('role');
        if(!in_array($role,session('roles')))
        {
            return redirect()->route('login')->with('error','Aksi dilarang!');
        }
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
