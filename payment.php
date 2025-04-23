<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id_pemesanan'])) {
    header("Location: index.php");
    exit();
}

$id_pemesanan = $_GET['id_pemesanan'];

// Ambil data pemesanan lengkap + info jadwal & kereta
$pemesanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        p.id_user, p.id_jadwal, p.jumlah_tiket, p.total_bayar, p.status_bayar, p.waktu_pemesanan,
        j.asal, j.tujuan, j.waktu_berangkat, j.waktu_tiba, j.harga,
        k.nama_kereta
    FROM pemesanan p
    JOIN jadwal j ON p.id_jadwal = j.id_jadwal
    JOIN kereta k ON j.id_kereta = k.id_kereta
    WHERE p.id_pemesanan = '$id_pemesanan'
"));

if (!$pemesanan) {
    echo "Data pemesanan tidak ditemukan.";
    exit();
}

$id_user = $pemesanan['id_user'];
$id_jadwal = $pemesanan['id_jadwal'];

// Ambil nomor kursi dari tabel kursi_terisi
$kursi = [];
$result = mysqli_query($conn, "SELECT no_kursi FROM kursi_terisi WHERE id_jadwal = '$id_jadwal'");
while ($row = mysqli_fetch_assoc($result)) {
    $kursi[] = $row['no_kursi'];
}

$status_pembayaran = '';

// Jika form pembayaran dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodebayar = mysqli_real_escape_string($conn, $_POST['metodebayar']);
    $bukti = basename($_FILES['bukti']['name']);
    $status_bayar = 'proses';

    // Pindahkan file ke folder uploads/
    $target_dir = "uploads/";
    $target_file = $target_dir . $bukti;

    if (move_uploaded_file($_FILES["bukti"]["tmp_name"], $target_file)) {
        // Cek apakah data pembayaran sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM pembayaran WHERE id_pemesanan = '$id_pemesanan'");
        if (mysqli_num_rows($cek) > 0) {
            // Update data pembayaran
            $query = "
                UPDATE pembayaran 
                SET metodebayar = '$metodebayar', status = '$status_bayar', bukti = '$target_file', waktu_bayar = NOW() 
                WHERE id_pemesanan = '$id_pemesanan'
            ";
        } else {
            // Insert data baru
            $query = "
                INSERT INTO pembayaran (id_pemesanan, metodebayar, status, bukti, waktu_bayar) 
                VALUES ('$id_pemesanan', '$metodebayar', '$status_bayar', '$target_file', NOW())
            ";
        }

        if (mysqli_query($conn, $query)) {
            $status_pembayaran = 'proses';
            $success = "Bukti pembayaran berhasil dikirim. Silakan tunggu konfirmasi admin.";
        } else {
            $error = "Terjadi kesalahan dalam proses pembayaran.";
        }
    } else {
        $error = "Gagal mengunggah bukti pembayaran.";
    }
} else {
    // Cek status pembayaran jika sudah pernah membayar
    $pembayaran = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM pembayaran WHERE id_pemesanan = '$id_pemesanan'"));
    if ($pembayaran) {
        $status_pembayaran = $pembayaran['status'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 bg-white p-4 rounded shadow">
    <h3 class="mb-4">💳 Pembayaran Tiket</h3>

    <p><strong>Nama Kereta:</strong> <?= $pemesanan['nama_kereta'] ?></p>
    <p><strong>Rute:</strong> <?= $pemesanan['asal'] ?> → <?= $pemesanan['tujuan'] ?></p>
    <p><strong>Waktu Berangkat:</strong> <?= $pemesanan['waktu_berangkat'] ?></p>
    <p><strong>Jumlah Tiket:</strong> <?= $pemesanan['jumlah_tiket'] ?></p>
    <p><strong>Total Harga:</strong> Rp <?= number_format($pemesanan['total_bayar'], 0, ',', '.') ?></p>

    <h5 class="mt-4">Transfer ke:</h5>
    <ul>
        <li>Bank BCA - 1234567890 a.n PT Kereta Hebat</li>
        <li>OVO / DANA - 081234567890</li>
    </ul>
    <p class="text-danger">* Setelah transfer, silakan kirim bukti pembayaran</p>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($status_pembayaran === 'proses' || $status_pembayaran === 'lunas'): ?>
        <div class="alert alert-info">Pembayaran sedang diproses atau sudah lunas.</div>
        <a href="cetak_bukti.php?id_pemesanan=<?= $id_pemesanan ?>" class="btn btn-success">🎫 Ambil E-Tiket</a>
    <?php else: ?>
        <h4 class="mt-4">Konfirmasi Pembayaran</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="metodebayar" class="form-label">Metode Pembayaran:</label>
                <select class="form-select" name="metodebayar" id="metodebayar" required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="Bank BCA">Bank BCA</option>
                    <option value="OVO">OVO</option>
                    <option value="DANA">DANA</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="bukti" class="form-label">Bukti Pembayaran:</label>
                <input type="file" class="form-control" name="bukti" id="bukti" required>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Bukti Pembayaran</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
