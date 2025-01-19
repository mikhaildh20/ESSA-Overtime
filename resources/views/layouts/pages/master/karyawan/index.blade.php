@extends ('layouts.app')

@section('content')
    <style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>

    <!-- Container -->
    <div class="container-fluid my-5">
        <h1 class="mb-4">Data Karyawan</h1>


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
            <form action="{{ route('karyawan.index') }}" method="GET">
                <div class="input-group">
                    <!-- Create Button -->
                    <a href="{{ route('karyawan.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Tambah
                    </a>
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
                                <option value="1" {{ $sortStatus === '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $sortStatus === '0' ? 'selected' : '' }}>Tidak Aktif</option>
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
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($dto as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->kry_name }}</td>
                    <td>{{ $d->jbt_name }}</td>
                    <td>{{ $d->kry_username }}</td>
                    <td>{{ $d->kry_email }}</td>
                    <td>
                        <form action="{{ route('karyawan.update_status', $d->kry_id_alternative) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <a href="{{ route('karyawan.edit', $d->kry_id_alternative) }}" class="btn btn-link btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Modal Trigger untuk Hapus atau Aktif -->
                            @if($d->kry_status == 1)
                                <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" 
                                        data-action="{{ route('karyawan.update_status', $d->kry_id_alternative) }}" 
                                        data-status="inactive" data-id="{{ $d->kry_id_alternative }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-link btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" 
                                        data-action="{{ route('karyawan.update_status', $d->kry_id_alternative) }}" 
                                        data-status="active" data-id="{{ $d->kry_id_alternative }}">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>


        <div class="mt-4">
            {{ $pagination->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>


    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Aksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage">Apakah Anda yakin ingin melakukan aksi ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="confirmForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-danger" id="confirmButton">Ya, Lanjutkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tangkap event saat tombol di klik
        const modal = document.getElementById('confirmModal');
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang di klik
            const actionUrl = button.getAttribute('data-action'); // Ambil URL aksi
            const status = button.getAttribute('data-status'); // Ambil status
            const id = button.getAttribute('data-id'); // Ambil ID

            // Update form action dan message sesuai dengan status
            const form = document.getElementById('confirmForm');
            form.action = actionUrl;

            const message = document.getElementById('modalMessage');
            if (status === 'active') {
                message.textContent = 'Apakah Anda yakin ingin mengaktifkan karyawan ini?';
            } else {
                message.textContent = 'Apakah Anda yakin ingin menghapus karyawan ini?';
            }

            const confirmButton = document.getElementById('confirmButton');
            confirmButton.classList.toggle('btn-danger', status === 'inactive');
            confirmButton.classList.toggle('btn-success', status === 'active');
        });
    </script>
@endsection