<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if (isset($_GET['id_pemesanan'], $_GET['status'])) {
    // Escape input untuk keamanan
    $id_pemesanan = mysqli_real_escape_string($conn, $_GET['id_pemesanan']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    // Validasi status yang diizinkan
    $status_diizinkan = ['lunas', 'batal'];
    if (!in_array($status, $status_diizinkan)) {
        echo "Status tidak valid.";
        exit();
    }

    // Update status di tabel pembayaran
    $query = "UPDATE pembayaran SET status = '$status' WHERE id_pemesanan = '$id_pemesanan'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Mapping status ke status_bayar di pemesanan
        $status_bayar_pemesanan = ($status === 'lunas') ? 'berhasil' : 'gagal';

        // Update status di tabel pemesanan
        mysqli_query($conn, "UPDATE pemesanan SET status_bayar = '$status_bayar_pemesanan' WHERE id_pemesanan = '$id_pemesanan'");

        // Redirect kembali ke kelola pembayaran
        header("Location: kelola_pembayaran.php?verifikasi=sukses");
        exit();
    } else {
        echo "Gagal memperbarui status pembayaran.";
    }
} else {
    echo "Parameter tidak lengkap.";
}
?>
