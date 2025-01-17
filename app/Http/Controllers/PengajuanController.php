<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\JenisPengajuan;
use App\Models\Karyawan;
use App\DataTransferObjects\JenisPengajuanDto;
use App\DataTransferObjects\PengajuanDto;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
                htmlspecialchars(Carbon::parse($pengajuan->created_at)->format('l, d F Y') ?? '', ENT_QUOTES, 'UTF-8')
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
        //
    }

    public function detail(string $id, string $session_alternative, string $session_name)
    {
        // Authorization check: Ensure the user has permission to access the pengajuan
        if ($session_alternative != session('kry_id')) {
            return redirect()->route('pengajuan.index')->with('error', 'Aksi dilarang!');
        }

        // Retrieve the Pengajuan data with the related dpo_msjenispengajuan table
        $data = Pengajuan::with([
            'dpo_msjenispengajuan:jpj_id,jpj_name' // Include specific columns from the related table
        ])->findOrFail($id);


        // Prepare the data to be passed to the view (no need for map, as $data is a single instance)
        $dto = new PengajuanDto(
            htmlspecialchars($data->pjn_id ?? '' , ENT_QUOTES,'UTF-8'),
            htmlspecialchars($data->dpo_msjenispengajuan->jpj_name ?? '' , ENT_QUOTES,'UTF-8'),
            htmlspecialchars($data->pjn_status ?? '' , ENT_QUOTES, 'UTF-8'),
            htmlspecialchars(Carbon::parse($data->created_at)->format('l, d F Y') ?? '', ENT_QUOTES,'UTF-8'),
            htmlspecialchars($data->pjn_id_alternative ?? '', ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($data->pjn_description ?? '' , ENT_QUOTES, 'UTF-8'),
            htmlspecialchars( $data->pjn_pdf_proof ?? '', ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($data->pjn_excel_proof ?? '', ENT_QUOTES, 'UTF-8')
        );

        // Pass the necessary data to the view
        return view("layouts.pages.transaksi.detail", compact('dto', 'session_alternative', 'session_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Pengajuan::where('pjn_id',$id)->firstOrFail();
        if($data->pjn_kry_id != session('topkey') || $data->pjn_id_alternative != 'Draft'){
            return redirect()->route('pengajuan.index')->with('error', 'Aksi dilarang!');
        }

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
        // Validasi input dari form
        $validated = $request->validate([
            'jenis-pengajuan' => 'required|exists:dpo_msjenispengajuan,jpj_id', // Pastikan jenis pengajuan ada di tabel yang terkait
            'keterangan' => 'required|string|min:100', // Keterangan harus minimal 100 karakter
            'bukti-pdf' => 'nullable|file|mimes:pdf|max:2048', // File PDF opsional, dengan ukuran maksimum 2MB
            'bukti-excel' => 'nullable|file|mimes:xlsx,xls|max:2048', // File Excel opsional
        ], [
            'keterangan.min' => 'Keterangan minimal 100 karakter!' // Pesan error kustom untuk panjang keterangan
        ]);

        // Ambil ID karyawan yang sedang login
        $id_karyawan = session('kry_id'); // Pastikan session ini valid atau sudah divalidasi sebelumnya

        // Cari data Pengajuan berdasarkan ID yang diberikan
        $pengajuan = Pengajuan::findOrFail($id); // Menggunakan 'findOrFail' agar gagal jika data tidak ditemukan
        if($pengajuan->pjn_kry_id != session('topkey') || $pengajuan->pjn_id_alternative != 'Draft'){
            return redirect()->route('pengajuan.index')->with('error', 'Aksi dilarang!');
        }

        // Variabel untuk menyimpan nama file PDF jika ada
        $pdfFilename = null;
        if ($request->hasFile('bukti-pdf')) {
            // Hapus file PDF lama jika ada
            
            if ($pengajuan->pjn_pdf_proof && Storage::disk('local')->exists('pdf/' . $pengajuan->pjn_pdf_proof)) {
                Storage::disk('local')->delete('pdf/' . $pengajuan->pjn_pdf_proof); // Menghapus file PDF lama dengan aman
            }
            // Tentukan nama file baru untuk PDF
            $pdfFilename = "Bukti-Penunjang-PDF-{$id_karyawan}-{$pengajuan->pjn_id}.pdf"; 
            // Simpan file PDF dengan nama baru
            $request->file('bukti-pdf')->storeAs('pdf', $pdfFilename, 'local');
        }

        // Variabel untuk menyimpan nama file Excel jika ada
        $excelFilename = null;
        if ($request->hasFile('bukti-excel')) {
            // Hapus file Excel lama jika ada
            if ($pengajuan->pjn_excel_proof && Storage::disk('local')->exists('excel/' . $pengajuan->pjn_excel_proof)) {
                Storage::disk('local')->delete('excel/' . $pengajuan->pjn_excel_proof); // Menghapus file Excel lama dengan aman
            }
            // Tentukan nama file baru untuk Excel
            $excelFilename = "Bukti-Penunjang-Excel-{$id_karyawan}-{$pengajuan->pjn_id}.xlsx"; 
            // Simpan file Excel dengan nama baru
            $request->file('bukti-excel')->storeAs('excel', $excelFilename, 'local');
        }

        // Ambil ID Karyawan untuk update data
        $kryPrId = Karyawan::where('kry_id_alternative', session('kry_id'))->first(); // Menyaring berdasarkan ID alternatif karyawan

        try {
            // Lakukan pembaruan data Pengajuan
            $pengajuan->update([
                'pjn_type' => $request->input('jenis-pengajuan'), // Jenis pengajuan
                'pjn_description' => $request->input('keterangan'), // Keterangan
                'pjn_excel_proof' => $excelFilename ?? $pengajuan->pjn_excel_proof, // Update file Excel jika ada, jika tidak biarkan lama
                'pjn_pdf_proof' => $pdfFilename ?? $pengajuan->pjn_pdf_proof, // Update file PDF jika ada, jika tidak biarkan lama
                'pjn_modified_by' => session('kry_name'), // Nama karyawan yang mengubah data
                'pjn_kry_id' => $kryPrId->kry_id, // ID karyawan yang terkait
            ]);

        } catch (\Exception $e) {
            // Jika ada error, arahkan kembali ke halaman edit dengan pesan error
            return redirect()->route('pengajuan.edit', ['pjn_id' => $request->input('pjn_id')])
                ->with('error', $e->getMessage()); // Kirimkan pesan error jika terjadi masalah
        }

        // Setelah berhasil, arahkan ke halaman pengajuan dengan pesan sukses
        return redirect()->route('pengajuan.index')->with('success', 'Data pengajuan berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Pengajuan::findOrFail($id);
        if($data->pjn_kry_id != session('topkey') || $data->pjn_id_alternative != 'Draft'){
            return redirect()->route('pengajuan.index')->with('error', 'Aksi Dilarang!');
        }

        $data->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Draft berhasil dihapus!');
    }

    public function download($filename){
        try{
            $startPos = strpos($filename, 'KRY-');
            $extracted = substr($filename, $startPos, strpos($filename, '-', $startPos + 4) - $startPos);
            
            if($extracted != session('kry_id')){
                return redirect()->route('pengajuan.index')->with('error', 'Aksi Dilarang!');
            }

            // Find the position of the dot
            $dotPos = strpos($filename, '.');

            // Extract the part after the dot
            $extension = substr($filename, $dotPos + 1);

            // Use ternary operator to set the directory
            $type = ($extension != 'pdf') ? 'excel' : 'pdf';

            $directory = $type."/".$filename;

            return Storage::disk('local')->download($directory);
        }catch(\Exception $e){
            return redirect()->route('pengajuan.index')->with('error', $e->getMessage());
        }
    }

    public function update_status(Request $request, $id, $decision = null)
    {
        
        // Ambil data pengajuan berdasarkan ID, jika tidak ditemukan akan memunculkan error 404
        $data = Pengajuan::findOrFail($id); 
        if($data->pjn_kry_id != session('topkey')){
            return redirect()->route('pengajuan.index')->with('error', 'Aksi dilarang!');
        }

        // Pesan-pesan untuk berbagai status
        $messages = [
            "Draft berhasil dikirim!", // Pesan untuk draft dikirim
            "Pengajuan diterima!",    // Pesan untuk pengajuan diterima
            "Pengajuan ditolak!"      // Pesan untuk pengajuan ditolak
        ];

        // Inisialisasi status awal (default 2)
        $init_status = 2; 
        // Inisialisasi variabel untuk ID alternatif (diisi jika status = 1)
        $formatted_id_alternative = null; 
        // Inisialisasi variabel untuk catatan review (diisi jika status != 1)
        $review = null; 
        // Inisialisasi indeks pesan default
        $arrMsg = 0; 
        // Inisialisasi array untuk field yang akan diupdate
        $update_field = [
            'pjn_status' => $init_status, // Status default
            'pjn_modified_by' => session('kry_name') // Nama pengubah diambil dari sesi
        ];

        // Cek apakah status pengajuan saat ini adalah 1 (Draft)
        if ($data->pjn_status == 1) {
            // Hitung jumlah pengajuan yang bukan draft, lalu tambahkan 1
            $lastId = Pengajuan::where('pjn_id_alternative', '!=', 'Draft')->count() + 1;
            // Format ID menjadi 3 digit (contoh: 001, 002)
            $formattedId = str_pad($lastId, 3, '0', STR_PAD_LEFT);
            // Konversi bulan saat ini ke format angka Romawi
            $bulan = $this->convertToRoman(now()->month);
            // Ambil tahun saat ini
            $tahun = now()->year;

            // Gabungkan semua komponen untuk membentuk ID alternatif
            $formatted_id_alternative = $formattedId . '/PA/PO/' . $bulan . '/' . $tahun;
            // Tambahkan ID alternatif ke array field yang akan diupdate
            $update_field['pjn_id_alternative'] = $formatted_id_alternative;
        } else {
            // Validasi input dari pengguna (review harus diisi dan maksimal 100 karakter)
            $validatedData = $request->validate([
                'review' => 'required|max:100'
            ]);

            // Sanitasi input review untuk menghindari serangan XSS
            $review = htmlspecialchars($validatedData['review'] ?? '', ENT_QUOTES, 'UTF-8'); 
            // Tambahkan review ke array field yang akan diupdate
            $update_field['pjn_review_notes'] = $review; 
            // Ubah status berdasarkan parameter keputusan
            $init_status = $decision; 
            // Tentukan indeks pesan berdasarkan nilai keputusan
            $arrMsg = ($decision === 3) ? 1 : 2; 
        }

        // Update data pengajuan dengan field yang sudah disiapkan
        $data->update($update_field);

        // Redirect ke halaman index pengajuan dengan pesan sukses
        return redirect()->route('pengajuan.index')->with('success', $messages[$arrMsg]);
    }
}
