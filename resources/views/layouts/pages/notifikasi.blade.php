@extends ('layouts.app')

@section('content')
    <style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>
    <!-- Container -->
    <div class="container-fluid my-5">
        <h1 class="mb-4">Notifikasi Pengajuan Lembur</h1>

        <!-- Search and Filter -->
        <div class="search-container">
            <form action="{{ route('notifikasi.index') }}" method="GET">
            <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Pencarian" name="search" value="{{ request()->input('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <ul class="dropdown-menu" style="padding-left: 10px; padding-right: 10px;">
                        <li>
                            <label for="sort" style="margin-bottom: 5px; font-weight: bold;">Urutkan</label>
                            <select name="sort" id="sort" class="form-select" style="width: 100%; padding-left: 10px; padding-right: 10px;">
                                <option value="">-- Pilih Urutan --</option>
                                <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>A-Z [↑]</option>
                                <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Z-A [↓]</option>
                            </select>
                        </li>
                        <li>
                            <label for="sort-status" style="margin-bottom: 5px; font-weight: bold;">Status</label>
                            <select id="sort-status" name="sort-status" class="form-select" style="width: 100%; padding-left: 10px; padding-right: 10px;">
                                <option value="">-- Pilih Status --</option>
                                <option value="1" {{ $sortStatus === '1' ? 'selected' : '' }}>Belum dibaca</option>
                                <option value="0" {{ $sortStatus === '0' ? 'selected' : '' }}>Sudah dibaca</option>
                            </select>
                        </li>
                    </ul>  
                </div>
            </form>
        </div>

        <!-- Table -->
        <table class="table table-bordered table-striped" id="jabatanTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Dari</th>
                    <th>Berita</th>
                    <th>Pesan</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($dto as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->ntf_from }}</td>
                    <td>Pengajuan Overtime No.{{ $d->pjn_id_alternative }}-{{ $d->pjn_status == '3' ? 'Disetujui' : 'Ditolak' }}</td>
                    <td>{{ $d->ntf_message == '' ? '-' : $d->ntf_message }}</td>
                    <td>{{ $d->ntf_tanggal }}</td>
                    <td>{{ $d->ntf_status == '1' ? 'Belum dibaca' : 'Sudah dibaca' }}</td>
                    <td>
                        <form action="{{ route('notifikasi.update',$d->ntf_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-link btn-sm @if($d->ntf_status == '0') disabled @endif">
                                <i class="fa fa-check"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data.</td>
                </tr>
                @endforelse
                <!-- You can add more rows as needed -->
            </tbody>
        </table>

        <div class="mt-4">
            {{ $pagination->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
@endsection