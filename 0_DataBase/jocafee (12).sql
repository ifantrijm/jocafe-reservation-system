-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2026 at 06:43 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `id_staff` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `id_staff`) VALUES
(8, 22),
(9, 23),
(10, 24);

-- --------------------------------------------------------

--
-- Table structure for table `blog`
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
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id_blog`, `id_admin`, `judul`, `isi`, `gambar`, `tanggal`) VALUES
(1, NULL, 'weeding', 'Nuansa Trasisional', '1779621766_Screenshot 2026-05-24 182203.png', '2026-05-24 06:22:46'),
(3, NULL, 'Berbuka Bersama', 'Ramadhan', '1779621842_Screenshot 2026-05-24 182328.png', '2026-05-24 06:24:02');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
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
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id_gallery`, `id_admin`, `gambar`, `kategori`, `keterangan`, `tanggal`) VALUES
(1, NULL, '1778999447_hiking.jpg', 'room', 'Wisuda', '2026-05-17'),
(2, NULL, '1779170383_IMG_1621.JPG.jpeg', 'room', 'Yearbook', '2026-05-19'),
(3, NULL, '1779248675_2.jpg', 'event', 'yearbook', '2026-05-20'),
(4, NULL, '1779326905_songa.jpg', 'menu', 'tracveling', '2026-05-21'),
(5, NULL, '1779677593_Screenshot 2026-05-24 182203.png', 'event', 'Wedding', '2025-01-28');

-- --------------------------------------------------------

--
-- Table structure for table `manager`
--

CREATE TABLE `manager` (
  `id_manager` int(11) NOT NULL,
  `id_staff` int(11) DEFAULT NULL,
  `id_statistik` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager`
--

INSERT INTO `manager` (`id_manager`, `id_staff`, `id_statistik`) VALUES
(4, 20, NULL),
(5, 25, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
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
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `id_admin`, `nama_item`, `kategori`, `deskripsi`, `harga`, `is_bestseller`, `gambar`) VALUES
(1, NULL, 'Cah Kangkung', 'makanan', 'Tradisional Food', 12000.00, 0, '1779620590_CAH_KANGKUNG.jpeg'),
(2, NULL, 'Bihun Goreng', 'makanan', 'Dengan Cita Rasa', 15000.00, 1, '1779620542_BIHUN_GORENG.jpeg'),
(4, NULL, 'Nasgor Jawa', 'makanan', 'Dengan Rempah Trasional', 17000.00, 1, '1779620296_NASGOR_JAWA.jpeg'),
(6, NULL, 'Coffe Martillo', 'minuman', ' Kopi dengan citarasa buah yang manis', 25000.00, 0, '1779621109_IMG_3526.jpg'),
(7, NULL, 'Sexy Lips', 'minuman', 'minuman dengan rasa manis sedikit pedas seperti mulut tetangga', 20000.00, 0, '1779621538_IMG_5929.jpg'),
(8, NULL, 'Coffe Latte Hot', 'minuman', 'Kopi dengan tekstur yang sangat lembut dan rasa yang lebih milky', 20000.00, 1, '1779621669_IMG_6371.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `email`, `telepon`) VALUES
(1, 'Ahmad Dani', '', '085853473633'),
(2, 'Akbar', '', ' 085336705008'),
(3, 'rido', '', '123123'),
(4, 'Rio', '', '085648580238'),
(5, 'Leon', '', '085879134998'),
(6, 'anwar', '', '2345234234'),
(7, 'rio', '', '0858791349834'),
(8, 'leon', '', '9890182093823'),
(9, 'opik', '', '987127309809123'),
(10, 'irvan', '', '9813241239234'),
(11, 'arnad', '', '129389018203');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi_event`
--

CREATE TABLE `reservasi_event` (
  `id_event_res` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tanggal_event` date DEFAULT NULL,
  `jam_event` time DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `jenis_event` varchar(50) DEFAULT NULL,
  `status_booking` enum('on progres','confirmed','cancelled','selesai') DEFAULT 'on progres',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi_event`
--

INSERT INTO `reservasi_event` (`id_event_res`, `id_pelanggan`, `tanggal_event`, `jam_event`, `no_telp`, `jenis_event`, `status_booking`, `created_at`) VALUES
(1, 2, '2026-05-31', '19:06:00', ' 085336705008', 'Prewedding', 'selesai', '2026-05-23 15:06:49'),
(3, 1, '2026-05-31', '12:34:00', '085853473633', 'Prewedding', 'selesai', '2026-05-24 05:34:23'),
(4, 1, '2026-05-31', '12:37:00', '085853473633', 'Birthday', 'selesai', '2026-05-24 05:36:50'),
(5, 1, '2026-05-31', '12:41:00', '085853473633', 'Meeting', 'selesai', '2026-05-24 05:41:24'),
(6, 4, '2026-05-31', '19:42:00', '085648580238', 'Prewedding', 'selesai', '2026-05-24 05:43:07'),
(7, 5, '2026-05-31', '20:53:00', '085879134998', 'Meeting', 'selesai', '2026-05-24 05:53:48'),
(8, 6, '2026-05-31', '20:01:00', '2345234234', 'Birthday', 'selesai', '2026-05-24 06:01:42'),
(9, 7, '2026-05-31', '13:11:00', '0858791349834', 'Meeting', 'selesai', '2026-05-24 06:11:20'),
(10, 7, '2026-05-31', '19:01:00', '0858791349834', 'Meeting', 'on progres', '2026-05-24 12:01:48'),
(11, 1, '2026-05-31', '20:02:00', '085853473633', 'Prewedding', 'on progres', '2026-05-24 12:03:14');

-- --------------------------------------------------------

--
-- Table structure for table `reservasi_room`
--

CREATE TABLE `reservasi_room` (
  `id_reservasi_room` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `tanggal_reservasi` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `id_room` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status_pesanan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservasi_room`
--

INSERT INTO `reservasi_room` (`id_reservasi_room`, `id_pelanggan`, `tanggal_reservasi`, `jam_mulai`, `jam_selesai`, `bukti_pembayaran`, `id_room`, `deskripsi`, `status_pesanan`) VALUES
(1, 1, '2026-05-24', '21:58:00', '00:00:00', '1779548368_Screenshot 2026-05-23 215913.png', 5, 'Lainnya', 'Selesai'),
(2, 1, '2026-05-23', '22:49:00', '00:00:00', '1779551398_Screenshot 2026-05-23 215913.png', 7, 'Lainnya', 'Selesai'),
(3, 1, '2026-05-25', '13:16:00', '00:00:00', '1779603411_Screenshot 2026-05-23 215913.png', 5, 'Lainnya', 'Selesai'),
(4, 1, '2026-05-26', '13:29:00', '00:00:00', '1779604204_Screenshot 2026-05-23 215913.png', 6, 'Meeting', 'Selesai'),
(5, 1, '2026-05-31', '21:05:00', '22:05:00', '1779627975_Screenshot 2023-09-12 181847.png', 7, 'Anniversary', 'Selesai'),
(6, 8, '2026-05-25', '20:48:00', '20:50:00', '1779630517_Screenshot 2026-05-23 215913.png', 7, 'Komunitas', 'Selesai'),
(7, 9, '2026-05-24', '20:50:00', '20:52:00', '1779630658_Screenshot 2023-09-12 181847.png', 9, 'Private Event', 'Selesai'),
(8, 10, '2026-05-24', '21:00:00', '21:02:00', '1779631245_bg.png', 9, 'Family Gathering', 'Selesai'),
(9, 1, '2026-05-25', '19:11:00', '21:11:00', '1779631922_bg.png', 5, 'Meeting', ''),
(10, 11, '2026-05-24', '21:12:00', '21:13:00', '1779631965_Screenshot 2023-09-12 181847.png', 6, 'Birthday', 'Selesai'),
(11, 1, '2026-05-25', '07:50:00', '13:50:00', '1779670240_img.jpeg', 7, 'Anniversary', 'Selesai'),
(12, 7, '2026-05-27', '19:52:00', '21:52:00', '1779800007_Screenshot 2026-05-23 215913.png', 6, 'Private Event', 'Selesai'),
(13, 1, '2026-05-28', '13:40:00', '14:40:00', '1779900071_IMG_5929.HEIC', 7, 'Meeting', '');

-- --------------------------------------------------------

--
-- Table structure for table `room`
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
-- Dumping data for table `room`
--

INSERT INTO `room` (`id_room`, `id_admin`, `nama_area`, `kapasitas`, `gambar`, `status`) VALUES
(5, NULL, 'Keluarga', '8', '1778903672_room (1).jpg', 'Dipesan'),
(6, NULL, 'ekonomi', '5', '1778903644_room (3).jpg', 'Tersedia'),
(7, NULL, 'Bersama', '7', '1778903777_room (2).jpg', 'Dipesan'),
(9, NULL, 'Elit', '10', '1778987752_Screenshot 2024-09-23 105437.png', 'Tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id_staff` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_staff` enum('manager','admin') NOT NULL,
  `status_akun` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id_staff`, `username`, `password`, `role_staff`, `status_akun`) VALUES
(20, 'manager', '$2y$10$wrHvKkxBfsVs1Mr1AFlJo.3U0T3A3Gl.1FNMsgbH9XORjsajnv/Xq', 'manager', 'aktif'),
(22, 'admin2', '$2y$10$GJPFAjtIyt4iHoak.oPhLun/w0h3PmFC1hXczNvVxoGcWddC2bfi6', 'admin', 'Aktif'),
(23, 'ifan', '$2y$10$L1QU5gtx14NzMfyC6j7Ky.I0ymMnZG2R7pibGPhifWnSTUBNCx23a', 'admin', 'Aktif'),
(24, 'akbar', '$2y$10$OT.z1GeBhIvZKvc0RC5tHOo/tN60NL1m/.IEvEH1jOhm7AYlX0iyy', 'admin', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `testimoni`
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
-- Dumping data for table `testimoni`
--

INSERT INTO `testimoni` (`id_testimoni`, `id_admin`, `nama`, `id_pelanggan`, `no_telp`, `pesan`, `rating`, `status`, `tanggal`) VALUES
(4, NULL, 'akbar', 10, '109209123', 'lajlskjd', 5, 'tampilkan', '2026-05-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `id_staff` (`id_staff`);

--
-- Indexes for table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id_blog`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id_gallery`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `manager`
--
ALTER TABLE `manager`
  ADD PRIMARY KEY (`id_manager`),
  ADD KEY `id_staff` (`id_staff`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `reservasi_event`
--
ALTER TABLE `reservasi_event`
  ADD PRIMARY KEY (`id_event_res`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `reservasi_room`
--
ALTER TABLE `reservasi_room`
  ADD PRIMARY KEY (`id_reservasi_room`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id_room`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id_staff`);

--
-- Indexes for table `testimoni`
--
ALTER TABLE `testimoni`
  ADD PRIMARY KEY (`id_testimoni`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `blog`
--
ALTER TABLE `blog`
  MODIFY `id_blog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id_gallery` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `manager`
--
ALTER TABLE `manager`
  MODIFY `id_manager` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reservasi_event`
--
ALTER TABLE `reservasi_event`
  MODIFY `id_event_res` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `reservasi_room`
--
ALTER TABLE `reservasi_room`
  MODIFY `id_reservasi_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id_testimoni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`) ON DELETE CASCADE;

--
-- Constraints for table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `blog_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
