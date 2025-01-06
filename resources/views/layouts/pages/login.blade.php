<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        main {
            flex: 1;
        }
        .custom-alert {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            margin: 0.5rem 0;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body style="background-image: url('{{ asset('images/background.jpg') }}'); background-size: cover; background-repeat: no-repeat; background-position: center;">

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
        <div class="container-fluid">
            <a class="navbar-brand" style="margin-left: 30px;" href="#">
                <img src="{{ asset('images/astratech.png') }}" alt="Astratech Logo" style="height: 55px;">
            </a>
        </div>
    </nav>

    <main class="d-flex justify-content-center align-items-center">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body">
                <h3 class="mb-4 text-center">Selamat Datang</h3>
                <hr />
                @if($errors->any())
                    <div class="alert alert-danger custom-alert" role="alert">
                        Invalid Credentials.
                    </div>
                @endif 
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> -->
