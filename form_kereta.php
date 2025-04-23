<?php
session_start();

include 'koneksi.php';

$edit = false;
$judul_form = "Tambah Kereta";
$button_label = "Tambah Kereta";

// Jika edit
if (isset($_GET['edit'])) {
    $edit = true;
    $id_edit = $_GET['edit'];
    $data_edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kereta WHERE id_kereta = '$id_edit'"));
    $judul_form = "Edit Kereta";
    $button_label = "Simpan Perubahan";
}

// Simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kereta = $_POST['nama_kereta'];
    $jenis = $_POST['jenis'];
    $kapasitas = $_POST['kapasitas'];

    if (isset($_POST['id_kereta'])) {
        $id = $_POST['id_kereta'];
        $query = "UPDATE kereta SET nama_kereta='$nama_kereta', jenis='$jenis', kapasitas='$kapasitas'
                  WHERE id_kereta='$id'";
    } else {
        $query = "INSERT INTO kereta (nama_kereta, jenis, kapasitas)
                  VALUES ('$nama_kereta', '$jenis', '$kapasitas')";
    }

    mysqli_query($conn, $query);
    header("Location: kelola_kereta.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul_form ?></title>
    <style>
        form { max-width: 400px; margin-top: 30px; }
        input, select { width: 100%; padding: 6px; margin: 8px 0; }
        button { padding: 8px 12px; background-color: #28a745; color: white; border: none; }
        .button-back { padding: 8px 12px; background-color: #007bff; color: white; text-decoration: none; display: inline-block; margin-top: 10px; }
    </style>
</head>
<body>
    <h2><?= $judul_form ?></h2>

    <form method="POST">
        <?php if ($edit): ?>
            <input type="hidden" name="id_kereta" value="<?= $data_edit['id_kereta'] ?>">
        <?php endif; ?>

        <label>Nama Kereta:</label>
        <input type="text" name="nama_kereta" required value="<?= $edit ? $data_edit['nama_kereta'] : '' ?>">

        <label>Jenis:</label>
        <select name="jenis" required>
            <option value="">Pilih Jenis</option>
            <option value="Ekonomi" <?= $edit && $data_edit['jenis'] == 'Ekonomi' ? 'selected' : '' ?>>Ekonomi</option>
            <option value="VIP" <?= $edit && $data_edit['jenis'] == 'VIP' ? 'selected' : '' ?>>VIP</option>
            <option value="Eksekutif" <?= $edit && $data_edit['jenis'] == 'Eksekutif' ? 'selected' : '' ?>>Eksekutif</option>
        </select>

        <label>Kapasitas:</label>
        <input type="number" name="kapasitas" required value="<?= $edit ? $data_edit['kapasitas'] : '' ?>">

        <button type="submit"><?= $button_label ?></button><br>
        
        <!-- Tombol Kembali -->
        <a href="kelola_kereta.php" class="button-back">⬅ Kembali ke Daftar Kereta</a>
    </form>
</body>
</html>
