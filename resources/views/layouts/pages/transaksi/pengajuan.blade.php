@extends ('layouts.app')

@section('content')
<style>
        .search-container {
            margin-bottom: 20px;
        }
        th, td{
            text-align: center;
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

        <!-- Search and Filter -->
        <div class="search-container">
            <form action="{{ route('pengajuan.index') }}" method="GET">
                <input type="text" id="searchInput" class="form-control" placeholder="Pencarian" name="search" value="{{ request()->input('search') }}">
            </form>
        </div>

        <!-- Table -->
        <table class="table table-bordered table-striped" id="jabatanTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Karyawan </th>
                    <th>Jenis Pengajuan</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td>1</td>
                    <td>KRY001</td>
                    <td>Panitia Acara</td>
                    <td>20 Oktober 2004</td>
                    <td>Draft</td>
                    <td>    
                        <a class="btn btn-link" title="Kirim"><i class="fa fa-paper-plane"></i></a>
                        <a class="btn btn-link" title="Edit"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-link" title="Hapus"><i class="fa fa-trash"></i></a>
                        <a class="btn btn-link" title="Detail"><i class="fa fa-bars"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>


    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage">Apakah Anda yakin ingin menghapus data ini?</p>
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