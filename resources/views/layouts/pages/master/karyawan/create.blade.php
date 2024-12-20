@extends('layouts.app')

@section('content')

        <!-- Container -->
        <div class="container my-5">
            <h1 class="mb-4">Add Karyawan</h1>

            <!-- Back Button -->
            <a href="index.html" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Karyawan List
            </a>

            <!-- Karyawan Add Form -->
            <form action="submit_karyawan.php" method="POST">
                <div class="mb-3">
                    <label for="karyawanID" class="form-label">ID</label>
                    <input type="text" class="form-control" id="karyawanID" name="karyawanID" placeholder="Enter Karyawan ID" required>
                </div>

                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Enter Jabatan" required>
                </div>

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Enter Nama" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Karyawan
                </button>
            </form>
        </div>

@endsection