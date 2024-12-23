@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <h1 class="mb-4">Tambah Data Karyawan</h1>

        <!-- Back Button -->
        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Karyawan Add Form -->
        <form action="{{ route('karyawan.update', $karyawan->kry_id_alternative) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Nama Karyawan -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Karyawan</label>
                <input type="text" class="form-control" name="kry_name" placeholder="Masukkan Nama Karyawan" value="{{ $karyawan->kry_name }}" required>
            </div>

            <!-- Jabatan Karyawan -->
            <div class="mb-3">
                <label for="jabatan" class="form-label">Jabatan</label>
                <select name="jbt_id" class="form-control" required>
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($dto as $d)
                        <option value="{{ $d->jbt_id }}" {{ $karyawan->jbt_id == $d->jbt_id ? 'selected' : '' }}>{{ $d->jbt_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="kry_username" placeholder="Masukkan Username" value="{{ $karyawan->kry_username }}" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="kry_email" placeholder="Masukkan Email" value="{{ $karyawan->kry_email }}" required>
            </div>

            <!-- Check if Email Changed -->
            @if($karyawan->kry_email !== old('kry_email'))
                <div class="mb-3">
                    <label for="send_password" class="form-label">Kirim Password ke Email Baru</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_password" id="send_password">
                        <label class="form-check-label" for="send_password">
                            Centang untuk mengirim password ke email baru
                        </label>
                    </div>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Data Karyawan
            </button>
        </form>
    </div>

@endsection
