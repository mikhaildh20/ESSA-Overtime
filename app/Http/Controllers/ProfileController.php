<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTransferObjects\ProfileDto;
use App\Models\Karyawan;
use App\Models\Sso;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil ID dari session untuk digunakan dalam query
        $id = session('topkey');

        // Mengambil profil karyawan yang sesuai dengan ID yang ada di session
        $profile = Karyawan::with('dpo_msjabatan')
            ->select('kry_id', 'kry_name', 'kry_email', 'jbt_id', 'kry_username')  // Pilih kolom yang diperlukan saja
            ->where('kry_id', $id)  // Menyaring berdasarkan ID karyawan
            ->firstOrFail();  // Ambil data pertama, atau gagal jika tidak ditemukan

        // Ambil nama jabatan dari relasi dpo_msjabatan, jika ada
        $jabatan = $profile->dpo_msjabatan->jbt_name ?? null;  // Operator null coalescing untuk menghindari error jika relasi kosong

        // Ambil level SSO dan map ke nama peran dalam satu query
        $sso_levels = Sso::select('sso_level')  // Pilih hanya kolom sso_level
            ->where('kry_id', $id)  // Menyaring berdasarkan ID karyawan
            ->orderBy('sso_level', 'desc')  // Urutkan berdasarkan level SSO, yang tertinggi lebih dulu
            ->get()  // Ambil semua data SSO yang cocok
            ->map(function ($item) {
                // Mapping untuk level SSO ke nama peran
                $levelMapping = [
                    1 => 'Karyawan',
                    2 => 'Human Resources',
                    3 => 'Administrator',
                ];

                // Kembalikan nama peran atau 'Unknown' jika tidak ada kecocokan
                return $levelMapping[$item->sso_level] ?? 'Unknown';
            });

        // Gabungkan level peran menjadi string dengan koma sebagai pemisah
        $user_level = $sso_levels->implode(", ");  // Menggunakan implode untuk efisiensi penggabungan string

        // Membuat objek ProfileDto dan menghindari XSS dengan htmlspecialchars
        $dto = new ProfileDto(
            htmlspecialchars($profile->kry_name ?? '', ENT_QUOTES, 'UTF-8'),  // Menghindari XSS pada nama
            htmlspecialchars($jabatan ?? '', ENT_QUOTES, 'UTF-8'),  // Menghindari XSS pada jabatan
            htmlspecialchars($profile->kry_email ?? '', ENT_QUOTES, 'UTF-8'),  // Menghindari XSS pada email
            htmlspecialchars($user_level ?? '', ENT_QUOTES, 'UTF-8'),  // Menghindari XSS pada level peran
            htmlspecialchars($profile->kry_username ?? '', ENT_QUOTES, 'UTF-8')  // Menghindari XSS pada username
        );

        // Mengembalikan tampilan dengan membawa data ProfileDto
        return view('layouts.pages.profile-preview', compact('dto','id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cek apakah ID yang diberikan sama dengan sesi pengguna saat ini
        if ($id != session('topkey')) {
            return redirect()->route('profile.index')->with('error', 'Aksi dilarang!');
        }

        // Validasi input dari pengguna
        $request->validate([
            'current_password' => 'required', // Password lama wajib diisi
            'new_password' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/', // Password baru harus sesuai aturan
            'confirm_password' => 'required|same:new_password' // Konfirmasi password harus sama dengan password baru
        ], [
            'new_password.min' => 'Password minimal 8 karakter!', // Pesan error untuk panjang minimal
            'new_password.regex' => 'Password baru setidaknya memiliki 1 huruf kecil, huruf besar, angka dan simbol!', // Pesan error untuk aturan regex
            'confirm_password.same' => 'Password tidak sama!' // Pesan error jika konfirmasi tidak cocok
        ]);

        // Ambil password lama dari database berdasarkan sesi pengguna
        $old_password = Karyawan::select('kry_password')->where('kry_id', $id)->first();

        // Cek apakah password lama yang diinputkan cocok dengan password yang ada di database
        if (!Hash::check($request->input('current_password'), $old_password->kry_password)) {
            return back()->withErrors(['current_password' => 'Password salah!']); // Jika tidak cocok, kembalikan dengan error
        }

        // Hash password baru dan update di database
        Karyawan::where('kry_id', $id)->update([
            'kry_password' => Hash::make($request->input('new_password')), // Hash password baru sebelum menyimpannya
            'kry_modified_by' => session('kry_name') // Update kolom kry_modified_by dengan pengguna yang sedang aktif
        ]);

        // Redirect ke halaman profil dengan pesan sukses
        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
