@extends('layouts.app')

@section('content')

    <div class="container-fluid my-5">
        <h1 class="mb-4">Tambah Data Karyawan</h1>

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Ubah Karyawan</h5>
            </div>
            <div class="card-body">
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
                        <select name="jbt_id" class="form-select" required>
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

                    <div class="d-flex justify-content-start mt-3 gap-2">
                        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
