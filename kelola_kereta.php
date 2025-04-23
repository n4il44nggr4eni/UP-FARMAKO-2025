<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Hapus kereta
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kereta WHERE id_kereta = '$id'");
    header("Location: kelola_kereta.php");
    exit();
}

// Ambil data kereta
$data = mysqli_query($conn, "SELECT * FROM kereta");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kereta</title>

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
    </style>
</head>
<body>

<div class="container">
    <h2>Kelola Kereta 🚄</h2>
    
    <!-- Tombol Tambah Kereta -->
    <a href="form_kereta.php" class="btn btn-primary mb-3">➕ Tambah Kereta</a>
    
    <!-- Tombol Kembali ke Dashboard -->
    <a href="dashboard_admin.php" class="btn btn-back mb-3">⬅ Kembali ke Dashboard</a>

    <!-- Tabel Data Kereta -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nama Kereta</th>
                <th>Jenis</th>
                <th>Kapasitas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($data)) : ?>
                <tr>
                    <td><?= $row['id_kereta'] ?></td>
                    <td><?= $row['nama_kereta'] ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['kapasitas'] ?></td>
                    <td>
                        <a href="form_kereta.php?edit=<?= $row['id_kereta'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?hapus=<?= $row['id_kereta'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kereta ini?')">Hapus</a>
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
