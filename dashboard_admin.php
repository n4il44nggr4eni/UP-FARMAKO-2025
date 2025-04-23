<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Ambil data ringkasan
$totalKereta = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kereta"))['total'];
$totalJadwal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM jadwal"))['total'];
$totalPemesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pemesanan"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }
        .dashboard-header {
            margin-top: 40px;
            margin-bottom: 30px;
            text-align: center;
        }
        .dashboard-header h2 {
            font-weight: 700;
            color: #343a40;
        }
        .dashboard-header p {
            color: #6c757d;
        }
        .card-icon {
            font-size: 30px;
            margin-bottom: 8px;
        }
        .card-title {
            font-size: 1rem;
            font-weight: 500;
            color: #495057;
        }
        .card-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: #212529;
        }
        .nav-section a {
            margin-right: 10px;
        }
        .card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .shadow-sm {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="dashboard-header">
        <h2>Selamat Datang, Admin <?= $_SESSION['nama']; ?> 👋</h2>
        <p class="text-muted">Berikut adalah ringkasan sistem pemesanan tiket</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-center p-4 bg-white shadow-sm">
                <div class="card-icon text-primary"><i class="bi bi-train-front-fill"></i></div>
                <div class="card-title">Total Kereta</div>
                <div class="card-value"><?= $totalKereta; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 bg-white shadow-sm">
                <div class="card-icon text-success"><i class="bi bi-calendar-event-fill"></i></div>
                <div class="card-title">Total Jadwal</div>
                <div class="card-value"><?= $totalJadwal; ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 bg-white shadow-sm">
                <div class="card-icon text-warning"><i class="bi bi-ticket-perforated-fill"></i></div>
                <div class="card-title">Total Pemesanan</div>
                <div class="card-value"><?= $totalPemesanan; ?></div>
            </div>
        </div>
    </div>

    <div class="card p-4 shadow-sm mb-5">
        <h4 class="mb-3">Navigasi Cepat</h4>
        <div class="nav-section d-flex flex-wrap gap-2">
            <a class="btn btn-primary" href="kelola_kereta.php"><i class="bi bi-train-front"></i> Kelola Kereta</a>
            <a class="btn btn-success" href="kelola_jadwal.php"><i class="bi bi-calendar-check"></i> Kelola Jadwal</a>
            <a class="btn btn-warning" href="kelola_penumpang.php"><i class="bi bi-people-fill"></i> Kelola Penumpang</a>
            <a class="btn btn-info" href="kelola_pembayaran.php"><i class="bi bi-wallet2"></i> Kelola Pembayaran</a>
            <a class="btn btn-outline-danger ms-auto" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
