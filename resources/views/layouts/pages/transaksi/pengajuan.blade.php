@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Pengajuan Lembur</h4>
        </div>
        <div class="card-body">
            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary mb-3">+ Tambah Baru</a>
            <div class="input-group mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Pencarian">
                <button class="btn btn-primary" type="button" id="filterButton">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>NIDN</th>
                        <th>Nama</th>
                        <th>Jenis Pengajuan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dto as $key => $d)
                        <tr class="text-center">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $d->dpo_mskaryawan->nidn ?? '-' }}</td>
                            <td>{{ $d->dpo_mskaryawan->nama ?? '-' }}</td>
                            <td>{{ $d->jen_type }}</td>
                            <td>{{ $d->pjn_created_date }}</td>
                            <td>{{ $d->pjn_status }}</td>
                            <td>
                                <a href="#" class="me-2"><i class="fas fa-pencil-alt text-primary"></i></a>
                                <a href="#" class="me-2"><i class="fas fa-trash text-primary"></i></a>
                                <a href="#" class="me-2"><i class="fas fa-bars text-primary"></i></a>
                                <a href="#"><i class="fas fa-paper-plane text-primary"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data pengajuan lembur.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
