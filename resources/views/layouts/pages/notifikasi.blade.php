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
        <h1 class="mb-4">Notifikasi Pengajuan Lembur</h1>

        <!-- Table -->
        <table class="table table-bordered table-striped" id="jabatanTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Dari</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td>1</td>
                    <td>Mikhail Daffa</td>
                    <td>Pemberitahuan tentang pengajuan lembur.</td>
                    <td>Belum Dibaca</td>
                    <td style="text-align: center;">
                        <a href="#" class="btn btn-link" title="Check">
                            <i class="fa fa-check"></i>
                        </a>
                    </td>
                </tr>
                <!-- You can add more rows as needed -->
            </tbody>
        </table>
    </div>
@endsection