<!DOCTYPE html>
<html>
<head>
    <title>Employee Self Service Politeknik Astra</title>
    <link rel="icon" href="{{ asset('images/Logogram.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;700&display=swap" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    
    <nav class="navbar navbar-expand-lg border-bttom">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/Logogram.png') }}" alt="Astratech" class="me-3" style="width: 80px; height: auto;">
                <div class="d-flex flex-column">
                    <h5 class="mb-1">Employee Self Service</h5>
                    <h5 class="mb-0">Politeknik Astra</h5>
                </div>
            </div>

            <ul class="navbar nav me-auto mb-2 mb-lg-0 ms-5">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('index') }}">Beranda</a>
                </li>
                @if(session('role') == 1)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pengajuan.index') }}">Lembur</a>
                    </li>
                @elseif(session('role') == 2)

                @elseif(session('role') == 3)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('jabatan.index') }}">Jabatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('karyawan.index') }}">Karyawan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sso.index') }}">SSO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('jenis_pengajuan.index') }}">Jenis Pengajuan</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('notifikasi.index') }}">Notifikasi <span class="notification">9</span></a>
                </li>
            </ul>

        
            <button class="navbar nav mb-2 mb-lg-0 btn p-4 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Hai, {{ session('kry_name') }}
            </button>

            <ul class="dropdown-menu  dropdown-menu-end">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.index') }}">
                        <i class="fas fa-user"></i> Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="modal" data-target="#logoutModal" style="cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
                </li>
            </ul>
        </div>
    </nav>


    <div class="content wide-content container-fluid">
        <div class="row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <!-- Form for Logout POST Request -->
                    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>