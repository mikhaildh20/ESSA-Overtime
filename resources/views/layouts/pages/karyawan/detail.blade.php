@extends('layouts.app')

@section('content')
        <style>
            .bordered {
                border: 1px solid #007bff; /* Bootstrap primary color */
                padding: 15px;
                border-radius: 5px;
            }
            hr {
                border-top: 2px solid #007bff; /* Blue color for the horizontal line */
            }
            .document-container {
                background-color: #f7f7f7; /* Grey background */
                padding: 10px;
                border-radius: 5px;
                margin-top: 20px;
            }
            .document-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
            }
            .document-item span {
                font-weight: bold;
            }
            .btn-download {
                background-color: #007bff;
                color: white;
                padding: 5px 15px;
                text-decoration: none;
                border-radius: 5px;
            }
            .btn-download:hover {
                background-color: #0056b3;
            }
            .document-header {
                font-size: 1.2rem;
                font-weight: bold;
                margin-bottom: 10px;
            }
        </style>

        <h2 class="mb-4">Detail Pengajuan Lembur</h2>

        <hr />

        <!-- Informasi Pengajuan -->
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <label for="nidn" class="form-label">NIDN</label>
                <input type="text" id="nidn" class="form-control" value="0320230125" readonly>
            </div>
            <div class="col-md-6">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" id="nama" class="form-control" value="RADIT SURYA WIJAYA" readonly>
            </div>
        </div>
        <hr>

        <!-- Detail Pengajuan -->
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <label for="jenisPengajuan" class="form-label">Jenis Pengajuan</label>
                <input type="text" id="jenisPengajuan" class="form-control" value="Akademik" readonly>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label">Status</label>
                <input type="text" id="status" class="form-control" value="Pending" readonly>
            </div>
        </div>
        <hr>

        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <label for="tanggalPengajuan" class="form-label">Tanggal Pengajuan</label>
                <input type="text" id="tanggalPengajuan" class="form-control" value="17 November 2024" readonly>
            </div>
            <div class="col-md-6">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea id="keterangan" class="form-control" rows="1" readonly>PANITIA PKKMB 2024</textarea>
            </div>
        </div>
        <hr />

        <!-- Supporting Documents Section -->
        <div class="document-container">
            <div class="document-header">Dokumen Pendukung</div>
            <div class="document-item">
                <span>Document_1.pdf</span>
                <a href="#" class="btn-download">Unduh</a>
            </div>
            <!-- Add more document items here if needed -->
        </div>

        <hr />

        <!-- Button Kembali -->
        <div class="text-start">
            <a href="#" class="btn btn-secondary">Kembali</a>
        </div>


@endsection