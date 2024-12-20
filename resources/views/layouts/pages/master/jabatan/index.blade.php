@extends ('layouts.app')

@section('content')
    <style>
        .search-container {
            margin-bottom: 20px;
        }
    </style>

    <!-- Container -->
    <div class="container my-5">
        <h1 class="mb-4">Jabatan Data</h1>

        <!-- Create Button -->
        <a href="#" class="btn btn-primary mb-3">Create Jabatan</a>

        <!-- Search and Filter -->
        <div class="search-container">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Nama Jabatan..." onkeyup="searchTable()">
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
            <tbody>
                <!-- Example Data Row 1 -->
                <tr>
                    <td>1</td>
                    <td>Jabatan A</td>
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
                    <td>Jabatan B</td>
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
                    <td>Jabatan C</td>
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
    </script>

@endsection