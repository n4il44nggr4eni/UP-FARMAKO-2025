<?php
session_start();

include 'koneksi.php';

// Ambil data penumpang yang akan diubah
if (isset($_GET['id'])) {
    $id_penumpang = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM penumpang WHERE id_penumpang = '$id_penumpang'");
    $data_penumpang = mysqli_fetch_assoc($result);
}

// Proses perubahan data penumpang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penumpang = $_POST['nama_penumpang'];
    $no_kursi = $_POST['no_kursi'];
    mysqli_query($conn, "UPDATE penumpang SET nama_penumpang = '$nama_penumpang', no_kursi = '$no_kursi' WHERE id_penumpang = '$id_penumpang'");
    header("Location: kelola_penumpang.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penumpang</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 650px;
            margin-top: 50px;
        }

        .card {
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        h2 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-control,
        .btn {
            font-size: 16px;
        }

        .form-label {
            font-weight: 600;
        }

        .btn-custom {
            background-color: #1d72b8;
            color: white;
            font-size: 16px;
            border-radius: 8px;
        }

        .btn-custom:hover {
            background-color: #155a8a;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            border-radius: 8px;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .form-control:focus {
            border-color: #1d72b8;
            box-shadow: 0 0 5px rgba(29, 114, 184, 0.5);
        }

        .back-link {
            font-size: 0.9rem;
            color: #555;
        }

        .back-link:hover {
            color: #1d72b8;
        }

        .card-header {
            background-color: #f9f9f9;
            font-weight: 600;
            font-size: 1.2rem;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h3>Edit Data Penumpang</h3>
            </div>
            <form method="POST">
                <div class="mb-4">
                    <label for="nama_penumpang" class="form-label">Nama Penumpang</label>
                    <input type="text" class="form-control" id="nama_penumpang" name="nama_penumpang" value="<?= $data_penumpang['nama_penumpang'] ?>" required>
                </div>
                <div class="mb-4">
                    <label for="no_kursi" class="form-label">No Kursi</label>
                    <input type="text" class="form-control" id="no_kursi" name="no_kursi" value="<?= $data_penumpang['no_kursi'] ?>" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Simpan Perubahan</button>
            </form>
            <a href="kelola_penumpang.php" class="btn btn-back w-100 mt-3">⬅ Kembali ke Daftar Penumpang</a>
        </div>
    </div>

    <!-- Bootstrap JS (optional for some features like modals, tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybM0tGzF1tQe8Zx3h18p6Ql1t7hT7tW5U5p0jsU5Pp8gc5p1n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0eJwD3lMe8Iu7uA23zWgnf6Tfkkm+V+K/d1cF+6dL+Xqln1N" crossorigin="anonymous"></script>
</body>
</html>
