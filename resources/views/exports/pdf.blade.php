<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Lembur</title>
</head>
<body>
    <h1>Users List</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nomor Surat</th>
                <th>Jenis Lembur</th>
                <th>Keterangan</th>
                <th>Karyawan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->pjn_id_alternative }}</td>
                    <td>{{ $p->dpo_msjenispengajuan->jpj_name }}</td>
                    <td>{{ $p->pjn_description }}</td>
                    <td>{{ $p->dpo_mskaryawan->kry_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
