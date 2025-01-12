@extends('layouts.app')

@section('content')

    <div class="container-fluid my-5">
        <h1 class="mb-4">Ubah Jenis Pengajuan</h1>

        <!-- Back Button -->
        <a href="{{ route('jenis_pengajuan.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Jabatan Add Form -->
        <form action="{{ route('jenis_pengajuan.update',$jenis->jpj_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3 position-relative">
                <label for="jpj_name" class="form-label">Jenis Pengajuan</label>
                <input type="text" class="form-control @error('jpj_name') is-invalid @enderror" name="jpj_name" placeholder="Masukkan Jenis Pengajuan" value="{{ $jenis->jpj_name }}" required>
                @error('jpj_name')
                    <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Ubah Jenis Pengajuan
            </button>
        </form>
    </div>

@endsection