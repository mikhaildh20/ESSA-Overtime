@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <h1 class="mb-4">Tambah Jenis Pengajuan</h1>

        <!-- Back Button -->
        <a href="{{ route('jenis.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Jenis Pengajuan Add Form -->
        <form action="{{ route('jenis.store') }}" method="POST">
            @csrf
            <div class="mb-3 position-relative">
                <label for="jen_nama" class="form-label">Nama Jenis Pengajuan</label>
                <input type="text" class="form-control @error('jen_nama') is-invalid @enderror" name="jen_nama" placeholder="Masukkan Nama Jenis Pengajuan" value="{{ old('jen_nama') }}" required>
                @error('jen_nama')
                    <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="jen_deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('jen_deskripsi') is-invalid @enderror" name="jen_deskripsi" placeholder="Masukkan Deskripsi Jenis Pengajuan" required>{{ old('jen_deskripsi') }}</textarea>
                @error('jen_deskripsi')
                    <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Jenis Pengajuan
            </button>
        </form>
    </div>

@endsection
