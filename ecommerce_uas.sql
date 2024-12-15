-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Des 2024 pada 15.13
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce_uas`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `id_pesanan`, `id_produk`, `jumlah`, `harga`, `total`) VALUES
(1, 4, 2, 1, 2222, 2222),
(2, 6, 2, 2, 2222, 4444),
(3, 7, 2, 1, 2222, 2222),
(4, 8, 8, 1, 33, 33),
(5, 8, 4, 1, 999, 999),
(6, 8, 5, 1, 99, 99),
(7, 9, 2, 1, 2222, 2222),
(8, 9, 3, 1, 100000000, 100000000),
(9, 9, 9, 1, 100000000, 100000000),
(10, 10, 9, 2, 100000000, 200000000),
(11, 10, 7, 2, 33, 66),
(12, 10, 2, 1, 2222, 2222),
(13, 11, 2, 1, 2222, 2222),
(14, 11, 3, 1, 100000000, 100000000),
(15, 11, 4, 1, 999, 999);

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `password`) VALUES
(1, 'anjai', 'anjai@gmail.com', '$2y$10$hOSDmzwkdVtVIV9cR3jwieW4J2NB5zaEtkOR9iGa244HTs3OLKeOi'),
(2, 'wwww', 'woiiii@gmail.com', '$2y$10$ggjdX3Q6nY01jcD2ZyNj1.ZjqGt6kV4KnaT8YeyP47wvI8Hcg2.NO'),
(3, 'ntahlah', 'wooo4@gmail.com', '$2y$10$fK8VD2g/inU42fwHWRs6SO3hSyhKMadI0HOraYkeWzi1On83KoSKS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_penerima` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Menunggu Pembayaran',
  `tanggal_pesanan` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `id_user`, `nama_penerima`, `alamat`, `metode_pembayaran`, `total_harga`, `status`, `tanggal_pesanan`) VALUES
(1, 2, 'aaa', 'aaaa', 'Transfer Bank', 600024508, 'Menunggu Pembayaran', '2024-12-04 04:17:50'),
(2, 2, 'wwwwwwwwww', 'dsddddddddddddddddddd', 'Transfer Bank', 2222, 'Menunggu Pembayaran', '2024-12-04 04:20:43'),
(3, 2, 'fff', 'ffff', 'Transfer Bank', 2222, 'Menunggu Pembayaran', '2024-12-04 04:22:35'),
(4, 2, 'ggg', 'ggggg', 'Transfer Bank', 2222, 'Menunggu Pembayaran', '2024-12-04 04:24:03'),
(5, 2, 'ddd', 'dddd', 'Transfer Bank', 0, 'Menunggu Pembayaran', '2024-12-04 04:26:12'),
(6, 2, 'yyyy', 'yyyy', 'Transfer Bank', 4444, 'Menunggu Pembayaran', '2024-12-04 04:34:16'),
(7, 2, 'rrr', 'rrr', 'Transfer Bank', 2222, 'Menunggu Pembayaran', '2024-12-04 04:38:40'),
(8, 2, 'aaaaaa', 'aaaaaaaaaaaaaaaaaaa', 'COD', 1131, 'Menunggu Pembayaran', '2024-12-04 04:39:39'),
(9, 2, 'si anjai', 'ya gitu deh', 'Transfer Bank', 200002222, 'Menunggu Pembayaran', '2024-12-04 04:53:23'),
(10, 3, 'anjay', '22222222222222222222222222222ddddddd', 'Kartu Kredit', 200002288, 'Menunggu Pembayaran', '2024-12-04 04:55:21'),
(11, 3, 'fff', 'cffffffff', 'Transfer Bank', 100003221, 'Menunggu Pembayaran', '2024-12-04 10:49:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `deskripsi`, `harga`, `gambar`, `stok`) VALUES
(2, 'ppppp', 'woiiii', 2222.00, '441570709_850376097117842_5386138720198061244_n.jpg', 2222),
(3, 'ffffffffffff', 'ffffffffff', 99999999.99, 'Screenshot_4.png', 2147483647),
(4, 'anjai', 'ttt', 999.00, 'Screenshot_3.png', 1111),
(5, 'mmm', 'jjj', 99.00, 'Screenshot_4.png', 88),
(6, 'ss', 'sss', 222.00, 'Screenshot_3.png', 222),
(7, 'dd', 'ddd', 33.00, 'anjau.jpg', 33),
(8, 'rrr', 'rrr', 33.00, '441570709_850376097117842_5386138720198061244_n.jpg', 33),
(9, 'bjiir cuy', 'tiba tiba banget', 99999999.99, 'Gambar WhatsApp 2024-07-06 pukul 12.45.29_9104dd61.jpg', 222222),
(10, 'ssss', 'ssss', 2233333.00, '468230703_1304182120860830_7444498058301083993_n.jpg', 333333);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id`),
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`);

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `pengguna` (`id`),
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`);

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
