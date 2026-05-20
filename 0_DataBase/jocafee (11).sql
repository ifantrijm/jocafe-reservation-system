-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Bulan Mei 2026 pada 08.36
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
-- Database: `jocafee`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `id_staff` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `id_staff`) VALUES
(8, 22),
(9, 23),
(10, 24);

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog`
--

CREATE TABLE `blog` (
  `id_blog` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `judul` varchar(200) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `blog`
--

INSERT INTO `blog` (`id_blog`, `id_admin`, `judul`, `isi`, `gambar`, `tanggal`) VALUES
(1, NULL, 'weeding', 'Nuansa Trasisional', '1777619234_hyper_nawab-lonely-5251775_1920.jpg', '2026-05-01 02:07:14'),
(3, NULL, 'reog ponorogo', 'siap', '1778987160_Screenshot 2024-06-02 201126.png', '2026-05-16 22:06:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gallery`
--

CREATE TABLE `gallery` (
  `id_gallery` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` enum('room','event','menu') NOT NULL DEFAULT 'room',
  `keterangan` varchar(250) NOT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `gallery`
--

INSERT INTO `gallery` (`id_gallery`, `id_admin`, `gambar`, `kategori`, `keterangan`, `tanggal`) VALUES
(1, NULL, '1778999447_hiking.jpg', 'room', 'Wisuda', '2026-05-17'),
(2, NULL, '1779170383_IMG_1621.JPG.jpeg', 'room', 'Yearbook', '2026-05-19'),
(3, NULL, '1779248675_2.jpg', 'event', 'yearbook', '2026-05-20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `manager`
--

CREATE TABLE `manager` (
  `id_manager` int(11) NOT NULL,
  `id_staff` int(11) DEFAULT NULL,
  `id_statistik` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `manager`
--

INSERT INTO `manager` (`id_manager`, `id_staff`, `id_statistik`) VALUES
(4, 20, NULL),
(5, 25, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `nama_item` varchar(100) DEFAULT NULL,
  `kategori` enum('makanan','minuman') DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `is_bestseller` tinyint(1) DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `id_admin`, `nama_item`, `kategori`, `deskripsi`, `harga`, `is_bestseller`, `gambar`) VALUES
(1, NULL, 'esteh', 'minuman', 'esteh pandang', 300000.00, 1, '1777619152_teh.jpg'),
(2, NULL, 'soto', 'makanan', 'soto ayam', 15000.00, 1, '1778987202_Screenshot 2024-09-04 224719.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `email`, `telepon`) VALUES
(7, 'geg', 'geg@gaksbas', '84327984'),
(8, 'akbar', 'ifantrijmrpl@gmail.com', '123123'),
(9, 'Ifan Tri J.M Rpl3', 'ifantrijmrpl@gmail.com', '123124'),
(10, 'akbar', NULL, '109209123'),
(11, 'ROM', 'hay@gmail.com', '897986976'),
(12, 'haisoh', 'shushak@gmail.com', '769869'),
(13, 'romli', 'hgsiayg@gmail.com', '4353464363'),
(14, 'alskdjf', NULL, '12321as'),
(15, 'Ifan Tri J.M Rpl3', 'ifantrijmrpl@gmail.com', '234234'),
(16, 'Ifan Tri J.M Rpl3', 'ifantrijmrpl@gmail.com', '123123123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservasi_event`
--

CREATE TABLE `reservasi_event` (
  `id_event_res` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tanggal_event` date DEFAULT NULL,
  `jam_event` time DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `jenis_event` varchar(50) DEFAULT NULL,
  `status_booking` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reservasi_event`
--

INSERT INTO `reservasi_event` (`id_event_res`, `id_pelanggan`, `tanggal_event`, `jam_event`, `no_telp`, `jenis_event`, `status_booking`, `created_at`) VALUES
(2, 13, '2026-02-07', '10:28:00', '4353464363', 'Birthday', 'pending', '2026-05-16 14:28:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservasi_room`
--

CREATE TABLE `reservasi_room` (
  `id_reservasi_room` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `tanggal_reservasi` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `id_room` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_pesanan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reservasi_room`
--

INSERT INTO `reservasi_room` (`id_reservasi_room`, `id_pelanggan`, `tanggal_reservasi`, `jam_mulai`, `bukti_pembayaran`, `id_room`, `deskripsi`, `status_pesanan`) VALUES
(8, 11, '2026-10-05', '20:55:00', '1778939715_room (2).jpg', 7, '', ''),
(9, 12, '2026-12-03', '09:20:00', '1778941209_room (2).jpg', 6, '', ''),
(10, 16, '2026-05-17', '00:57:00', '1779011868_Screenshot 2023-09-12 181847.png', 5, 'Lainnya', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `room`
--

CREATE TABLE `room` (
  `id_room` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `nama_area` varchar(250) NOT NULL,
  `kapasitas` varchar(100) NOT NULL,
  `gambar` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `room`
--

INSERT INTO `room` (`id_room`, `id_admin`, `nama_area`, `kapasitas`, `gambar`, `status`) VALUES
(5, NULL, 'Keluarga', '8', '1778903672_room (1).jpg', 'Dipesan'),
(6, NULL, 'ekonomi', '5', '1778903644_room (3).jpg', 'Tersedia'),
(7, NULL, 'Bersama', '7', '1778903777_room (2).jpg', 'Dipesan'),
(9, NULL, 'Elit', '10', '1778987752_Screenshot 2024-09-23 105437.png', 'Tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `staff`
--

CREATE TABLE `staff` (
  `id_staff` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_staff` enum('manager','admin') NOT NULL,
  `status_akun` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `staff`
--

INSERT INTO `staff` (`id_staff`, `username`, `password`, `role_staff`, `status_akun`) VALUES
(20, 'manager', '$2y$10$wrHvKkxBfsVs1Mr1AFlJo.3U0T3A3Gl.1FNMsgbH9XORjsajnv/Xq', 'manager', 'aktif'),
(22, 'admin2', '$2y$10$GJPFAjtIyt4iHoak.oPhLun/w0h3PmFC1hXczNvVxoGcWddC2bfi6', 'admin', 'Aktif'),
(23, 'ifan', '$2y$10$L1QU5gtx14NzMfyC6j7Ky.I0ymMnZG2R7pibGPhifWnSTUBNCx23a', 'admin', 'Aktif'),
(24, 'akbar', '$2y$10$OT.z1GeBhIvZKvc0RC5tHOo/tN60NL1m/.IEvEH1jOhm7AYlX0iyy', 'admin', 'Pending'),
(25, 'iqbal', '$2y$10$4eDrfKgluPdj7O3Vnw7tXu7gtFJgH5PkRoOGFO2NnEZx.m9GidIYC', 'manager', 'Pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `testimoni`
--

CREATE TABLE `testimoni` (
  `id_testimoni` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `id_pelanggan` int(100) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `status` enum('pending','tampilkan') DEFAULT 'pending',
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `testimoni`
--

INSERT INTO `testimoni` (`id_testimoni`, `id_admin`, `nama`, `id_pelanggan`, `no_telp`, `pesan`, `rating`, `status`, `tanggal`) VALUES
(4, NULL, 'akbar', 10, '109209123', 'lajlskjd', 5, 'tampilkan', '2026-05-16'),
(5, NULL, 'alskdjf', 14, '12321as', 'sdf', 4, 'pending', '2026-05-17');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_staff` (`id_staff`);

--
-- Indeks untuk tabel `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id_blog`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id_gallery`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`id_manager`),
  ADD KEY `id_staff` (`id_staff`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `reservasi_event`
--
ALTER TABLE `reservasi_event`
  ADD PRIMARY KEY (`id_event_res`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indeks untuk tabel `reservasi_room`
--
ALTER TABLE `reservasi_room`
  ADD PRIMARY KEY (`id_reservasi_room`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indeks untuk tabel `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id_room`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indeks untuk tabel `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id_staff`);

--
-- Indeks untuk tabel `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id_testimoni`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `blog`
--
ALTER TABLE `blog`
  MODIFY `id_blog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id_gallery` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `manager`
--
ALTER TABLE `manager`
  MODIFY `id_manager` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `reservasi_event`
--
ALTER TABLE `reservasi_event`
  MODIFY `id_event_res` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `reservasi_room`
--
ALTER TABLE `reservasi_room`
  MODIFY `id_reservasi_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `room`
--
ALTER TABLE `room`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `staff`
--
ALTER TABLE `staff`
  MODIFY `id_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id_testimoni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
