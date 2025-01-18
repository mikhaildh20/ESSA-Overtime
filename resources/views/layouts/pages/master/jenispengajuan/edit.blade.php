@extends('layouts.app')

@section('content')

    <div class="container-fluid my-5">
        <h1 class="mb-4">Ubah Jenis Pengajuan</h1>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Ubah Jenis Pengajuan</h5>
            </div>
            <div class="card-body">
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

                    <div class="d-flex justify-content-start mt-3">
                        <a href="{{ route('jenis_pengajuan.index') }}" class="btn btn-secondary mr-2">
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