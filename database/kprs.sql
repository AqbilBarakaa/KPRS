-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 10:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kprs`
--

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) NOT NULL,
  `nidn` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `jabatan` enum('Dosen','Kaprodi') DEFAULT 'Dosen',
  `prodi` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id`, `nidn`, `nama`, `password`, `email`, `foto`, `jabatan`, `prodi`, `created_at`) VALUES
(1, '12341', 'Dr. Rika Yunitarini ST., MT.', '123', 'rika@univ.ac.id', 'DOSEN_1234_1764415221.png', 'Dosen', 'Teknik Informatika', '2025-11-27 07:12:08'),
(2, '12351', 'Dr. Fika Hastarita Rachman, S.T., M.Eng.', '123', 'fika@univ.ac.id', 'DOSEN_1235_1764415228.png', 'Kaprodi', 'Teknik Informatika', '2025-11-27 07:12:08'),
(3, '12352', 'Dr. Budi Dwi Satoto, S.T., M.Kom', '$2y$10$pee33w1361NaggfMl6FR8.QjSUNc/8GXX5..5ESImm.ZTHVxgRmUu', 'budi@univ.ac.id', 'DOSEN_1236_1764419746.jpeg', 'Kaprodi', 'Sistem Informasi', '2025-11-29 12:35:46'),
(4, '12342', 'Achmad Jauhari S.T., M.Kom.', '$2y$10$.5yZ8F1TjgtUZFvLQHT0h.Wn/xC6.MPUHVcfMbjAziipUOeS4G5ka', 'achmad@univ.ac.id', 'DOSEN_12341_1764420980.jpeg', 'Dosen', 'Teknik Informatika', '2025-11-29 12:56:20'),
(5, '12343', 'Mula\'ab S.Si., M.Kom', '$2y$10$yhI7NBty/4K5.JY0fFlnHuvTUAt7.z9TOVURloAi6xFTgFQrbsFp2', 'mulaab@univ.ac.id', 'DOSEN_12343_1764421117.jpeg', 'Dosen', 'Teknik Informatika', '2025-11-29 12:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `mata_kuliah_id` int(11) DEFAULT NULL,
  `kode_kelas` varchar(10) DEFAULT NULL,
  `nama_kelas` varchar(50) DEFAULT NULL,
  `ruangan` varchar(20) DEFAULT NULL,
  `dosen_pengampu_id` int(11) DEFAULT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `kuota` int(11) DEFAULT NULL,
  `terisi` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `mata_kuliah_id`, `kode_kelas`, `nama_kelas`, `ruangan`, `dosen_pengampu_id`, `hari`, `jam_mulai`, `jam_selesai`, `kuota`, `terisi`, `created_at`) VALUES
(1, 1, 'A', 'IF 1A', 'F206', 5, 'Jumat', '07:00:00', '09:30:00', 40, 29, '2025-12-01 12:39:38'),
(2, 1, 'B', 'IF 1B', 'F401', 4, 'Rabu', '12:30:00', '15:00:00', 40, 14, '2025-12-01 12:39:38'),
(3, 1, 'C', 'IF 1C', 'F101', 1, 'Jumat', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(4, 2, 'A', 'IF 1A', 'F310', 4, 'Kamis', '12:30:00', '15:00:00', 40, 12, '2025-12-01 12:39:38'),
(5, 2, 'B', 'IF 1B', 'F406', 1, 'Selasa', '07:00:00', '09:30:00', 40, 33, '2025-12-01 12:39:38'),
(6, 2, 'C', 'IF 1C', 'F204', 3, 'Kamis', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(7, 3, 'A', 'IF 1A', 'F310', 2, 'Rabu', '12:30:00', '15:00:00', 40, 40, '2025-12-01 12:39:38'),
(8, 3, 'B', 'IF 1B', 'F110', 2, 'Kamis', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(9, 3, 'C', 'IF 1C', 'F301', 4, 'Selasa', '09:30:00', '12:00:00', 40, 26, '2025-12-01 12:39:38'),
(10, 4, 'A', 'IF 1A', 'F306', 4, 'Rabu', '15:30:00', '18:00:00', 40, 12, '2025-12-01 12:39:38'),
(11, 4, 'B', 'IF 1B', 'F110', 4, 'Jumat', '09:30:00', '12:00:00', 40, 12, '2025-12-01 12:39:38'),
(12, 4, 'C', 'IF 1C', 'F208', 3, 'Rabu', '07:00:00', '09:30:00', 40, 34, '2025-12-01 12:39:38'),
(13, 4, 'D', 'IF 1D', 'F202', 3, 'Selasa', '09:30:00', '12:00:00', 40, 13, '2025-12-01 12:39:38'),
(14, 5, 'A', 'IF 1A', 'F103', 5, 'Rabu', '09:30:00', '12:00:00', 40, 37, '2025-12-01 12:39:38'),
(15, 5, 'B', 'IF 1B', 'F204', 1, 'Kamis', '09:30:00', '12:00:00', 40, 35, '2025-12-01 12:39:38'),
(16, 5, 'C', 'IF 1C', 'F203', 1, 'Jumat', '09:30:00', '12:00:00', 40, 26, '2025-12-01 12:39:38'),
(17, 6, 'A', 'IF 1A', 'F107', 1, 'Kamis', '07:00:00', '09:30:00', 40, 32, '2025-12-01 12:39:38'),
(18, 6, 'B', 'IF 1B', 'F405', 3, 'Rabu', '09:30:00', '12:00:00', 40, 28, '2025-12-01 12:39:38'),
(19, 6, 'C', 'IF 1C', 'F408', 5, 'Jumat', '12:30:00', '15:00:00', 40, 23, '2025-12-01 12:39:38'),
(20, 7, 'A', 'IF 1A', 'F205', 4, 'Kamis', '09:30:00', '12:00:00', 40, 26, '2025-12-01 12:39:38'),
(21, 7, 'B', 'IF 1B', 'F306', 4, 'Jumat', '07:00:00', '09:30:00', 40, 31, '2025-12-01 12:39:38'),
(22, 7, 'C', 'IF 1C', 'F401', 5, 'Kamis', '15:30:00', '18:00:00', 40, 23, '2025-12-01 12:39:38'),
(23, 8, 'A', 'IF 1A', 'F202', 5, 'Rabu', '12:30:00', '15:00:00', 40, 20, '2025-12-01 12:39:38'),
(24, 8, 'B', 'IF 1B', 'F303', 4, 'Jumat', '07:00:00', '09:30:00', 40, 29, '2025-12-01 12:39:38'),
(25, 8, 'C', 'IF 1C', 'F202', 3, 'Jumat', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(26, 8, 'D', 'IF 1D', 'F302', 5, 'Senin', '15:30:00', '18:00:00', 40, 25, '2025-12-01 12:39:38'),
(27, 9, 'A', 'IF 2A', 'F302', 4, 'Rabu', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(28, 9, 'B', 'IF 2B', 'F204', 2, 'Kamis', '12:30:00', '15:00:00', 40, 31, '2025-12-01 12:39:38'),
(29, 9, 'C', 'IF 2C', 'F202', 5, 'Kamis', '07:00:00', '09:30:00', 40, 19, '2025-12-01 12:39:38'),
(30, 9, 'D', 'IF 2D', 'F101', 5, 'Rabu', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(31, 10, 'A', 'IF 2A', 'F401', 5, 'Senin', '09:30:00', '12:00:00', 40, 20, '2025-12-01 12:39:38'),
(32, 10, 'B', 'IF 2B', 'F209', 1, 'Selasa', '15:30:00', '18:00:00', 40, 12, '2025-12-01 12:39:38'),
(33, 10, 'C', 'IF 2C', 'F106', 2, 'Rabu', '07:00:00', '09:30:00', 40, 12, '2025-12-01 12:39:38'),
(34, 11, 'A', 'IF 2A', 'F407', 1, 'Selasa', '07:00:00', '09:30:00', 40, 33, '2025-12-01 12:39:38'),
(35, 11, 'B', 'IF 2B', 'F402', 4, 'Selasa', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(36, 11, 'C', 'IF 2C', 'F309', 1, 'Selasa', '09:30:00', '12:00:00', 40, 22, '2025-12-01 12:39:38'),
(37, 12, 'A', 'IF 2A', 'F302', 5, 'Senin', '15:30:00', '18:00:00', 40, 38, '2025-12-01 12:39:38'),
(38, 12, 'B', 'IF 2B', 'F307', 4, 'Selasa', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(39, 12, 'C', 'IF 2C', 'F203', 4, 'Selasa', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(40, 13, 'A', 'IF 2A', 'F402', 2, 'Kamis', '15:30:00', '18:00:00', 40, 24, '2025-12-01 12:39:38'),
(41, 13, 'B', 'IF 2B', 'F201', 1, 'Senin', '07:00:00', '09:30:00', 40, 35, '2025-12-01 12:39:38'),
(42, 13, 'C', 'IF 2C', 'F106', 1, 'Senin', '15:30:00', '18:00:00', 40, 39, '2025-12-01 12:39:38'),
(43, 13, 'D', 'IF 2D', 'F303', 2, 'Senin', '07:00:00', '09:30:00', 40, 24, '2025-12-01 12:39:38'),
(44, 14, 'A', 'IF 2A', 'F108', 4, 'Kamis', '09:30:00', '12:00:00', 40, 36, '2025-12-01 12:39:38'),
(45, 14, 'B', 'IF 2B', 'F301', 4, 'Selasa', '15:30:00', '18:00:00', 40, 25, '2025-12-01 12:39:38'),
(46, 14, 'C', 'IF 2C', 'F406', 2, 'Kamis', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(47, 15, 'A', 'IF 3A', 'F103', 2, 'Senin', '09:30:00', '12:00:00', 40, 26, '2025-12-01 12:39:38'),
(48, 15, 'B', 'IF 3B', 'F105', 5, 'Rabu', '09:30:00', '12:00:00', 40, 16, '2025-12-01 12:39:38'),
(49, 15, 'C', 'IF 3C', 'F301', 2, 'Senin', '12:30:00', '15:00:00', 40, 14, '2025-12-01 12:39:38'),
(50, 15, 'D', 'IF 3D', 'F305', 3, 'Rabu', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(51, 16, 'A', 'IF 3A', 'F101', 5, 'Selasa', '15:30:00', '18:00:00', 40, 39, '2025-12-01 12:39:38'),
(52, 16, 'B', 'IF 3B', 'F205', 3, 'Selasa', '15:30:00', '18:00:00', 40, 18, '2025-12-01 12:39:38'),
(53, 16, 'C', 'IF 3C', 'F404', 5, 'Selasa', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(54, 17, 'A', 'IF 3A', 'F102', 5, 'Kamis', '09:30:00', '12:00:00', 40, 11, '2025-12-01 12:39:38'),
(55, 17, 'B', 'IF 3B', 'F303', 4, 'Rabu', '09:30:00', '12:00:00', 40, 12, '2025-12-01 12:39:38'),
(56, 17, 'C', 'IF 3C', 'F210', 3, 'Selasa', '12:30:00', '15:00:00', 40, 29, '2025-12-01 12:39:38'),
(57, 18, 'A', 'IF 3A', 'F104', 2, 'Kamis', '12:30:00', '15:00:00', 40, 40, '2025-12-01 12:39:38'),
(58, 18, 'B', 'IF 3B', 'F210', 3, 'Kamis', '15:30:00', '18:00:00', 40, 26, '2025-12-01 12:39:38'),
(59, 18, 'C', 'IF 3C', 'F308', 2, 'Selasa', '12:30:00', '15:00:00', 40, 14, '2025-12-01 12:39:38'),
(60, 19, 'A', 'IF 3A', 'F404', 4, 'Rabu', '07:00:00', '09:30:00', 40, 14, '2025-12-01 12:39:38'),
(61, 19, 'B', 'IF 3B', 'F402', 4, 'Senin', '12:30:00', '15:00:00', 40, 22, '2025-12-01 12:39:38'),
(62, 19, 'C', 'IF 3C', 'F304', 5, 'Selasa', '15:30:00', '18:00:00', 40, 35, '2025-12-01 12:39:38'),
(63, 20, 'A', 'IF 3A', 'F210', 5, 'Senin', '07:00:00', '09:30:00', 40, 28, '2025-12-01 12:39:38'),
(64, 20, 'B', 'IF 3B', 'F205', 1, 'Rabu', '12:30:00', '15:00:00', 40, 17, '2025-12-01 12:39:38'),
(65, 20, 'C', 'IF 3C', 'F308', 1, 'Kamis', '07:00:00', '09:30:00', 40, 12, '2025-12-01 12:39:38'),
(66, 21, 'A', 'IF 3A', 'F409', 1, 'Kamis', '15:30:00', '18:00:00', 40, 27, '2025-12-01 12:39:38'),
(67, 21, 'B', 'IF 3B', 'F107', 4, 'Senin', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(68, 21, 'C', 'IF 3C', 'F105', 3, 'Kamis', '09:30:00', '12:00:00', 40, 11, '2025-12-01 12:39:38'),
(69, 22, 'A', 'IF 3A', 'F102', 4, 'Kamis', '12:30:00', '15:00:00', 40, 29, '2025-12-01 12:39:38'),
(70, 22, 'B', 'IF 3B', 'F103', 5, 'Senin', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(71, 22, 'C', 'IF 3C', 'F401', 3, 'Senin', '12:30:00', '15:00:00', 40, 17, '2025-12-01 12:39:38'),
(72, 23, 'A', 'IF 3A', 'F304', 1, 'Senin', '07:00:00', '09:30:00', 40, 13, '2025-12-01 12:39:38'),
(73, 23, 'B', 'IF 3B', 'F405', 1, 'Selasa', '12:30:00', '15:00:00', 40, 10, '2025-12-01 12:39:38'),
(74, 23, 'C', 'IF 3C', 'F302', 1, 'Senin', '07:00:00', '09:30:00', 40, 26, '2025-12-01 12:39:38'),
(75, 24, 'A', 'IF 4A', 'F307', 3, 'Kamis', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(76, 24, 'B', 'IF 4B', 'F107', 5, 'Kamis', '09:30:00', '12:00:00', 40, 31, '2025-12-01 12:39:38'),
(77, 24, 'C', 'IF 4C', 'F306', 4, 'Kamis', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(78, 24, 'D', 'IF 4D', 'F210', 2, 'Kamis', '15:30:00', '18:00:00', 40, 13, '2025-12-01 12:39:38'),
(79, 25, 'A', 'IF 4A', 'F201', 2, 'Kamis', '07:00:00', '09:30:00', 40, 29, '2025-12-01 12:39:38'),
(80, 25, 'B', 'IF 4B', 'F206', 1, 'Selasa', '15:30:00', '18:00:00', 40, 14, '2025-12-01 12:39:38'),
(81, 25, 'C', 'IF 4C', 'F209', 4, 'Kamis', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(82, 25, 'D', 'IF 4D', 'F202', 3, 'Rabu', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(83, 26, 'A', 'IF 4A', 'F110', 5, 'Kamis', '09:30:00', '12:00:00', 40, 10, '2025-12-01 12:39:38'),
(84, 26, 'B', 'IF 4B', 'F407', 4, 'Rabu', '15:30:00', '18:00:00', 40, 23, '2025-12-01 12:39:38'),
(85, 26, 'C', 'IF 4C', 'F402', 1, 'Selasa', '12:30:00', '15:00:00', 40, 40, '2025-12-01 12:39:38'),
(86, 26, 'D', 'IF 4D', 'F304', 5, 'Selasa', '09:30:00', '12:00:00', 40, 21, '2025-12-01 12:39:38'),
(87, 27, 'A', 'IF 4A', 'F204', 4, 'Rabu', '09:30:00', '12:00:00', 40, 30, '2025-12-01 12:39:38'),
(88, 27, 'B', 'IF 4B', 'F305', 3, 'Selasa', '12:30:00', '15:00:00', 40, 22, '2025-12-01 12:39:38'),
(89, 27, 'C', 'IF 4C', 'F409', 5, 'Rabu', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(90, 28, 'A', 'IF 4A', 'F303', 2, 'Kamis', '15:30:00', '18:00:00', 40, 35, '2025-12-01 12:39:38'),
(91, 28, 'B', 'IF 4B', 'F406', 3, 'Selasa', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(92, 29, 'A', 'IF 4A', 'F308', 5, 'Kamis', '07:00:00', '09:30:00', 40, 36, '2025-12-01 12:39:38'),
(93, 29, 'B', 'IF 4B', 'F204', 5, 'Selasa', '09:30:00', '12:00:00', 40, 12, '2025-12-01 12:39:38'),
(94, 30, 'A', 'IF 4A', 'F206', 1, 'Selasa', '09:30:00', '12:00:00', 40, 35, '2025-12-01 12:39:38'),
(95, 30, 'B', 'IF 4B', 'F107', 1, 'Senin', '15:30:00', '18:00:00', 40, 28, '2025-12-01 12:39:38'),
(96, 30, 'C', 'IF 4C', 'F209', 1, 'Selasa', '07:00:00', '09:30:00', 40, 29, '2025-12-01 12:39:38'),
(97, 31, 'A', 'IF 4A', 'F208', 3, 'Rabu', '07:00:00', '09:30:00', 40, 31, '2025-12-01 12:39:38'),
(98, 31, 'B', 'IF 4B', 'F204', 3, 'Kamis', '12:30:00', '15:00:00', 40, 38, '2025-12-01 12:39:38'),
(99, 32, 'A', 'IF 4A', 'F408', 4, 'Rabu', '09:30:00', '12:00:00', 40, 29, '2025-12-01 12:39:38'),
(100, 32, 'B', 'IF 4B', 'F402', 5, 'Selasa', '09:30:00', '12:00:00', 40, 27, '2025-12-01 12:39:38'),
(101, 33, 'A', 'IF 4A', 'F207', 5, 'Senin', '12:30:00', '15:00:00', 40, 30, '2025-12-01 12:39:38'),
(102, 33, 'B', 'IF 4B', 'F103', 3, 'Rabu', '07:00:00', '09:30:00', 40, 38, '2025-12-01 12:39:38'),
(103, 33, 'C', 'IF 4C', 'F402', 5, 'Senin', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(104, 34, 'A', 'IF 4A', 'F404', 5, 'Selasa', '07:00:00', '09:30:00', 40, 32, '2025-12-01 12:39:38'),
(105, 34, 'B', 'IF 4B', 'F406', 1, 'Kamis', '07:00:00', '09:30:00', 40, 35, '2025-12-01 12:39:38'),
(106, 34, 'C', 'IF 4C', 'F206', 1, 'Selasa', '12:30:00', '15:00:00', 40, 12, '2025-12-01 12:39:38'),
(107, 35, 'A', 'IF 4A', 'F306', 5, 'Senin', '12:30:00', '15:00:00', 40, 16, '2025-12-01 12:39:38'),
(108, 35, 'B', 'IF 4B', 'F304', 1, 'Rabu', '09:30:00', '12:00:00', 40, 24, '2025-12-01 12:39:38'),
(109, 36, 'A', 'IF 5A', 'F407', 2, 'Selasa', '12:30:00', '15:00:00', 40, 40, '2025-12-01 12:39:38'),
(110, 36, 'B', 'IF 5B', 'F206', 2, 'Senin', '12:30:00', '15:00:00', 40, 32, '2025-12-01 12:39:38'),
(111, 36, 'C', 'IF 5C', 'F107', 2, 'Senin', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(112, 37, 'A', 'IF 5A', 'F205', 5, 'Kamis', '09:30:00', '12:00:00', 40, 18, '2025-12-01 12:39:38'),
(113, 37, 'B', 'IF 5B', 'F208', 3, 'Rabu', '12:30:00', '15:00:00', 40, 19, '2025-12-01 12:39:38'),
(114, 37, 'C', 'IF 5C', 'F302', 3, 'Rabu', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(115, 38, 'A', 'IF 5A', 'F309', 2, 'Selasa', '07:00:00', '09:30:00', 40, 20, '2025-12-01 12:39:38'),
(116, 38, 'B', 'IF 5B', 'F407', 3, 'Rabu', '15:30:00', '18:00:00', 40, 29, '2025-12-01 12:39:38'),
(117, 38, 'C', 'IF 5C', 'F107', 3, 'Rabu', '07:00:00', '09:30:00', 40, 17, '2025-12-01 12:39:38'),
(118, 38, 'D', 'IF 5D', 'F407', 2, 'Rabu', '15:30:00', '18:00:00', 40, 22, '2025-12-01 12:39:38'),
(119, 39, 'A', 'IF 5A', 'F305', 5, 'Rabu', '15:30:00', '18:00:00', 40, 24, '2025-12-01 12:39:38'),
(120, 39, 'B', 'IF 5B', 'F106', 2, 'Selasa', '09:30:00', '12:00:00', 40, 21, '2025-12-01 12:39:38'),
(121, 39, 'C', 'IF 5C', 'F103', 4, 'Senin', '07:00:00', '09:30:00', 40, 16, '2025-12-01 12:39:38'),
(122, 40, 'A', 'IF 5A', 'F409', 5, 'Senin', '12:30:00', '15:00:00', 40, 23, '2025-12-01 12:39:38'),
(123, 40, 'B', 'IF 5B', 'F101', 1, 'Selasa', '09:30:00', '12:00:00', 40, 29, '2025-12-01 12:39:38'),
(124, 40, 'C', 'IF 5C', 'F203', 3, 'Senin', '09:30:00', '12:00:00', 40, 26, '2025-12-01 12:39:38'),
(125, 40, 'D', 'IF 5D', 'F101', 5, 'Rabu', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(126, 41, 'A', 'IF 5A', 'F101', 4, 'Selasa', '15:30:00', '18:00:00', 40, 33, '2025-12-01 12:39:38'),
(127, 41, 'B', 'IF 5B', 'F404', 1, 'Selasa', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(128, 42, 'A', 'IF 5A', 'F302', 3, 'Senin', '07:00:00', '09:30:00', 40, 20, '2025-12-01 12:39:38'),
(129, 42, 'B', 'IF 5B', 'F206', 4, 'Senin', '09:30:00', '12:00:00', 40, 25, '2025-12-01 12:39:38'),
(130, 43, 'A', 'IF 5A', 'F405', 5, 'Senin', '12:30:00', '15:00:00', 40, 22, '2025-12-01 12:39:38'),
(131, 43, 'B', 'IF 5B', 'F306', 1, 'Selasa', '12:30:00', '15:00:00', 40, 30, '2025-12-01 12:39:38'),
(132, 44, 'A', 'IF 5A', 'F305', 2, 'Senin', '15:30:00', '18:00:00', 40, 32, '2025-12-01 12:39:38'),
(133, 44, 'B', 'IF 5B', 'F109', 3, 'Rabu', '12:30:00', '15:00:00', 40, 29, '2025-12-01 12:39:38'),
(134, 45, 'A', 'IF 5A', 'F310', 2, 'Senin', '15:30:00', '18:00:00', 40, 19, '2025-12-01 12:39:38'),
(135, 45, 'B', 'IF 5B', 'F303', 4, 'Selasa', '07:00:00', '09:30:00', 40, 37, '2025-12-01 12:39:38'),
(136, 46, 'A', 'IF 5A', 'F110', 1, 'Rabu', '09:30:00', '12:00:00', 40, 15, '2025-12-01 12:39:38'),
(137, 46, 'B', 'IF 5B', 'F308', 4, 'Kamis', '09:30:00', '12:00:00', 40, 28, '2025-12-01 12:39:38'),
(138, 46, 'C', 'IF 5C', 'F409', 5, 'Selasa', '15:30:00', '18:00:00', 40, 26, '2025-12-01 12:39:38'),
(139, 47, 'A', 'IF 5A', 'F307', 3, 'Rabu', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(140, 47, 'B', 'IF 5B', 'F408', 3, 'Selasa', '15:30:00', '18:00:00', 40, 16, '2025-12-01 12:39:38'),
(141, 48, 'A', 'IF 6A', 'F406', 3, 'Selasa', '12:30:00', '15:00:00', 40, 17, '2025-12-01 12:39:38'),
(142, 48, 'B', 'IF 6B', 'F206', 2, 'Rabu', '12:30:00', '15:00:00', 40, 35, '2025-12-01 12:39:38'),
(143, 49, 'A', 'IF 6A', 'F208', 3, 'Rabu', '09:30:00', '12:00:00', 40, 37, '2025-12-01 12:39:38'),
(144, 49, 'B', 'IF 6B', 'F203', 3, 'Senin', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(145, 50, 'A', 'IF 6A', 'F208', 3, 'Senin', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(146, 50, 'B', 'IF 6B', 'F301', 1, 'Kamis', '12:30:00', '15:00:00', 40, 28, '2025-12-01 12:39:38'),
(147, 50, 'C', 'IF 6C', 'F209', 4, 'Rabu', '09:30:00', '12:00:00', 40, 28, '2025-12-01 12:39:38'),
(148, 51, 'A', 'IF 6A', 'F310', 3, 'Kamis', '07:00:00', '09:30:00', 40, 12, '2025-12-01 12:39:38'),
(149, 51, 'B', 'IF 6B', 'F401', 3, 'Selasa', '12:30:00', '15:00:00', 40, 33, '2025-12-01 12:39:38'),
(150, 52, 'A', 'IF 6A', 'F209', 5, 'Selasa', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(151, 52, 'B', 'IF 6B', 'F206', 5, 'Kamis', '12:30:00', '15:00:00', 40, 10, '2025-12-01 12:39:38'),
(152, 52, 'C', 'IF 6C', 'F406', 3, 'Senin', '15:30:00', '18:00:00', 40, 26, '2025-12-01 12:39:38'),
(153, 53, 'A', 'IF 6A', 'F401', 1, 'Rabu', '12:30:00', '15:00:00', 40, 31, '2025-12-01 12:39:38'),
(154, 53, 'B', 'IF 6B', 'F208', 3, 'Rabu', '15:30:00', '18:00:00', 40, 19, '2025-12-01 12:39:38'),
(155, 53, 'C', 'IF 6C', 'F301', 3, 'Selasa', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(156, 54, 'A', 'IF 6A', 'F404', 4, 'Rabu', '09:30:00', '12:00:00', 40, 39, '2025-12-01 12:39:38'),
(157, 54, 'B', 'IF 6B', 'F304', 4, 'Senin', '12:30:00', '15:00:00', 40, 39, '2025-12-01 12:39:38'),
(158, 55, 'A', 'IF 6A', 'F206', 5, 'Kamis', '12:30:00', '15:00:00', 40, 40, '2025-12-01 12:39:38'),
(159, 55, 'B', 'IF 6B', 'F305', 4, 'Senin', '15:30:00', '18:00:00', 40, 36, '2025-12-01 12:39:38'),
(160, 55, 'C', 'IF 6C', 'F304', 1, 'Rabu', '09:30:00', '12:00:00', 40, 39, '2025-12-01 12:39:38'),
(161, 56, 'A', 'IF 6A', 'F105', 5, 'Kamis', '12:30:00', '15:00:00', 40, 35, '2025-12-01 12:39:38'),
(162, 56, 'B', 'IF 6B', 'F401', 1, 'Rabu', '12:30:00', '15:00:00', 40, 27, '2025-12-01 12:39:38'),
(163, 57, 'A', 'IF 6A', 'F104', 3, 'Rabu', '07:00:00', '09:30:00', 40, 23, '2025-12-01 12:39:38'),
(164, 57, 'B', 'IF 6B', 'F303', 2, 'Senin', '12:30:00', '15:00:00', 40, 24, '2025-12-01 12:39:38'),
(165, 57, 'C', 'IF 6C', 'F307', 2, 'Selasa', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(166, 58, 'A', 'IF 6A', 'F203', 1, 'Selasa', '12:30:00', '15:00:00', 40, 29, '2025-12-01 12:39:38'),
(167, 58, 'B', 'IF 6B', 'F406', 5, 'Selasa', '12:30:00', '15:00:00', 40, 27, '2025-12-01 12:39:38'),
(168, 58, 'C', 'IF 6C', 'F106', 2, 'Senin', '07:00:00', '09:30:00', 40, 29, '2025-12-01 12:39:38'),
(169, 59, 'A', 'IF 6A', 'F306', 4, 'Rabu', '15:30:00', '18:00:00', 40, 26, '2025-12-01 12:39:38'),
(170, 59, 'B', 'IF 6B', 'F303', 2, 'Rabu', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(171, 59, 'C', 'IF 6C', 'F105', 4, 'Senin', '12:30:00', '15:00:00', 40, 11, '2025-12-01 12:39:38'),
(172, 60, 'A', 'IF 6A', 'F209', 4, 'Senin', '07:00:00', '09:30:00', 40, 37, '2025-12-01 12:39:38'),
(173, 60, 'B', 'IF 6B', 'F208', 5, 'Kamis', '07:00:00', '09:30:00', 40, 36, '2025-12-01 12:39:38'),
(174, 60, 'C', 'IF 6C', 'F210', 2, 'Rabu', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(175, 61, 'A', 'IF 6A', 'F110', 5, 'Kamis', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(176, 61, 'B', 'IF 6B', 'F402', 1, 'Senin', '12:30:00', '15:00:00', 40, 39, '2025-12-01 12:39:38'),
(177, 61, 'C', 'IF 6C', 'F306', 5, 'Kamis', '15:30:00', '18:00:00', 40, 16, '2025-12-01 12:39:38'),
(178, 62, 'A', 'IF 7A', 'F202', 2, 'Kamis', '12:30:00', '15:00:00', 40, 28, '2025-12-01 12:39:38'),
(179, 62, 'B', 'IF 7B', 'F307', 5, 'Kamis', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(180, 62, 'C', 'IF 7C', 'F202', 4, 'Senin', '07:00:00', '09:30:00', 40, 29, '2025-12-01 12:39:38'),
(181, 63, 'A', 'IF 7A', 'F103', 1, 'Senin', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(182, 63, 'B', 'IF 7B', 'F303', 2, 'Rabu', '12:30:00', '15:00:00', 40, 22, '2025-12-01 12:39:38'),
(183, 63, 'C', 'IF 7C', 'F306', 1, 'Senin', '15:30:00', '18:00:00', 40, 36, '2025-12-01 12:39:38'),
(184, 64, 'A', 'IF 7A', 'F301', 4, 'Kamis', '15:30:00', '18:00:00', 40, 29, '2025-12-01 12:39:38'),
(185, 64, 'B', 'IF 7B', 'F107', 3, 'Selasa', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(186, 64, 'C', 'IF 7C', 'F103', 2, 'Kamis', '15:30:00', '18:00:00', 40, 10, '2025-12-01 12:39:38'),
(187, 65, 'A', 'IF 7A', 'F209', 4, 'Senin', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(188, 65, 'B', 'IF 7B', 'F106', 3, 'Kamis', '09:30:00', '12:00:00', 40, 15, '2025-12-01 12:39:38'),
(189, 65, 'C', 'IF 7C', 'F308', 5, 'Rabu', '15:30:00', '18:00:00', 40, 29, '2025-12-01 12:39:38'),
(190, 66, 'A', 'IF 7A', 'F402', 2, 'Selasa', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(191, 66, 'B', 'IF 7B', 'F203', 3, 'Senin', '09:30:00', '12:00:00', 40, 33, '2025-12-01 12:39:38'),
(192, 67, 'A', 'IF 7A', 'F105', 4, 'Rabu', '15:30:00', '18:00:00', 40, 30, '2025-12-01 12:39:38'),
(193, 67, 'B', 'IF 7B', 'F110', 5, 'Kamis', '12:30:00', '15:00:00', 40, 40, '2025-12-01 12:39:38'),
(194, 68, 'A', 'IF 7A', 'F404', 2, 'Rabu', '15:30:00', '18:00:00', 40, 24, '2025-12-01 12:39:38'),
(195, 68, 'B', 'IF 7B', 'F401', 3, 'Senin', '09:30:00', '12:00:00', 40, 32, '2025-12-01 12:39:38'),
(196, 69, 'A', 'IF 7A', 'F305', 2, 'Rabu', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(197, 69, 'B', 'IF 7B', 'F306', 5, 'Rabu', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(198, 69, 'C', 'IF 7C', 'F106', 3, 'Kamis', '07:00:00', '09:30:00', 40, 39, '2025-12-01 12:39:38'),
(199, 70, 'A', 'IF 8A', 'F105', 1, 'Senin', '09:30:00', '12:00:00', 40, 30, '2025-12-01 12:39:38'),
(200, 70, 'B', 'IF 8B', 'F306', 2, 'Kamis', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(201, 70, 'C', 'IF 8C', 'F408', 5, 'Senin', '09:30:00', '12:00:00', 40, 28, '2025-12-01 12:39:38'),
(202, 71, 'A', 'IF 8A', 'F406', 5, 'Rabu', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(203, 71, 'B', 'IF 8B', 'F206', 3, 'Rabu', '09:30:00', '12:00:00', 40, 15, '2025-12-01 12:39:38'),
(204, 71, 'C', 'IF 8C', 'F410', 2, 'Kamis', '09:30:00', '12:00:00', 40, 11, '2025-12-01 12:39:38'),
(205, 72, 'A', 'IF 8A', 'F303', 5, 'Senin', '15:30:00', '18:00:00', 40, 11, '2025-12-01 12:39:38'),
(206, 72, 'B', 'IF 8B', 'F201', 1, 'Kamis', '09:30:00', '12:00:00', 40, 40, '2025-12-01 12:39:38'),
(207, 73, 'A', 'IF 8A', 'F307', 5, 'Selasa', '12:30:00', '15:00:00', 40, 40, '2025-12-01 12:39:38'),
(208, 73, 'B', 'IF 8B', 'F309', 1, 'Rabu', '09:30:00', '12:00:00', 40, 34, '2025-12-01 12:39:38'),
(209, 74, 'A', 'IF 8A', 'F307', 3, 'Senin', '09:30:00', '12:00:00', 40, 24, '2025-12-01 12:39:38'),
(210, 74, 'B', 'IF 8B', 'F401', 2, 'Kamis', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(211, 74, 'C', 'IF 8C', 'F309', 5, 'Kamis', '12:30:00', '15:00:00', 40, 39, '2025-12-01 12:39:38'),
(212, 75, 'A', 'IF 8A', 'F407', 3, 'Rabu', '15:30:00', '18:00:00', 40, 24, '2025-12-01 12:39:38'),
(213, 75, 'B', 'IF 8B', 'F104', 2, 'Kamis', '15:30:00', '18:00:00', 40, 40, '2025-12-01 12:39:38'),
(214, 76, 'A', 'IF 8A', 'F306', 2, 'Selasa', '07:00:00', '09:30:00', 40, 29, '2025-12-01 12:39:38'),
(215, 76, 'B', 'IF 8B', 'F210', 4, 'Rabu', '12:30:00', '15:00:00', 40, 17, '2025-12-01 12:39:38'),
(216, 76, 'C', 'IF 8C', 'F206', 3, 'Rabu', '07:00:00', '09:30:00', 40, 26, '2025-12-01 12:39:38'),
(217, 77, 'A', 'IF 8A', 'F403', 3, 'Kamis', '07:00:00', '09:30:00', 40, 40, '2025-12-01 12:39:38'),
(218, 77, 'B', 'IF 8B', 'F207', 4, 'Senin', '15:30:00', '18:00:00', 40, 17, '2025-12-01 12:39:38'),
(219, 77, 'C', 'IF 8C', 'F201', 4, 'Rabu', '15:30:00', '18:00:00', 40, 29, '2025-12-01 12:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `krs_awal`
--

CREATE TABLE `krs_awal` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `status` enum('draft','diajukan','disetujui','ditolak') DEFAULT 'draft',
  `tanggal_daftar` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `prodi` varchar(50) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `ips` decimal(3,2) DEFAULT 0.00,
  `dpa_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `jenis_kelamin`, `password`, `email`, `prodi`, `semester`, `ips`, `dpa_id`, `created_at`, `foto`) VALUES
(1, '230411100073', 'Asep Prayogi', 'L', '$2y$10$uF4r4DVgpRxxPEqWBAVvveanxAdM7D//QpyBnIAArihSyyOPusK.m', 'asep@student.univ.ac.id', 'Teknik Informatika', 5, 3.40, 1, '2025-11-27 07:12:08', '230411100073_1764407735.jpg'),
(2, '230411100076', 'Rypaldho Ridotua Hutagaol', 'L', '$2y$10$Wqn7V79IM3y7J.Z/SnSDIOjpoxVEy05lJjIOFas2fzny6oj5fCJ62', 'paldo@student.univ.ac.id', 'Teknik Informatika', 5, 3.40, 1, '2025-11-29 09:16:27', '230411100076_1764407787.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int(11) NOT NULL,
  `kode_mk` varchar(20) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL,
  `minimal_kelulusan` varchar(5) DEFAULT 'C',
  `semester` int(11) DEFAULT NULL,
  `prodi_id` int(11) DEFAULT 1,
  `sifat` enum('Wajib','Pilihan') DEFAULT 'Wajib',
  `prasyarat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `sks`, `minimal_kelulusan`, `semester`, `prodi_id`, `sifat`, `prasyarat`, `created_at`) VALUES
(1, 'UNG112', 'Pendidikan Agama Islam', 2, 'C', 1, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(2, 'UNG118', 'Kewarganegaraan', 2, 'C', 1, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(3, 'UNG119', 'Bahasa Indonesia', 2, 'C', 1, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(4, 'UNG120', 'Pancasila', 2, 'C', 1, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(5, 'UNG121', 'Bahasa Inggris', 2, 'C', 1, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(6, 'IF2211', 'Pengantar Teknologi Informasi (P)', 3, 'C', 1, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(7, 'IF2212', 'Matematika Teknik', 2, 'C', 1, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(8, 'IF2213', 'Algoritma & Dasar Pemrograman (P)', 4, 'C', 1, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(9, 'IF2214', 'Struktur Data (P)', 4, 'C', 2, 1, 'Wajib', 'IF2213', '2025-11-29 10:15:39'),
(10, 'IF2215', 'Metode Statistika', 3, 'C', 2, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(11, 'IF2216', 'Komputasi Aljabar Linier', 3, 'C', 2, 1, 'Wajib', '', '2025-11-29 10:15:39'),
(12, 'IF2217', 'Matematika Diskret', 3, 'C', 2, 1, 'Wajib', 'IF2211', '2025-11-29 10:15:39'),
(13, 'IF2218', 'Organisasi Komputer dan Sistem Operasi', 3, 'C', 2, 1, 'Wajib', 'IF2211', '2025-11-29 10:15:39'),
(14, 'IF2219', 'Dasar Pemrograman Web (P)', 4, 'C', 2, 1, 'Wajib', 'IF2211', '2025-11-29 10:15:39'),
(15, 'IF2220', 'Pengembangan Aplikasi Web (P)', 4, 'C', 3, 1, 'Wajib', 'IF2219', '2025-11-29 10:15:39'),
(16, 'IF2221', 'Basis Data I', 3, 'C', 3, 1, 'Wajib', 'IF2214', '2025-11-29 10:15:39'),
(17, 'IF2222', 'Jaringan Komputer I (P)', 4, 'C', 3, 1, 'Wajib', 'IF2218', '2025-11-29 10:15:39'),
(18, 'IF2223', 'Teori Komputasi', 3, 'C', 3, 1, 'Wajib', 'IF2217', '2025-11-29 10:15:39'),
(19, 'IF2241', 'Etika Profesional', 2, 'C', 3, 1, 'Pilihan', 'IF2211', '2025-11-29 10:15:39'),
(20, 'IF2242', 'Sistem Informasi', 3, 'C', 3, 1, 'Pilihan', 'IF2211', '2025-11-29 10:15:39'),
(21, 'IF2243', 'Pemrograman Desktop', 3, 'C', 3, 1, 'Pilihan', 'IF2214', '2025-11-29 10:15:39'),
(22, 'IF2244', 'Pemrograman Berorientasi Obyek', 3, 'C', 3, 1, 'Pilihan', 'IF2214', '2025-11-29 10:15:39'),
(23, 'IF2245', 'Rekayasa Multimedia', 3, 'C', 3, 1, 'Pilihan', 'IF2211', '2025-11-29 10:15:39'),
(24, 'IF2224', 'Penambangan Data', 3, 'C', 4, 1, 'Wajib', 'IF2226*', '2025-11-29 10:15:39'),
(25, 'IF2225', 'Rekayasa Perangkat Lunak', 3, 'C', 4, 1, 'Wajib', 'IF2221', '2025-11-29 10:15:39'),
(26, 'IF2226', 'Kecerdasan Komputasional', 3, 'C', 4, 1, 'Wajib', 'IF2217', '2025-11-29 10:15:39'),
(27, 'IF2227', 'Basis Data II', 3, 'C', 4, 1, 'Wajib', 'IF2221', '2025-11-29 10:15:39'),
(28, 'IF2246', 'Pengembangan Sistem Berbasis Framework', 3, 'C', 4, 1, 'Pilihan', 'IF2220', '2025-11-29 10:15:39'),
(29, 'IF2247', 'Interaksi Manusia & Komputer', 3, 'C', 4, 1, 'Pilihan', 'IF2225*', '2025-11-29 10:15:39'),
(30, 'IF2248', 'Bahasa Inggris Informatika', 2, 'C', 4, 1, 'Pilihan', '50 sks', '2025-11-29 10:15:39'),
(31, 'IF2249', 'Temu-Kembali informasi', 3, 'C', 4, 1, 'Pilihan', 'IF2226*', '2025-11-29 10:15:39'),
(32, 'IF2250', 'Pemrograman Perangkat Bergerak', 3, 'C', 4, 1, 'Pilihan', 'IF2220', '2025-11-29 10:15:39'),
(33, 'IF2251', 'Grafika Komputer', 3, 'C', 4, 1, 'Pilihan', 'IF2214', '2025-11-29 10:15:39'),
(34, 'IF2252', 'Strategi Algoritma', 3, 'C', 4, 1, 'Pilihan', 'IF2214', '2025-11-29 10:15:39'),
(35, 'IF2253', 'Jaringan Komputer II', 3, 'C', 4, 1, 'Pilihan', 'IF2222', '2025-11-29 10:15:39'),
(36, 'IF2228', 'Sistem Terdistribusi', 3, 'C', 5, 1, 'Wajib', 'IF2222', '2025-11-29 10:15:39'),
(37, 'IF2229', 'Proyek Perangkat Lunak', 3, 'C', 5, 1, 'Wajib', 'IF2225', '2025-11-29 10:15:39'),
(38, 'IF2230', 'Pembelajaran Mesin', 3, 'C', 5, 1, 'Wajib', 'IF2226', '2025-11-29 10:15:39'),
(39, 'IF2231', 'Proyek Sains Data', 3, 'C', 5, 1, 'Wajib', 'IF2224', '2025-11-29 10:15:39'),
(40, 'IF2232', 'Metodologi Penelitian', 2, 'C', 5, 1, 'Wajib', '70 sks', '2025-11-29 10:15:39'),
(41, 'IF2254', 'Keamanan Data & Aplikasi', 3, 'C', 5, 1, 'Pilihan', 'IF2225', '2025-11-29 10:15:39'),
(42, 'IF2255', 'Technopreneurship', 2, 'C', 5, 1, 'Pilihan', '50 SKS', '2025-11-29 10:15:39'),
(43, 'IF2256', 'Komputasi Numerik', 3, 'C', 5, 1, 'Pilihan', 'IF2214', '2025-11-29 10:15:39'),
(44, 'IF2257', 'Pemrograman Game', 3, 'C', 5, 1, 'Pilihan', 'IF2251', '2025-11-29 10:15:39'),
(45, 'IF2258', 'Basis Data III', 3, 'C', 5, 1, 'Pilihan', 'IF2228', '2025-11-29 10:15:39'),
(46, 'IF2259', 'Pengolahan Citra', 3, 'C', 5, 1, 'Pilihan', 'IF2226*', '2025-11-29 10:15:39'),
(47, 'IF2260', 'Pemodelan Proses Bisnis', 3, 'C', 5, 1, 'Pilihan', 'IF2225', '2025-11-29 10:15:39'),
(48, 'IF2261', 'Pengembangan Aplikasi Terintegrasi', 3, 'C', 6, 1, 'Pilihan', 'IF2229', '2025-11-29 10:15:39'),
(49, 'IF2262', 'Pemrosesan Bahasa Alami', 3, 'C', 6, 1, 'Pilihan', 'IF2223', '2025-11-29 10:15:39'),
(50, 'IF2263', 'Sistem Rekomendasi & Personalisasi', 3, 'C', 6, 1, 'Pilihan', 'IF2224', '2025-11-29 10:15:39'),
(51, 'IF2264', 'Biomedika', 3, 'C', 6, 1, 'Pilihan', 'IF2230', '2025-11-29 10:15:39'),
(52, 'IF2265', 'Penambangan Teks', 3, 'C', 6, 1, 'Pilihan', 'IF2224', '2025-11-29 10:15:39'),
(53, 'IF2266', 'Keamanan Siber', 3, 'C', 6, 1, 'Pilihan', 'IF2254', '2025-11-29 10:15:39'),
(54, 'IF2267', 'Internet of Things', 3, 'C', 6, 1, 'Pilihan', 'IF2222', '2025-11-29 10:15:39'),
(55, 'UNG111', 'Kuliah Kerja Nyata', 3, 'C', 6, 1, 'Pilihan', '100 SKS', '2025-11-29 10:15:39'),
(56, 'IF2268', 'Kecerdasan Bisnis', 3, 'C', 6, 1, 'Pilihan', 'IF2224', '2025-11-29 10:15:39'),
(57, 'IF2269', 'Kriptografi', 3, 'C', 6, 1, 'Pilihan', 'IF2254', '2025-11-29 10:15:39'),
(58, 'IF2270', 'Topik Khusus I', 3, 'C', 6, 1, 'Pilihan', 'IF2229, IF2231', '2025-11-29 10:15:39'),
(59, 'IF2271', 'Arsitektur Perangkat Lunak', 3, 'C', 6, 1, 'Pilihan', 'IF2229', '2025-11-29 10:15:39'),
(60, 'IF2272', 'Kerja Praktek', 2, 'C', 6, 1, 'Pilihan', '84 SKS', '2025-11-29 10:15:39'),
(61, 'IF2273', 'Pengenalan Pola', 3, 'C', 6, 1, 'Pilihan', 'IF2259', '2025-11-29 10:15:39'),
(62, 'IF2274', 'Biometrika', 3, 'C', 7, 1, 'Pilihan', 'IF2230', '2025-11-29 10:15:39'),
(63, 'IF2275', 'Analisis Big Data', 3, 'C', 7, 1, 'Pilihan', 'IF2224', '2025-11-29 10:15:39'),
(64, 'IF2276', 'Komputasi Video', 3, 'C', 7, 1, 'Pilihan', 'IF2259', '2025-11-29 10:15:39'),
(65, 'IF2277', 'Deep Learning', 3, 'C', 7, 1, 'Pilihan', 'IF2230', '2025-11-29 10:15:39'),
(66, 'IF2278', 'Pencarian dan Penambangan Web', 3, 'C', 7, 1, 'Pilihan', 'IF2224', '2025-11-29 10:15:39'),
(67, 'IF2279', 'Topik Khusus II', 3, 'C', 7, 1, 'Pilihan', 'IF2229, IF2231', '2025-11-29 10:15:39'),
(68, 'IF2280', 'Proyek Perangkat Lunak Lanjut', 2, 'C', 7, 1, 'Pilihan', 'IF2229', '2025-11-29 10:15:39'),
(69, 'IF2281', 'DevOps Perangkat Lunak', 3, 'C', 7, 1, 'Pilihan', 'IF2229', '2025-11-29 10:15:39'),
(70, 'IF2299', 'Skripsi', 6, 'C', 8, 1, 'Wajib', '120 sks', '2025-11-29 10:15:39'),
(71, 'IF2282', 'Pengembangan Aplikasi Terdistribusi', 3, 'C', 8, 1, 'Pilihan', 'IF2229', '2025-11-29 10:15:39'),
(72, 'IF2283', 'Penjaminan Mutu Perangkat Lunak', 3, 'C', 8, 1, 'Pilihan', 'IF2229', '2025-11-29 10:15:39'),
(73, 'IF2284', 'Pemrosesan Sinyal Digital', 3, 'C', 8, 1, 'Pilihan', 'IF2230', '2025-11-29 10:15:39'),
(74, 'IF2285', 'Informatika Pariwisata', 3, 'C', 8, 1, 'Pilihan', 'IF2224', '2025-11-29 10:15:39'),
(75, 'IF2286', 'Proyek Sains Data Lanjut', 2, 'C', 8, 1, 'Pilihan', 'IF2231', '2025-11-29 10:15:39'),
(76, 'IF2287', 'Teknologi Keuangan (FinTech)', 3, 'C', 8, 1, 'Pilihan', 'IF2226*', '2025-11-29 10:15:39'),
(77, 'IF2288', 'Realitas Virtual & Augmentasi', 3, 'C', 8, 1, 'Pilihan', 'IF2251', '2025-11-29 10:15:39');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('mahasiswa','dosen','tata_usaha') NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `sender_type` enum('mahasiswa','dosen','tata_usaha') DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('info','warning','success','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_tambah_kelas`
--

CREATE TABLE `pengajuan_tambah_kelas` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL,
  `mata_kuliah_id` int(11) DEFAULT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') DEFAULT 'pending',
  `tanggal_pengajuan` timestamp NULL DEFAULT current_timestamp(),
  `tanggal_validasi` timestamp NULL DEFAULT NULL,
  `validator_id` int(11) DEFAULT NULL,
  `catatan_validasi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periode_akademik`
--

CREATE TABLE `periode_akademik` (
  `id` int(11) NOT NULL,
  `tahun_akademik` varchar(10) DEFAULT NULL,
  `semester` enum('Ganjil','Genap') DEFAULT NULL,
  `tanggal_mulai_krs` datetime DEFAULT NULL,
  `tanggal_selesai_krs` datetime DEFAULT NULL,
  `tanggal_mulai_kprs` datetime DEFAULT NULL,
  `tanggal_selesai_kprs` datetime DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `periode_akademik`
--

INSERT INTO `periode_akademik` (`id`, `tahun_akademik`, `semester`, `tanggal_mulai_krs`, `tanggal_selesai_krs`, `tanggal_mulai_kprs`, `tanggal_selesai_kprs`, `status`, `created_at`) VALUES
(1, '2025/2026', 'Ganjil', '2025-12-03 15:00:00', '2025-12-03 15:15:00', '2025-12-03 16:21:00', '2025-12-03 19:15:00', 'active', '2025-12-01 09:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id` int(11) NOT NULL,
  `kode_prodi` varchar(10) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  `kaprodi_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id`, `kode_prodi`, `nama_prodi`, `kaprodi_id`, `created_at`) VALUES
(1, 'TI', 'Teknik Informatika', 2, '2025-11-27 07:12:08'),
(2, 'SI', 'Sistem Informasi', 3, '2025-11-29 12:33:12');

-- --------------------------------------------------------

--
-- Table structure for table `tata_usaha`
--

CREATE TABLE `tata_usaha` (
  `id` int(11) NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tata_usaha`
--

INSERT INTO `tata_usaha` (`id`, `nip`, `nama`, `password`, `email`, `created_at`, `foto`) VALUES
(1, '123', 'Staff', '$2y$10$ZWByPnNKiedrSyuoO.cZGe9EyJTC/eUwKqKnqwWWqxzEwJe.MazlG', 'tu@univ.ac.id', '2025-11-29 08:30:04', 'TU_123_1764413631.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mata_kuliah_id` (`mata_kuliah_id`),
  ADD KEY `dosen_pengampu_id` (`dosen_pengampu_id`);

--
-- Indexes for table `krs_awal`
--
ALTER TABLE `krs_awal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `kelas_id` (`kelas_id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dpa_id` (`dpa_id`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mk` (`kode_mk`),
  ADD KEY `prodi_id` (`prodi_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_tambah_kelas`
--
ALTER TABLE `pengajuan_tambah_kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `mata_kuliah_id` (`mata_kuliah_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `validator_id` (`validator_id`);

--
-- Indexes for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kaprodi_id` (`kaprodi_id`);

--
-- Indexes for table `tata_usaha`
--
ALTER TABLE `tata_usaha`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `krs_awal`
--
ALTER TABLE `krs_awal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuan_tambah_kelas`
--
ALTER TABLE `pengajuan_tambah_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tata_usaha`
--
ALTER TABLE `tata_usaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`),
  ADD CONSTRAINT `kelas_ibfk_2` FOREIGN KEY (`dosen_pengampu_id`) REFERENCES `dosen` (`id`);

--
-- Constraints for table `krs_awal`
--
ALTER TABLE `krs_awal`
  ADD CONSTRAINT `krs_awal_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`),
  ADD CONSTRAINT `krs_awal_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`);

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`dpa_id`) REFERENCES `dosen` (`id`);

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`);

--
-- Constraints for table `pengajuan_tambah_kelas`
--
ALTER TABLE `pengajuan_tambah_kelas`
  ADD CONSTRAINT `pengajuan_tambah_kelas_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`),
  ADD CONSTRAINT `pengajuan_tambah_kelas_ibfk_2` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`),
  ADD CONSTRAINT `pengajuan_tambah_kelas_ibfk_3` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `pengajuan_tambah_kelas_ibfk_4` FOREIGN KEY (`validator_id`) REFERENCES `dosen` (`id`);

--
-- Constraints for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD CONSTRAINT `program_studi_ibfk_1` FOREIGN KEY (`kaprodi_id`) REFERENCES `dosen` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
