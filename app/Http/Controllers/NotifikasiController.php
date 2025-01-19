<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\DataTransferObjects\NotifikasiDto;
use Carbon\Carbon;

class NotifikasiController extends Controller
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
        $data = Notifikasi::when($search, function ($query, $search) { // Jika ada input 'search'
            return $query->where('ntf_name', 'like', '%' . $search . '%'); // Filter berdasarkan nama jabatan
        })
        ->when($sortStatus !== null, function ($query) use ($sortStatus) { // Tambahkan filter status jika 'sortStatus' diisi
            return $query->where('ntf_status', $sortStatus); // Filter berdasarkan status
        }, function ($query) { // Jika 'sortStatus' tidak diisi, tampilkan hanya data yang aktif
            return $query->where('ntf_status', '1'); // Default filter: hanya data aktif
        })
        ->with('dpo_trpengajuanovertime')  // Eager load the relationship
        ->whereHas('dpo_trpengajuanovertime', function ($query) {
            $query->where('pjn_kry_id', session('topkey'));  // Apply condition to the related model
        })
        ->orderBy(Notifikasi::sanitizeColumn('ntf_message'), $sort) // Urutkan berdasarkan kolom yang disanitasi
        ->paginate(10); // Batasi hasil query dengan paginasi, 10 data per halaman

        $dto = $data->map(function ($notifikasi) {
            return new NotifikasiDto(
                htmlspecialchars($notifikasi->ntf_id, ENT_QUOTES, 'UTF-8'), // Sanitasi ID notifikasi
                htmlspecialchars($notifikasi->ntf_message ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi pesan notifikasi
                htmlspecialchars($notifikasi->dpo_trpengajuanovertime->pjn_id_alternative ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi ID pengajuan
                htmlspecialchars($notifikasi->dpo_trpengajuanovertime->pjn_status ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi status pengajuan
                htmlspecialchars($notifikasi->ntf_status ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi status notifikasi
                htmlspecialchars($notifikasi->ntf_created_by ?? '', ENT_QUOTES, 'UTF-8'), // Sanitasi dari
                htmlspecialchars(Carbon::parse($notifikasi->ntf_created_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y h:i:s') ?? '', ENT_QUOTES, 'UTF-8') // Sanitasi tanggal
            );
        });

        return view('layouts.pages.notifikasi',[
            'dto' => $dto, // Data yang sudah dikonversi ke DTO
            'pagination' => $data, // Data pagination untuk kontrol navigasi halaman
            'search' => $search, // Input pencarian
            'sort' => $sort, // Input sorting
            'sortStatus' => $sortStatus // Input sorting status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $data = Notifikasi::findOrFail($id); // This fetches the specific notification
        $data->ntf_status = '0'; // Set the new status
        $data->ntf_modified_by = session('kry_name'); // Assign the session value
        $data->save(); // Save the updated record

        $unread = Notifikasi::with('dpo_trpengajuanovertime')  // Eager load the relationship
                ->whereHas('dpo_trpengajuanovertime', function ($query) {
                $query->where('pjn_kry_id', session('topkey'));  // Apply condition to the related model
            })
            ->where('ntf_status','1')
            ->count();

        session([
            'unread' => $unread
        ]);

        return redirect()->route('notifikasi.index'); // Redirect to the desired route
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
