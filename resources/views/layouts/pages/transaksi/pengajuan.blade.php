@extends ('layouts.app')

@section('content')
<style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>

    <!-- Container -->
    <div class="container-fluid my-5">
        <h1 class="mb-4">Pengajuan Lembur</h1>

        <!-- Create Button -->
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Baru
        </a>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="search-container">
            <form action="{{ route('pengajuan.index') }}" method="GET">
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
                                <option value="1" {{ $sortStatus === '1' ? 'selected' : '' }}>Draft</option>
                                <option value="2" {{ $sortStatus === '2' ? 'selected' : '' }}>Menunggu Approval HRD</option>
                                <option value="3" {{ $sortStatus === '3' ? 'selected' : '' }}>Terverifikasi HRD</option>
                                <option value="4" {{ $sortStatus === '4' ? 'selected' : '' }}>Ditolak</option>
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
                    <th>ID Karyawan </th>
                    <th>Nama</th>
                    <th>Jenis Pengajuan</th>
                    <th>Tanggal Buat</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($dto as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $alternative }}</td>
                    <td>{{ $name }}</td>
                    <td>{{ $d->jpj_name }}</td>
                    <td>{{ $d->pjn_tanggal ?? 'Belum diajukan' }}</td>
                    <td>
                    @if ($d->pjn_status === '1')
                        Draft
                    @elseif ($d->pjn_status === '2')
                        Menunggu Approval HRD
                    @elseif ($d->pjn_status === '3')
                        Terverifikasi HRD
                    @elseif ($d->pjn_status === '4')
                        Ditolak
                    @endif
                    </td>
                    <td>
                        @if($d->pjn_status == '1')    
                        <form id="kirimForm" action="{{ route('pengajuan.update_status', $d->pjn_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="button" class="btn btn-link" title="Kirim" data-toggle="modal" data-target="#confirmKirimModal">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </form>
                        <a href="{{ route('pengajuan.edit', $d->pjn_id) }}" class="btn btn-link" title="Edit"><i class="fa fa-pencil"></i></a>
                        <form id="hapusForm" action="{{ route('pengajuan.destroy', $d->pjn_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-link" title="Hapus" data-toggle="modal" data-target="#confirmHapusModal">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('pengajuan.detail', ['pjn_id' => $d->pjn_id, 'alternative' => $alternative, 'name' => $name]) }}" class="btn btn-link" title="Detail"><i class="fa fa-bars"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- konfirmasi kirim -->
        <div class="modal fade" id="confirmKirimModal" tabindex="-1" aria-labelledby="confirmKirimModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmKirimModalLabel">Konfirmasi Kirim</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin mengirim pengajuan ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" id="confirmKirimBtn">Kirim</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- konfirmasi hapus -->
        <div class="modal fade" id="confirmHapusModal" tabindex="-1" aria-labelledby="confirmHapusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmHapusModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus pengajuan ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="confirmHapusBtn">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $pagination->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script>
        document.getElementById('confirmKirimBtn').addEventListener('click', function() {
            // Submit the form after confirmation
            document.getElementById('kirimForm').submit();
        });

        document.getElementById('confirmHapusBtn').addEventListener('click', function() {
            // Submit the form after confirmation
            document.getElementById('hapusForm').submit();
        });
    </script>
@endsection