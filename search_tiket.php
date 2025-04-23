<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $asal = $_POST['asal'];
    $tujuan = $_POST['tujuan'];
    $tanggal = $_POST['tanggal'];

    $query = "SELECT * FROM jadwal 
              JOIN kereta ON jadwal.id_kereta = kereta.id_kereta 
              WHERE asal = '$asal' AND tujuan = '$tujuan' AND tanggal = '$tanggal'";
    $result = mysqli_query($conn, $query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pencarian Tiket</title>
</head>
<body>
    <h2>Cari Tiket</h2>
    
    <form method="POST">
        <label>Asal:</label>
        <input type="text" name="asal" required>
        <label>Tujuan:</label>
        <input type="text" name="tujuan" required>
        <label>Tanggal:</label>
        <input type="date" name="tanggal" required>
        <button type="submit">Cari</button>
    </form>

    <?php if (isset($result)) : ?>
        <h3>Hasil Pencarian</h3>
        <table>
            <tr>
                <th>Nama Kereta</th>
                <th>Asal</th>
                <th>Tujuan</th>
                <th>Tanggal</th>
                <th>Waktu Berangkat</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $row['nama_kereta'] ?></td>
                    <td><?= $row['asal'] ?></td>
                    <td><?= $row['tujuan'] ?></td>
                    <td><?= $row['tanggal'] ?></td>
                    <td><?= $row['waktu_berangkat'] ?></td>
                    <td><?= number_format($row['harga'], 2, ',', '.') ?></td>
                    <td><a href="fill_passenger.php?id_jadwal=<?= $row['id_jadwal'] ?>">Pilih</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</body>
</html>
