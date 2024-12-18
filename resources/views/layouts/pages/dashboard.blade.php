@extends('layouts.app')

@section('content')
    <div class="row">
            <div class="col-12 col-md-6">
                <h2 class="h4 mb-4">Beranda</h2>
                <p>Selamat datang di Employment Self Service Politeknik Astra. Silahkan klik pada menu di atas untuk memulai menggunakan sistem ini.</p>
                <h3 class="h5 mt-4">Panduan</h3>
                <ul class="list-unstyled">
                    <li>Pembuatan klaim pengobatan, kacamata, dan sumbangan rumah sakit <a href="#" class="text-primary">klik disini</a>.</li>
                    <li>Pembuatan cuti dan perizinan <a href="#" class="text-primary">klik disini</a>.</li>
                </ul>
            </div>
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="h5">Status Pemakaian</h3>
                        <p>Pengobatan/Medical</p>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 84%;" aria-valuenow="84" aria-valuemin="0" aria-valuemax="100">84%</div>
                        </div>
                        <h3 class="h5">Sisa Cuti</h3>
                        <p>Cuti Tahunan</p>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 30%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">3 hari</div>
                        </div>
                        <p>Cuti Besar</p>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <p class="text-muted">Klik pada grafik untuk detail lebih lanjut.</p>
                    </div>
                </div>
            </div>
        </div>
@endsection