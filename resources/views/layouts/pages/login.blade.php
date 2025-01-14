<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Barlow font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* Apply the Barlow font to the body */
        body {
            font-family: 'Barlow', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .custom-alert {
            font-size: 0.8rem; /* Adjust the font size */
            padding: 0.5rem 1rem; /* Adjust the padding */
            margin: 0.5rem 0; /* Adjust the margin */
        }
        .list-group-item {
            border-radius: 4px;
        }
        .list-group-item + .list-group-item {
            margin-top: 10px;
        }
    </style>
</head>
<body style="background-image: url('{{ asset('images/background.jpg') }}'); background-size: cover; background-repeat: no-repeat; background-position: center;">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
        <div class="container-fluid">
            <a class="navbar-brand" style="margin-left: 30px;" href="#">
                <img src="{{asset('images/astratech.png')}}" style="height: 50px;"> <!-- Replace 'logo.png' with your logo file path -->
            </a>
        </div>
    </nav>

    <!-- Login Form Container -->
    <main class="d-flex justify-content-center align-items-center">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <h3 class="mb-4 text-center">Selamat Datang</h3>
                <hr />
                @if(session('error'))
                    <div class="alert alert-danger custom-alert">
                        {{ session('error') }}
                    </div>
                @endif
                <form action="{{ route('submitLogin') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="username" value="{{ old('username') }}" placeholder="Username">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="password" value="{{ old('password') }}" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center py-3">
        <p class="mb-0">Copyright &copy; 2024 - Employee Self Service Politeknik Astra</p>
    </footer>


    @if(session('roles'))
        <!-- Modal -->
        <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="roleModalLabel">Pilih masuk sebagai..</h5>
                    </div>
                    <div class="modal-body">
                        <p>Selamat datang {{ session('kry_name') }}, anda ingin masuk sebagai apa?</p>
                        <ul class="list-group">
                            @foreach (session('roles') as $role)
                                @php
                                    $roleName = match($role) {
                                        1 => 'Karyawan',
                                        2 => 'Human Resources',
                                        3 => 'Administrator',
                                        default => strtoupper(str_replace('_', ' ', $role)),
                                    };
                                @endphp
                                <form action="{{ route('authenticate') }}" method="POST" id="form-role-{{ $role }}">
                                    @csrf
                                    <input type="hidden" name="role" value="{{ $role }}">
                                    <a href="#" class="list-group-item list-group-item-action" data-form-id="form-role-{{ $role }}">
                                        Login sebagai {{ $roleName }}
                                    </a>
                                </form>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Tutup</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- Automatically open the modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if ({{ session()->has('roles') ? 'true' : 'false' }}) {
                const roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
                roleModal.show();
            }

            document.querySelectorAll('.list-group-item-action').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const formId = this.getAttribute('data-form-id');
                    document.getElementById(formId).submit();
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
