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
        return 'login:' . $request->ip(); // Menggunakan alamat IP sebagai kunci untuk pembatasan laju login
    }

    // Increment the login attempts counter
    protected function incrementLoginAttempts(Request $request)
    {
        RateLimiter::hit($this->getLoginRateLimiterKey($request)); // Menambah hit pada rate limiter untuk kunci login
    }

    // Rate limiting to prevent brute force attacks
    protected function checkLoginAttempts(Request $request)
    {
        $key = $this->getLoginRateLimiterKey($request); // Mendapatkan kunci pembatasan laju login berdasarkan IP

        if (RateLimiter::tooManyAttempts($key, 5)) { // Memeriksa apakah upaya login melebihi batas maksimum
            throw ValidationException::withMessages(['username' => 'Terlalu banyak upaya masuk. Coba lagi nanti.']); // Melempar pengecualian jika batas dilanggar
        }
    }

    // Show login form
    public function showLoginForm(Request $request)
    {
        return view('layouts.pages.login'); // Menampilkan halaman formulir login
    }

    // Handle login
    public function login(Request $request)
    {
        if(Auth::check()) // Jika pengguna sudah login
        {
            $this->logout(); // Logout pengguna yang sedang aktif
        }

        $request->validate([ // Validasi input login
            'username' => 'required|string|max:255', // Username wajib diisi, tipe string, dan maksimal 255 karakter
            'password' => 'required|string|min:8', // Password wajib diisi, tipe string, dan minimal 8 karakter
        ]);

        // Apply rate limiting on login attempts to prevent brute force attacks
        $this->checkLoginAttempts($request); // Memeriksa pembatasan laju untuk upaya login

        $karyawan = Karyawan::where('kry_username', $request->username)->first(); // Mencari pengguna berdasarkan username

        if (!$karyawan || !Hash::check($request->password, $karyawan->kry_password)) { // Memeriksa apakah pengguna ada dan password sesuai
            $this->incrementLoginAttempts($request); // Menambah hit rate limiter jika login gagal
            return back()->with('error', 'Kredensial tidak valid.'); // Mengembalikan pesan error jika kredensial tidak valid
        }

        if($karyawan->kry_status == 0){
            return back()->with('error', 'Akun anda telah dinonaktfikan.');
        }

        $sso = Sso::where('kry_id', $karyawan->kry_id)
                ->where('sso_status',1)
                ->orderBy('sso_level','desc')
                ->pluck('sso_level')->toArray(); // Mengambil level akses (sso_level) pengguna
        if(count($sso) == 0){ // Memeriksa apakah pengguna tidak memiliki level akses
            return back()->with('error','User ini belum mempunyai hak akses.'); // Mengembalikan pesan error jika tidak ada hak akses
        }

        session([ // Menyimpan data pengguna di session
            'topkey' => $karyawan->kry_id,
            'kry_id' => $karyawan->kry_id_alternative, // ID alternatif karyawan
            'roles' => $sso, // Level akses pengguna
            'kry_name' => $karyawan->kry_name // Nama karyawan
        ]);

        return redirect('login'); // Mengarahkan ke halaman login
    }

    // Authenticate user based on selected role
    public function authenticate(Request $request)
    {
        if(Auth::check()) // Jika pengguna sudah login
        {
            $this->logout(); // Logout pengguna aktif
        }

        $role = $request->input('role'); // Mendapatkan role yang dipilih dari input
        if(in_array($role, session('roles',[]))) // Memeriksa apakah role termasuk dalam daftar roles yang disimpan di session
        {
            Auth::loginUsingId(session('kry_id')); // Login pengguna berdasarkan ID karyawan
            session(['role' => $role]); // Menyimpan role aktif di session
            // Mencegah session hijacking
            session()->regenerate(); // Meregenerasi session untuk keamanan
            return redirect('/'); // Mengarahkan ke halaman utama
        }

        return back()->with('error', 'Tindakan dilarang.'); // Mengembalikan pesan error jika role tidak valid
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout(); // Logout pengguna dari sistem
        $request->session()->invalidate(); // Menghapus seluruh data session
        $request->session()->regenerateToken(); // Meregenerasi token CSRF untuk keamanan

        return redirect('login'); // Mengarahkan kembali ke halaman login
    }

}
