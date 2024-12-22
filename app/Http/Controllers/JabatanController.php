<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Jabatan::all();
        return view('layouts.pages.master.jabatan.index',compact('data'));
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
        $request->validate([
            'jbt_name' => 'required',
        ]);

        $jabatan = new Jabatan();
        $jabatan->jbt_name = $request['jbt_name'];
        $jabatan->jbt_status = 1;

        $check = Jabatan::where('jbt_name',$jabatan->jbt_name)->first();

        if($check){
            return redirect()->route('jabatan.index')->with('error','Data sudah ada!');
        }

        $jabatan->save();

        return redirect()->route('jabatan.index')->with('success','Data berhasil ditambah!');
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
        $request->validate([
            'jbt_name' => 'required',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->jbt_name = $request['jbt_name'];

        $check = Jabatan::where('jbt_name',$jabatan->jbt_name)->first();

        if($check && $check->jbt_name != $jabatan->jbt_name){
            return redirect()->route('jabatan.index')->with('error','Data sudah ada!');
        }else if($check != null && $check->jbt_name == $jabatan->jbt_name){
            return redirect()->route('jabatan.index')->with('warning','Data tidak diubah.');
        }

        $jabatan->save();

        return redirect()->route('jabatan.index')->with('success','Data berhasil diubah!');
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
        $jabatan = Jabatan::findOrFail($id);

        $status = 1;
        $message  = "Data diaktifkan!";
        
        if($jabatan->jbt_status == 1){
            $status = 0;
            $message = "Data dihapus!";
        }

        $jabatan->jbt_status = $status;
        $jabatan->save();

        return redirect()->route('jabatan.index')->with('success',$message);
    }
}
