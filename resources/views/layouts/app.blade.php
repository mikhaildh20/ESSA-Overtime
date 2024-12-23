<!DOCTYPE html>
<html>
<head>
    <title>Employee Self Service Politeknik Astra</title>
    <link rel="icon" href="{{ asset('images/Logogram.png') }}" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
            background-color: #f8f9fa;
        }
        .header {
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
        }
        .header img {
            height: 50px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            display: inline-block;
            vertical-align: middle;
        }
        .header .title-top {
            display: block;
        }
        .header .title-bottom {
            display: block;
        }
        .nav-link {
            color: #333 !important;
        }
        .notification {
            background-color: #ddd;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 12px;
        }
        .content {
            padding: 20px;
        }
        .status {
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #fff;
        }
        .bar-container {
            width: 100%;
            background-color: #f1f1f1;
            border-radius: 25px;
            margin: 10px 0;
        }
        .bar {
            height: 20px;
            border-radius: 25px;
        }
        .bar.medical {
            width: 84%;
            background-color: #f0ad4e;
        }
        .bar.annual {
            width: 30%;
            background-color: #f0ad4e;
        }
        .bar.large {
            width: 0%;
            background-color: #f0ad4e;
        }
        .bar-text {
            position: relative;
            top: -20px;
            left: 10px;
            font-size: 12px;
            color: #fff;
        }
        @media (max-width: 768px) {
            .header h1 {
                display: block;
                text-align: center;
            }
            .header .title-top {
                display: block;
            }
            .header .title-bottom {
                display: block;
            }
            .nav {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header d-flex align-items-center flex-column flex-md-row">
        <img src="{{ asset('images/Logogram.png') }}" alt="Company Logo">
        <h1 class="ml-md-3 text-center text-md-left">
            <span class="title-top">Employee Self Service</span>
            <span class="title-bottom">Politeknik Astra</span>
        </h1>
        <nav class="ml-md-auto mt-3 mt-md-0">
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('jabatan.index') }}">Jabatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('karyawan.index') }}">Karyawan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">SSO</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Lembur</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Notifikasi <span class="notification">45686</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Hai, Ruci</a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="content container">
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>