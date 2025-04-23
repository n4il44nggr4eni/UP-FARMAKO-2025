<?php
include 'koneksi.php';

if (!isset($_GET['id_pemesanan'])) {
    echo "ID tidak valid.";
    exit();
}

$id = $_GET['id_pemesanan'];

// Query untuk mengambil data pemesanan dengan no_kursi dari tabel penumpang
$query = mysqli_query($conn, "
    SELECT p.*, j.asal, j.tujuan, j.tanggal, j.harga, k.nama_kereta
    FROM pemesanan p
    JOIN jadwal j ON p.id_jadwal = j.id_jadwal
    JOIN kereta k ON j.id_kereta = k.id_kereta
    WHERE p.id_pemesanan = '$id'
");

$data = mysqli_fetch_assoc($query);

// Ambil kursi dari penumpang
$kursi = [];
$result = mysqli_query($conn, "SELECT no_kursi FROM penumpang WHERE id_pemesanan = '$id'");
while ($row = mysqli_fetch_assoc($result)) {
    $kursi[] = $row['no_kursi'];
}

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Kereta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .ticket-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            background-color: #e9ecef;
        }
        .ticket {
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-direction: row;
            background-color: #ffffff;
            border: 2px solid #007bff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .ticket-left {
            flex: 1;
            padding: 20px;
            border-right: 2px solid #007bff;
        }
        .ticket-right {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .ticket-header {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin-bottom: 15px;
        }
        .ticket-info p {
            font-size: 16px;
            margin-bottom: 8px;
        }
        .ticket-info strong {
            font-weight: bold;
        }
        .ticket-info .highlight {
            color: #28a745;
            font-size: 18px;
        }
        .barcode {
            text-align: center;
            margin-top: 20px;
        }
        .barcode img {
            width: 200px;
        }
        .button-back {
            text-align: center;
            margin-top: 20px;
        }
        .button-back a {
            background-color: #6c757d;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin: 5px;
            display: inline-block;
        }
        .button-back a:hover {
            background-color: #5a6268;
        }
        .print-btn {
            background-color: #198754;
        }
        .print-btn:hover {
            background-color: #157347;
        }
        .ticket-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }

        /* Print-friendly */
        @media print {
            body * {
                visibility: hidden;
            }
            .ticket-container, .ticket-container * {
                visibility: visible;
            }
            .ticket-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .button-back {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket">
            <div class="ticket-left">
                <div class="ticket-header">
                    🎟️ Tiket Kereta Api 🎟️
                </div>
                <div class="ticket-info">
                    <p><strong>ID Pemesanan:</strong> <?= $data['id_pemesanan'] ?></p>
                    <p><strong>Nama Kereta:</strong> <?= $data['nama_kereta'] ?></p>
                    <p><strong>Rute:</strong> <?= $data['asal'] ?> ➡ <?= $data['tujuan'] ?></p>
                    <p><strong>Tanggal Keberangkatan:</strong> <?= date('d-m-Y', strtotime($data['tanggal'])) ?></p>
                    <p><strong>No. Kursi:</strong> <?= implode(', ', $kursi) ?></p>
                    <p><strong>Jumlah Tiket:</strong> <?= $data['jumlah_tiket'] ?></p>
                    <p><strong>Total Bayar:</strong> <span class="highlight">Rp <?= number_format($data['jumlah_tiket'] * $data['harga'], 0, ',', '.') ?></span></p>
                </div>
            </div>
            <div class="ticket-right">
                <div class="barcode">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $data['id_pemesanan'] ?>&size=200x200" alt="QR Code">
                    <p><small>Tunjukkan kode ini saat pemeriksaan tiket</small></p>
                </div>
                <div class="ticket-footer">
                    <p>Terima kasih telah memilih layanan kami! 🎉</p>
                </div>
                <div class="button-back">
                    <a href="#" onclick="window.print();" class="print-btn">🖨️ Cetak Tiket</a>
                    <a href="index.php">⬅ Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
