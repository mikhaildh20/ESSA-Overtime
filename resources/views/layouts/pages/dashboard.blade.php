@extends('layouts.app')

@section('content')
    <div class="row">
            <div class="col-md-8">
                <h2>Beranda</h2>
                <p>Selamat datang di Employment Self Service Politeknik Astra. Silahkan klik pada menu di atas untuk memulai menggunakan sistem ini.</p>
                <h3>Panduan</h3>
                <ul>
                    <li>Pembuatan klaim pengobatan, kacamata, dan sumbangan rumah sakit <a href="#">klik disini</a>.</li>
                    <li>Pembuatan cuti dan perizinan <a href="#">klik disini</a>.</li>
                </ul>
            </div>
            <div class="col-md-4">
                <div class="status">
                    <h3>Status Pemakaian</h3>
                    <p>Pengobatan/Medical</p>
                    <div class="bar-container">
                        <div class="bar medical">
                            <span class="bar-text">84%</span>
                        </div>
                    </div>
                    <h3>Sisa Cuti</h3>
                    <p>Cuti Tahunan</p>
                    <div class="bar-container">
                        <div class="bar annual">
                            <span class="bar-text">3 hari</span>
                        </div>
                    </div>
                    <p>Cuti Besar</p>
                    <div class="bar-container">
                        <div class="bar large"></div>
                    </div>
                    <p>Klik pada grafik untuk detail lebih lanjut.</p>
                </div>
            </div>
        </div>
@endsection