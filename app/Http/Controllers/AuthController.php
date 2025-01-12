<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;
use App\Models\Sso;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('layouts.pages.login');
    }

    // Handle login
    public function login(Request $request)
    {
         // Validate the form input
         $credentials = $request->only('username', 'password');
         $validator = Validator::make($credentials, [
             'username' => 'required',
             'password' => 'required',
         ]);
 
         if ($validator->fails()) {
             return back()->withErrors($validator)->withInput();
         }
 
         // Check the karyawan table for matching credentials
         $karyawan = Karyawan::where('kry_username', $credentials['username'])->first();
 
         if ($karyawan && Hash::check($credentials['password'], $karyawan->kry_password)) {
             // Check the roles from the sso table
             $roles = Sso::where('kry_id', $karyawan->kry_id)->pluck('sso_level')->toArray();
 
             // If there is at least one role, show the role selection modal
             if (count($roles) > 0) {
                 // Pass roles to the login view
                 return view('login', ['roles' => $roles, 'karyawan' => $karyawan]);
             }
 
             // If no roles found, return an error
             return back()->with('error', 'User ini belum mendapatkan hak akses');
         }
 
         return back()->with('error', 'Username atau password salah!');
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
