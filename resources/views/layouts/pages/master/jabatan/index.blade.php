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
        const data = @json($data);
        const rowsPerPage = 8;
        let currentPage = 1;
        let filteredData = data;

        function displayTable() {
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;
            const paginatedData = filteredData.slice(startIndex, endIndex);

            const tableBody = $("#tableBody");
            tableBody.empty();

            let i = startIndex + 1;

            paginatedData.forEach(row => {
                let actionButton = '';

                if (row.jbt_status === 1) {
                    actionButton = `
                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="${row.jbt_id}" data-status="${row.jbt_status}">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </button>
                    `;
                } else {
                    actionButton = `
                        <button type="button" class="btn btn-success btn-sm active-btn" data-id="${row.jbt_id}" data-status="${row.jbt_status}">
                            <i class="fas fa-check-circle"></i> Active
                        </button>
                    `;
                }

                tableBody.append(`
                    <tr>
                        <td>${i}</td>
                        <td>${row.jbt_name}</td>
                        <td>
                            <form action="/jabatan/${row.jbt_id}/update_status" method="POST">
                                @csrf
                                @method('put')
                                <a class="btn btn-warning btn-sm" href="/jabatan/${row.jbt_id}/edit">
                                    <i class="fas fa-edit"></i> Ubah
                                </a>
                                ${actionButton}
                            </form>
                        </td>
                    </tr>
                `);
                i++;
            });
        }


        function setupPagination() {
            const totalPages = Math.ceil(filteredData.length / rowsPerPage);
            const pagination = $("#pagination");
            pagination.empty();

            const maxPageButtons = 5; // Maximum number of page buttons to display
            let startPage, endPage;

            if (totalPages <= maxPageButtons) {
                startPage = 1;
                endPage = totalPages;
            } else {
                const halfMaxPageButtons = Math.floor(maxPageButtons / 2);
                if (currentPage <= halfMaxPageButtons) {
                    startPage = 1;
                    endPage = maxPageButtons;
                } else if (currentPage + halfMaxPageButtons >= totalPages) {
                    startPage = totalPages - maxPageButtons + 1;
                    endPage = totalPages;
                } else {
                    startPage = currentPage - halfMaxPageButtons;
                    endPage = currentPage + halfMaxPageButtons;
                }
            }

            // Add Previous button
            pagination.append(`
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" id="prevPage">Prev</a>
                </li>
            `);

            // Add page number buttons
            for (let i = startPage; i <= endPage; i++) {
                pagination.append(`
                    <li class="page-item ${i === currentPage ? "active" : ""}">
                        <a class="page-link" href="#">${i}</a>
                    </li>
                `);
            }

            // Add Next button
            pagination.append(`
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" id="nextPage">Next</a>
                </li>
            `);

            // Event handlers for page links
            $(".page-link").on("click", function (e) {
                e.preventDefault();
                const pageNum = $(this).text();
                if (pageNum === "Prev") {
                    if (currentPage > 1) {
                        currentPage--;
                    }
                } else if (pageNum === "Next") {
                    if (currentPage < totalPages) {
                        currentPage++;
                    }
                } else {
                    currentPage = parseInt(pageNum);
                }
                displayTable();
                setupPagination();
            });
        }

        function filterData() {
            const searchQuery = $("#searchInput").val().toLowerCase();
            filteredData = data.filter(row => row.jbt_name.toLowerCase().includes(searchQuery));
            currentPage = 1; // Reset to first page on search
            displayTable();
            setupPagination();
        }

        $("#searchInput").on("input", function () {
            filterData();
        });

        // Call the function to display the table
        $(document).ready(function() {
            displayTable();
            setupPagination();
        });
    </script>

@endsection