<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>

    @if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>

        <button type="submit">Login</button>
    </form>
</body>

</html> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="{{asset('style/assets/logo-sekolah.png')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('style/css/login_style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- @if ($errors->any())
    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif -->

    <div class="login-container">
        <div class="logo-section">
            <img src="{{asset('style/assets/logo-sekolah.png')}}" alt="Logo">
            <h1>Sistem Informasi Akademik</h1>
            <h2>SMP Negeri 1 Karangawen</h2>
        </div>
        <div class="form-section">
            <p class="intro">Selamat Datang!</p>
            <p style="margin-bottom: 30px">Masukkan Username dan Password untuk masuk.</p>

            @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input class="form-control" type="text" id="username" name="username" placeholder="Masukkan Username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input class="form-control" type="password" id="password" name="password" placeholder="Masukkan Password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 SMP Negeri 1 Karangawen. All Rights Reserved.</p>
    </div>
</body>

</html>