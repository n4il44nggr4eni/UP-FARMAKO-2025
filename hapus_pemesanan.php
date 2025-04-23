<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_pemesanan = $_GET['id'];

    // Hapus data terkait terlebih dahulu karena ada foreign key:
    // 1. Hapus dari tabel penumpang
    mysqli_query($conn, "DELETE FROM penumpang WHERE id_pemesanan = '$id_pemesanan'");

    // 2. Hapus dari tabel pembayaran
    mysqli_query($conn, "DELETE FROM pembayaran WHERE id_pemesanan = '$id_pemesanan'");

    // 3. Terakhir, hapus dari tabel pemesanan
    $delete = mysqli_query($conn, "DELETE FROM pemesanan WHERE id_pemesanan = '$id_pemesanan'");

    if ($delete) {
        header("Location: kelola_pembayaran.php?hapus=berhasil");
    } else {
        echo "Gagal menghapus pemesanan.";
    }
} else {
    echo "ID pemesanan tidak ditemukan.";
}
?>
