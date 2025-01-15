@extends('layouts.app')

@section('content')
<div class="container-fluid my-5">
    <h1 class="my-4">Detail Pengajuan</h1>
    <div class="card">
        <div class="card-header">
            <h5>Pengajuan #123</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Karyawan:</strong> K001</p>
                    <p><strong>Nama Lengkap:</strong> John Doe</p>
                    <p><strong>Jenis Pengajuan:</strong> Cuti</p>
                    <p><strong>Status:</strong> Disetujui</p>
                    <p><strong>Tanggal Pengajuan:</strong> 15 Januari 2025</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Keterangan:</strong></p>
                    <p>Pengajuan cuti tahunan untuk liburan keluarga.</p>
                </div>
            </div>

            <div class="mt-4">
                <h5>Dokumen Pendukung</h5>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action" download>
                        <i class="fas fa-file-pdf"></i> Bukti Penunjang PDF
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" download>
                        <i class="fas fa-file-excel"></i> Bukti Penunjang Excel
                    </a>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('pengajuan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
