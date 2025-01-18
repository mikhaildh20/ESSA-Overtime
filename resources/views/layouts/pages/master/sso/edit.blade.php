@extends('layouts.app')

@section('content')

    <div class="container-fluid my-5">
        <h1 class="mb-4">Ubah Data Single Sign-On</h1>

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Ubah Single Sign-On</h5>
            </div>
            <div class="card-body">
                <!-- Karyawan Add Form -->
                <form action="{{ route('sso.update',$sso->sso_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Nama Karyawan -->
                    <div class="mb-3 position-relative">
                        <label for="nama" class="form-label">Nama Karyawan</label>
                        <select name="kry_id" class="form-control" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($dto as $d)
                                <option value="{{ $d->kry_id_alternative }}" {{ $sso->kry_id == $d->kry_id_alternative ? 'selected' : '' }}>{{ $d->kry_name }}</option>
                            @endforeach
                        </select>
                        @error('kry_id')
                            <div class="text-danger position-absolute" style="top: 0; right: 0;">*{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Level Single Sign-On -->
                    <div class="mb-3 position-relative">
                        <label for="Level" class="form-label">Level</label>
                        <select name="level" class="form-control" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="1" {{ $sso->sso_level == 1 ? 'selected' : '' }}>Karyawan</option>
                            <option value="2" {{ $sso->sso_level == 2 ? 'selected' : '' }}>Human Resources</option>
                            <option value="3" {{ $sso->sso_level == 3 ? 'selected' : '' }}>Administrator</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-start mt-3 gap-2">
                        <a href="{{ route('sso.index') }}" class="btn btn-secondary mr-2">
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
