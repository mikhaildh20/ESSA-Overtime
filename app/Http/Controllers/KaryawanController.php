<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\DataTransferObjects\KaryawanDto;
use App\Models\Jabatan;
use App\DataTransferObjects\JabatanDto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\SendEmail;
use App\Mail\NotifikasiEmail;
use App\Mail\ChangeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validasi input dari request
        $validated = $request->validate([
            'search' => 'nullable|string|max:255', // 'search' bisa kosong, harus string, maksimal 255 karakter
            'sort' => 'nullable|in:asc,desc', // 'sort' hanya boleh bernilai 'asc' atau 'desc'\
            'sort-status' => 'nullable|in:0,1' // 'sort-status' hanya boleh bernilai 0 atau 1
        ]);

        // Ambil nilai 'search' dan 'sort' dengan sanitasi
        $search = htmlspecialchars($validated['search'] ?? null, ENT_QUOTES, 'UTF-8'); // Sanitasi input 'search' untuk mencegah XSS
        $sort = $validated['sort'] ?? 'asc'; // Default nilai 'sort' adalah 'asc'
        $sortStatus = $validated['sort-status'] ?? null; // Jika tidak ada 'sort-status', gunakan nilai default null

        // Ambil data karyawan dengan relasi 'jabatan', dan tambahkan kondisi pencarian serta sorting
        $data = Karyawan::with('dpo_msjabatan') // Eager loading untuk relasi 'dpo_msjabatan'
            ->when($search, function ($query, $search) { // Tambahkan kondisi pencarian jika input 'search' ada
                return $query->where('kry_name', 'like', '%' . $search . '%'); // Filter data berdasarkan nama karyawan
            })
            ->when($sortStatus !== null, function ($query) use ($sortStatus) { // Tambahkan filter status jika 'sortStatus' diisi
                return $query->where('kry_status', $sortStatus); // Filter berdasarkan status
            }, function ($query) { // Jika 'sortStatus' tidak diisi, tampilkan hanya data yang aktif
                return $query->where('kry_status', '1'); // Default filter: hanya data aktif
            })
            ->where('kry_id_alternative','!=',session('kry_id'))
            ->orderBy(Karyawan::sanitizeColumn('kry_name'), $sort) // Urutkan berdasarkan kolom yang disanitasi
            ->paginate(10); // Batasi hasil query dengan paginasi, 10 data per halaman

        // Konversi data hasil query menjadi DTO (Data Transfer Object)
        $dto = $data->map(function ($karyawan) {
            return new KaryawanDto(
                htmlspecialchars($karyawan->kry_id_alternative, ENT_QUOTES, 'UTF-8'), // ID alternatif karyawan yang disanitasi
                htmlspecialchars($karyawan->kry_name ?? '', ENT_QUOTES, 'UTF-8'), // Nama karyawan yang disanitasi
                htmlspecialchars($karyawan->dpo_msjabatan->jbt_name ?? '', ENT_QUOTES, 'UTF-8'), // Nama jabatan yang disanitasi
                htmlspecialchars($karyawan->kry_username ?? '', ENT_QUOTES, 'UTF-8'), // Username karyawan yang disanitasi
                htmlspecialchars($karyawan->kry_email ?? '', ENT_QUOTES, 'UTF-8'), // Email karyawan yang disanitasi
                htmlspecialchars($karyawan->kry_status ?? '', ENT_QUOTES, 'UTF-8') // Status karyawan yang disanitasi
            );
        });

        // Kembalikan data ke view untuk ditampilkan
        return view('layouts.pages.master.karyawan.index', [
            'dto' => $dto, // Data yang sudah dikonversi ke DTO untuk kebutuhan tampilan
            'pagination' => $data, // Data pagination untuk kontrol navigasi halaman
            'search' => $search, // Nilai input pencarian untuk dipertahankan di tampilan
            'sort' => $sort, // Status sorting (asc/desc) untuk dipertahankan di tampilan
            'sortStatus' => $sortStatus, // Nilai input 'sort-status' untuk dipertahankan di tampilan
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil semua data jabatan yang memiliki status aktif (status = 1) dari tabel Jabatan.
        $jabatan = Jabatan::where('jbt_status', 1)->get();

        // Melakukan mapping terhadap koleksi jabatan, dan mengubah setiap elemen menjadi objek JabatanDto
        // yang berisi jbt_id dan jbt_name dari setiap jabatan.
        $dto = $jabatan->map(function($data) {
            return new JabatanDto(
                $data->jbt_id,   // Mengambil ID jabatan
                $data->jbt_name  // Mengambil nama jabatan
            );
        });

        // Mengembalikan tampilan 'layouts.pages.master.karyawan.create' dan mengirimkan data dto 
        // (daftar jabatan yang telah diproses) ke view.
        return view('layouts.pages.master.karyawan.create', compact('dto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dengan aturan yang lebih ketat menggunakan metode validate pada request
        $request->validate([
            'kry_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/|unique:dpo_mskaryawan,kry_name', // Nama hanya boleh huruf dan spasi, harus unik di tabel karyawan
            'jbt_id' => 'required', // ID jabatan harus diisi
            'kry_username' => 'required|unique:dpo_mskaryawan,kry_username', // Username harus diisi dan unik
            'kry_email' => 'required|email|unique:dpo_mskaryawan,kry_email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/' // Email harus valid dan unik
        ], [
            'kry_name.regex' => 'Nama karyawan hanya bisa diisi huruf dan spasi.', // Pesan error jika nama tidak sesuai format
            'kry_name.unique' => 'Karyawan telah terdaftar', // Pesan error jika nama sudah ada di database
            'kry_username.unique' => 'Username tidak tersedia.', // Pesan error jika username sudah ada
            'kry_email.unique' => 'Email sudah terdaftar.', // Pesan error jika email sudah ada
            'kry_email.regex' => 'Format email tidak valid.' // Pesan error jika format email salah
        ]);

        try{
            // Mengambil data karyawan terakhir berdasarkan ID alternatif, diurutkan secara menurun
            $latestKaryawan = Karyawan::orderBy('kry_id_alternative', 'desc')->first();

            // Membuat ID baru berdasarkan ID karyawan terakhir
            if ($latestKaryawan) {
                // Mengambil angka dari ID yang terakhir dan menambahkannya
                $latestId = (int) substr($latestKaryawan->kry_id_alternative, 4);
                $newId = 'KRY-' . str_pad($latestId + 1, 3, '0', STR_PAD_LEFT); // ID baru dengan format KRY-001, KRY-002, dst.
            } else {
                // Jika belum ada data, mulai dengan ID pertama
                $newId = 'KRY-001';
            }

            // Membuat password acak yang kuat dengan kombinasi huruf, angka, dan simbol
            $randomPassword = Str::random(8) . rand(1000, 9999) . '!@#'; // Password terdiri dari 8 karakter acak, angka acak 4 digit, dan simbol

            // Hash password menggunakan bcrypt
            $hashedPassword = Hash::make($randomPassword);

            // Membuat instance baru dari model Karyawan dan mengisi atributnya
            $karyawan = new Karyawan();
            $karyawan->kry_id_alternative = $newId; // Menetapkan ID alternatif baru
            $karyawan->kry_name = $request->input('kry_name'); // Nama karyawan dari input request
            $karyawan->jbt_id = $request->input('jbt_id'); // ID jabatan dari input request
            $karyawan->kry_password = $hashedPassword; // Password yang telah di-hash
            $karyawan->kry_username = $request->input('kry_username'); // Username dari input request
            $karyawan->kry_email = $request->input('kry_email'); // Email dari input request
            $karyawan->kry_status = 1; // Status karyawan aktif (1)
            $karyawan->kry_created_by = session('kry_name'); // Pengguna yang membuat data sesuai session
            $karyawan->kry_modified_by = null;

            // Menyimpan data karyawan baru ke dalam database
            $karyawan->save();

            // Mengirim email kepada karyawan yang baru dibuat, berisi username dan password sementara
            Mail::to($karyawan->kry_email)->send(new SendEmail(
                $karyawan->kry_created_by, 
                $karyawan->kry_name, 
                $karyawan->kry_username, 
                $randomPassword
            ));
        }catch(\Exception $e){
            return redirect()->route('karyawan.create')->with('error','Terjadi kesalahan, hubungi Tim IT!');
        }

        // Mengalihkan pengguna ke halaman daftar karyawan dengan pesan sukses
        return redirect()->route('karyawan.index')->with('success', 'Data berhasil ditambah!');
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
        // Mendapatkan data karyawan berdasarkan kry_id_alternative dan mengembalikannya sebagai objek pertama yang ditemukan.
        // Jika tidak ada, maka akan memunculkan error 404 (firstOrFail).
        $karyawan = Karyawan::where('kry_id_alternative', $id)->firstOrFail();

        // Mengambil semua data jabatan yang memiliki status aktif (status = 1).
        $jabatan = Jabatan::where('jbt_status', 1)->get();

        // Melakukan mapping terhadap koleksi jabatan, dan mengubah setiap elemen menjadi objek JabatanDto
        // yang berisi jbt_id dan jbt_name dari setiap jabatan.
        $dto = $jabatan->map(function($data) {
            return new JabatanDto(
                $data->jbt_id,   // Mengambil ID jabatan
                $data->jbt_name  // Mengambil nama jabatan
            );
        });

        // Mengembalikan tampilan 'layouts.pages.master.karyawan.edit' dan mengirimkan data karyawan dan dto 
        // (daftar jabatan yang telah diproses) ke view.
        return view('layouts.pages.master.karyawan.edit', compact('karyawan', 'dto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(session('kry_id') == $id)
        {
            return redirect()->route('karyawan.index')->with('error','Anda tidak diizinkan untuk mengubah data ini.');
        }
        // Cari data karyawan berdasarkan alternative ID
        $karyawan = Karyawan::where('kry_id_alternative', $id)->firstOrFail();

        // Simpan email lama untuk perbandingan
        $oldEmail = $karyawan->kry_email;

        // Validasi input dari pengguna dengan aturan yang lebih ketat.
        $request->validate([
            'kry_name' => [
                'required', // Field 'kry_name' harus diisi.
                'string', // Harus berupa string.
                'max:255', // Maksimal panjang 255 karakter.
                'regex:/^[a-zA-Z\s]+$/', // Hanya boleh mengandung huruf dan spasi.
                Rule::unique('dpo_mskaryawan', 'kry_name')->ignore($karyawan->kry_id_alternative, 'kry_id_alternative'),
            ],
            'jbt_id' => 'required', // Field 'jbt_id' harus diisi (jabatan karyawan).
            'kry_username' => [
                'required', // Field 'kry_username' harus diisi.
                Rule::unique('dpo_mskaryawan', 'kry_username')->ignore($karyawan->kry_id_alternative, 'kry_id_alternative'),
            ],
            'kry_email' => [
                'required', // Field 'kry_email' harus diisi.
                'email', // Harus berupa format email yang valid.
                Rule::unique('dpo_mskaryawan', 'kry_email')->ignore($karyawan->kry_id_alternative, 'kry_id_alternative'),
            ],
        ], [
            'kry_name.regex' => 'Nama karyawan hanya bisa diisi huruf dan spasi.',
            'kry_name.unique' => 'Karyawan telah terdaftar.',
            'kry_username.unique' => 'Username tidak tersedia.',
            'kry_email.unique' => 'Email sudah terdaftar.',
            'kry_email.email' => 'Format email tidak valid.',
        ]);

        // Perbarui atribut karyawan dengan data dari input pengguna.
        $karyawan->kry_name = $request->input('kry_name');
        $karyawan->jbt_id = $request->input('jbt_id');
        $karyawan->kry_username = $request->input('kry_username');
        $karyawan->kry_email = $request->input('kry_email');
        $karyawan->kry_modified_by = session('kry_name');

        // Simpan perubahan data ke database.
        $karyawan->save();

        // Cek apakah email telah diubah
        if ($oldEmail !== $karyawan->kry_email) {
            try{
            
                    // Lakukan sesuatu jika email berubah, misalnya kirim notifikasi
                    // Contoh: Kirim email notifikasi tentang perubahan email
                    // mengirim ke email lama
                    Mail::to($oldEmail)->send(new NotifikasiEmail(
                        $karyawan->kry_modified_by,
                        $karyawan->kry_name,
                        $karyawan->kry_email
                    ));
                    // mengirim ke email baru
                    Mail::to($karyawan->kry_email)->send(new ChangeEmail(
                        $karyawan->kry_modified_by,
                        $karyawan->kry_name,
                        $karyawan->kry_username
                    ));
                
            }catch(\Exception $e){
                return redirect()->route('karyawan.edit')->with('error','Terjadi kesalahan, hubungi Tim IT!');
            }
        }

        // Redirect pengguna ke halaman daftar karyawan.
        return redirect()->route('karyawan.index')->with('success', 'Data berhasil diubah!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function update_status(string $id)
    {
        if(session('kry_id') == $id)
        {
            return redirect()->route('karyawan.index')->with('error','Anda tidak diizinkan untuk menghapus data ini.');
        }
         // Cari karyawan berdasarkan Alternative ID
        $karyawan = Karyawan::where('kry_id_alternative',$id)->firstOrFail();

        // Menentukan status dan pesan berdasarkan status karyawan saat ini
        $status = $karyawan->kry_status == 1 ? 0 : 1;
        $message = $status == 1 ? "Data diaktifkan!" : "Data dihapus!";

        // Menyimpan perubahan status
        $karyawan->kry_status = $status;
        $karyawan->kry_modified_by = session('kry_name');
        $karyawan->save();

        // Mengalihkan dan memberikan pesan status yang sesuai
        return redirect()->route('karyawan.index')->with('success', $message);
    }
}
