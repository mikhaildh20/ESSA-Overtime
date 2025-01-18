@extends('layouts.app')

@section('content')

<div class="container-fluid my-5">
    <h1 class="mb-4">Tambah Jabatan</h1>

    <!-- Card Wrapper for Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Form Tambah Jabatan</h5>
        </div>
        <div class="card-body">
            <!-- Jabatan Add Form -->
            <form action="{{ route('jabatan.store') }}" method="POST">
                @csrf
                <div class="mb-3 position-relative">
                    <label for="jbt_name" class="form-label">Nama Jabatan</label>
                    <input type="text" class="form-control @error('jbt_name') is-invalid @enderror" name="jbt_name" placeholder="Masukkan Nama Jabatan" value="{{ old('jbt_name') }}" required>
                    @error('jbt_name')
                        <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-start mt-3 gap-2">
                    <a href="{{ route('jabatan.index') }}" class="btn btn-secondary mr-2">
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