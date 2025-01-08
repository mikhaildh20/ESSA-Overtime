@extends ('layouts.app')

@section('content')
    <style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>

    <!-- Container -->
    <div class="container my-5">
        <h1 class="mb-4">Draft Pengajuan Lembur</h1>

        <!-- Create Button -->
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Tambah Pengajuan
        </a>

        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="search-container">
            <form action="{{ route('pengajuan.index') }}" method="GET">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari Data..." name="search" value="{{ request()->input('search') }}">
            </form>
        </div>

        <!-- Table -->
        <table class="table table-bordered table-striped" id="jabatanTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Karyawan</th>
                    <th>Jenis Pengajuan</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($dto as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kry_name }}</td>
                        <td>{{ $d->jbt_name }}</td>
                        @if($d->sso_level == 1)
                            <td>Karyawan</td>
                        @elseif($d->sso_level == 2)
                            <td>Human Resources</td>
                        @else
                            <td>Administrator</td>
                        @endif
                        <td>
                            <a href="{{ route('sso.edit', $d->sso_id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Ubah
                            </a>

                            <!-- Modal Trigger untuk Hapus atau Aktif -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal" 
                                        data-action="{{ route('sso.update_status', $d->sso_id) }}" >
                                    <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>



    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage">Apakah Anda yakin ingin menghapus pengajuan ini?</p>
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

            // Update form action dan message sesuai dengan status
            const form = document.getElementById('confirmForm');
            form.action = actionUrl;
        });
    </script>
@endsection