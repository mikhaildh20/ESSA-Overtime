<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\JenisPengajuanDto;
use App\Models\JenisPengajuan;
use Illuminate\Http\Request;

class JenisPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255', 
            'sort' => 'nullable|in:asc,desc', 
        ]);

        $search = htmlspecialchars($validated['search'] ?? null, ENT_QUOTES, 'UTF-8'); // Jika tidak ada 'search', set null
        $sort = $validated['sort'] ?? 'asc'; // Jika tidak ada 'sort', gunakan nilai default 'asc'

        $data = JenisPengajuan::when($search, function ($query, $search) { // Jika ada input 'search'
                return $query->where('jen_nama', 'like', '%' . $search . '%'); 
            })
            ->orderBy('jen_nama', $sort) 
            ->paginate(10); 

        $dto = $data->map(function ($jenisPengajuan) {
            return new JenisPengajuanDto(
                htmlspecialchars($jenisPengajuan->jen_id, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($jenisPengajuan->jen_id_alternative ?? '', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($jenisPengajuan->jen_nama ?? '', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($jenisPengajuan->jen_deskripsi ?? '', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($jenisPengajuan->jen_status ?? '', ENT_QUOTES, 'UTF-8')
            );
        });

        return view('layouts.pages.master.jenispengajuan.index', [
            'dto' => $dto, 
            'pagination' => $data,
            'search' => $search,
            'sort' => $sort,
        ]);
    }

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
            'jen_nama'          => 'required|string|max:255|unique:dpo_msjenispengajuan,jen_nama',
            'jen_deskripsi'     => 'required|string|max:500',
        ],[
            'jen_nama.unique'   => 'Nama jenis pengajuan sudah ada.',
        ]);

        try {
            // Menentukan ID alternatif otomatis
            $lastRecord = JenisPengajuan::orderBy('jen_id', 'desc')->first();
            $lastId = $lastRecord ? (int) substr($lastRecord->jen_id_alternative, 3) : 0;
            $newId = 'JNS' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            $jenisPengajuan = new JenisPengajuan();
            $jenisPengajuan->jen_id_alternative = $newId;
            $jenisPengajuan->jen_nama = $request->input('jen_nama');
            $jenisPengajuan->jen_deskripsi = $request->input('jen_deskripsi');
            $jenisPengajuan->jen_status = 'Aktif'; 

            $jenisPengajuan->save();
        } catch (\Exception $e) {
            return redirect()->route('jenis.create')->with('error', 'Terjadi kesalahan, hubungi Tim IT!');
        }

        return redirect()->route('jenis.index')->with('success', 'Data jenis pengajuan berhasil ditambah!');
    }

    public function update_status(string $id)
    {
        $jenisPengajuan = JenisPengajuan::findOrFail($id);

        $status = $jenisPengajuan->jen_status == 'Aktif' ? 'Tidak Aktif' : 'Aktif';
        $message = $status == 'Tidak Aktif' ? "Data dinonaktifkan!" : "Data diaktifkan!";

        $jenisPengajuan->jen_status = $status;
        $jenisPengajuan->save();

        return redirect()->route('jenis.index')->with('success', $message);
    }

    public function edit(string $id)
    {
        $jenisPengajuan = JenisPengajuan::findOrFail($id);
        
        return view('layouts.pages.master.jenispengajuan.edit', compact('jenisPengajuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jen_nama' => 'required|string|max:255|unique:dpo_msjenispengajuan,jen_nama,' . $id . ',jen_id',
            'jen_deskripsi' => 'required|string|max:500', 
        ], [
            'jen_nama.unique' => 'Nama jenis pengajuan sudah ada.',
        ]);

        $jenisPengajuan = JenisPengajuan::findOrFail($id);
        $jenisPengajuan->jen_nama = $request['jen_nama'];
        $jenisPengajuan->jen_deskripsi = $request['jen_deskripsi'];

        $jenisPengajuan->save();

        return redirect()->route('jenis.index')->with('success', 'Data jenis pengajuan berhasil diubah!');
    }

}
