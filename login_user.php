<?php
session_start();
include 'koneksi.php';

$error = '';

// Mengecek apakah ada ID jadwal yang perlu disimpan untuk redirect setelah login
if (isset($_SESSION['redirect_to_passenger'])) {
    $id_jadwal = $_SESSION['redirect_to_passenger'];
    unset($_SESSION['redirect_to_passenger']); // Menghapus session setelah dipakai
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            // Set session untuk pengguna yang berhasil login
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            // Arahkan sesuai dengan role pengguna
            if ($user['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                // Cek apakah ada session untuk redirect ke halaman pengisian penumpang
                if (isset($id_jadwal)) {
                    header("Location: fill_passenger.php?id_jadwal=$id_jadwal");
                } else {
                    header("Location: index.php");
                }
            }
            exit();
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Email tidak ditemukan!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">🔐 Login Pengguna</h3>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary w-100">Login</button>
                <p class="mt-3 text-center">Belum punya akun? <a href="register_user.php">Daftar di sini</a></p>
            </form>
        </div>
    </div>
</div>
</body>
</html>
