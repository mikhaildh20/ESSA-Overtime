@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <h1 class="mb-4">Update Jabatan</h1>

        <!-- Back Button -->
        <a href="{{ route('jabatan.index') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back to Jabatan List
        </a>

        <!-- Jabatan Add Form -->
        <form action="{{ route('jabatan.update',$jabatan->jbt_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="jbt_name" class="form-label">Nama Jabatan</label>
                <input type="text" class="form-control" name="jbt_name" placeholder="Enter Nama Jabatan" value="{{ $jabatan->jbt_name }}" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Jabatan
            </button>
        </form>
    </div>

@endsection