<?php
session_start();
require('koneksi.php');

$error = '';
$success = '';

// Proses form register
if (isset($_POST['submit'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    if (!empty(trim($nama)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($role))) {
        // Validasi role yang diizinkan
        $allowed_roles = ['admin', 'petugas', 'penumpang'];
        if (in_array($role, $allowed_roles)) {
            // Cek apakah email sudah digunakan
            $query = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert = "INSERT INTO users (nama, email, password, role) 
                           VALUES ('$nama', '$email', '$hashed_password', '$role')";

                if (mysqli_query($conn, $insert)) {
                    $success = "Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
                } else {
                    $error = "Terjadi kesalahan saat registrasi.";
                }
            } else {
                $error = "Email sudah terdaftar!";
            }
        } else {
            $error = "Role tidak valid!";
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | eTiket</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          crossorigin="anonymous">
</head>
<body>
<section class="container-fluid mb-4">
    <section class="row justify-content-center">
        <section class="col-12 col-sm-6 col-md-5">
            <form class="form-container mt-5 p-4 border bg-light rounded" action="register.php" method="POST">
                <h4 class="text-center font-weight-bold">Registrasi Akun</h4>

                <?php if ($error != ''): ?>
                    <div class="alert alert-danger mt-3" role="alert"><?= $error; ?></div>
                <?php endif; ?>

                <?php if ($success != ''): ?>
                    <div class="alert alert-success mt-3" role="alert"><?= $success; ?></div>
                <?php endif; ?>

                <div class="form-group mt-3">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>

                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="form-group mt-3">
                    <label for="role">Pilih Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="penumpang">Penumpang</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-success btn-block">Daftar</button>

                <div class="form-footer mt-3 text-center">
                    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                </div>
            </form>
        </section>
    </section>
</section>
</body>
</html>
