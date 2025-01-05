-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 29, 2024 at 05:42 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ippl`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Hadir','Sakit','Alfa','Dispensasi') NOT NULL DEFAULT 'Alfa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `jadwal_id`, `mahasiswa_id`, `waktu`, `created_at`, `status`) VALUES
(1, 15, 3, '2024-12-29 10:32:38', '2024-12-29 03:32:38', 'Hadir'),
(2, 15, 2, '2024-12-29 10:48:18', '2024-12-29 03:48:18', 'Hadir');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `matakuliah_id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(50) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_qr_timestamp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`id`, `matakuliah_id`, `dosen_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `qr_code`, `created_at`, `last_qr_timestamp`) VALUES
(1, 2, 1, 'Senin', '08:00:00', '10:30:00', 'Lab 301', 'QR_6770b13f5a447_1735438655', '2024-12-29 02:00:57', NULL),
(2, 2, 1, 'Senin', '13:00:00', '15:30:00', 'Lab 302', 'QR002', '2024-12-29 02:00:57', NULL),
(3, 1, 1, 'Senin', '08:00:00', '10:30:00', 'Lab 301', '', '2024-12-29 02:45:00', NULL),
(4, 2, 5, 'Senin', '13:00:00', '15:30:00', 'Lab 302', '', '2024-12-29 02:45:00', NULL),
(5, 3, 1, 'Selasa', '08:00:00', '10:30:00', 'Lab 303', '', '2024-12-29 02:45:00', NULL),
(6, 1, 5, 'Selasa', '13:00:00', '15:30:00', 'Lab 304', '', '2024-12-29 02:45:00', NULL),
(7, 2, 1, 'Rabu', '08:00:00', '10:30:00', 'Lab 301', '', '2024-12-29 02:45:00', NULL),
(8, 3, 5, 'Rabu', '13:00:00', '15:30:00', 'Lab 302', '', '2024-12-29 02:45:00', NULL),
(9, 1, 1, 'Kamis', '08:00:00', '10:30:00', 'Lab 303', '', '2024-12-29 02:45:00', NULL),
(10, 2, 5, 'Kamis', '13:00:00', '15:30:00', 'Lab 304', '', '2024-12-29 02:45:00', NULL),
(11, 3, 1, 'Jumat', '08:00:00', '10:30:00', 'Lab 301', '', '2024-12-29 02:45:00', NULL),
(12, 1, 5, 'Jumat', '13:00:00', '15:30:00', 'Lab 302', '', '2024-12-29 02:45:00', NULL),
(13, 2, 1, 'Sabtu', '08:00:00', '10:30:00', 'Lab 303', '', '2024-12-29 02:45:00', NULL),
(14, 3, 5, 'Sabtu', '13:00:00', '15:30:00', 'Lab 304', '', '2024-12-29 02:45:00', NULL),
(15, 1, 1, 'Minggu', '08:00:00', '10:30:00', 'Lab 301', '1735441928', '2024-12-29 02:45:00', 1735444030),
(16, 2, 1, 'Minggu', '13:00:00', '15:30:00', 'Lab 302', '', '2024-12-29 02:45:00', 1735445749);

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `jadwal_id` int(11) NOT NULL,
  `semester` enum('1','2','3','4','5','6','7','8') NOT NULL,
  `tahun_ajaran` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `mahasiswa_id`, `jadwal_id`, `semester`, `tahun_ajaran`, `created_at`) VALUES
(1, 2, 1, '1', '2023/2024', '2024-12-29 02:45:00'),
(2, 2, 2, '1', '2023/2024', '2024-12-29 02:45:00'),
(3, 2, 3, '1', '2023/2024', '2024-12-29 02:45:00'),
(4, 2, 4, '1', '2023/2024', '2024-12-29 02:45:00'),
(5, 2, 5, '1', '2023/2024', '2024-12-29 02:45:00'),
(6, 2, 6, '1', '2023/2024', '2024-12-29 02:45:00'),
(7, 2, 7, '1', '2023/2024', '2024-12-29 02:45:00'),
(8, 2, 8, '1', '2023/2024', '2024-12-29 02:45:00'),
(9, 2, 15, '1', '2023/2024', '2024-12-29 02:45:00'),
(10, 2, 16, '1', '2023/2024', '2024-12-29 02:45:00'),
(16, 3, 9, '1', '2023/2024', '2024-12-29 02:45:00'),
(17, 3, 10, '1', '2023/2024', '2024-12-29 02:45:00'),
(18, 3, 11, '1', '2023/2024', '2024-12-29 02:45:00'),
(19, 3, 12, '1', '2023/2024', '2024-12-29 02:45:00'),
(20, 3, 13, '1', '2023/2024', '2024-12-29 02:45:00'),
(21, 3, 14, '1', '2023/2024', '2024-12-29 02:45:00'),
(22, 3, 15, '1', '2023/2024', '2024-12-29 02:45:00'),
(23, 3, 16, '1', '2023/2024', '2024-12-29 02:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `matakuliah`
--

CREATE TABLE `matakuliah` (
  `id` int(11) NOT NULL,
  `kode_mk` varchar(20) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `matakuliah`
--

INSERT INTO `matakuliah` (`id`, `kode_mk`, `nama_mk`, `sks`) VALUES
(1, 'MK001', 'Pemrograman Web', 3),
(2, 'MK002', 'Basis Data', 3),
(3, 'MK003', 'Algoritma dan Pemrograman', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','dosen') NOT NULL,
  `nim` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `nim`) VALUES
(1, 'Fahmi Huzen', 'fahmihuzen', 'fahmi123', 'dosen', NULL),
(2, 'Alfiatul Nabila', '111111', 'alfiatul123', 'mahasiswa', '111111'),
(3, 'Muhammad Ardhito', '222222', 'ardhito123', 'mahasiswa', '222222'),
(4, 'Admin Sistem', 'admin', 'admin', '', NULL),
(5, 'Dosen Contoh', 'dosen1', 'dosen1', 'dosen', NULL),
(6, 'Mahasiswa Contoh', 'mhs1', 'mahasiswa', 'mahasiswa', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_id` (`jadwal_id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matakuliah_id` (`matakuliah_id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `jadwal_id` (`jadwal_id`);

--
-- Indexes for table `matakuliah`
--
ALTER TABLE `matakuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mk` (`kode_mk`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `matakuliah`
--
ALTER TABLE `matakuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`matakuliah_id`) REFERENCES `matakuliah` (`id`),
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `krs_ibfk_2` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
