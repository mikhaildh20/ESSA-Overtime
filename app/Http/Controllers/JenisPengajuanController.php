<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPengajuan;
use App\DataTransferObjects\JenisPengajuanDto;

class JenisPengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validasi input dari request
        $validated = $request->validate([
            'search' => 'nullable|string|max:255', // Input "search" opsional, harus berupa string, maksimal 255 karakter
            'sort' => 'nullable|in:asc,desc', // Input "sort" opsional, hanya boleh bernilai "asc" atau "desc"
        ]);

        // Sanitasi input "search" untuk mencegah serangan XSS
        $search = htmlspecialchars($validated['search'] ?? null, ENT_QUOTES, 'UTF-8'); // Jika "search" tidak ada, set null
        // Gunakan nilai default "asc" jika "sort" tidak diberikan
        $sort = $validated['sort'] ?? 'asc';

        // Ambil data dari tabel "JenisPengajuan" dengan filter dan sorting
        $data = JenisPengajuan::where('jpj_status',1)->when($search, function ($query, $search) {
                // Jika "search" ada, filter berdasarkan kolom "jpj_name" yang mengandung nilai "search"
                return $query->where('jpj_name', 'like', '%' . $search . '%');
            })
            // Urutkan hasil berdasarkan kolom "jpj_name" dengan arah sorting sesuai input "sort"
            ->orderBy(JenisPengajuan::sanitizeColumn('jpj_name'), $sort)
            // Batasi hasil query menjadi 10 data per halaman dengan paginasi
            ->paginate(10);

        // Konversi hasil query menjadi DTO (Data Transfer Object)
        $dto = $data->map(function ($jenis) {
            // Sanitasi setiap atribut sebelum dimasukkan ke DTO
            return new JenisPengajuanDto(
                htmlspecialchars($jenis->jpj_id, ENT_QUOTES, 'UTF-8'), // Sanitasi ID
                htmlspecialchars($jenis->jpj_name ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi Jenis Pengajuan
                htmlspecialchars($jenis->jpj_status ?? '', ENT_QUOTES, 'UTF-8') // Sanitasi status Jenis Pengajuan
            );
        });

        // Kembalikan data ke view untuk ditampilkan di halaman
        return view('layouts.pages.master.jenispengajuan.index', [
            'dto' => $dto, // Data yang telah dikonversi menjadi DTO
            'pagination' => $data, // Data hasil paginasi untuk navigasi halaman
            'search' => $search, // Nilai pencarian untuk ditampilkan kembali di form
            'sort' => $sort, // Status sorting untuk ditampilkan di UI
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.pages.master.jenispengajuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'jpj_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/|unique:dpo_msjenispengajuan,jpj_name', // hanya huruf dan spasi
        ],[
            'jpj_name.regex' => 'Jenis Pengajuan hanya bisa diisi huruf dan spasi.',
            'jpj_name.unique' => 'Data sudah ada.'
        ]);

        try{
            // Membuat instance JenisPengajuan
            $jenis = new JenisPengajuan();
            $jenis->jpj_name = $request->input('jpj_name');
            $jenis->jpj_status = 1;

            // Menyimpan data jenis pengajuan baru
            $jenis->save();
        }catch(\Exception $e){
            return redirect()->route('jenis_pengajuan.create')->with('error','Terjadi kesalahan, hubungi Tim IT!');
        }
        

        // Kembali dengan pesan sukses
        return redirect()->route('jenis_pengajuan.index')->with('success', 'Data berhasil ditambah!');
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
        $jenis = JenisPengajuan::findOrFail($id);
        return view('layouts.pages.master.jenispengajuan.edit',compact('jenis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'jpj_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/|unique:dpo_msjenispengajuan,jpj_name', // hanya huruf dan spasi
        ],[
            'jpj_name.regex' => 'Jenis Pengajuan hanya bisa diisi huruf dan spasi.',
            'jpj_name.unique' => 'Data sudah ada.'
        ]);

        // Mencari jenis pengajuan berdasarkan ID
        $jenis = JenisPengajuan::findOrFail($id);
        $jenis->jpj_name = $request['jpj_name'];

        // Menyimpan perubahan data jenis pengajuan
        $jenis->save();

        return redirect()->route('jenis_pengajuan.index')->with('success', 'Data berhasil diubah!');
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
        // Cari jenis pengajuan berdasarkan ID
        $jenis = JenisPengajuan::findOrFail($id);

        // Menentukan status dan pesan berdasarkan status jenis pengajuan saat ini
        $status = $jenis->jpj_status == 1 ? 0 : 1;
        $message = $status == 1 ? "Data diaktifkan!" : "Data dihapus!";

        // Menyimpan perubahan status
        $jenis->jpj_status = $status;
        $jenis->save();

        // Mengalihkan dan memberikan pesan status yang sesuai
        return redirect()->route('jenis_pengajuan.index')->with('success', $message);
    }
}
