@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <h1 class="mb-4">Tambah Jabatan</h1>

        <!-- Back Button -->
        <a href="{{ route('jabatan.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Jabatan Add Form -->
        <form action="{{ route('jabatan.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="jbt_name" class="form-label">Nama Jabatan</label>
                <input type="text" class="form-control" name="jbt_name" placeholder="Masukkan Nama Jabatan" value="{{ old('jbt_name') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Jabatan
            </button>
        </form>
    </div>

@endsection