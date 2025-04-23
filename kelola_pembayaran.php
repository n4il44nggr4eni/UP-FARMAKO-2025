<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if (isset($_GET['verifikasi']) && $_GET['verifikasi'] == 'sukses'): ?>
    <div class="alert alert-success" role="alert">
        ✅ Verifikasi pembayaran berhasil diperbarui.
    </div>
<?php endif; 

// Ambil semua data pemesanan + pembayaran + nama dari tabel users
$query = "
    SELECT p.id_pemesanan, u.nama AS nama_pemesan, pay.metodebayar, pay.bukti, pay.waktu_bayar, pay.status AS status_pembayaran
    FROM pembayaran pay
    JOIN pemesanan p ON pay.id_pemesanan = p.id_pemesanan
    JOIN users u ON p.id_user = u.id_user
";

// Eksekusi query untuk menampilkan data di halaman admin
$result = mysqli_query($conn, $query);

// Ekspor ke CSV jika parameter 'export' ada
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    $filename = "laporan_pemesanan_" . date("Y-m-d_H-i-s") . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Membuka output ke file CSV
    $output = fopen("php://output", "w");

    // Menulis header kolom
    fputcsv($output, [
        'ID Pemesanan', 'Nama Pemesan', 'Metode Pembayaran', 'Bukti Pembayaran', 'Waktu Bayar', 'Status Pembayaran'
    ]);

    // Menulis data ke file CSV sesuai dengan data yang ditampilkan
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['id_pemesanan'],
            $row['nama_pemesan'],
            $row['metodebayar'],
            $row['bukti'] ? $row['bukti'] : 'Tidak ada bukti',
            $row['waktu_bayar'],
            ucfirst($row['status_pembayaran'])
        ]);
    }

    // Menutup file
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pemesanan dan Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Custom CSS untuk styling tambahan -->
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
        }
        h2 {
            color: #007bff;
            font-weight: bold;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: red;
            border-color: red;
        }
        .btn-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-success {
            background-color: green;
            border-color: green;
        }
        .btn-success:hover {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📋 Kelola Pemesanan dan Pembayaran</h2>

    <!-- Button untuk mengekspor ke CSV -->
    <a href="kelola_pembayaran.php?export=excel" class="btn btn-primary mb-3">🗂️ Export ke Excel (CSV)</a>

    <!-- Tabel Data Pemesanan dan Pembayaran -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID Pemesanan</th>
                <th>Nama Pemesan</th>
                <th>Metode Pembayaran</th>
                <th>Bukti</th>
                <th>Waktu Bayar</th>
                <th>Status Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id_pemesanan'] ?></td>
                    <td><?= $row['nama_pemesan'] ?></td>
                    <td><?= $row['metodebayar'] ?></td>
                    <td>
                        <?php if ($row['bukti']): ?>
                            <a href="<?= $row['bukti'] ?>" target="_blank" class="btn btn-primary btn-sm">Lihat Bukti</a>
                        <?php else: ?>
                            Tidak ada
                        <?php endif; ?>
                    </td>
                    <td><?= $row['waktu_bayar'] ?></td>
                    <td><?= ucfirst($row['status_pembayaran']) ?></td>
                    <td>
                        <a href="verifikasi_pembayaran.php?id_pemesanan=<?= $row['id_pemesanan'] ?>&status=lunas" class="btn btn-success btn-sm ms-2">Tandai Lunas</a>
                        <a href="verifikasi_pembayaran.php?id_pemesanan=<?= $row['id_pemesanan'] ?>&status=batal" class="btn btn-danger btn-sm ms-2">Batalkan</a>
                        <hr>
                        <a href="hapus_pemesanan.php?id=<?= $row['id_pemesanan'] ?>" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Yakin ingin menghapus pemesanan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br><a href="dashboard_admin.php" class="btn btn-back">⬅️ Kembali ke Dashboard</a>
</div>

<!-- Menambahkan Bootstrap JS dan Popper.js untuk interaksi -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
