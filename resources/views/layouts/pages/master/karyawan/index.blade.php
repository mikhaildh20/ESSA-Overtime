@extends('layouts.app')

@section('content')

    <style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>

    <!-- Container -->
    <div class="container my-5">
        <h1 class="mb-4">Karyawan Data</h1>

        <!-- Create Button with Icon -->
        <a href="#" class="btn btn-primary mb-3">
            <i class="fas fa-plus-circle"></i> Create Karyawan
        </a>

        <!-- Search and Filter -->
        <div class="search-container">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Nama, Jabatan, or Username..." onkeyup="searchTable()">
        </div>

        <!-- Table -->
        <table class="table table-bordered table-striped" id="karyawanTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID</th>
                    <th>Jabatan</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Data Row 1 -->
                <tr>
                    <td>1</td>
                    <td>K001</td>
                    <td>Jabatan A</td>
                    <td>John Doe</td>
                    <td>johndoe@example.com</td>
                    <td>
                        <button class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
                <!-- Example Data Row 2 -->
                <tr>
                    <td>2</td>
                    <td>K002</td>
                    <td>Jabatan B</td>
                    <td>Jane Smith</td>
                    <td>janesmith@example.com</td>
                    <td>
                        <button class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
                <!-- Example Data Row 3 -->
                <tr>
                    <td>3</td>
                    <td>K003</td>
                    <td>Jabatan C</td>
                    <td>Mark Wilson</td>
                    <td>markwilson@example.com</td>
                    <td>
                        <button class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
                <!-- Add more rows here as needed -->
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to search and filter the table rows
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById('searchInput');
            filter = input.value.toUpperCase();
            table = document.getElementById('karyawanTable');
            tr = table.getElementsByTagName('tr');

            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName('td');
                var match = false;
                // Search across multiple columns
                for (var j = 1; j < td.length - 1; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }
                if (match) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    </script>

@endsection