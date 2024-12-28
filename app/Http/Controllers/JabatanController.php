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
        // Validate inputs
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'sort' => 'nullable|in:asc,desc',
        ]);

        $search = $validated['search'] ?? null;
        $sort = $validated['sort'] ?? 'asc';

        // Fetch data with search and sorting
        $data = Jabatan::when($search, function($query, $search) {
                        return $query->where('jbt_name', 'like', '%'.$search.'%');
                    })
                    ->orderBy('jbt_name', $sort)
                    ->paginate(10);

        // Convert to DTOs
        $dto = $data->map(function ($jabatan) {
            return new JabatanDto($jabatan->jbt_id, $jabatan->jbt_name, $jabatan->jbt_status);
        });

        // Return view with data
        return view('layouts.pages.master.jabatan.index', [
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

        // Membuat instance Jabatan baru
        $jabatan = new Jabatan();
        $jabatan->jbt_name = $request->input('jbt_name');
        $jabatan->jbt_status = 1;

        // Menyimpan data jabatan baru
        $jabatan->save();

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
