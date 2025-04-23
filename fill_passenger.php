<?php
session_start();
include 'koneksi.php';

if (!isset($_GET['id_jadwal'])) {
    header("Location: index.php");
    exit();
}

$id_jadwal = $_GET['id_jadwal'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_penumpang = $_POST['nama_penumpang'];
    $jumlah_tiket = $_POST['jumlah_tiket'];

    $query = "INSERT INTO pemesanan (id_jadwal, id_user, jumlah_tiket, total_bayar, status_bayar, waktu_pemesanan)
              VALUES ('$id_jadwal', '{$_SESSION['id_user']}', '$jumlah_tiket', 0, 'proses', NOW())";
    mysqli_query($conn, $query);
    $id_pemesanan = mysqli_insert_id($conn);

    // Simpan penumpang tanpa nomor kursi
    for ($i = 0; $i < $jumlah_tiket; $i++) {
        $query_penumpang = "INSERT INTO penumpang (id_pemesanan, nama_penumpang)
                            VALUES ('$id_pemesanan', '$nama_penumpang')";
        mysqli_query($conn, $query_penumpang);
    }

    // Redirect ke halaman pilih kursi setelah pemesanan
    header("Location: pilih_kursi.php?id_pemesanan=$id_pemesanan");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Isi Data Penumpang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .form-section {
            max-width: 600px;
            margin: 60px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-section h2 {
            text-align: center;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-section">
        <h2>🧍‍♂️ Isi Data Penumpang</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Penumpang</label>
                <input type="text" name="nama_penumpang" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Tiket</label>
                <input type="number" name="jumlah_tiket" class="form-control" min="1" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Lanjutkan Pemesanan</button>
            <a href="index.php" class="btn btn-secondary w-100 mt-2">Kembali ke Beranda</a>
        </form>
    </div>
</div>

</body>
</html>
