<?php
session_start();

include 'koneksi.php';

$edit = false;
$judul_form = "Tambah Jadwal";
$button_label = "Tambah Jadwal";

// Ambil data kereta
$kereta = mysqli_query($conn, "SELECT * FROM kereta");

// Jika edit
if (isset($_GET['edit'])) {
    $edit = true;
    $id_edit = $_GET['edit'];
    $data_edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jadwal WHERE id_jadwal = '$id_edit'"));

    $judul_form = "Edit Jadwal";
    $button_label = "Simpan Perubahan";
}

// Simpan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kereta = $_POST['id_kereta'];
    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $tanggal = $_POST['tanggal'];
    $waktu_berangkat = $_POST['waktu_berangkat'];
    $waktu_tiba = $_POST['waktu_tiba'];
    $harga = $_POST['harga'];

    if (isset($_POST['id_jadwal'])) {
        // Update
        $id = $_POST['id_jadwal'];
        $query = "UPDATE jadwal SET id_kereta='$id_kereta', asal='$asal', tujuan='$tujuan', tanggal='$tanggal',
                  waktu_berangkat='$waktu_berangkat', waktu_tiba='$waktu_tiba', harga='$harga'
                  WHERE id_jadwal='$id'";
    } else {
        // Insert
        $query = "INSERT INTO jadwal (id_kereta, asal, tujuan, tanggal, waktu_berangkat, waktu_tiba, harga)
                  VALUES ('$id_kereta', '$asal', '$tujuan', '$tanggal', '$waktu_berangkat', '$waktu_tiba', '$harga')";
    }

    mysqli_query($conn, $query);
    header("Location: kelola_jadwal.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul_form ?></title>
    <style>
        form { margin-top: 30px; max-width: 400px; }
        input, select { width: 100%; padding: 6px; margin: 8px 0; }
        button { padding: 8px 12px; background-color: #28a745; color: white; border: none; }
        a { display: inline-block; margin-top: 10px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <h2><?= $judul_form ?></h2>

    <form method="POST">
        <?php if ($edit): ?>
            <input type="hidden" name="id_jadwal" value="<?= $data_edit['id_jadwal'] ?>">
        <?php endif; ?>

        <label>Kereta:</label>
        <select name="id_kereta" required>
            <option value="">Pilih Kereta</option>
            <?php while ($k = mysqli_fetch_assoc($kereta)) : ?>
                <option value="<?= $k['id_kereta'] ?>" <?= ($edit && $k['id_kereta'] == $data_edit['id_kereta']) ? 'selected' : '' ?>>
                    <?= $k['nama_kereta'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Asal:</label>
        <input type="text" name="asal" required value="<?= $edit ? $data_edit['asal'] : '' ?>">

        <label>Tujuan:</label>
        <input type="text" name="tujuan" required value="<?= $edit ? $data_edit['tujuan'] : '' ?>">

        <label>Tanggal:</label>
        <input type="date" name="tanggal" required value="<?= $edit ? $data_edit['tanggal'] : '' ?>">

        <label>Waktu Berangkat:</label>
        <input type="time" name="waktu_berangkat" required value="<?= $edit ? $data_edit['waktu_berangkat'] : '' ?>">

        <label>Waktu Tiba:</label>
        <input type="time" name="waktu_tiba" required value="<?= $edit ? $data_edit['waktu_tiba'] : '' ?>">

        <label>Harga:</label>
        <input type="number" name="harga" required value="<?= $edit ? $data_edit['harga'] : '' ?>">

        <button type="submit"><?= $button_label ?></button>
        <br>
        <a href="kelola_jadwal.php">⬅ Kembali ke Jadwal</a>
    </form>
</body>
</html>
