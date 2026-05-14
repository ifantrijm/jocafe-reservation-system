-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Bulan Mei 2026 pada 10.08
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
(1, NULL, 'weeding', 'Nuansa Trasisional', '1777619234_hyper_nawab-lonely-5251775_1920.jpg', '2026-05-01 02:07:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `gallery`
--

CREATE TABLE `gallery` (
  `id_gallery` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `kategori` enum('makanan','minuman') DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `manager`
--

CREATE TABLE `manager` (
  `id_manager` int(11) NOT NULL,
  `id_staff` int(11) DEFAULT NULL,
  `id_statistik` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `id_admin`, `nama_item`, `kategori`, `deskripsi`, `harga`, `gambar`) VALUES
(1, NULL, 'esteh', 'minuman', 'esteh pandang', 300000.00, '1777619152_teh.jpg');

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
(7, 'geg', 'geg@gaksbas', '84327984');

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

-- --------------------------------------------------------

--
-- Struktur dari tabel `reservasi_room`
--

CREATE TABLE `reservasi_room` (
  `id_reservasi_room` int(11) NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `tanggal_reservasi` date DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `id_room` int(11) NOT NULL,
  `nama_event` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reservasi_room`
--

INSERT INTO `reservasi_room` (`id_reservasi_room`, `id_pelanggan`, `tanggal_reservasi`, `jam_mulai`, `jam_selesai`, `bukti_pembayaran`, `id_room`, `nama_event`, `deskripsi`) VALUES
(5, 7, '2026-05-14', '10:00:00', '12:00:00', '1778745758_Screenshot (34).png', 5, '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `room`
--

CREATE TABLE `room` (
  `id_room` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `id_detail_reservasi` int(11) DEFAULT NULL,
  `nama_area` varchar(250) NOT NULL,
  `kapasitas` varchar(100) NOT NULL,
  `gambar` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `room`
--

INSERT INTO `room` (`id_room`, `id_admin`, `id_detail_reservasi`, `nama_area`, `kapasitas`, `gambar`, `status`) VALUES
(5, NULL, NULL, 'kjjkj', '87', '1777528783_dfd_real.png', 'Dipesan'),
(6, NULL, NULL, 'ekonomi', '50', '1777529076_WhatsApp Image 2026-02-09 at 18.26.07.jpeg', 'Tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `staff`
--

CREATE TABLE `staff` (
  `id_staff` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_staff` enum('manager','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `staff`
--

INSERT INTO `staff` (`id_staff`, `username`, `password`, `role_staff`) VALUES
(1, 'asd', '$2y$10$QpDk8cIpQbaDmiAia7OhCO1.nGZmmbE3iq2DLXWGaRqCefq/wNoiy', 'admin'),
(2, 'manajer', '$2y$10$Kd9W8YuQzIvvKCl4312XMugBNiqmONQp6BWvawzLhBDMFiJvtzmJ.', 'manager'),
(3, 'ifan', '$2y$10$Qrp/y9rrMYKTmCdzQj76xen9LEwDIPnhUDw3sxOJB/lOls.7PaXtW', 'admin'),
(4, 'rio', '$2y$10$7hnT8KIknkltQ588eGR98.ZuVKRqYNtBiyzj5QZnrLLMEEi7bFVKW', 'admin'),
(5, 'akbar', '$2y$10$lv7bcureacfEsR3/ofNOV.sjEkAe2gmrsdwodf85JFnks5rswbiqm', 'manager'),
(6, 'ipan', '$2y$10$HdwUX7Kvhow1g/uuVzx.FeCDLt.FO5yUBUsOvXGWALCQ1k3LLBXIC', 'admin'),
(7, 'dapa', '$2y$10$U25Q35plEr.yFBb8L1KtjuWjwAsj9Ts0GUrwykz0tztzRAl.Ew3Ey', 'admin'),
(8, 'romli', '$2y$10$1Arc6SPfevWdDSjXqvcKF.vt0ecd/EZ9cx.6t1MsFybbe6sDQaNIG', 'manager'),
(9, 'doni', '$2y$10$9p44HGQI.HjAPhrnBuSzdeDxg9LxLpRqe7uzUazKbLeqtz9Bc3976', 'admin'),
(10, 'ayat', '$2y$10$lOzAhnZXdw0tqkrLBpP.M.fVkXycpiNdJPJHXnVus5esSKcmaL6yW', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `statistik`
--

CREATE TABLE `statistik` (
  `id_statistik` int(11) NOT NULL,
  `id_form_reservasi` int(100) NOT NULL,
  `total_reservasi_room` int(11) DEFAULT 0,
  `total_reservasi_event` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD KEY `id_staff` (`id_staff`),
  ADD KEY `id_statistik` (`id_statistik`);

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
-- Indeks untuk tabel `statistik`
--
ALTER TABLE `statistik`
  ADD PRIMARY KEY (`id_statistik`),
  ADD KEY `id_form_reservasi` (`id_form_reservasi`);

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
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `blog`
--
ALTER TABLE `blog`
  MODIFY `id_blog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id_gallery` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `manager`
--
ALTER TABLE `manager`
  MODIFY `id_manager` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `reservasi_event`
--
ALTER TABLE `reservasi_event`
  MODIFY `id_event_res` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `reservasi_room`
--
ALTER TABLE `reservasi_room`
  MODIFY `id_reservasi_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `room`
--
ALTER TABLE `room`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `staff`
--
ALTER TABLE `staff`
  MODIFY `id_staff` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `statistik`
--
ALTER TABLE `statistik`
  MODIFY `id_statistik` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `testimoni`
--
ALTER TABLE `testimoni`
  MODIFY `id_testimoni` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Ketidakleluasaan untuk tabel `manager`
--
ALTER TABLE `manager`
  ADD CONSTRAINT `manager_ibfk_1` FOREIGN KEY (`id_staff`) REFERENCES `staff` (`id_staff`) ON DELETE CASCADE,
  ADD CONSTRAINT `manager_ibfk_2` FOREIGN KEY (`id_statistik`) REFERENCES `statistik` (`id_statistik`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`);

--
-- Ketidakleluasaan untuk tabel `reservasi_event`
--
ALTER TABLE `reservasi_event`
  ADD CONSTRAINT `reservasi_event_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reservasi_room`
--
ALTER TABLE `reservasi_room`
  ADD CONSTRAINT `reservasi_room_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`id_detail_reservasi`) REFERENCES `detail_reservasi` (`id_detail_reservasi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `statistik`
--
ALTER TABLE `statistik`
  ADD CONSTRAINT `statistik_ibfk_1` FOREIGN KEY (`id_form_reservasi`) REFERENCES `reservasi_room` (`id_reservasi_room`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `testimoni`
--
ALTER TABLE `testimoni`
  ADD CONSTRAINT `testimoni_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`),
  ADD CONSTRAINT `testimoni_ibfk_2` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
