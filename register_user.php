<?php
session_start();
include 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = 'Email sudah terdaftar!';
    } else {
        $query = "INSERT INTO users (nama, email, password, role, create_account) 
                  VALUES ('$nama', '$email', '$password', '$role', NOW())";
        if (mysqli_query($conn, $query)) {
            $success = 'Registrasi berhasil! Silakan login.';
        } else {
            $error = 'Gagal mendaftar, coba lagi.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">📝 Registrasi Pengguna</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?> <a href="login_user.php">Login</a></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-success w-100">Daftar</button>
                <p class="mt-3 text-center">Sudah punya akun? <a href="login_user.php">Login di sini</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
