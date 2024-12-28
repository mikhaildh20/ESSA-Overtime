<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTransferObjects\SsoDto;
use App\DataTransferObjects\KaryawanDto;
use App\Models\Sso;
use App\Models\Karyawan;
use Illuminate\Validation\Rule;

class SsoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validasi input dari request dengan aturan yang ditentukan
        $validated = $request->validate([
            'search' => 'nullable|string|max:255', // 'search' boleh kosong, harus berupa string, maksimal 255 karakter
            'sort' => 'nullable|in:asc,desc', // 'sort' hanya boleh bernilai 'asc' atau 'desc'
        ]);

        // Sanitasi nilai input 'search' untuk mencegah XSS
        $search = htmlspecialchars($validated['search'] ?? null, ENT_QUOTES, 'UTF-8'); // Jika tidak ada 'search', set null
        $sort = $validated['sort'] ?? 'asc'; // Jika tidak ada 'sort', gunakan nilai default 'asc'

        // Ambil data SSO dengan relasi ke tabel karyawan dan jabatan menggunakan Eloquent
        $data = Sso::with(['dpo_mskaryawan.dpo_msjabatan']) // Ambil relasi 'dpo_mskaryawan' dan 'dpo_msjabatan'
            ->where('sso_status',1) // Filter berdasarkan sso_status
            ->when($search, function ($query, $search) { // Jika ada input 'search'
                return $query->whereHas('dpo_mskaryawan', function ($query) use ($search) { // Filter berdasarkan nama karyawan
                    $query->where('kry_name', 'like', '%' . $search . '%'); // Nama karyawan mengandung input 'search'
                });
            })
            ->orderBy(Sso::sanitizeColumn('dpo_mskaryawan.kry_name'), $sort) // Urutkan berdasarkan nama karyawan dengan sort direction
            ->paginate(10); // Batasi hasil query menjadi 10 data per halaman

        // Konversi data hasil query ke dalam bentuk DTO (Data Transfer Object)
        $dto = $data->map(function ($sso) { // Proses setiap data SSO
            return new SsoDto(
                htmlspecialchars($sso->sso_id, ENT_QUOTES, 'UTF-8'), // Sanitasi ID SSO
                htmlspecialchars($sso->dpo_mskaryawan->kry_name ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi nama karyawan
                htmlspecialchars($sso->dpo_mskaryawan->dpo_msjabatan->jbt_name ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi nama jabatan
                htmlspecialchars($sso->sso_level ?? '', ENT_QUOTES, 'UTF-8') // Sanitasi level SSO
            );
        });

        // Kembalikan data ke view untuk ditampilkan
        return view('layouts.pages.master.sso.index', [ // Render view dengan data berikut
            'dto' => $dto, // Data karyawan yang sudah dikonversi ke DTO
            'pagination' => $data, // Data pagination untuk navigasi halaman
            'search' => $search, // Nilai input 'search' untuk dipertahankan di tampilan
            'sort' => $sort, // Nilai input 'sort' untuk dipertahankan di tampilan
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil semua data karyawan yang memiliki status aktif (status = 1) dari tabel karyawan.
        $karyawan = Karyawan::where('kry_status', 1)->get();

        // Melakukan mapping terhadap koleksi karyawan, dan mengubah setiap elemen menjadi objek KaryawanDto
        // yang berisi kry_id dan kry_name dari setiap karyawan.
        $dto = $karyawan->map(function($data) {
            return new KaryawanDto(
                $data->kry_id,   // Mengambil ID karyawan
                $data->kry_name  // Mengambil nama karyawan
            );
        });

        // Mengembalikan tampilan 'layouts.pages.master.karyawan.create' dan mengirimkan data dto 
        // (daftar jabatan yang telah diproses) ke view.
        return view('layouts.pages.master.sso.create', compact('dto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'kry_id' => [
                'required',
                Rule::unique('dpo_sso')->where(function ($query) use ($request) {
                    return $query->where('sso_level', $request->level); // Filter berdasarkan level
                }),
            ],
            'level' => 'required',
        ], [
            'kry_id.unique' => 'Data sudah ada!' // Custom error message
        ]);


        try{
            // Membuat instance baru dari model Karyawan dan mengisi atributnya
            $sso = new Sso();
            $sso->kry_id = $request->input('kry_id');
            $sso->sso_level = $request->input('level');
            $sso->sso_status = 1; // Status karyawan aktif (1)
            $sso->sso_created_by = 'mike'; // Pengguna yang membuat data (misalnya 'mike')
            $sso->sso_modified_by = 'mike'; // Pengguna yang terakhir mengubah data (misalnya 'mike')

            // Menyimpan data karyawan baru ke dalam database
            $sso->save();
        }catch(\Exception $e){
            return redirect()->route('sso.create')->with('error','Terjadi kesalahan, hubungi Tim IT!');
        }

        // Mengalihkan pengguna ke halaman daftar karyawan dengan pesan sukses
        return redirect()->route('sso.index')->with('success', 'Data berhasil ditambah!');
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
        $sso = Sso::findOrFail($id);
        $karyawan = Karyawan::where('kry_status',1)->get();

        $dto = $karyawan->map(function($data) {
            return new KaryawanDto(
                $data->kry_id,   // Mengambil ID karyawan
                $data->kry_name  // Mengambil nama karyawan
            );
        });

        return view('layouts.pages.master.sso.edit', compact('sso', 'dto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input dengan aturan yang lebih ketat
        $request->validate([
            'kry_id' => [
                'required',
                Rule::unique('dpo_sso')->where(function ($query) use ($request, $id) {
                    // Menambahkan pengecekan kecuali untuk record yang sedang diupdate
                    return $query->where('sso_level', $request->level)
                                ->where('sso_id', '!=', $id); // Pastikan id SSO yang sedang diupdate tidak terpengaruh
                }),
            ],
            'level' => 'required',
        ], [
            'kry_id.unique' => 'Data sudah ada!' // Custom error message
        ]);

        try {
            // Ambil record yang akan diupdate berdasarkan ID
            $sso = Sso::findOrFail($id);

            // Update field yang diperlukan
            $sso->kry_id = $request->input('kry_id');
            $sso->sso_level = $request->input('level');
            $sso->sso_status = 1; // Status karyawan aktif (1)
            $sso->sso_modified_by = 'mike'; // Pengguna yang terakhir mengubah data (misalnya 'mike')

            // Simpan perubahan ke database
            $sso->save();
        } catch (\Exception $e) {
            return redirect()->route('sso.edit', $id)->with('error', 'Terjadi kesalahan, hubungi Tim IT!');
        }

        // Mengalihkan pengguna ke halaman daftar karyawan dengan pesan sukses
        return redirect()->route('sso.index')->with('success', 'Data berhasil diubah!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
    }

    public function update_status(string $id)
    {
         // Cari karyawan berdasarkan Alternative ID
        $sso = Sso::findOrFail($id);

        // Menentukan status dan pesan berdasarkan status karyawan saat ini
        $status = $sso->sso_status == 1 ? 0 : 1;
        $message = $status == 1 ? "Data diaktifkan!" : "Data dihapus!";

        // Menyimpan perubahan status
        $sso->sso_status = $status;
        $sso->save();

        // Mengalihkan dan memberikan pesan status yang sesuai
        return redirect()->route('sso.index')->with('success', $message);
    }

}
