@extends ('layouts.app')

@section('content')
    <style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>

    <!-- Container -->
    <div class="container-fluid my-5">
        <h1 class="mb-4">Data Jabatan</h1>

        <!-- Create Button -->
        <a href="{{ route('jabatan.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Jabatan
        </a>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="search-container">
            <form action="{{ route('jabatan.index') }}" method="GET">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama Jabatan..." name="search" value="{{ request()->input('search') }}">
            </form>
        </div>

        <!-- Table -->
        <table class="table table-bordered table-striped" id="jabatanTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>
                        <a href="{{ route('jabatan.index', ['sort' => $sort == 'asc' ? 'desc' : 'asc', 'search' => $search]) }}" style="text-decoration: none; color: black;">
                            Nama Jabatan
                            @if($sort == 'asc')
                                <i class="fas fa-sort-alpha-down"></i>
                            @else
                                <i class="fas fa-sort-alpha-up"></i>
                            @endif
                        </a>
                    </th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($dto as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->jbt_name }}</td>
                    <td>
                        <form action="{{ route('jabatan.update_status', $d->jbt_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <a href="{{ route('jabatan.edit', $d->jbt_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Ubah
                            </a>

                            <!-- Modal Trigger untuk Hapus atau Aktif -->
                            @if($d->jbt_status == 1)
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" 
                                        data-action="{{ route('jabatan.update_status', $d->jbt_id) }}" 
                                        data-status="inactive" data-id="{{ $d->jbt_id }}">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            @else
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" 
                                        data-action="{{ route('jabatan.update_status', $d->jbt_id) }}" 
                                        data-status="active" data-id="{{ $d->jbt_id }}">
                                    <i class="fas fa-check-circle"></i> Aktif
                                </button>
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada data.</td>
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

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
                message.textContent = 'Apakah Anda yakin ingin mengaktifkan jabatan ini?';
            } else {
                message.textContent = 'Apakah Anda yakin ingin menghapus jabatan ini?';
            }

            const confirmButton = document.getElementById('confirmButton');
            confirmButton.classList.toggle('btn-danger', status === 'inactive');
            confirmButton.classList.toggle('btn-success', status === 'active');
        });
    </script>
@endsection