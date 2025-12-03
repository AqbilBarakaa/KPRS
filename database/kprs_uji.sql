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
-- Table structure for table `kprs`
--

CREATE TABLE `kprs` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) DEFAULT NULL,
  `jenis_aksi` enum('tambah','hapus') NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','auto_approved') DEFAULT 'pending',
  `tanggal_pengajuan` timestamp NULL DEFAULT current_timestamp(),
  `tanggal_validasi` timestamp NULL DEFAULT NULL,
  `validator_id` int(11) DEFAULT NULL,
  `catatan_validasi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Dumping data for table `krs_awal`
--

INSERT INTO `krs_awal` (`id`, `mahasiswa_id`, `kelas_id`, `status`, `tanggal_daftar`) VALUES
(85, 3, 4, 'disetujui', '2025-12-02 04:20:35'),
(86, 3, 9, 'disetujui', '2025-12-02 04:20:35'),
(87, 3, 14, 'disetujui', '2025-12-02 04:20:35'),
(88, 3, 19, 'disetujui', '2025-12-02 04:22:08'),
(89, 3, 21, 'disetujui', '2025-12-02 04:22:08'),
(116, 1, 21, 'ditolak', '2025-12-03 08:12:32'),
(118, 1, 14, 'ditolak', '2025-12-03 08:14:11'),
(120, 1, 9, 'ditolak', '2025-12-03 09:24:13'),
(122, 1, 26, 'ditolak', '2025-12-03 09:26:51'),
(123, 1, 12, 'disetujui', '2025-12-03 09:27:40');

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
(3, '230411100076', 'Rypaldho Ridotua Hutagaol', 'L', '$2y$10$Wqn7V79IM3y7J.Z/SnSDIOjpoxVEy05lJjIOFas2fzny6oj5fCJ62', 'paldo@student.univ.ac.id', 'Teknik Informatika', 5, 3.40, 1, '2025-11-29 09:16:27', '230411100076_1764407787.jpg');

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

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `user_type`, `sender_id`, `sender_type`, `judul`, `pesan`, `tipe`, `is_read`, `is_deleted`, `link`, `created_at`) VALUES
(1, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui Kaprodi', 'Pengajuan tambah kelas Anda disetujui Kaprodi dan diteruskan ke TU.', 'success', 1, 0, '#', '2025-11-30 02:07:35'),
(2, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Ada pengajuan tambah kelas baru yang disetujui Kaprodi. Harap proses.', 'warning', 1, 0, 'pengajuan.php', '2025-11-30 02:07:35'),
(3, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan Anda. Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, '#', '2025-11-30 02:08:08'),
(4, 2, 'dosen', NULL, NULL, 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pancasila.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-11-30 02:46:47'),
(5, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Matematika Teknik.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-11-30 02:49:21'),
(6, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Inggris.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-11-30 02:56:37'),
(7, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Bahasa Inggris.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-11-30 02:57:01'),
(8, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'krs.php', '2025-12-01 12:45:43'),
(9, 1, 'mahasiswa', NULL, NULL, 'Status Pengajuan Validasi', 'Anda telah berhasil mengajukan validasi KRS kepada Dosen Wali. Mohon tunggu persetujuan.', 'info', 1, 0, '#', '2025-12-01 12:45:43'),
(10, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi (230411100073) mengajukan validasi Pengajuan KRS. Mohon dicek.', 'warning', 1, 0, 'krs.php', '2025-12-01 12:50:25'),
(11, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 12:56:46'),
(12, 1, 'mahasiswa', NULL, NULL, 'Mulai Revisi KRS', 'Anda telah memulai masa revisi KRS. Silakan tambah/hapus mata kuliah dan jangan lupa ajukan validasi ulang.', 'info', 1, 0, '#', '2025-12-01 15:06:16'),
(13, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Mulai Revisi', 'Mahasiswa Asep Prayogi (230411100073) sedang melakukan revisi KRS.', 'info', 1, 0, '#', '2025-12-01 15:06:16'),
(14, 1, 'mahasiswa', NULL, NULL, 'Mulai Revisi KRS', 'Anda telah memulai masa revisi KRS. Silakan tambah/hapus mata kuliah dan jangan lupa ajukan validasi ulang.', 'info', 1, 0, '#', '2025-12-01 15:07:32'),
(15, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Mulai Revisi', 'Mahasiswa Asep Prayogi (230411100073) sedang melakukan revisi KRS.', 'info', 1, 0, '#', '2025-12-01 15:07:32'),
(16, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Semua mata kuliah kembali menjadi Draft. Silakan sesuaikan dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 15:11:49'),
(17, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-01 15:11:49'),
(18, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 15:13:07'),
(19, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 15:13:30'),
(20, 1, 'dosen', 3, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 15:15:32'),
(21, 3, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 15:15:58'),
(22, 3, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Semua mata kuliah kembali menjadi Draft. Silakan sesuaikan dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 15:17:15'),
(23, 1, 'dosen', 3, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Rypaldho Ridotua Hutagaol (230411100076) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-01 15:17:15'),
(24, 1, 'dosen', 3, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Rypaldho Ridotua Hutagaol (230411100076) mengajukan validasi Revisi KPRS. Mohon dicek.', 'warning', 1, 0, 'perwalian.php', '2025-12-01 15:39:42'),
(25, 3, 'mahasiswa', 1, 'dosen', 'KRS Ditolak/Revisi', 'Terdapat perbaikan pada KRS Anda. Catatan Dosen: ', 'error', 1, 0, 'krs.php', '2025-12-01 15:40:09'),
(26, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Semua mata kuliah kembali menjadi Draft. Silakan sesuaikan dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 15:41:28'),
(27, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-01 15:41:28'),
(28, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 15:55:16'),
(29, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 15:55:37'),
(30, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 16:04:27'),
(31, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 16:04:27'),
(32, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 16:16:07'),
(33, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 16:16:27'),
(34, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi KPRS telah diaktifkan. Silakan tambah/hapus mata kuliah dan ajukan validasi ulang.', 'info', 1, 0, '#', '2025-12-01 16:27:10'),
(35, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah memulai proses revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 16:27:10'),
(36, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 16:35:33'),
(37, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-01 16:35:33'),
(38, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 16:36:38'),
(39, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 16:36:53'),
(40, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 16:37:54'),
(41, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-01 16:37:54'),
(42, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 16:41:54'),
(43, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 16:42:13'),
(44, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS.', 'info', 1, 0, '#', '2025-12-01 16:42:59'),
(45, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 16:44:08'),
(46, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 16:44:21'),
(47, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi diaktifkan. Silakan ubah KRS Anda dan AJUKAN VALIDASI kembali.', 'info', 1, 0, '#', '2025-12-01 16:48:11'),
(48, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-01 16:48:11'),
(49, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 16:51:16'),
(50, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 16:51:28'),
(51, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 16:52:57'),
(52, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 16:52:57'),
(53, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 16:53:49'),
(54, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 16:54:08'),
(55, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 16:55:35'),
(56, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 16:55:35'),
(57, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 16:56:11'),
(58, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 16:56:25'),
(59, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 17:03:04'),
(60, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 17:03:04'),
(61, 1, 'mahasiswa', NULL, NULL, 'KRS Otomatis Disetujui', 'Masa KPRS telah berakhir. Mata kuliah Anda yang pending telah divalidasi otomatis oleh sistem.', 'success', 1, 0, '#', '2025-12-01 17:12:27'),
(62, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS.', 'info', 1, 0, '#', '2025-12-01 17:13:31'),
(63, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 17:16:20'),
(64, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 17:16:32'),
(65, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS.', 'info', 1, 0, '#', '2025-12-01 17:18:17'),
(66, 1, 'mahasiswa', NULL, NULL, 'KRS Otomatis Disetujui', 'Masa KPRS telah berakhir. Sistem secara otomatis memvalidasi mata kuliah Anda yang masih pending.', 'success', 1, 0, '#', '2025-12-01 17:22:05'),
(67, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 17:25:44'),
(68, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 17:25:44'),
(69, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 17:26:37'),
(70, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 17:26:50'),
(71, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 17:27:39'),
(72, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 17:27:39'),
(73, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 17:28:01'),
(74, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 17:28:22'),
(75, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 17:34:02'),
(76, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 17:34:02'),
(77, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 17:34:49'),
(78, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 17:35:08'),
(79, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 17:36:11'),
(80, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 17:36:11'),
(81, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 17:44:30'),
(82, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 17:44:42'),
(83, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 17:47:42'),
(84, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 17:47:42'),
(85, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 17:48:44'),
(86, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 17:49:02'),
(87, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 17:50:08'),
(88, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 17:50:08'),
(89, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 17:53:41'),
(90, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 17:53:55'),
(91, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 18:01:03'),
(92, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 18:01:03'),
(93, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 18:02:29'),
(94, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 18:02:41'),
(95, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-01 18:03:04'),
(96, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-01 18:03:04'),
(97, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 18:05:58'),
(98, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-01 18:06:19'),
(99, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-01 18:06:30'),
(100, 1, 'mahasiswa', NULL, NULL, 'Revisi Aktif', 'Mode revisi aktif. Silakan ubah dan ajukan ulang.', 'info', 1, 0, '#', '2025-12-02 04:16:58'),
(101, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 04:18:33'),
(102, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 04:19:07'),
(103, 1, 'dosen', 3, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 04:20:43'),
(104, 3, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 04:21:10'),
(105, 3, 'mahasiswa', NULL, NULL, 'Revisi Aktif', 'Mode revisi aktif. Silakan ubah dan ajukan ulang.', 'info', 1, 0, '#', '2025-12-02 04:22:01'),
(106, 1, 'dosen', 3, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 04:22:14'),
(107, 3, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 04:22:32'),
(108, 1, 'mahasiswa', NULL, NULL, 'Revisi Aktif', 'Mode revisi aktif. Silakan ubah dan ajukan ulang.', 'info', 1, 0, '#', '2025-12-02 04:22:56'),
(109, 1, 'mahasiswa', NULL, NULL, 'KRS Otomatis Disetujui', 'Masa KPRS berakhir. Sistem memvalidasi otomatis.', 'success', 1, 0, '#', '2025-12-02 04:23:04'),
(110, 1, 'mahasiswa', NULL, NULL, 'Revisi Aktif', 'Mode revisi aktif. Silakan ubah dan ajukan ulang.', 'info', 1, 0, '#', '2025-12-02 04:23:52'),
(111, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 04:24:03'),
(112, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 04:24:22'),
(113, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Inggris.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 04:28:50'),
(114, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Bahasa Inggris.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 04:29:22'),
(115, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Bahasa Indonesia.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 04:29:30'),
(116, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 14:40:07'),
(117, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-02 14:40:07'),
(118, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 14:41:02'),
(119, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 14:41:17'),
(120, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 14:42:30'),
(121, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-02 14:42:30'),
(122, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 14:42:48'),
(123, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 14:43:07'),
(124, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 14:54:03'),
(125, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-02 14:54:03'),
(126, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 14:54:48'),
(127, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 14:55:07'),
(128, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi aktif. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 14:55:18'),
(129, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) melakukan revisi KRS.', 'info', 1, 0, 'perwalian.php', '2025-12-02 14:55:18'),
(130, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 14:58:51'),
(131, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 14:59:17'),
(132, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 15:03:40'),
(133, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-02 15:03:40'),
(134, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 15:05:44'),
(135, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 15:05:56'),
(136, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 15:06:25'),
(137, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-02 15:06:25'),
(138, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 15:06:34'),
(139, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 15:06:46'),
(140, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 15:18:18'),
(141, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-02 15:18:18'),
(142, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 15:19:14'),
(143, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 15:19:43'),
(144, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 15:20:09'),
(145, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-02 15:20:09'),
(146, 1, 'mahasiswa', NULL, NULL, 'KRS Otomatis Disetujui', 'Masa KPRS telah berakhir. Sistem memvalidasi sisa mata kuliah yang belum divalidasi.', 'success', 1, 0, '#', '2025-12-02 15:21:02'),
(147, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 15:25:32'),
(148, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'perwalian.php', '2025-12-02 15:25:32'),
(149, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-02 15:27:05'),
(150, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-02 15:27:33'),
(151, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-02 15:30:11'),
(152, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'krs.php', '2025-12-02 15:30:11'),
(153, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'krs.php', '2025-12-02 15:30:33'),
(154, 1, 'mahasiswa', NULL, NULL, 'KRS Otomatis Disetujui', 'Masa KPRS telah berakhir. Sistem memvalidasi sisa mata kuliah yang belum divalidasi.', 'success', 1, 0, '#', '2025-12-02 15:31:03'),
(155, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 15:44:37'),
(156, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Indonesia.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 15:45:08'),
(157, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Matematika Teknik.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 15:45:14'),
(158, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pancasila.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 15:45:27'),
(159, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 15:46:03'),
(160, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Bahasa Inggris.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 15:46:05'),
(161, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Bahasa Indonesia.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 15:46:17'),
(162, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Pancasila.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 15:46:19'),
(163, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Anda disetujui Kaprodi dan diteruskan ke TU.', 'success', 1, 0, '#', '2025-12-02 15:52:48'),
(164, 3, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Anda disetujui Kaprodi dan diteruskan ke TU.', 'success', 0, 0, '#', '2025-12-02 15:52:48'),
(165, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Ada pengajuan tambah kelas massal yang disetujui Kaprodi. Harap proses.', 'warning', 1, 0, 'pengajuan.php', '2025-12-02 15:52:48'),
(166, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Maaf, pengajuan tambah kelas Anda ditolak. Catatan: tidak saya buka karena alasan tertentu', 'error', 1, 0, '#', '2025-12-02 15:53:07'),
(167, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pendidikan Agama Islam.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:07:24'),
(168, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pengantar Teknologi Informasi (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:07:28'),
(169, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Pengantar Teknologi Informasi (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:07:42'),
(170, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Matematika Teknik.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:07:47'),
(171, 1, 'mahasiswa', 2, 'dosen', 'Pengajuan Disetujui', 'Pengajuan tambah kelas Anda disetujui Kaprodi dan diteruskan ke TU. Catatan: buarkan kelasnya', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:08:11'),
(172, 3, 'mahasiswa', 2, 'dosen', 'Pengajuan Disetujui', 'Pengajuan tambah kelas Anda disetujui Kaprodi dan diteruskan ke TU. Catatan: buarkan kelasnya', 'success', 0, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:08:11'),
(173, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Ada pengajuan tambah kelas massal yang disetujui Kaprodi. Harap proses.', 'warning', 1, 0, 'verifikasi_pengajuan.php', '2025-12-02 16:08:11'),
(174, 3, 'mahasiswa', 2, 'dosen', 'Pengajuan Ditolak', 'Maaf, pengajuan tambah kelas Anda ditolak. Catatan: saya tidak bisa', 'error', 0, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:08:21'),
(175, 3, 'mahasiswa', 2, 'dosen', 'Pengajuan Ditolak', 'Maaf, pengajuan tambah kelas Matematika Teknik ditolak. Catatan: saya tolak', 'error', 0, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:29:45'),
(176, 1, 'mahasiswa', 2, 'dosen', 'Pengajuan Disetujui', 'Pengajuan tambah kelas Pengantar Teknologi Informasi (P) disetujui Kaprodi dan diteruskan ke TU. Catatan: saya terima', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:29:53'),
(177, 3, 'mahasiswa', 2, 'dosen', 'Pengajuan Disetujui', 'Pengajuan tambah kelas Pengantar Teknologi Informasi (P) disetujui Kaprodi dan diteruskan ke TU. Catatan: saya terima', 'success', 0, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:29:53'),
(178, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Pengajuan massal Pengantar Teknologi Informasi (P) disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-02 16:29:53'),
(179, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Pancasila disetujui. Catatan: ww', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:36:43'),
(180, 3, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Pancasila disetujui. Catatan: ww', 'success', 0, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:36:43'),
(181, 2, 'dosen', 2, 'dosen', 'Validasi Massal Berhasil', 'Anda telah menyetujui 2 pengajuan untuk mata kuliah Pancasila.', 'info', 1, 0, '#', '2025-12-02 16:36:43'),
(182, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 2 pengajuan Pancasila yang disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-02 16:36:43'),
(183, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Pendidikan Agama Islam ditolak. Catatan: ww', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:36:47'),
(184, 2, 'dosen', 2, 'dosen', 'Validasi Massal Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Pendidikan Agama Islam.', 'info', 1, 0, '#', '2025-12-02 16:36:47'),
(185, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:38:22'),
(186, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Indonesia.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:38:33'),
(187, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Inggris.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:38:35'),
(188, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Kewarganegaraan.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:38:38'),
(189, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pancasila.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:38:42'),
(190, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pendidikan Agama Islam.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:38:44'),
(191, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pengantar Teknologi Informasi (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:38:47'),
(192, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:39:05'),
(193, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Matematika Teknik.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:39:07'),
(194, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Pendidikan Agama Islam.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:39:09'),
(195, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Pengantar Teknologi Informasi (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 16:39:12'),
(196, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Bahasa Indonesia ditolak. Catatan: -', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 17:00:33'),
(197, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Bahasa Indonesia.', 'info', 1, 0, '#', '2025-12-02 17:00:33'),
(198, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Bahasa Inggris disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 17:12:32'),
(199, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 1 pengajuan untuk mata kuliah Bahasa Inggris.', 'info', 1, 0, '#', '2025-12-02 17:12:32'),
(200, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 1 pengajuan Bahasa Inggris yang disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-02 17:12:32'),
(201, 3, 'mahasiswa', 1, 'tata_usaha', 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Pancasila. Silakan cek jadwal dan ambil KRS.', 'success', 0, 0, 'krs.php', '2025-12-02 17:23:58'),
(202, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'Pengajuan tambah kelas Pancasila yang Anda setujui telah selesai diproses oleh Tata Usaha.', 'info', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 17:23:58'),
(203, 1, 'mahasiswa', 1, 'tata_usaha', 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Pancasila. Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-02 17:25:19'),
(204, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'Pengajuan tambah kelas Pancasila yang Anda setujui telah selesai diproses oleh Tata Usaha.', 'info', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 17:25:19'),
(205, 1, 'mahasiswa', 1, 'tata_usaha', 'Kelas Dibuka!', 'TU telah memproses pengajuan tambah kelas Bahasa Indonesia. Silakan cek jadwal.', 'success', 1, 0, 'krs.php', '2025-12-02 17:36:44'),
(206, 3, 'mahasiswa', 1, 'tata_usaha', 'Kelas Dibuka!', 'TU telah memproses pengajuan tambah kelas Bahasa Indonesia. Silakan cek jadwal.', 'success', 0, 0, 'krs.php', '2025-12-02 17:36:44'),
(207, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Bahasa Indonesia.', 'info', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 17:36:44'),
(208, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Pengantar Teknologi Informasi (P). Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-02 17:58:51'),
(209, 3, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Pengantar Teknologi Informasi (P). Silakan cek jadwal dan ambil KRS.', 'success', 0, 0, 'krs.php', '2025-12-02 17:58:51'),
(210, 2, 'dosen', NULL, NULL, 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Pengantar Teknologi Informasi (P).', 'info', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 17:58:51'),
(211, 1, 'tata_usaha', 1, 'tata_usaha', 'Proses Selesai', 'Anda telah menandai selesai 2 pengajuan untuk mata kuliah Pengantar Teknologi Informasi (P).', 'info', 1, 0, '#', '2025-12-02 17:58:51'),
(212, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Algoritma & Dasar Pemrograman (P). Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-02 18:04:08'),
(213, 3, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Algoritma & Dasar Pemrograman (P). Silakan cek jadwal dan ambil KRS.', 'success', 0, 0, 'krs.php', '2025-12-02 18:04:08'),
(214, 2, 'dosen', NULL, NULL, 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:04:08'),
(215, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:04:08'),
(216, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Indonesia.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:06:18'),
(217, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:07:45'),
(218, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Indonesia.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:07:50'),
(219, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Inggris.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:07:52'),
(220, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Kewarganegaraan.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:07:55'),
(221, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Matematika Teknik.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:07:58'),
(222, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pendidikan Agama Islam.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:08:02'),
(223, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Bahasa Inggris.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:08:38'),
(224, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Kewarganegaraan.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:08:41'),
(225, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:08:43'),
(226, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Algoritma & Dasar Pemrograman (P) disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:00'),
(227, 3, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Algoritma & Dasar Pemrograman (P) disetujui. Catatan: -', 'success', 0, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:00'),
(228, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 1, 0, '#', '2025-12-02 18:09:00'),
(229, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 2 pengajuan Algoritma & Dasar Pemrograman (P) yang disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-02 18:09:00'),
(230, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Bahasa Indonesia disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:01'),
(231, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 1 pengajuan untuk mata kuliah Bahasa Indonesia.', 'info', 1, 0, '#', '2025-12-02 18:09:01'),
(232, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 1 pengajuan Bahasa Indonesia yang disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-02 18:09:01'),
(233, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Bahasa Inggris disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:04'),
(234, 3, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Bahasa Inggris disetujui. Catatan: -', 'success', 0, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:04'),
(235, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 2 pengajuan untuk mata kuliah Bahasa Inggris.', 'info', 1, 0, '#', '2025-12-02 18:09:04'),
(236, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 2 pengajuan Bahasa Inggris yang disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-02 18:09:04'),
(237, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Kewarganegaraan disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:06'),
(238, 3, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Kewarganegaraan disetujui. Catatan: -', 'success', 0, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:06'),
(239, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 2 pengajuan untuk mata kuliah Kewarganegaraan.', 'info', 1, 0, '#', '2025-12-02 18:09:06'),
(240, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 2 pengajuan Kewarganegaraan yang disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-02 18:09:06'),
(241, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Matematika Teknik ditolak. Catatan: -', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:08'),
(242, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Matematika Teknik.', 'info', 1, 0, '#', '2025-12-02 18:09:08'),
(243, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Pendidikan Agama Islam ditolak. Catatan: -', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:09:11'),
(244, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Pendidikan Agama Islam.', 'info', 1, 0, '#', '2025-12-02 18:09:11'),
(245, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Algoritma & Dasar Pemrograman (P). Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-02 18:10:12'),
(246, 3, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Algoritma & Dasar Pemrograman (P). Silakan cek jadwal dan ambil KRS.', 'success', 0, 0, 'krs.php', '2025-12-02 18:10:12'),
(247, 2, 'dosen', NULL, NULL, 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:10:12'),
(248, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 1, 0, 'validasi_tambah_kelas.php', '2025-12-02 18:10:12'),
(249, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-03 08:11:27'),
(250, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'krs.php', '2025-12-03 08:11:27'),
(251, 1, 'dosen', 1, 'mahasiswa', 'Pengajuan KRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Pengajuan KRS).', 'warning', 1, 0, 'krs.php', '2025-12-03 08:13:06'),
(252, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-03 08:13:18'),
(253, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 1, 0, '#', '2025-12-03 08:13:56'),
(254, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'krs.php', '2025-12-03 08:13:56'),
(255, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'krs.php', '2025-12-03 08:14:23'),
(256, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 1, 0, 'krs.php', '2025-12-03 08:14:37'),
(257, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Indonesia.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:16:01'),
(258, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:16:07'),
(259, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pendidikan Agama Islam.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:16:11'),
(260, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:16:44'),
(261, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Bahasa Indonesia.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:16:47'),
(262, 2, 'dosen', 3, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Rypaldho Ridotua Hutagaol mengajukan tambah kelas Kewarganegaraan.', 'warning', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:16:50'),
(263, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Bahasa Indonesia ditolak. Catatan: gaboleh', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:17:44'),
(264, 3, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Bahasa Indonesia ditolak. Catatan: gaboleh', 'error', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:17:44');
INSERT INTO `notifikasi` (`id`, `user_id`, `user_type`, `sender_id`, `sender_type`, `judul`, `pesan`, `tipe`, `is_read`, `is_deleted`, `link`, `created_at`) VALUES
(265, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 2 pengajuan untuk mata kuliah Bahasa Indonesia.', 'info', 1, 0, '#', '2025-12-03 08:17:44'),
(266, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Algoritma & Dasar Pemrograman (P) disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:17:47'),
(267, 3, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Algoritma & Dasar Pemrograman (P) disetujui. Catatan: -', 'success', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:17:47'),
(268, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 1, 0, '#', '2025-12-03 08:17:47'),
(269, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 2 pengajuan untuk Mata Kuliah Algoritma & Dasar Pemrograman (P) yang telah disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-03 08:17:47'),
(270, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Algoritma & Dasar Pemrograman (P). Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-03 08:18:56'),
(271, 3, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Algoritma & Dasar Pemrograman (P). Silakan cek jadwal dan ambil KRS.', 'success', 0, 0, 'krs.php', '2025-12-03 08:18:56'),
(272, 2, 'dosen', NULL, NULL, 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:18:56'),
(273, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:18:56'),
(274, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Indonesia.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:31:39'),
(275, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Bahasa Indonesia disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:32:19'),
(276, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 1 pengajuan untuk mata kuliah Bahasa Indonesia.', 'info', 0, 0, '#', '2025-12-03 08:32:19'),
(277, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 1 pengajuan untuk Mata Kuliah Bahasa Indonesia yang telah disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-03 08:32:19'),
(278, 3, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Kewarganegaraan disetujui. Catatan: -', 'success', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:38:44'),
(279, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 1 pengajuan untuk mata kuliah Kewarganegaraan.', 'info', 0, 0, '#', '2025-12-03 08:38:44'),
(280, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 1 pengajuan untuk Mata Kuliah Kewarganegaraan yang telah disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-03 08:38:44'),
(281, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Pendidikan Agama Islam ditolak. Catatan: -', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:39:34'),
(282, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Pendidikan Agama Islam.', 'info', 0, 0, '#', '2025-12-03 08:39:34'),
(283, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Indonesia.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:22'),
(284, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:25'),
(285, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Inggris.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:28'),
(286, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Kewarganegaraan.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:30'),
(287, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Matematika Teknik.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:33'),
(288, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pancasila.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:41'),
(289, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pendidikan Agama Islam.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:43'),
(290, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pengantar Teknologi Informasi (P).', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:46'),
(291, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Algoritma & Dasar Pemrograman (P) disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:40:59'),
(292, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 1 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 0, 0, '#', '2025-12-03 08:40:59'),
(293, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 1 pengajuan untuk Mata Kuliah Algoritma & Dasar Pemrograman (P) yang telah disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-03 08:40:59'),
(294, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Bahasa Indonesia ditolak. Catatan: -', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:41:09'),
(295, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Bahasa Indonesia.', 'info', 0, 0, '#', '2025-12-03 08:41:09'),
(296, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Bahasa Inggris ditolak. Catatan: w', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:41:13'),
(297, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Bahasa Inggris.', 'info', 0, 0, '#', '2025-12-03 08:41:13'),
(298, 1, 'mahasiswa', 2, 'dosen', 'Pengajuan Disetujui', 'Pengajuan tambah kelas Kewarganegaraan disetujui Kaprodi dan diteruskan ke TU. Catatan: -', 'success', 1, 0, 'tambah_kelas_history.php', '2025-12-03 08:43:56'),
(299, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Pengajuan massal Kewarganegaraan disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-03 08:43:56'),
(300, 1, 'mahasiswa', 2, 'dosen', 'Pengajuan Ditolak', 'Maaf, pengajuan tambah kelas Matematika Teknik ditolak. Catatan: -', 'error', 1, 0, 'tambah_kelas_history.php', '2025-12-03 08:44:07'),
(301, 1, 'mahasiswa', 2, 'dosen', 'Pengajuan Ditolak', 'Maaf, pengajuan tambah kelas Pancasila ditolak. Catatan: -', 'error', 1, 0, 'tambah_kelas_history.php', '2025-12-03 08:44:12'),
(302, 1, 'mahasiswa', 2, 'dosen', 'Pengajuan Disetujui', 'Pengajuan tambah kelas Pengantar Teknologi Informasi (P) disetujui Kaprodi dan diteruskan ke TU. Catatan: -', 'success', 1, 0, 'tambah_kelas_history.php', '2025-12-03 08:44:22'),
(303, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Pengajuan massal Pengantar Teknologi Informasi (P) disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-03 08:44:22'),
(304, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Pendidikan Agama Islam ditolak. Catatan: -', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:44:49'),
(305, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Pendidikan Agama Islam.', 'info', 0, 0, '#', '2025-12-03 08:44:49'),
(306, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Algoritma & Dasar Pemrograman (P).', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:45:39'),
(307, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Bahasa Inggris.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:45:42'),
(308, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Matematika Teknik.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:45:43'),
(309, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pancasila.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:45:46'),
(310, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pengantar Teknologi Informasi (P).', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:45:54'),
(311, 2, 'dosen', 1, 'mahasiswa', 'Pengajuan Tambah Kelas', 'Mahasiswa Asep Prayogi mengajukan tambah kelas Pendidikan Agama Islam.', 'warning', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:46:03'),
(312, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Disetujui', 'Pengajuan tambah kelas Algoritma & Dasar Pemrograman (P) disetujui. Catatan: -', 'success', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:46:48'),
(313, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menyetujui 1 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 0, 0, '#', '2025-12-03 08:46:48'),
(314, 1, 'tata_usaha', NULL, NULL, 'Pengajuan Kelas Baru', 'Terdapat 1 pengajuan untuk Mata Kuliah Algoritma & Dasar Pemrograman (P) yang telah disetujui Kaprodi.', 'warning', 1, 0, 'pengajuan.php', '2025-12-03 08:46:48'),
(315, 1, 'mahasiswa', NULL, NULL, 'Pengajuan Ditolak', 'Pengajuan tambah kelas Bahasa Inggris ditolak. Catatan: -', 'error', 1, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:46:51'),
(316, 2, 'dosen', 2, 'dosen', 'Validasi Berhasil', 'Anda telah menolak 1 pengajuan untuk mata kuliah Bahasa Inggris.', 'info', 0, 0, '#', '2025-12-03 08:46:51'),
(317, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Algoritma & Dasar Pemrograman (P). Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-03 08:47:32'),
(318, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Algoritma & Dasar Pemrograman (P). Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-03 08:47:32'),
(319, 2, 'dosen', NULL, NULL, 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:47:32'),
(320, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Algoritma & Dasar Pemrograman (P).', 'info', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:47:32'),
(321, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Bahasa Indonesia. Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-03 08:49:17'),
(322, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Bahasa Indonesia. Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-03 08:49:17'),
(323, 2, 'dosen', NULL, NULL, 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Bahasa Indonesia.', 'info', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:49:17'),
(324, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Bahasa Indonesia.', 'info', 0, 0, 'validasi_tambah_kelas.php', '2025-12-03 08:49:17'),
(325, 1, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Bahasa Inggris. Silakan cek jadwal dan ambil KRS.', 'success', 1, 0, 'krs.php', '2025-12-03 08:50:35'),
(326, 3, 'mahasiswa', NULL, NULL, 'Kelas Dibuka!', 'Bagian TU telah memproses pengajuan tambah kelas Bahasa Inggris. Silakan cek jadwal dan ambil KRS.', 'success', 0, 0, 'krs.php', '2025-12-03 08:50:35'),
(327, 2, 'dosen', NULL, NULL, 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Bahasa Inggris.', 'info', 0, 0, 'pengajuan.php', '2025-12-03 08:50:35'),
(328, 2, 'dosen', 1, 'tata_usaha', 'Pengajuan Diproses TU', 'TU telah menyelesaikan proses 2 pengajuan untuk mata kuliah Bahasa Inggris.', 'info', 0, 0, 'pengajuan.php', '2025-12-03 08:50:35'),
(329, 1, 'mahasiswa', NULL, NULL, 'Status Revisi Aktif', 'Mode revisi telah diaktifkan. Silakan sesuaikan KRS dan AJUKAN VALIDASI ULANG.', 'info', 0, 0, '#', '2025-12-03 09:21:06'),
(330, 1, 'dosen', 1, 'mahasiswa', 'Mahasiswa Melakukan Revisi', 'Mahasiswa Asep Prayogi (230411100073) telah membuka kembali KRS-nya untuk revisi.', 'info', 1, 0, 'krs.php', '2025-12-03 09:21:06'),
(331, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'krs.php', '2025-12-03 09:21:10'),
(332, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'krs.php', '2025-12-03 09:22:12'),
(333, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'krs.php', '2025-12-03 09:24:16'),
(334, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'validasi_krs.php', '2025-12-03 09:25:50'),
(335, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 1, 0, 'perwalian.php', '2025-12-03 09:26:53'),
(336, 1, 'mahasiswa', 1, 'dosen', 'KRS Ditolak/Revisi', 'Terdapat perbaikan pada KRS Anda. Catatan Dosen: ', 'error', 0, 0, 'validasi_krs.php', '2025-12-03 09:27:08'),
(337, 1, 'dosen', 1, 'mahasiswa', 'Revisi KPRS Masuk', 'Mahasiswa Asep Prayogi mengajukan validasi (Revisi KPRS).', 'warning', 0, 0, 'perwalian.php', '2025-12-03 09:27:45'),
(338, 1, 'mahasiswa', 1, 'dosen', 'KRS Disetujui', 'KRS Anda telah disetujui oleh Dosen Wali. Anda dapat mencetak Kartu Studi sekarang.', 'success', 0, 0, 'validasi_krs.php', '2025-12-03 09:27:57');

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

--
-- Dumping data for table `pengajuan_tambah_kelas`
--

INSERT INTO `pengajuan_tambah_kelas` (`id`, `mahasiswa_id`, `mata_kuliah_id`, `kelas_id`, `alasan`, `status`, `tanggal_pengajuan`, `tanggal_validasi`, `validator_id`, `catatan_validasi`, `created_at`) VALUES
(1, 1, 5, NULL, 'knadk', 'completed', '2025-12-02 04:28:50', '2025-12-02 04:29:57', 2, '', '2025-12-02 04:28:50'),
(2, 3, 5, NULL, 'wsw', 'completed', '2025-12-02 04:29:22', '2025-12-02 04:30:02', 2, 'eeeee', '2025-12-02 04:29:22'),
(3, 3, 3, NULL, 'sdsd', 'rejected', '2025-12-02 04:29:30', '2025-12-02 04:30:07', 2, 'dadaa', '2025-12-02 04:29:30'),
(4, 1, 8, NULL, 'sdad', 'completed', '2025-12-02 15:44:37', '2025-12-02 15:52:48', 2, 'oke kelas akan terbuka nanti', '2025-12-02 15:44:37'),
(5, 1, 3, NULL, 'w', 'completed', '2025-12-02 15:45:08', '2025-12-02 16:08:11', 2, 'buarkan kelasnya', '2025-12-02 15:45:08'),
(6, 1, 7, NULL, 'www', 'rejected', '2025-12-02 15:45:14', '2025-12-02 15:53:07', 2, 'tidak saya buka karena alasan tertentu', '2025-12-02 15:45:14'),
(7, 1, 4, NULL, 'www', 'completed', '2025-12-02 15:45:27', '2025-12-02 16:36:43', 2, 'ww', '2025-12-02 15:45:27'),
(8, 3, 8, NULL, 'ww', 'completed', '2025-12-02 15:46:03', '2025-12-02 15:52:48', 2, 'oke kelas akan terbuka nanti', '2025-12-02 15:46:03'),
(9, 3, 5, NULL, 'ee', 'rejected', '2025-12-02 15:46:05', '2025-12-02 16:08:21', 2, 'saya tidak bisa', '2025-12-02 15:46:05'),
(10, 3, 3, NULL, 'fff', 'completed', '2025-12-02 15:46:17', '2025-12-02 16:08:11', 2, 'buarkan kelasnya', '2025-12-02 15:46:17'),
(11, 3, 4, NULL, 'fff', 'completed', '2025-12-02 15:46:19', '2025-12-02 16:36:43', 2, 'ww', '2025-12-02 15:46:19'),
(12, 1, 1, NULL, 'sssss', 'rejected', '2025-12-02 16:07:24', '2025-12-02 16:36:47', 2, 'ww', '2025-12-02 16:07:24'),
(13, 1, 6, NULL, 'sssssss', 'completed', '2025-12-02 16:07:28', '2025-12-02 16:29:53', 2, 'saya terima', '2025-12-02 16:07:28'),
(14, 3, 6, NULL, 'ssssss', 'completed', '2025-12-02 16:07:42', '2025-12-02 16:29:53', 2, 'saya terima', '2025-12-02 16:07:42'),
(15, 3, 7, NULL, 'sssssss', 'rejected', '2025-12-02 16:07:47', '2025-12-02 16:29:45', 2, 'saya tolak', '2025-12-02 16:07:47'),
(17, 1, 3, NULL, 'ww', 'rejected', '2025-12-02 16:38:33', '2025-12-02 17:00:33', 2, '', '2025-12-02 16:38:33'),
(18, 1, 5, NULL, 'd', 'completed', '2025-12-02 16:38:35', '2025-12-02 17:12:32', 2, '', '2025-12-02 16:38:35'),
(28, 1, 8, NULL, 'sbc', 'completed', '2025-12-02 18:07:45', '2025-12-02 18:09:00', 2, '', '2025-12-02 18:07:45'),
(29, 1, 3, NULL, 'aba', 'completed', '2025-12-02 18:07:50', '2025-12-02 18:09:01', 2, '', '2025-12-02 18:07:50'),
(30, 1, 5, NULL, 'aba', 'completed', '2025-12-02 18:07:52', '2025-12-02 18:09:04', 2, '', '2025-12-02 18:07:52'),
(31, 1, 2, NULL, 'aba', 'approved', '2025-12-02 18:07:55', '2025-12-02 18:09:06', 2, '', '2025-12-02 18:07:55'),
(32, 1, 7, NULL, 'aba', 'rejected', '2025-12-02 18:07:58', '2025-12-02 18:09:08', 2, '', '2025-12-02 18:07:58'),
(33, 1, 1, NULL, 'aba', 'rejected', '2025-12-02 18:08:02', '2025-12-02 18:09:11', 2, '', '2025-12-02 18:08:02'),
(34, 3, 5, NULL, 'da', 'completed', '2025-12-02 18:08:38', '2025-12-02 18:09:04', 2, '', '2025-12-02 18:08:38'),
(35, 3, 2, NULL, 'da', 'approved', '2025-12-02 18:08:41', '2025-12-02 18:09:06', 2, '', '2025-12-02 18:08:41'),
(36, 3, 8, NULL, 'ada', 'completed', '2025-12-02 18:08:43', '2025-12-02 18:09:00', 2, '', '2025-12-02 18:08:43'),
(37, 1, 3, NULL, 'qeq', 'rejected', '2025-12-03 08:16:01', '2025-12-03 08:17:44', 2, 'gaboleh', '2025-12-03 08:16:01'),
(38, 1, 8, NULL, 'we', 'completed', '2025-12-03 08:16:07', '2025-12-03 08:17:47', 2, '', '2025-12-03 08:16:07'),
(39, 1, 1, NULL, 'we', 'rejected', '2025-12-03 08:16:11', '2025-12-03 08:39:34', 2, '', '2025-12-03 08:16:11'),
(40, 3, 8, NULL, 'wrwe', 'completed', '2025-12-03 08:16:44', '2025-12-03 08:17:47', 2, '', '2025-12-03 08:16:44'),
(41, 3, 3, NULL, 'we', 'rejected', '2025-12-03 08:16:47', '2025-12-03 08:17:44', 2, 'gaboleh', '2025-12-03 08:16:47'),
(42, 3, 2, NULL, 'wew', 'approved', '2025-12-03 08:16:50', '2025-12-03 08:38:44', 2, '', '2025-12-03 08:16:50'),
(43, 1, 3, NULL, 's', 'completed', '2025-12-03 08:31:39', '2025-12-03 08:32:19', 2, '', '2025-12-03 08:31:39'),
(44, 1, 3, NULL, 'weawe', 'rejected', '2025-12-03 08:40:22', '2025-12-03 08:41:09', 2, '', '2025-12-03 08:40:22'),
(45, 1, 8, NULL, 'ewew', 'completed', '2025-12-03 08:40:25', '2025-12-03 08:40:59', 2, '', '2025-12-03 08:40:25'),
(46, 1, 5, NULL, 'awewa', 'rejected', '2025-12-03 08:40:28', '2025-12-03 08:41:13', 2, 'w', '2025-12-03 08:40:28'),
(47, 1, 2, NULL, 'awea', 'approved', '2025-12-03 08:40:30', '2025-12-03 08:43:56', 2, '', '2025-12-03 08:40:30'),
(48, 1, 7, NULL, 'waew', 'rejected', '2025-12-03 08:40:33', '2025-12-03 08:44:07', 2, '', '2025-12-03 08:40:33'),
(49, 1, 4, NULL, 'waewae', 'rejected', '2025-12-03 08:40:41', '2025-12-03 08:44:12', 2, '', '2025-12-03 08:40:41'),
(50, 1, 1, NULL, 'awewa', 'rejected', '2025-12-03 08:40:43', '2025-12-03 08:44:49', 2, '', '2025-12-03 08:40:43'),
(51, 1, 6, NULL, 'aweawe', 'approved', '2025-12-03 08:40:46', '2025-12-03 08:44:22', 2, '', '2025-12-03 08:40:46'),
(52, 1, 8, NULL, '123', 'completed', '2025-12-03 08:45:39', '2025-12-03 08:46:48', 2, '', '2025-12-03 08:45:39'),
(53, 1, 5, NULL, '123', 'rejected', '2025-12-03 08:45:42', '2025-12-03 08:46:51', 2, '', '2025-12-03 08:45:42'),
(54, 1, 7, NULL, '123', 'pending', '2025-12-03 08:45:43', NULL, NULL, NULL, '2025-12-03 08:45:43'),
(55, 1, 4, NULL, '123', 'pending', '2025-12-03 08:45:46', NULL, NULL, NULL, '2025-12-03 08:45:46'),
(56, 1, 6, NULL, '12313', 'pending', '2025-12-03 08:45:54', NULL, NULL, NULL, '2025-12-03 08:45:54'),
(57, 1, 1, NULL, '123', 'pending', '2025-12-03 08:46:03', NULL, NULL, NULL, '2025-12-03 08:46:03');

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
-- Table structure for table `reminder_kprs`
--

CREATE TABLE `reminder_kprs` (
  `id` int(11) NOT NULL,
  `kprs_id` int(11) DEFAULT NULL,
  `hari_reminder` int(11) DEFAULT NULL,
  `tanggal_reminder` date DEFAULT NULL,
  `status` enum('pending','sent') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `kprs`
--
ALTER TABLE `kprs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `validator_id` (`validator_id`);

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
-- Indexes for table `reminder_kprs`
--
ALTER TABLE `reminder_kprs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kprs_id` (`kprs_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `kprs`
--
ALTER TABLE `kprs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `krs_awal`
--
ALTER TABLE `krs_awal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=339;

--
-- AUTO_INCREMENT for table `pengajuan_tambah_kelas`
--
ALTER TABLE `pengajuan_tambah_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reminder_kprs`
--
ALTER TABLE `reminder_kprs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tata_usaha`
--
ALTER TABLE `tata_usaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Constraints for table `kprs`
--
ALTER TABLE `kprs`
  ADD CONSTRAINT `kprs_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`),
  ADD CONSTRAINT `kprs_ibfk_2` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`),
  ADD CONSTRAINT `kprs_ibfk_3` FOREIGN KEY (`validator_id`) REFERENCES `dosen` (`id`);

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

--
-- Constraints for table `reminder_kprs`
--
ALTER TABLE `reminder_kprs`
  ADD CONSTRAINT `reminder_kprs_ibfk_1` FOREIGN KEY (`kprs_id`) REFERENCES `kprs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
