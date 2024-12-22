<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\DataTransferObjects\KaryawanDto;
use App\Models\Jabatan;
use App\DataTransferObjects\JabatanDto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         // Ambil input pencarian dan sorting
        $search = $request->input('search'); 
        $sort = $request->input('sort', 'asc'); // Default sorting ke 'asc'

        // Validasi apakah 'sort' input valid (asc atau desc)
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc'; // Default ke 'asc' jika input invalid
        }

        // Ambil data dari database dengan pencarian dan sorting, menggunakan relasi 'jabatan'
        $data = Karyawan::with('dpo_msjabatan') // Include relasi 'jabatan'
                    ->when($search, function($query, $search) {
                        return $query->where('kry_name', 'like', '%'.$search.'%');
                    })
                    ->orderBy('kry_name', $sort) // Urutkan berdasarkan 'kry_name' sesuai arah sorting
                    ->paginate(10); // Batasi jumlah data per halaman

        // Konversi data dari table ke bentuk Data Transfer Object (DTO)
        $dto = $data->map(function ($karyawan) {
            return new KaryawanDto(
                $karyawan->kry_id_alternative,
                $karyawan->kry_name,
                $karyawan->dpo_msjabatan->jbt_name, // Ambil nama jabatan dari relasi
                $karyawan->kry_username,
                $karyawan->kry_email,
                $karyawan->kry_status
            );
        });

        // Kirim data ke view
        return view('layouts.pages.master.karyawan.index', [
            'dto' => $dto,
            'pagination' => $data, // Tetap kirim objek pagination untuk navigasi
            'search' => $search, // Kirim input pencarian ke view
            'sort' => $sort, // Kirim status sorting ke view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatan = Jabatan::where('jbt_status',1)->get();

        $dto = $jabatan->map(function($data){
            return new JabatanDto(
                $data->jbt_id,
                $data->jbt_name
            );
        });

        return view('layouts.pages.master.karyawan.create',compact('dto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'kry_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/', // hanya huruf dan spasi
            'jbt_id' => 'required',
            'kry_username' => 'required|unique:karyawan,kry_username', // memastikan username unik
            'kry_email' => 'required|email|unique:karyawan,kry_email' // validasi email dan keunikan
        ]);

        // Mengambil data karyawan terakhir berdasarkan ID alternatif
        $latestKaryawan = Karyawan::orderBy('kry_id_alternative', 'desc')->first();

        // Membuat ID baru
        if ($latestKaryawan) {
            // Mengambil bagian angka dan menambahkannya
            $latestId = (int) substr($latestKaryawan->kry_id_alternative, 4);
            $newId = 'KRY-' . str_pad($latestId + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Mulai dengan ID pertama
            $newId = 'KRY-001';
        }

        // Membuat password acak dengan kompleksitas tambahan
        $randomPassword = Str::random(8) . rand(1000, 9999) . '!@#'; // Password acak yang kuat

        // Hash password
        $hashedPassword = Hash::make($randomPassword);

        // Membuat instance Karyawan baru
        $karyawan = new Karyawan();
        $karyawan->kry_id_alternative = $newId;
        $karyawan->kry_name = $request->input('kry_name');
        $karyawan->jbt_id = $request->input('jbt_id');
        $karyawan->kry_password = $hashedPassword;
        $karyawan->kry_username = $request->input('kry_username');
        $karyawan->kry_email = $request->input('kry_email');
        $karyawan->kry_status = 1;
        $karyawan->kry_created_by = 'mike';
        $karyawan->kry_modified_by = 'mike';

        // Memeriksa apakah sudah ada data dengan nama yang sama
        if (Karyawan::where('kry_name', $karyawan->kry_name)->exists()) {
            // Jika ada, kembalikan dengan pesan error
            return redirect()->route('karyawan.index')->with('error', 'Data sudah ada!');
        }

        // Menyimpan data karyawan baru
        $karyawan->save();

        // Kembali dengan pesan sukses
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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

    }
}
