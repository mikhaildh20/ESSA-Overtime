<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\JenisPengajuan;
use App\Models\Karyawan;
use App\DataTransferObjects\JenisPengajuanDto;
use App\DataTransferObjects\PengajuanDto;

class PengajuanController extends Controller
{
    // Fungsi untuk mengonversi bulan ke angka Romawi
    public function convertToRoman($month) {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];

        return $romans[$month] ?? '';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validasi input dari request
        $validated = $request->validate([
            'search' => 'nullable|string|max:255', // 'search' bisa kosong, harus string, maksimal 255 karakter
            'sort' => 'nullable|in:asc,desc', // 'sort' hanya boleh bernilai 'asc' atau 'desc'
        ]);

        // Ambil nilai 'search' dan 'sort' dengan sanitasi
        $search = htmlspecialchars($validated['search'] ?? null, ENT_QUOTES, 'UTF-8'); // Sanitasi input 'search' untuk mencegah XSS
        $sort = $validated['sort'] ?? 'asc'; // Default nilai 'sort' adalah 'asc'

        // Ambil data pengajuan dengan relasi 'dpo_jenispengajuan', dan tambahkan kondisi pencarian serta sorting
        $data = Pengajuan::with('dpo_msjenispengajuan') // Eager loading untuk relasi 'dpo_jenispengajuan'
            ->when($search, function ($query, $search) { // Tambahkan kondisi pencarian jika input 'search' ada
                return $query->whereHas('dpo_jenispengajuan', function ($q) use ($search) {
                    $q->where('jpj_name', 'like', '%' . $search . '%'); // Filter berdasarkan nama jenis pengajuan
                });
            })
            ->where('pjn_status','!=','0')
            ->where('pjn_kry_id', session('topkey'))
            ->orderBy(Pengajuan::sanitizeColumn('pjn_status'), $sort) // Urutkan berdasarkan kolom yang disanitasi
            ->paginate(10); // Batasi hasil query dengan paginasi, 10 data per halaman


        // Konversi data hasil query menjadi DTO (Data Transfer Object)
        $dto = $data->map(function ($pengajuan) {
            return new PengajuanDto(
                htmlspecialchars($pengajuan->pjn_id ?? '', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($pengajuan->dpo_msjenispengajuan->jpj_name ?? '', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($pengajuan->pjn_status ?? '', ENT_QUOTES, 'UTF-8'),
                htmlspecialchars(\Carbon\Carbon::parse($pengajuan->created_at)->format('l, d F Y') ?? '', ENT_QUOTES, 'UTF-8')
            );
        });        

        $alternative = session('kry_id');
        $session_name = session('kry_name');

        // Kembalikan data ke view untuk ditampilkan
        return view('layouts.pages.transaksi.pengajuan', [
            'dto' => $dto, // Data yang sudah dikonversi ke DTO untuk kebutuhan tampilan
            'pagination' => $data, // Data pagination untuk kontrol navigasi halaman
            'search' => $search, // Nilai input pencarian untuk dipertahankan di tampilan
            'sort' => $sort, // Status sorting (asc/desc) untuk dipertahankan di tampilan
            'name' => $session_name,
            'alternative' => $alternative
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pengajuan = JenisPengajuan::where('jpj_status',1)->get();

        $dto = $pengajuan->map(function($data){
            return new JenisPengajuanDto(
                $data->jpj_id,
                $data->jpj_name
            );
        });

        return view('layouts.pages.transaksi.create', compact('dto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input fields
        $validated = $request->validate([
            'jenis-pengajuan' => 'required|exists:dpo_msjenispengajuan,jpj_id', // Pastikan nilai ada di tabel terkait
            'keterangan' => 'required|string|min:100', // Minimal 500 karakter
            'bukti-pdf' => 'required|file|mimes:pdf|max:2048', // PDF wajib dengan ukuran maksimal 2MB
            'bukti-excel' => 'nullable|file|mimes:xlsx,xls|max:2048', // Excel opsional
        ], [
            'keterangan.min' => 'Keterangan minimal 100 karakter!' // Pesan error kustom
        ]);

        // Get the authenticated user's ID
        $id_karyawan = session('kry_id'); // Ensure this session is valid or validated earlier

        // Fetch the most recent 'pjn_id' and calculate the new ID
        $newIdPengajuan = Pengajuan::max('pjn_id') + 1; // More efficient than query orderBy

        // Generate the unique filename for the PDF
        $pdfFilename = "Bukti-Penunjang-PDF-{$id_karyawan}-{$newIdPengajuan}.pdf";

        // Store the PDF file in the 'documents/pdf' folder (private storage)
        $pdfPath = $request->file('bukti-pdf')->storeAs('pdf', $pdfFilename, 'local');

        // Prepare to store Excel file if provided
        $excelFilename = null;
        if ($request->hasFile('bukti-excel')) {
            $excelFilename = "Bukti-Penunjang-Excel-{$id_karyawan}-{$newIdPengajuan}.xlsx";
            $request->file('bukti-excel')->storeAs('excel', $excelFilename, 'local');
        }


        $kryPrId = Karyawan::where('kry_id_alternative', session('kry_id'))->first();

        try {
            // Create a new Pengajuan instance and save it
            Pengajuan::create([
                'pjn_id_alternative' => 'Draft', // Nilai default "Draft"
                'pjn_type' => $request->input('jenis-pengajuan'), // Input jenis pengajuan
                'pjn_description' => $request->input('keterangan'), // Input keterangan
                'pjn_excel_proof' => $excelFilename, // Bukti Excel jika ada
                'pjn_pdf_proof' => $pdfFilename, // Bukti PDF
                'pjn_review_notes' => null, // Default null
                'pjn_status' => 1, // Status default
                'pjn_created_by' => session('kry_name'), // Nama pembuat
                'pjn_modified_by' => null, // Default null
                'pjn_kry_id' => $kryPrId->kry_id, // ID karyawan
            ]);

        } catch (\Exception $e) {
            // Redirect with an error message if any exception occurs
            return redirect()->route('pengajuan.create')->with('error', $e->getMessage());
        }

        // Redirect or return success response
        return redirect()->route('pengajuan.index')->with('success', 'Data pengajuan disimpan ke draft');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view("layouts.pages.transaksi.detail");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Pengajuan::where('pjn_id',$id)->firstOrFail();

        $pengajuan = JenisPengajuan::where('jpj_status',1)->get();

        $dto = $pengajuan->map(function($data){
            return new JenisPengajuanDto(
                $data->jpj_id,
                $data->jpj_name
            );
        });

        return view('layouts.pages.transaksi.edit',compact('data','dto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the input fields
        $validated = $request->validate([
            'jenis-pengajuan' => 'required|exists:dpo_msjenispengajuan,jpj_id', // Make sure the value exists in the related table
            'keterangan' => 'required|string|min:100', // Minimum 100 characters
            'bukti-pdf' => 'nullable|file|mimes:pdf|max:2048', // PDF optional, max size 2MB
            'bukti-excel' => 'nullable|file|mimes:xlsx,xls|max:2048', // Excel optional
        ], [
            'keterangan.min' => 'Keterangan minimal 100 karakter!' // Custom error message for min length
        ]);

        // Get the authenticated user's ID
        $id_karyawan = session('kry_id'); // Ensure this session is valid or validated earlier

        // Fetch the Pengajuan record to update
        $pengajuan = Pengajuan::findOrFail($id); // Assuming 'pjn_id' is being sent to identify the record

        // Generate the unique filename for the PDF if file is provided
        $pdfFilename = null;
        if ($request->hasFile('bukti-pdf')) {
            $pdfFilename = "Bukti-Penunjang-PDF-{$id_karyawan}-{$pengajuan->pjn_id}.pdf"; // Use the existing pjn_id
            $request->file('bukti-pdf')->storeAs('pdf', $pdfFilename, 'local');
        }

        // Prepare to store Excel file if provided
        $excelFilename = null;
        if ($request->hasFile('bukti-excel')) {
            $excelFilename = "Bukti-Penunjang-Excel-{$id_karyawan}-{$pengajuan->pjn_id}.xlsx"; // Use the existing pjn_id
            $request->file('bukti-excel')->storeAs('excel', $excelFilename, 'local');
        }

        // Fetch the Karyawan ID for update
        $kryPrId = Karyawan::where('kry_id_alternative', session('kry_id'))->first();

        try {
            // Update the Pengajuan instance with new data
            $pengajuan->update([
                'pjn_type' => $request->input('jenis-pengajuan'), // Input jenis pengajuan
                'pjn_description' => $request->input('keterangan'), // Input keterangan
                'pjn_excel_proof' => $excelFilename ?? $pengajuan->pjn_excel_proof, // Update Excel file if provided, else retain old
                'pjn_pdf_proof' => $pdfFilename ?? $pengajuan->pjn_pdf_proof, // Update PDF file if provided, else retain old
                'pjn_modified_by' => session('kry_name'), // Modified by the authenticated user
                'pjn_kry_id' => $kryPrId->kry_id, // Karyawan ID
            ]);

        } catch (\Exception $e) {
            // Redirect with an error message if any exception occurs
            return redirect()->route('pengajuan.edit', ['pjn_id' => $request->input('pjn_id')])
                ->with('error', $e->getMessage());
        }

        // Redirect or return success response
        return redirect()->route('pengajuan.index')->with('success', 'Data pengajuan berhasil diubah.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function update_status($id){

    }
}
