<!DOCTYPE html>
<html>
<head>
    <title>Parkizo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/parkizo.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
        }

        /* SIDEBAR FIX */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        /* CONTENT SHIFT */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar bg-dark text-white p-3">

    <h5 class="text-center mb-4">
        <i class="bi bi-car-front-fill"></i> Parkizo
    </h5>

    <a href="/dashboard"
       class="d-block text-white text-decoration-none mb-2 p-2 rounded {{ request()->is('dashboard*') ? 'bg-primary' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    <hr class="text-secondary">

    @if(session('role') == 'admin')

        <a href="/users"
           class="d-block text-white text-decoration-none mb-2 p-2 rounded {{ request()->is('users*') ? 'bg-primary' : '' }}">
            <i class="bi bi-people-fill me-2"></i> User
        </a>

        <a href="/tarif"
           class="d-block text-white text-decoration-none mb-2 p-2 rounded {{ request()->is('tarif*') ? 'bg-primary' : '' }}">
            <i class="bi bi-cash-coin me-2"></i> Tarif Parkir
        </a>

        <a href="/area"
           class="d-block text-white text-decoration-none mb-2 p-2 rounded {{ request()->is('area*') ? 'bg-primary' : '' }}">
            <i class="bi bi-geo-alt-fill me-2"></i> Area Parkir
        </a>

        <a href="/log"
           class="d-block text-white text-decoration-none mb-2 p-2 rounded {{ request()->is('log*') ? 'bg-primary' : '' }}">
            <i class="bi bi-journal-text me-2"></i> Log Aktivitas
        </a>

        <a href="/kendaraan"
        class="d-block text-white text-decoration-none mb-2 p-2 rounded {{ request()->is('kendaraan*') ? 'bg-primary' : '' }}">
            <i class="bi bi-car-front-fill me-2"></i> Kendaraan
        </a>

    @endif

    @if(session('role') == 'petugas')

        <a href="/transaksi"
           class="d-block text-white text-decoration-none mb-2 p-2 rounded {{ request()->is('transaksi*') ? 'bg-primary' : '' }}">
            <i class="bi bi-truck me-2"></i> Transaksi
        </a>

    @endif

    @if(session('role') == 'owner')

        <a href="/rekap"
           class="d-block text-white text-decoration-none mb-2 p-2 rounded {{ request()->is('rekap*') ? 'bg-primary' : '' }}">
            <i class="bi bi-bar-chart-fill me-2"></i> Rekap Transaksi
        </a>

    @endif

    <hr class="text-secondary">

    <a href="/logout" class="text-danger text-decoration-none d-block p-2">
        <i class="bi bi-box-arrow-right me-2"></i> Logout
    </a>

</div>

<!-- CONTENT -->
<div class="main-content p-4 bg-light">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>@yield('title')</h4>
        <span class="badge bg-dark">
            {{ strtoupper(session('role')) }}
        </span>
    </div>

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>