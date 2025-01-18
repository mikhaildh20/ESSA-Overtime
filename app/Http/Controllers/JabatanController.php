<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;
use App\DataTransferObjects\JabatanDto;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validasi input dari request
        $validated = $request->validate([
            'search' => 'nullable|string|max:255', // 'search' bisa kosong, harus string, maksimal 255 karakter
            'sort' => 'nullable|in:asc,desc', // 'sort' hanya boleh bernilai 'asc' atau 'desc'
            'sort-status' => 'nullable|in:0,1' // 'sort-status' hanya boleh bernilai 0 atau 1
        ]);

        // Sanitasi input untuk mencegah XSS
        $search = htmlspecialchars($validated['search'] ?? null, ENT_QUOTES, 'UTF-8'); // Jika tidak ada 'search', set null
        $sort = $validated['sort'] ?? 'asc'; // Jika tidak ada 'sort', gunakan nilai default 'asc'
        $sortStatus = $validated['sort-status'] ?? null; // Jika tidak ada 'sort-status', gunakan nilai default null

        // Ambil data 'jabatan' dengan kondisi pencarian dan sorting
        $data = Jabatan::when($search, function ($query, $search) { // Jika ada input 'search'
            return $query->where('jbt_name', 'like', '%' . $search . '%'); // Filter berdasarkan nama jabatan
        })
        ->when($sortStatus !== null, function ($query) use ($sortStatus) { // Tambahkan filter status jika 'sortStatus' diisi
            return $query->where('jbt_status', $sortStatus); // Filter berdasarkan status
        }, function ($query) { // Jika 'sortStatus' tidak diisi, tampilkan hanya data yang aktif
            return $query->where('jbt_status', '1'); // Default filter: hanya data aktif
        })
        ->orderBy(Jabatan::sanitizeColumn('jbt_name'), $sort) // Urutkan berdasarkan kolom yang disanitasi
        ->paginate(10); // Batasi hasil query dengan paginasi, 10 data per halaman


        // Konversi data hasil query menjadi DTO (Data Transfer Object)
        $dto = $data->map(function ($jabatan) {
            return new JabatanDto(
                htmlspecialchars($jabatan->jbt_id, ENT_QUOTES, 'UTF-8'), // Sanitasi ID jabatan
                htmlspecialchars($jabatan->jbt_name ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi nama jabatan
                htmlspecialchars($jabatan->jbt_status ?? '', ENT_QUOTES, 'UTF-8') // Sanitasi status jabatan
            );
        });

        // Kembalikan data ke view untuk ditampilkan
        return view('layouts.pages.master.jabatan.index', [
            'dto' => $dto, // Data yang sudah dikonversi ke DTO
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
        return view('layouts.pages.master.jabatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'jbt_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/|unique:dpo_msjabatan,jbt_name', // hanya huruf dan spasi
        ],[
            'jbt_name.regex' => 'Nama jabatan hanya bisa diisi huruf dan spasi.',
            'jbt_name.unique' => 'Data sudah ada.'
        ]);

        try{
            // Membuat instance Jabatan baru
            $jabatan = new Jabatan();
            $jabatan->jbt_name = $request->input('jbt_name');
            $jabatan->jbt_status = 1;

            // Menyimpan data jabatan baru
            $jabatan->save();
        }catch(\Exception $e){
            return redirect()->route('jabatan.create')->with('error','Terjadi kesalahan, hubungi Tim IT!');
        }
        

        // Kembali dengan pesan sukses
        return redirect()->route('jabatan.index')->with('success', 'Data berhasil ditambah!');
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
        $jabatan = Jabatan::findOrFail($id);
        return view('layouts.pages.master.jabatan.edit',compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'jbt_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/|unique:dpo_msjabatan,jbt_name', // hanya huruf dan spasi
        ],[
            'jbt_name.regex' => 'Nama jabatan hanya bisa diisi huruf dan spasi.',
            'jbt_name.unique' => 'Data sudah ada.'
        ]);

        // Mencari jabatan berdasarkan ID
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->jbt_name = $request['jbt_name'];

        // Menyimpan perubahan data jabatan
        $jabatan->save();

        return redirect()->route('jabatan.index')->with('success', 'Data berhasil diubah!');
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
         // Cari jabatan berdasarkan ID
        $jabatan = Jabatan::findOrFail($id);

        // Menentukan status dan pesan berdasarkan status jabatan saat ini
        $status = $jabatan->jbt_status == 1 ? 0 : 1;
        $message = $status == 1 ? "Data diaktifkan!" : "Data dihapus!";

        // Menyimpan perubahan status
        $jabatan->jbt_status = $status;
        $jabatan->save();

        // Mengalihkan dan memberikan pesan status yang sesuai
        return redirect()->route('jabatan.index')->with('success', $message);
    }
}
