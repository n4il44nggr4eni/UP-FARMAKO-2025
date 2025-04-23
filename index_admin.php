<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel Kereta | Sistem Pemesanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9fbfd;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .hero {
            background: linear-gradient(135deg, #0d6efd, #4dabf7);
            color: white;
            padding: 80px 20px;
            text-align: center;
        }
        .hero h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .hero p {
            font-size: 1.2rem;
            margin-top: 10px;
        }
        .feature-card {
            border: none;
            transition: 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }
        .footer {
            padding: 20px;
            background-color: #f1f3f5;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="#">AdminKereta</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                <li class="nav-item">
                    <a href="login.php" class="btn btn-primary ms-3">Login Admin</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1>Kelola Pemesanan Tiket Kereta dengan Mudah</h1>
        <p>Panel admin untuk mengatur jadwal, kursi, pembayaran, dan data penumpang.</p>
        <a href="login.php" class="btn btn-light btn-lg mt-4">Masuk ke Admin Panel</a>
    </div>
</section>

<!-- Features Section -->
<section class="py-5" id="fitur">
    <div class="container">
        <h2 class="text-center mb-4">✨ Fitur Unggulan</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-calendar3 mb-3 text-primary" style="font-size: 2rem;"></i>
                    <h5>Kelola Jadwal</h5>
                    <p class="text-muted">Tambahkan, ubah, atau hapus jadwal perjalanan kereta.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-person-check mb-3 text-success" style="font-size: 2rem;"></i>
                    <h5>Data Penumpang</h5>
                    <p class="text-muted">Pantau dan kelola data semua pemesan tiket.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-ticket-detailed mb-3 text-warning" style="font-size: 2rem;"></i>
                    <h5>Pilih Kursi</h5>
                    <p class="text-muted">Pantau ketersediaan dan pengaturan tempat duduk.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card feature-card text-center p-4">
                    <i class="bi bi-wallet2 mb-3 text-danger" style="font-size: 2rem;"></i>
                    <h5>Pembayaran</h5>
                    <p class="text-muted">Kelola pembayaran secara mudah dan aman.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang -->
<section class="py-5 bg-light" id="tentang">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <img src="asset/img/kereta_api.JPG" class="img-fluid rounded" alt="Kereta">
            </div>
            <div class="col-md-6">
                <h3>Tentang Sistem</h3>
                <p class="text-muted">Sistem Pemesanan Tiket Kereta berbasis web ini dirancang untuk membantu admin dalam memonitor dan mengelola operasional pemesanan tiket secara efisien dan real-time. Anda dapat mengatur jadwal perjalanan, mengelola kursi, dan menangani pembayaran hanya dengan beberapa klik.</p>
                <a href="login.php" class="btn btn-primary mt-3">Masuk Sekarang</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<div class="footer">
    &copy; <?= date('Y') ?> AdminKereta | Dibuat dengan ❤️ untuk kemudahan pengelolaan tiket
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
