@extends('layouts.app')

@section('content')

    <div class="container my-5">
        <h1 class="mb-4">Add Jabatan</h1>

        <!-- Back Button -->
        <a href="index.html" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back to Jabatan List
        </a>

        <!-- Jabatan Add Form -->
        <form action="submit_jabatan.php" method="POST">
            <div class="mb-3">
                <label for="namaJabatan" class="form-label">Nama Jabatan</label>
                <input type="text" class="form-control" id="namaJabatan" name="namaJabatan" placeholder="Enter Nama Jabatan" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Jabatan
            </button>
        </form>
    </div>

@endsection