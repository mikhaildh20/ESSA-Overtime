@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <h1 class="mb-4">Tambah Data Karyawan</h1>

        <!-- Back Button -->
        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <!-- Karyawan Add Form -->
        <form action="{{ route('karyawan.store') }}" method="POST">
            @csrf
            <!-- Nama Karyawan -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Karyawan</label>
                <input type="text" class="form-control" name="kry_name" placeholder="Masukkan Nama Karyawan" value="{{ old('kry_name') }}" required>
            </div>

            <!-- Jabatan Karyawan -->
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <select name="jbt_id" class="form-control" required>
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($dto as $d)
                        <option value="{{ $d->jbt_id }}" {{ old('jbt_id') == '$d->jbt_id' ? 'selected' : '' }}>{{ $d->jbt_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="kry_username" placeholder="Masukkan Username" value="{{ old('kry_username') }}" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="kry_email" placeholder="Masukkan Email" value="{{ old('kry_email') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Data Karyawan
            </button>
        </form>
    </div>

@endsection
