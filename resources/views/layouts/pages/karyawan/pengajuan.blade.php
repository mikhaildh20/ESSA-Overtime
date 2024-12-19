@extends('layouts.app')

@section('content')
    <div class="card">
            <div class="card-header">
                <h4>Pengajuan Lembur</h4>
            </div>
            <div class="card-body">
                <button class="btn btn-primary mb-3">+ Tambah Baru</button>
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
                    <tbody id="tableBody">
                        <!-- Table rows will be populated by JavaScript -->
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-start" id="pagination">
                        <!-- Pagination buttons will be populated by JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>

        <script>
        const data = [
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 1, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Pengajaran", tanggal: "09 September 2024", status: "Draft" },
            { no: 2, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Operasional", tanggal: "09 September 2024", status: "Diajukan" },
            { no: 3, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Kepanitiaan", tanggal: "09 September 2024", status: "Disetujui" },
            { no: 4, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Produksi", tanggal: "09 September 2024", status: "Ditolak " },
            { no: 5, nidn: "0987654321", nama: "YUNIARTO FIRMANSYAH RINALDI", jenis: "Akademik ", tanggal: "09 September 2024", status: "Disetujui" },
            // Add more rows if needed
        ];

        const rowsPerPage = 8;
        let currentPage = 1;
        let filteredData = data;

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
                            <a href="#" class="btn btn-sm"><i class="fas fa-pencil-alt text-primary"></i></a>
                            <a href="#" class="btn btn-sm"><i class="fas fa-trash text-primary"></i></a>
                            <a href="#" class="btn btn-sm"><i class="fas fa-bars text-primary"></i></a>
                            <a href="#" class="btn btn-sm"><i class="fas fa-paper-plane text-primary"></i></a>
                        </td>
                    </tr>
                `);
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

        $("#applyFilter").on("click", function () {
            const selectedJenis = $("#jenisFilter").val();
            filteredData = selectedJenis ? data.filter(item => item.jenis === selectedJenis) : data;
            currentPage = 1; // Reset to first page
            displayTable();
            setupPagination();
        });

        // Initialize table and pagination
        $(document).ready(() => {
            displayTable();
            setupPagination();
        });
    </script>
@endsection