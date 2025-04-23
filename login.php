<?php
require('koneksi.php');
session_start();

$error = '';

// Cek jika sudah login
if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: dashboard_admin.php');
        exit;
    } else {
        // Jika role bukan admin, logout
        session_destroy();
        header('Location: login.php');
        exit;
    }
}

// Proses saat tombol login ditekan
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = 'admin'; // role ditentukan langsung

    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM users WHERE email = '$email' AND role = '$role'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $data = mysqli_fetch_assoc($result);

            if (password_verify($password, $data['password'])) {
                $_SESSION['email'] = $data['email'];
                $_SESSION['nama'] = $data['nama'];
                $_SESSION['role'] = $data['role'];

                header('Location: dashboard_admin.php');
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Email tidak ditemukan atau Anda bukan admin!";
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
    <title>Login Admin | eTiket Kereta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<section class="container-fluid mt-5">
    <section class="row justify-content-center">
        <section class="col-md-4">
            <form class="p-4 border rounded bg-light" method="POST" action="login.php">
                <h4 class="text-center font-weight-bold">Login Admin</h4>

                <?php if ($error != ''): ?>
                    <div class="alert alert-danger mt-3"><?= $error; ?></div>
                <?php endif; ?>

                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>

                <div class= "text-center mt-3" id="landingpage-link">
                    <p>Kembali ke Beranda?<a href="index_admin.php">Kembali</a></p>
                </div>
            </form>
        </section>
    </section>
</section>
</body>
</html>
