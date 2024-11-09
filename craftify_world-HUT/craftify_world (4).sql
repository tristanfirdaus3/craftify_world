-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2024 pada 18.12
-- Versi server: 10.4.20-MariaDB
-- Versi PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `craftify_world`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` varchar(10) NOT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
('admin', 'ninja', '123'),
('admin123', 'sanjaya', '123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_keranjang`
--

CREATE TABLE `detail_keranjang` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `detail_keranjang`
--

INSERT INTO `detail_keranjang` (`id`, `id_user`, `id_produk`, `jumlah`) VALUES
(35, 1, 13, 1),
(36, 1, 12, 4),
(37, 1, 12, 1),
(39, NULL, 12, 1),
(40, 15, 13, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(5) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `give_poin` varchar(20) DEFAULT NULL,
  `resistance` int(11) DEFAULT NULL,
  `Poin_karya` int(11) DEFAULT 0,
  `gambar` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama`, `give_poin`, `resistance`, `Poin_karya`, `gambar`) VALUES
(7, 'karya maskot oleh siswa', '1', 100, 34, 'img/Snapinsta.app_455692491_1031197265674253_9209868857674108250_n_1080.jpg'),
(8, 'karya among us oleh putra surya', '1', 100, 2, 'img/Snapinsta.app_454517724_1023822456411734_327122031597608660_n_1080.jpg'),
(9, 'buma ', '1', 100, 13, 'img/Snapinsta.app_455810251_1031197202340926_189026502273536587_n_1080.jpg'),
(10, 'juara karya foto', '1', 100, 51, 'img/Snapinsta.app_455818344_3711036655822141_819697786452664854_n_1080.jpg'),
(11, 'karya foto 2', '1', 100, 11, 'img/Snapinsta.app_455840801_2755157377985370_520175660455441107_n_1080.jpg'),
(12, 'karya foto ', '1', 100, 328, 'img/Snapinsta.app_455851031_1248748843147371_8002753606038617978_n_1080.jpg'),
(13, 'Buma', ' 1', 95, 19, 'img/Snapinsta.app_456114850_1031197302340916_1565034865564781905_n_1080.jpg'),
(16, 'Karya Anak Kelas XI DKV 2', ' 1', 100, 5, 'img/Snapinsta.app_462911989_1076013527859293_7599690348707030679_n_1080.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(20) DEFAULT NULL,
  `saldo_point` int(11) DEFAULT 10,
  `phone_number` varchar(15) NOT NULL,
  `device_id` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `saldo_point`, `phone_number`, `device_id`, `created_at`) VALUES
(1, 'budi', '123', 0, 'placeholder', 'placeholder', '2024-11-08 14:48:36'),
(5, 'jojo', '123', 570000, 'placeholder', 'placeholder', '2024-11-08 14:48:36'),
(6, 'budi1', '123', 1000000, 'placeholder', 'placeholder', '2024-11-08 14:48:36'),
(7, 'pplgg', '123', 1000000, 'placeholder', 'placeholder', '2024-11-08 14:48:36'),
(8, 'tristanfirdaus2', '1234', 1000000, 'placeholder', 'placeholder', '2024-11-08 14:48:36'),
(14, '2', '2', 1000000, 'placeholder', 'placeholder', '2024-11-08 14:48:36'),
(15, 'pplg11', '123', 999990, 'placeholder', 'placeholder', '2024-11-08 14:48:36'),
(16, '123', '123', 1000000, '', '', '2024-11-08 15:34:12'),
(17, 'hhh', '123', 4, '', '', '2024-11-09 16:31:30'),
(18, 'hhhh', '123', 10, '', '', '2024-11-09 16:53:06');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_keranjang`
--
ALTER TABLE `detail_keranjang`
  ADD CONSTRAINT `detail_keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
