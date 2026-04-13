<!DOCTYPE html>
<html>
<head>
    <title>Login - Parkizo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="card shadow" style="width: 350px;">

    <div class="card-header text-center bg-dark text-white">
        <i class="bi bi-car-front-fill"></i>
        <h5 class="mb-0">Parkizo</h5>
        <small>Login</small>
    </div>

    <div class="card-body">

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf

            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" name="username" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>

        </form>

    </div>

</div>

</body>
</html>