@extends('layouts.app')

@section('content')

<style>
    .required {
        color: red;
    }
</style>

<div class="container-fluid my-5">
    <h1 class="mb-4">Profil Saya</h1>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Form Profil</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Lengkap: </strong>{{ $dto->full_name }}</p>
                    <p><strong>Jabatan: </strong>{{ $dto->role }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Email: </strong>{{ $dto->email }}</p>
                    <p><strong>Hak Akses: </strong>{{ $dto->level }}</p>
                </div>
            </div>

            <form action="{{ route('profile.update',$id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mt-4">
                    <h5>Ganti Password</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 position-relative">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ $dto->username }}" disabled>
                        </div>
                        <div class="col-md-6 position-relative">
                            <label for="password" class="form-label">Password saat ini <span class="required">*</span></label>
                            <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Masukkan password saat ini" required>
                            @error('current_password')
                            <div class="text-danger position-absolute" style="top: 0px; right: 20px">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="row">
                        <div class="col-md-6 position-relative">
                            <label for="new_password" class="form-label">Password baru <span class="required">*</span></label>
                            <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" placeholder="Masukkan password baru" value="{{ session()->has('success') ? '' : old('new_password') }}" required>
                            @error('new_password')
                            <div class="text-danger position-absolute" style="top: 0px; right: 20px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 position-relative">
                            <label for="confirm_password" class="form-label">Ketik ulang password baru <span class="required">*</span></label>
                            <input name="confirm_password" type="password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Masukkan ulang password baru" value="{{ session()->has('success') ? '' : old('confirm_password') }}" required>
                            @error('confirm_password')
                            <div class="text-danger position-absolute" style="top: 0px; right: 20px">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection