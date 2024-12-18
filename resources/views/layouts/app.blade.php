<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Self Service</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
        }
    </style>
</head>
<body class="bg-light">
    <header class="bg-white shadow-sm">
        <div class="container py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/Logogram.png') }}" alt="Company Logo" class="mr-3" height="70" width="70">
                <div>
                    <h1 class="h4 mb-0">Employee Self Service</h1>
                    <h2 class="h6 mb-0">Politeknik Astra</h2>
                </div>
            </div>
            <nav class="d-flex flex-wrap align-items-center">
                <a href="#" class="text-dark mx-2">Beranda</a>
                <a href="#" class="text-dark mx-2">Kalender</a>
                <a href="#" class="text-dark mx-2">Medical</a>
                <div class="dropdown mx-2">
                    <a href="#" class="text-dark dropdown-toggle" data-toggle="dropdown">Perizinan</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Submenu 1</a>
                        <a class="dropdown-item" href="#">Submenu 2</a>
                    </div>
                </div>
                <a href="#" class="text-dark mx-2">Absensi</a>
                <div class="dropdown mx-2">
                    <a href="#" class="text-dark dropdown-toggle" data-toggle="dropdown">Laporan</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Submenu 1</a>
                        <a class="dropdown-item" href="#">Submenu 2</a>
                    </div>
                </div>
                <a href="#" class="text-dark mx-2">Lembur</a>
                <a href="#" class="text-dark mx-2">Notifikasi <span class="badge badge-secondary">45686</span></a>
                <div class="ml-auto d-flex align-items-center">
                    <a href="#" class="text-dark mx-2">Hai, Ruci</a>
                </div>
            </nav>
        </div>
    </header>
    <div class="container py-5">
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>