<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id_pemesanan'])) {
    header("Location: index.php");
    exit();
}

$id_pemesanan = $_GET['id_pemesanan'];

// Ambil data pemesanan lengkap
$query = mysqli_query($conn, "
    SELECT p.id_pemesanan, p.jumlah_tiket, p.total_bayar, p.status_bayar, p.waktu_pemesanan,
           j.asal, j.tujuan, j.tanggal, j.waktu_berangkat, j.waktu_tiba, j.harga,
           k.nama_kereta, b.metodebayar, b.bukti, b.status AS status_pembayaran
    FROM pemesanan p
    JOIN jadwal j ON p.id_jadwal = j.id_jadwal
    JOIN kereta k ON j.id_kereta = k.id_kereta
    LEFT JOIN pembayaran b ON p.id_pemesanan = b.id_pemesanan
    WHERE p.id_pemesanan = '$id_pemesanan'
");

if (!$query || mysqli_num_rows($query) == 0) {
    echo "Data pemesanan tidak ditemukan.";
    exit();
}

$pemesanan = mysqli_fetch_assoc($query);

// Ambil penumpang
$penumpang = mysqli_query($conn, "SELECT * FROM penumpang WHERE id_pemesanan = '$id_pemesanan'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 800px; margin-top: 40px; }
    </style>
</head>
<body class="bg-light">
<div class="container bg-white p-4 shadow rounded">
    <h3 class="text-center mb-4">✅ Konfirmasi Pemesanan</h3>

    <div class="mb-3">
        <strong>Kereta:</strong> <?= $pemesanan['nama_kereta'] ?><br>
        <strong>Rute:</strong> <?= $pemesanan['asal'] ?> ➡ <?= $pemesanan['tujuan'] ?><br>
        <strong>Tanggal:</strong> <?= $pemesanan['tanggal'] ?><br>
        <strong>Waktu:</strong> <?= $pemesanan['waktu_berangkat'] ?> - <?= $pemesanan['waktu_tiba'] ?><br>
        <strong>Jumlah Tiket:</strong> <?= $pemesanan['jumlah_tiket'] ?><br>
        <strong>Total Bayar:</strong> Rp <?= number_format($pemesanan['total_bayar'], 0, ',', '.') ?><br>
        <strong>Metode Bayar:</strong> <?= $pemesanan['metodebayar'] ?? '-' ?><br>
        <strong>Status Pembayaran:</strong>
        <span class="badge bg-<?= 
            $pemesanan['status_pembayaran'] === 'berhasil' ? 'success' :
            ($pemesanan['status_pembayaran'] === 'proses' ? 'warning' : 'secondary') ?>">
            <?= ucfirst($pemesanan['status_pembayaran']) ?>
        </span>
    </div>

    <hr>

    <h5>🧍‍♂️ Daftar Penumpang</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Penumpang</th>
                <th>No Kursi</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($penumpang)) : ?>
            <tr>
                <td><?= $row['nama_penumpang'] ?></td>
                <td><?= $row['no_kursi'] ? $row['no_kursi'] : 'Belum Dipilih' ?></td> <!-- Menampilkan nomor kursi -->
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <?php if ($pemesanan['status_pembayaran'] === 'berhasil'): ?>
        <a href="cetak_tiket.php?id_pemesanan=<?= $id_pemesanan ?>" class="btn btn-primary w-100 mt-3">🖨️ Cetak Tiket</a>
    <?php elseif ($pemesanan['status_pembayaran'] === 'proses'): ?>
        <div class="alert alert-info text-center mt-3">
            ⏳ Pembayaran sedang dalam proses verifikasi.
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center mt-3">
            ⚠️ Pembayaran belum dilakukan.
        </div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary w-100 mt-2">🔙 Kembali ke Beranda</a>
</div>
</body>
</html>
