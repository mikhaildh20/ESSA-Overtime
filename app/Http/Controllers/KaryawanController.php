<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\DataTransferObjects\KaryawanDto;
use App\Models\Jabatan;
use App\DataTransferObjects\JabatanDto;

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
                $karyawan->jabatan->jbt_name, // Ambil nama jabatan dari relasi
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
