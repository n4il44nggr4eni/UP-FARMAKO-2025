<?php
session_start();
include 'koneksi.php';

$hasil_pencarian = [];
$histori_pencarian = [];

// Cek jika user mengklik "Pesan" tapi belum login
if (isset($_GET['id_jadwal'])) {
    $_SESSION['redirect_to_passenger'] = $_GET['id_jadwal'];
    header("Location: login_user.php");
    exit;
}

// Hapus histori tertentu
if (isset($_POST['hapus_histori_id']) && isset($_SESSION['id_user'])) {
    $id_histori = $_POST['hapus_histori_id'];
    $id_user = $_SESSION['id_user'];
    $delete_query = "DELETE FROM histori_pencarian WHERE id_histori = '$id_histori' AND id_user = '$id_user'";
    mysqli_query($conn, $delete_query);
}

// Hapus semua histori
if (isset($_POST['hapus_semua']) && isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $delete_all_query = "DELETE FROM histori_pencarian WHERE id_user = '$id_user'";
    mysqli_query($conn, $delete_all_query);
}

// Ambil histori pencarian
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $histori_query = "SELECT * FROM histori_pencarian WHERE id_user = '$id_user' ORDER BY waktu_pencarian DESC LIMIT 5";
    $histori_pencarian = mysqli_query($conn, $histori_query);
}

// Proses pencarian
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['hapus_histori_id']) && !isset($_POST['hapus_semua'])) {
    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $tanggal = $_POST['tanggal'];

    $query = "SELECT jadwal.*, kereta.nama_kereta, kereta.jenis 
              FROM jadwal 
              JOIN kereta ON jadwal.id_kereta = kereta.id_kereta
              WHERE asal = '$asal' AND tujuan = '$tujuan' AND tanggal = '$tanggal'";
    $hasil_pencarian = mysqli_query($conn, $query);

    if (isset($_SESSION['id_user'])) {
        $insert_histori = "INSERT INTO histori_pencarian (id_user, asal, tujuan, tanggal) 
                           VALUES ('$id_user', '$asal', '$tujuan', '$tanggal')";
        mysqli_query($conn, $insert_histori);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>eTiket Kereta | Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .hero {
            background: url('https://source.unsplash.com/1600x600/?train,railway') center center/cover no-repeat;
            height: 90vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-shadow: 0 2px 4px rgba(0,0,0,0.7);
        }
        .features i { font-size: 2rem; color: #0d6efd; }
        .section-title { margin-top: 60px; margin-bottom: 30px; }
        footer { background-color: #f8f9fa; padding: 20px 0; }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="hero text-center">
    <h1 class="display-4 fw-bold">🚆 eTiket Kereta</h1>
    <p class="lead">Pesan tiket kereta dengan mudah dan cepat, dari mana saja!</p>
    <a href="#search" class="btn btn-lg btn-primary mt-3">🔍 Cari Tiket Sekarang</a>
</section>

<!-- Fitur Keunggulan -->
<div class="container text-center mt-5 features">
    <h2 class="section-title">Kenapa Memilih eTiket?</h2>
    <div class="row">
        <div class="col-md-4">
            <i class="bi bi-speedometer2"></i>
            <h5 class="mt-2">Cepat & Praktis</h5>
            <p>Pesan tiket hanya dalam beberapa klik, tanpa antre!</p>
        </div>
        <div class="col-md-4">
            <i class="bi bi-lock-fill"></i>
            <h5 class="mt-2">Aman & Terpercaya</h5>
            <p>Data dan transaksi Anda aman bersama kami.</p>
        </div>
        <div class="col-md-4">
            <i class="bi bi-clock-history"></i>
            <h5 class="mt-2">Histori Pencarian</h5>
            <p>Lihat kembali pencarian sebelumnya untuk kemudahan pemesanan.</p>
        </div>
    </div>
</div>

<!-- Form Pencarian -->
<div class="container mt-5" id="search">
    <div class="card p-4 shadow">
        <h4 class="text-center text-primary mb-4">Cari Jadwal Kereta</h4>
        <form method="POST">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="asal" class="form-label">Asal:</label>
                    <input type="text" class="form-control" name="asal" required>
                </div>
                <div class="col-md-4">
                    <label for="tujuan" class="form-label">Tujuan:</label>
                    <input type="text" class="form-control" name="tujuan" required>
                </div>
                <div class="col-md-4">
                    <label for="tanggal" class="form-label">Tanggal Keberangkatan:</label>
                    <input type="date" class="form-control" name="tanggal" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">🔍 Cari Tiket</button>
        </form>
    </div>

    <!-- Hasil Pencarian -->
    <?php if (!empty($hasil_pencarian) && mysqli_num_rows($hasil_pencarian) > 0): ?>
        <div class="card mt-4 shadow">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Hasil Pencarian</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kereta</th>
                            <th>Jenis</th>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Tanggal</th>
                            <th>Waktu Berangkat</th>
                            <th>Waktu Tiba</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($hasil_pencarian)) : ?>
                            <tr>
                                <td><?= $row['nama_kereta'] ?></td>
                                <td><?= $row['jenis'] ?></td>
                                <td><?= $row['asal'] ?></td>
                                <td><?= $row['tujuan'] ?></td>
                                <td><?= $row['tanggal'] ?></td>
                                <td><?= $row['waktu_berangkat'] ?></td>
                                <td><?= $row['waktu_tiba'] ?></td>
                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if (isset($_SESSION['id_user'])): ?>
                                        <a href="fill_passenger.php?id_jadwal=<?= $row['id_jadwal'] ?>" class="btn btn-sm btn-success">Pesan</a>
                                    <?php else: ?>
                                        <a href="?id_jadwal=<?= $row['id_jadwal'] ?>" class="btn btn-sm btn-warning">Login untuk Pesan</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['hapus_histori_id']) && !isset($_POST['hapus_semua'])): ?>
        <div class="alert alert-warning mt-4">Tidak ada jadwal ditemukan untuk pencarian tersebut.</div>
    <?php endif; ?>

    <!-- Histori -->
    <?php if (isset($_SESSION['id_user']) && mysqli_num_rows($histori_pencarian) > 0): ?>
        <div class="card mt-4 shadow">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Histori Pencarian Anda</h5>
                <form method="POST" onsubmit="return confirm('Yakin ingin menghapus semua histori?')">
                    <input type="hidden" name="hapus_semua" value="1">
                    <button type="submit" class="btn btn-sm btn-danger">🗑 Hapus Semua</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Asal</th>
                            <th>Tujuan</th>
                            <th>Tanggal</th>
                            <th>Waktu Pencarian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($histori_pencarian)) : ?>
                            <tr>
                                <td><?= $row['asal'] ?></td>
                                <td><?= $row['tujuan'] ?></td>
                                <td><?= $row['tanggal'] ?></td>
                                <td><?= $row['waktu_pencarian'] ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Yakin ingin menghapus histori ini?')">
                                        <input type="hidden" name="hapus_histori_id" value="<?= $row['id_histori'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <?php if (!isset($_SESSION['id_user'])): ?>
            <a href="login_user.php" class="btn btn-outline-primary">🔑 Login</a>
            <a href="register_user.php" class="btn btn-outline-success">📝 Daftar</a>
        <?php else: ?>
            <a href="logout_user.php" class="btn btn-outline-danger">🔓 Logout</a>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center mt-5">
    <div class="container">
        <p class="mb-0">&copy; <?= date('Y') ?> eTiket Kereta. Semua Hak Dilindungi.</p>
    </div>
</footer>

</body>
</html>
