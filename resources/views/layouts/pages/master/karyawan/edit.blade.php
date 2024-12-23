@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <h1 class="mb-4">Tambah Data Karyawan</h1>

        <!-- Back Button -->
        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Karyawan Add Form -->
        <form id="karyawanForm" action="{{ route('karyawan.update',$karyawan->kry_id_alternative) }}" method="POST">
            @csrf
            @method('PUT')
            <!-- Nama Karyawan -->
            <div class="mb-3 position-relative">
                <label for="nama" class="form-label">Nama Karyawan</label>
                <input type="text" class="form-control @error('kry_name') is-invalid @enderror" name="kry_name" placeholder="Masukkan Nama Karyawan" value="{{ $karyawan->kry_name }}" required>
                @error('kry_name')
                    <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Jabatan Karyawan -->
            <div class="mb-3 position-relative">
                <label for="jabatan" class="form-label">Jabatan</label>
                <select name="jbt_id" class="form-control" required>
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($dto as $d)
                        <option value="{{ $d->jbt_id }}" {{ $karyawan->jbt_id == $d->jbt_id ? 'selected' : '' }}>{{ $d->jbt_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Username -->
            <div class="mb-3 position-relative">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('kry_username') is-invalid @enderror" name="kry_username" placeholder="Masukkan Username" value="{{ $karyawan->kry_username }}" required>
                @error('kry_username')
                    <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3 position-relative">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('kry_email') is-invalid @enderror" name="kry_email" placeholder="Masukkan Email" value="{{ $karyawan->kry_email }}" required>
                @error('kry_email')
                    <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                @enderror
            </div>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                <i class="fas fa-save"></i> Simpan Data Karyawan
            </button>
        </form>
    </div>

    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Aksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyimpan perubahan ini? Data yang Anda ubah akan tercatat dan dapat dipertanggungjawabkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Ya, Simpan Data</button>
            </div>
            </div>
        </div>
    </div>

@endsection
