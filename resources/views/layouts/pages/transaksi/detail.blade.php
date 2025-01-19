@extends('layouts.app')

@section('content')
<div class="container-fluid my-5">
    <h1 class="my-4">Detail Pengajuan</h1>
    <div class="card">
        <div class="card-header">
        <h5 class="card-title mb-0">{{ $dto->pjn_id_alternative == 'Draft' ? 'Draft ' : '' }}Pengajuan {{ $dto->pjn_id_alternative == 'Draft' ? '' : $dto->pjn_id_alternative }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>ID Karyawan:</strong> {{ $dto->kry_id_alternative }}</p>
                    <p><strong>Nama Lengkap:</strong> {{ $dto->kry_name }}</p>
                    <p><strong>Jenis Pengajuan:</strong> {{ $dto->jpj_name  }}</p>
                    <p><strong>Status:</strong> 
                        @if($dto->pjn_status == '1')
                            Draft
                        @elseif($dto->pjn_status == '2')
                            Menunggu Approval HRD
                        @elseif($dto->pjn_status == '3')
                            Terverifikasi HRD
                        @elseif($dto->pjn_status == '4')
                            Ditolak
                        @endif
                    </p>
                    <p><strong>Tanggal Pengajuan:</strong> {{$dto->pjn_tanggal}}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Keterangan:</strong></p>
                    <p>{{ $dto->pjn_keterangan }}</p>
                </div>
            </div>

            <div class="mt-4">
                <h5>Dokumen Pendukung</h5>
                <hr>
                <div class="list-group">
                    <a href="{{ route('pengajuan.download', $dto->pjn_pdf) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-pdf"></i> {{ $dto->pjn_pdf }}
                    </a>
                    @if($dto->pjn_excel)
                    <a href="{{ route('pengajuan.download', $dto->pjn_excel) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-excel"></i> {{ $dto->pjn_excel }}
                    </a>
                    @endif
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
