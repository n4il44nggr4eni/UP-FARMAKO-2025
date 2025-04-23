<?php
session_start();
include 'koneksi.php';

// Cek koneksi ke database
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Hapus penumpang dengan prepared statement untuk menghindari SQL Injection
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    if (is_numeric($id)) {
        $stmt = $conn->prepare("DELETE FROM penumpang WHERE id_penumpang = ?");
        $stmt->bind_param("i", $id); // Bind parameter $id sebagai integer
        $stmt->execute();
        $stmt->close();
    }
    header("Location: kelola_penumpang.php");
    exit();
}

// Ambil data penumpang dengan query yang benar
$query = "
    SELECT penumpang.id_penumpang, penumpang.id_pemesanan, penumpang.nama_penumpang, penumpang.no_kursi, pemesanan.id_jadwal, pemesanan.id_user, users.nama AS nama_pemesan, 
           jadwal.asal, jadwal.tujuan, jadwal.tanggal, jadwal.waktu_berangkat
    FROM penumpang
    JOIN pemesanan ON penumpang.id_pemesanan = pemesanan.id_pemesanan
    JOIN jadwal ON pemesanan.id_jadwal = jadwal.id_jadwal
    JOIN users ON pemesanan.id_user = users.id_user
";
$data_penumpang = mysqli_query($conn, $query);

// Ekspor ke CSV jika parameter 'export' ada
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    $filename = "laporan_penumpang_" . date("Y-m-d_H-i-s") . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Membuka output ke file CSV
    $output = fopen("php://output", "w");

    // Menulis header kolom
    fputcsv($output, [
        'ID Penumpang', 'Nama Pemesan', 'Nama Penumpang', 'Asal', 'Tujuan', 'Tanggal Keberangkatan', 'Waktu Berangkat', 'No Kursi'
    ]);

    // Menulis data ke file CSV sesuai dengan data yang ditampilkan
    while ($row = mysqli_fetch_assoc($data_penumpang)) {
        fputcsv($output, [
            $row['id_penumpang'],
            $row['nama_pemesan'],
            $row['nama_penumpang'],
            $row['asal'],
            $row['tujuan'],
            $row['tanggal'],
            $row['waktu_berangkat'],
            $row['no_kursi']
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
    <title>Kelola Penumpang</title>

    <!-- Menambahkan Bootstrap CDN untuk styling modern -->
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
        .btn-warning {
            background-color: orange;
            border-color: orange;
        }
        .btn-warning:hover {
            background-color: #ffc107;
            border-color: #ffc107;
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
    <h2>Kelola Penumpang 🚂</h2>

    <!-- Tombol untuk ekspor ke CSV -->
    <a href="kelola_penumpang.php?export=excel" class="btn btn-primary mb-3">🗂️ Export ke Excel (CSV)</a>

    <!-- Tombol Kembali ke Dashboard -->
    <a href="dashboard_admin.php" class="btn btn-back mb-3">⬅ Kembali ke Dashboard</a>

    <!-- Tabel Data Penumpang -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID Penumpang</th>
                <th>Nama Pemesan</th>
                <th>Nama Penumpang</th>
                <th>Asal</th>
                <th>Tujuan</th>
                <th>Tanggal Keberangkatan</th>
                <th>Waktu Berangkat</th>
                <th>No Kursi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data_penumpang)) : ?>
                <tr>
                    <td><?= $row['id_penumpang'] ?></td>
                    <td><?= $row['nama_pemesan'] ?></td>
                    <td><?= !empty($row['nama_penumpang']) ? $row['nama_penumpang'] : 'Data tidak tersedia' ?></td>
                    <td><?= $row['asal'] ?></td>
                    <td><?= $row['tujuan'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['waktu_berangkat'] ?></td>
                    <td><?= !empty($row['no_kursi']) ? $row['no_kursi'] : 'Data tidak tersedia' ?></td>
                    <td>
                        <a href="edit_penumpang.php?id=<?= $row['id_penumpang'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <hr>
                        <a href="?hapus=<?= $row['id_penumpang'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus penumpang ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Menambahkan Bootstrap JS dan Popper.js untuk interaksi -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
