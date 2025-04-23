<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id_pemesanan'])) {
    header("Location: index.php");
    exit();
}

$id_pemesanan = $_GET['id_pemesanan'];

// Ambil data pemesanan + jadwal + kereta
$pemesanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        p.id_jadwal, p.jumlah_tiket, p.total_bayar,
        j.asal, j.tujuan, j.waktu_berangkat, j.waktu_tiba,
        k.nama_kereta
    FROM pemesanan p
    JOIN jadwal j ON p.id_jadwal = j.id_jadwal
    JOIN kereta k ON j.id_kereta = k.id_kereta
    WHERE p.id_pemesanan = '$id_pemesanan'
"));

$id_jadwal = $pemesanan['id_jadwal'];

// Ambil kursi dari penumpang
$kursi = [];
$result = mysqli_query($conn, "SELECT no_kursi FROM penumpang WHERE id_pemesanan = '$id_pemesanan'");
while ($row = mysqli_fetch_assoc($result)) {
    $kursi[] = $row['no_kursi'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bayar Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>💳 Konfirmasi & Pembayaran</h3>
    <p><strong>Nama Kereta:</strong> <?= $pemesanan['nama_kereta'] ?></p>
    <p><strong>Rute:</strong> <?= $pemesanan['asal'] ?> → <?= $pemesanan['tujuan'] ?></p>
    <p><strong>Waktu Berangkat:</strong> <?= $pemesanan['waktu_berangkat'] ?></p>
    <p><strong>Waktu Tiba:</strong> <?= $pemesanan['waktu_tiba'] ?></p>
    <p><strong>Jumlah Tiket:</strong> <?= $pemesanan['jumlah_tiket'] ?></p>
    <p><strong>No Kursi:</strong> <?= implode(', ', $kursi) ?></p>
    <p><strong>Total Harga:</strong> Rp <?= number_format($pemesanan['total_bayar'], 0, ',', '.') ?></p>

    <h5 class="mt-4">Transfer ke:</h5>
    <ul>
        <li>Bank BCA - 1234567890 a.n PT Kereta Hebat</li>
        <li>OVO / DANA - 081234567890</li>
    </ul>
    <p class="text-danger">* Setelah transfer, silakan kirim bukti pembayaran</p>
    <a href="payment.php?id_pemesanan=<?= $id_pemesanan ?>" class="btn btn-primary">Saya Sudah Bayar</a>
</div>
</body>
</html>
