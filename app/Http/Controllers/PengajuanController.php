<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\PengajuanDto;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\JenisPengajuan;
use Illuminate\Support\Facades\Log; // Menambahkan import untuk Log
use App\Models\Karyawan;
use Illuminate\Support\Facades\Storage;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255', 
            'sort' => 'nullable|in:asc,desc', 
        ]);

        $search = htmlspecialchars($validated['search'] ?? null, ENT_QUOTES, 'UTF-8'); 
        $sort = $validated['sort'] ?? 'asc';

        $data = Pengajuan::with('dpo_mskaryawan') 
            ->when($search, function ($query, $search) { 
                return $query->whereHas('dpo_mskaryawan', function ($q) use ($search) {
                    $q->where('kry_name', 'like', '%' . $search . '%'); 
                });
            })
            ->orderBy(Pengajuan::sanitizeColumn('pjn_created_date'), $sort) 
            ->paginate(10); 

        $dto = $data->map(function ($pengajuan) {
            return new PengajuanDto(
                htmlspecialchars($pengajuan->id, ENT_QUOTES, 'UTF-8'), 
                htmlspecialchars($pengajuan->dpo_mskaryawan->nidn ?? '', ENT_QUOTES, 'UTF-8'), 
                htmlspecialchars($pengajuan->dpo_mskaryawan->nama ?? '', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($pengajuan->jen_id ?? '', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($pengajuan->pjn_created_date ?? '', ENT_QUOTES, 'UTF-8'), 
                htmlspecialchars($pengajuan->pjn_status ?? '', ENT_QUOTES, 'UTF-8') 
            );
        });

        return view('layouts.pages.transaksi.pengajuan', [
            'dto' => $dto, 
            'pagination' => $data,
            'search' => $search, 
            'sort' => $sort, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karyawan = Karyawan::all();
        $jenisPengajuan = JenisPengajuan::where('jen_status', 'Aktif')->get();
        
        if ($jenisPengajuan->isEmpty()) {
            return redirect()->route('pengajuan.index')->with('error', 'Tidak ada jenis pengajuan aktif.');
        }

        return view('layouts.pages.transaksi.create', compact('jenisPengajuan', 'karyawan')); 
    }

    // Fungsi untuk mengonversi bulan ke angka Romawi
    public function convertToRoman($month) {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        return $romans[$month] ?? '';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());

        $request->validate([
            'jen_id' => 'required|integer',
            'pjn_deskripsi' => 'required|string|max:255',
            'pjn_pdf_proof' => 'required|file|mimes:pdf|max:5120',
            'pjn_excel_proof' => 'nullable|file|mimes:xlsx,xls|max:5120',
            'kry_id' => 'required|integer',
        ], [
            'pjn_pdf_proof.required' => 'File bukti penunjang PDF wajib diunggah.',
            'pjn_pdf_proof.mimes' => 'File bukti penunjang PDF harus berupa file PDF.',
            'pjn_excel_proof.mimes' => 'File bukti penunjang Excel harus berupa file Excel (.xlsx, .xls).',
            'pjn_excel_proof.max' => 'Ukuran file Excel tidak boleh lebih dari 5 MB.',
            'pjn_pdf_proof.max' => 'Ukuran file PDF tidak boleh lebih dari 5 MB.',
        ]);

        try {
            $pdfFileName = $request->file('pjn_pdf_proof')->store('uploads/pdf', 'public');

            $excelFileName = null;
            if ($request->file('pjn_excel_proof')) {
                $excelFileName = $request->file('pjn_excel_proof')->store('uploads/excel', 'public');
            }

            // Buat ID alternatif
            $lastPengajuan = Pengajuan::latest('pjn_id_alternative')->first();
            $urut = $lastPengajuan ? str_pad((int) substr($lastPengajuan->pjn_id_alternative, 0, 3) + 1, 3, '0', STR_PAD_LEFT) : '001';
            $bulan = $this->convertToRoman(now()->month);
            $tahun = now()->year;
            $pjnIdAlternative = $urut . '/PA/PO/' . $bulan . '/' . $tahun;

            Pengajuan::create([
                'pjn_id_alternative' => $pjnIdAlternative,
                'jen_id' => $request->input('jen_id'),
                'pjn_deskripsi' => $request->input('pjn_deskripsi'),
                'pjn_excel_proof' => $excelFileName,
                'pjn_pdf_proof' => $pdfFileName,
                'pjn_status' => 'Draft',
                'pjn_created_date' => now(),
                'pjn_modified_date' => now(),
                'kry_id' => $request->input('kry_id')
            ]);

            return redirect()->route('pengajuan.index')->with('success', 'Pengajuan lembur berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error saving data: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
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

    

}
