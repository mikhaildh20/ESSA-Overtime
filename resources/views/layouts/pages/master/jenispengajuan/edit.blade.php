@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <h1 class="mb-4">Ubah Jenis Pengajuan</h1>

        <a href="{{ route('jenis.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <form action="{{ route('jenis.update', $jenisPengajuan->jen_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3 position-relative">
                <label for="jen_nama" class="form-label">Nama Jenis Pengajuan</label>
                <input type="text" class="form-control @error('jen_nama') is-invalid @enderror" name="jen_nama" placeholder="Masukkan Nama Jenis Pengajuan" value="{{ $jenisPengajuan->jen_nama }}" required>
                @error('jen_nama')
                    <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label for="jen_deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control @error('jen_deskripsi') is-invalid @enderror" name="jen_deskripsi" placeholder="Masukkan Deskripsi Jenis Pengajuan" required>{{ $jenisPengajuan->jen_deskripsi }}</textarea>
                @error('jen_deskripsi')
                    <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Ubah Jenis Pengajuan
            </button>
        </form>
    </div>

@endsection
