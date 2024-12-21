@extends ('layouts.app')

@section('content')
    <style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>

    <!-- Container -->
    <div class="container my-5">
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

        @if(session('warning'))
            <div class="alert alert-warning" role="alert">
                {{ session('warning') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="search-container">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Nama Jabatan..." onkeyup="searchTable()">
        </div>

        <!-- Table -->
        <table class="table table-bordered table-striped" id="jabatanTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- Example Data Row 1 -->
                @foreach($data as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->jbt_name }}</td>
                    <td>
                        <a class="btn btn-warning btn-sm" href="{{ route('jabatan.edit', $d) }}">
                            <i class="fas fa-edit"></i> Ubah
                        </a>

                        <!-- Button to trigger modal -->
                        @if($d->jbt_status == 1)
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $d->jbt_id }}">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                        @else
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $d->jbt_id }}">
                            <i class="fas fa-undo"></i> Aktif
                        </button>
                        @endif
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="confirmModal-{{ $d->jbt_id }}" tabindex="-1" aria-labelledby="confirmModalLabel-{{ $d->jbt_id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmModalLabel-{{ $d->jbt_id }}">
                                    Confirm {{ $d->jbt_status == 1 ? 'Hapus' : 'Aktif' }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Apakah anda yakin akan {{ $d->jbt_status == 1 ? 'hapus' : 'aktifkan' }} jabatan "{{ $d->jbt_name }}"?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('jabatan.update_status', $d->jbt_id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn {{ $d->jbt_status == 1 ? 'btn-danger' : 'btn-success' }}">
                                        Iya, {{ $d->jbt_status == 1 ? 'Hapus' : 'Aktif' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
        <nav>
            <ul class="pagination justify-content-start" id="pagination">
                <!-- Pagination buttons will be populated by JavaScript -->
            </ul>
        </nav>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to search and filter the table rows
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('jabatanTable');
            tr = table.getElementsByTagName('tr');

            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td')[1]; // Nama Jabatan column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        const data = @json($data);
        const rowsPerPage = 8;
        let currentPage = 1;

        function displayTable() {
            const startIndex = (currentPage -  1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const paginatedData = filteredData.slice(startIndex, endIndex);

            const tableBody = $("#tableBody");
            tableBody.empty();

            paginatedData.forEach(row => {
                tableBody.append(`
                    <tr class="text-center">
                        <td>${row.no}</td>
                        <td>${row.nidn}</td>
                        <td>${row.nama}</td>
                        <td>${row.jenis}</td>
                        <td>${row.tanggal}</td>
                        <td>${row.status}</td>
                        <td>
                            <a href="#" class="me-2"><i class="fas fa-pencil-alt text-primary"></i></a>
                            <a href="#" class="me-2"><i class="fas fa-trash text-primary"></i></a>
                            <a href="#" class="me-2"><i class="fas fa-bars text-primary"></i></a>
                            <a href="#"><i class="fas fa-paper-plane text-primary"></i></a>
                        </td>
                    </tr>
                `);
            });
        }
    </script>

@endsection