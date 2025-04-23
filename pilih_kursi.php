<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id_pemesanan'])) {
    header("Location: index.php");
    exit();
}

$id_pemesanan = $_GET['id_pemesanan'];

// Ambil info pemesanan
$pemesanan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM pemesanan WHERE id_pemesanan = '$id_pemesanan'
"));

$id_jadwal = $pemesanan['id_jadwal'];
$jumlah_tiket = $pemesanan['jumlah_tiket'];

// Ambil kursi yang sudah terisi
$kursi_terisi = [];
$query_kursi = mysqli_query($conn, "SELECT no_kursi FROM kursi_terisi WHERE id_jadwal = '$id_jadwal'");
while ($row = mysqli_fetch_assoc($query_kursi)) {
    $kursi_terisi[] = $row['no_kursi'];
}

// Ambil kapasitas kereta
$query_kereta = mysqli_query($conn, "
    SELECT kapasitas 
    FROM kereta 
    JOIN jadwal ON jadwal.id_kereta = kereta.id_kereta 
    WHERE jadwal.id_jadwal = '$id_jadwal'
");
$kapasitas = mysqli_fetch_assoc($query_kereta)['kapasitas'];

// Simpan kursi yang dipilih & hitung total bayar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    for ($i = 0; $i < $jumlah_tiket; $i++) {
        $no_kursi = $_POST["kursi_$i"];

        // Simpan ke kursi_terisi
        mysqli_query($conn, "
            INSERT INTO kursi_terisi (id_jadwal, no_kursi) 
            VALUES ('$id_jadwal', '$no_kursi')
        ");

        // Simpan ke penumpang
        mysqli_query($conn, "
            INSERT INTO penumpang (id_pemesanan, no_kursi)
            VALUES ('$id_pemesanan', '$no_kursi')
        ");
    }

    // Ambil harga tiket dari jadwal
    $query_harga = mysqli_query($conn, "SELECT harga FROM jadwal WHERE id_jadwal = '$id_jadwal'");
    $harga_per_tiket = mysqli_fetch_assoc($query_harga)['harga'];

    // Hitung total bayar
    $total_bayar = $jumlah_tiket * $harga_per_tiket;

    // Update total_bayar ke tabel pemesanan
    mysqli_query($conn, "
        UPDATE pemesanan 
        SET total_bayar = '$total_bayar' 
        WHERE id_pemesanan = '$id_pemesanan'
    ");

    // Redirect ke halaman pembayaran
    header("Location: bayar.php?id_pemesanan=$id_pemesanan");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Kursi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .kursi-box {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            margin: 5px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .terisi { background-color: #dc3545; color: white; }
        .tersedia { background-color: #28a745; color: white; cursor: pointer; }
        .form-section {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
<div class="container">
    <div class="form-section">
        <h3 class="text-center">🪑 Pilih Kursi</h3>
        <p>Jumlah Tiket: <strong><?= $jumlah_tiket ?></strong></p>

        <form method="POST">
            <?php for ($i = 0; $i < $jumlah_tiket; $i++): ?>
                <div class="mb-3">
                    <label for="kursi_<?= $i ?>" class="form-label">Pilih Kursi untuk Penumpang <?= $i + 1 ?>:</label>
                    <select name="kursi_<?= $i ?>" class="form-select" required>
                        <option value="">-- Pilih Kursi --</option>
                        <?php for ($k = 1; $k <= $kapasitas; $k++): ?>
                            <?php if (!in_array($k, $kursi_terisi)): ?>
                                <option value="<?= $k ?>">Kursi <?= $k ?></option>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </select>
                </div>
            <?php endfor; ?>

            <button type="submit" class="btn btn-primary w-100 mt-2">Lanjut ke Pembayaran</button>
            <a href="index.php" class="btn btn-secondary w-100 mt-2">Kembali ke Beranda</a>
        </form>

        <hr>
        <h6>Legenda Kursi:</h6>
        <div>
            <span class="kursi-box tersedia">T</span> Tersedia
            <span class="kursi-box terisi">X</span> Terisi
        </div>

        <div class="mt-3">
            <strong>Layout Kursi:</strong><br>
            <?php for ($i = 1; $i <= $kapasitas; $i++): ?>
                <?php if (in_array($i, $kursi_terisi)): ?>
                    <span class="kursi-box terisi">X</span>
                <?php else: ?>
                    <span class="kursi-box tersedia"><?= $i ?></span>
                <?php endif; ?>
                <?= ($i % 4 === 0) ? '<br>' : '' ?>
            <?php endfor; ?>
        </div>
    </div>
</div>
</body>
</html>
