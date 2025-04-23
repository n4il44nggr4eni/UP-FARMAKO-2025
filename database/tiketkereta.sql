-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Apr 2025 pada 03.12
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tiketkereta`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `histori_pencarian`
--

CREATE TABLE `histori_pencarian` (
  `id_histori` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `asal` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_pencarian` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `histori_pencarian`
--

INSERT INTO `histori_pencarian` (`id_histori`, `id_user`, `asal`, `tujuan`, `tanggal`, `waktu_pencarian`) VALUES
(1, 3, 'bogor', 'semarang', '2025-04-21', '2025-04-21 02:06:59'),
(2, 3, 'bogor', 'surabaya', '2025-04-21', '2025-04-21 02:22:24'),
(3, 3, 'bogor', 'surabaya', '2025-04-21', '2025-04-21 02:58:10'),
(5, 4, 'bogor', 'semarang', '2025-04-21', '2025-04-21 03:52:51'),
(6, 4, 'bogor', 'semarang', '2025-04-21', '2025-04-21 03:54:35'),
(7, 4, 'bogor', 'surabaya', '2025-04-21', '2025-04-21 04:08:07'),
(8, 4, 'bogor', 'surabaya', '2025-04-21', '2025-04-21 05:25:00'),
(9, 5, 'bogor', 'semarang', '2025-04-21', '2025-04-21 06:29:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `id_kereta` int(11) NOT NULL,
  `asal` varchar(255) NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu_berangkat` time NOT NULL,
  `waktu_tiba` time NOT NULL,
  `harga` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `id_kereta`, `asal`, `tujuan`, `tanggal`, `waktu_berangkat`, `waktu_tiba`, `harga`) VALUES
(1, 1, 'Bogor', 'Semarang', '2025-04-21', '08:38:00', '10:38:00', '400000.00'),
(2, 1, 'Bogor', 'Surabaya', '2025-04-21', '08:48:00', '11:48:00', '500000.00'),
(3, 4, 'bogor', 'sukabumi', '2025-04-21', '11:18:00', '13:18:00', '75000.00'),
(4, 4, 'bogor', 'jakarta', '2025-04-21', '13:27:00', '15:27:00', '200000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kereta`
--

CREATE TABLE `kereta` (
  `id_kereta` int(11) NOT NULL,
  `nama_kereta` varchar(255) NOT NULL,
  `jenis` enum('ekonomi','eksekutif','vip') NOT NULL,
  `kapasitas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kereta`
--

INSERT INTO `kereta` (`id_kereta`, `nama_kereta`, `jenis`, `kapasitas`) VALUES
(1, 'kereta woosh!', 'ekonomi', 100),
(4, 'kereta KAI', 'vip', 100);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kursi_terisi`
--

CREATE TABLE `kursi_terisi` (
  `id_kursi` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `no_kursi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kursi_terisi`
--

INSERT INTO `kursi_terisi` (`id_kursi`, `id_jadwal`, `no_kursi`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 2, 2),
(4, 1, 2),
(5, 1, 3),
(6, 2, 3),
(7, 2, 4),
(8, 1, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `metodebayar` varchar(255) NOT NULL,
  `status` enum('proses','berhasil','gagal') NOT NULL,
  `bukti` varchar(255) NOT NULL,
  `waktu_bayar` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status_bayar` enum('pending','lunas','batal') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pemesanan`, `metodebayar`, `status`, `bukti`, `waktu_bayar`, `status_bayar`) VALUES
(1, 1, 'Bank BCA', '', 'uploads/bca.jpeg', '2025-04-21 02:13:53', 'pending'),
(2, 2, 'Bank BCA', '', 'uploads/bca.jpeg', '2025-04-21 02:23:58', 'pending'),
(3, 3, 'OVO', 'proses', 'uploads/bca.jpeg', '2025-04-21 03:18:33', 'pending'),
(4, 4, 'DANA', 'proses', 'uploads/bca.jpeg', '2025-04-21 03:41:36', 'pending'),
(5, 5, 'Bank BCA', '', 'uploads/bca.jpeg', '2025-04-21 04:08:55', 'pending'),
(6, 6, 'Bank BCA', '', 'uploads/bca.jpeg', '2025-04-21 04:08:59', 'pending'),
(7, 7, 'Bank BCA', '', 'uploads/bca.jpeg', '2025-04-21 06:27:52', 'pending'),
(8, 8, 'Bank BCA', 'proses', 'uploads/bca.jpeg', '2025-04-21 06:29:34', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah_tiket` int(11) NOT NULL,
  `total_bayar` decimal(10,2) NOT NULL,
  `status_bayar` enum('gagal','proses','berhasil') NOT NULL,
  `waktu_pemesanan` time NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_jadwal`, `id_user`, `jumlah_tiket`, `total_bayar`, `status_bayar`, `waktu_pemesanan`, `nama`) VALUES
(1, 1, 3, 1, '400000.00', 'berhasil', '09:10:35', ''),
(2, 2, 3, 1, '500000.00', 'berhasil', '09:22:32', ''),
(3, 2, 3, 1, '500000.00', 'berhasil', '09:58:21', ''),
(4, 1, 4, 1, '400000.00', 'proses', '10:25:31', ''),
(5, 1, 4, 1, '400000.00', 'berhasil', '10:54:54', ''),
(6, 2, 4, 1, '500000.00', 'berhasil', '11:08:14', ''),
(7, 2, 4, 1, '500000.00', 'berhasil', '12:30:50', ''),
(8, 1, 5, 1, '400000.00', 'proses', '13:29:11', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penumpang`
--

CREATE TABLE `penumpang` (
  `id_penumpang` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `nama_penumpang` varchar(255) NOT NULL,
  `no_kursi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penumpang`
--

INSERT INTO `penumpang` (`id_penumpang`, `id_pemesanan`, `nama_penumpang`, `no_kursi`) VALUES
(1, 1, 'riana', 1),
(4, 2, 'riana', 1),
(7, 4, 'Reyza', 0),
(8, 4, '', 2),
(9, 5, 'Reyza', 0),
(10, 5, '', 3),
(11, 6, 'Reyza', 0),
(12, 6, '', 3),
(13, 7, 'Reyza', 0),
(14, 7, '', 4),
(15, 8, 'fauzan', 0),
(16, 8, '', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `create_account` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `role`, `create_account`) VALUES
(1, 'naila anggraeni', 'naila@gmail.com', '$2y$10$8oaUUGkhuQrLftXajruBAeUAVJaePpMgDwLilq9rVlZTkSiqW8A6K', 'admin', '2025-04-21 01:36:38'),
(2, 'sekar maharani', 'sekar@gmail.com', '$2y$10$yH3.fCtFqFK2sxkswZZJK.2hjgx4Q08tB5y9ljxNy5y4bdgd9Yp5m', '', '2025-04-21 01:39:53'),
(3, 'riana ', 'riana@gmail.com', '$2y$10$eB/WP6dDmkLIy/KI8Yc1UuMJemQje0B670CqeBuia1T31lKKf/kIO', 'user', '2025-04-21 01:46:14'),
(4, 'reyza', 'reyza@gmail.com', '$2y$10$9wRHgP4gkPpkg85F93ykZOODKQLTb29Qukjb7G6s6VODgNNId1OWq', 'user', '2025-04-21 03:24:52'),
(5, 'fauzan', 'fauzan@gmail.com', '$2y$10$VDFm9sQyt6yLZ/VhLYLkxur49gXTtN9DiuHGdvO13v9yXIo9Yvmse', 'user', '2025-04-21 06:28:34');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `histori_pencarian`
--
ALTER TABLE `histori_pencarian`
  ADD PRIMARY KEY (`id_histori`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_kereta` (`id_kereta`);

--
-- Indeks untuk tabel `kereta`
--
ALTER TABLE `kereta`
  ADD PRIMARY KEY (`id_kereta`);

--
-- Indeks untuk tabel `kursi_terisi`
--
ALTER TABLE `kursi_terisi`
  ADD PRIMARY KEY (`id_kursi`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indeks untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `penumpang`
--
ALTER TABLE `penumpang`
  ADD PRIMARY KEY (`id_penumpang`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `histori_pencarian`
--
ALTER TABLE `histori_pencarian`
  MODIFY `id_histori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kereta`
--
ALTER TABLE `kereta`
  MODIFY `id_kereta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `kursi_terisi`
--
ALTER TABLE `kursi_terisi`
  MODIFY `id_kursi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `penumpang`
--
ALTER TABLE `penumpang`
  MODIFY `id_penumpang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `histori_pencarian`
--
ALTER TABLE `histori_pencarian`
  ADD CONSTRAINT `histori_pencarian_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`id_kereta`) REFERENCES `kereta` (`id_kereta`);

--
-- Ketidakleluasaan untuk tabel `kursi_terisi`
--
ALTER TABLE `kursi_terisi`
  ADD CONSTRAINT `kursi_terisi_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`);

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`);

--
-- Ketidakleluasaan untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `penumpang`
--
ALTER TABLE `penumpang`
  ADD CONSTRAINT `penumpang_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
