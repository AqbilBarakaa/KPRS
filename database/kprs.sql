-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2025 at 05:29 PM
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
(1, 8, 'A', 'IF 1A', 'F101', 4, 'Senin', '07:00:00', '09:30:00', 40, 38, '2025-11-29 13:03:00'),
(2, 8, 'B', 'IF 1B', 'F102', 5, 'Senin', '09:30:00', '12:00:00', 40, 40, '2025-11-29 13:03:00'),
(3, 8, 'C', 'IF 1C', 'F103', 4, 'Selasa', '07:00:00', '09:30:00', 40, 35, '2025-11-29 13:03:00'),
(4, 8, 'D', 'IF 1D', 'F104', 5, 'Selasa', '12:30:00', '15:00:00', 40, 20, '2025-11-29 13:03:00'),
(5, 6, 'A', 'IF 1A', 'F105', 1, 'Rabu', '07:00:00', '09:30:00', 40, 40, '2025-11-29 13:03:00'),
(6, 6, 'B', 'IF 1B', 'F106', 1, 'Rabu', '09:30:00', '12:00:00', 40, 38, '2025-11-29 13:03:00'),
(7, 16, 'A', 'IF 3A', 'F201', 1, 'Kamis', '07:00:00', '09:30:00', 35, 30, '2025-11-29 13:03:00'),
(8, 16, 'B', 'IF 3B', 'F202', 4, 'Kamis', '09:30:00', '12:00:00', 35, 32, '2025-11-29 13:03:00'),
(9, 16, 'C', 'IF 3C', 'F203', 1, 'Jumat', '08:00:00', '10:30:00', 35, 35, '2025-11-29 13:03:00'),
(10, 17, 'A', 'IF 3A', 'F204', 5, 'Senin', '12:30:00', '15:00:00', 30, 25, '2025-11-29 13:03:00'),
(11, 17, 'B', 'IF 3B', 'F205', 2, 'Selasa', '07:00:00', '09:30:00', 30, 28, '2025-11-29 13:03:00'),
(12, 36, 'A', 'IF 5A', 'F301', 2, 'Rabu', '12:30:00', '15:00:00', 40, 15, '2025-11-29 13:03:00'),
(13, 36, 'B', 'IF 5B', 'F302', 2, 'Kamis', '07:00:00', '09:30:00', 40, 10, '2025-11-29 13:03:00'),
(14, 37, 'A', 'IF 5A', 'F303', 1, 'Jumat', '13:00:00', '15:30:00', 25, 25, '2025-11-29 13:03:00'),
(15, 37, 'B', 'IF 5B', 'F304', 1, 'Senin', '09:30:00', '12:00:00', 25, 22, '2025-11-29 13:03:00'),
(16, 63, 'A', 'IF 7A', 'F401', 4, 'Selasa', '09:30:00', '12:00:00', 30, 29, '2025-11-29 13:03:00'),
(17, 63, 'B', 'IF 7B', 'F402', 4, 'Rabu', '12:30:00', '15:00:00', 30, 12, '2025-11-29 13:03:00'),
(18, 65, 'A', 'IF 7A', 'F403', 5, 'Kamis', '12:30:00', '15:00:00', 30, 30, '2025-11-29 13:03:00'),
(19, 47, NULL, 'IF 5A', 'F304', 1, 'Kamis', '07:00:00', '09:30:00', 40, 0, '2025-11-29 13:19:58');

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
  `status` enum('terdaftar','selesai','drop') DEFAULT 'terdaftar',
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
  `dpa_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `jenis_kelamin`, `password`, `email`, `prodi`, `semester`, `dpa_id`, `created_at`, `foto`) VALUES
(1, '230411100073', 'Asep Prayogi', 'L', '$2y$10$uF4r4DVgpRxxPEqWBAVvveanxAdM7D//QpyBnIAArihSyyOPusK.m', 'asep@student.univ.ac.id', 'Teknik Informatika', 5, 1, '2025-11-27 07:12:08', '230411100073_1764407735.jpg'),
(3, '230411100076', 'Rypaldho Ridotua Hutagaol', 'L', '$2y$10$Wqn7V79IM3y7J.Z/SnSDIOjpoxVEy05lJjIOFas2fzny6oj5fCJ62', 'paldo@student.univ.ac.id', 'Teknik Informatika', 5, 1, '2025-11-29 09:16:27', '230411100076_1764407787.jpg');

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
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('info','warning','success','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
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
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
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
(1, 1, 8, 2, 'Saya ingin mengambil Mata Kuliah ini tetapi sudah penuh', 'pending', '2025-11-29 13:13:22', NULL, NULL, NULL, '2025-11-29 13:13:22');

-- --------------------------------------------------------

--
-- Table structure for table `periode_akademik`
--

CREATE TABLE `periode_akademik` (
  `id` int(11) NOT NULL,
  `tahun_akademik` varchar(10) DEFAULT NULL,
  `semester` enum('Ganjil','Genap') DEFAULT NULL,
  `tanggal_mulai_krs` date DEFAULT NULL,
  `tanggal_selesai_krs` date DEFAULT NULL,
  `tanggal_mulai_kprs` date DEFAULT NULL,
  `tanggal_selesai_kprs` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '123', 'Staff', '123', 'tu@univ.ac.id', '2025-11-29 08:30:04', 'TU_123_1764413631.jpeg');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `kprs`
--
ALTER TABLE `kprs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `krs_awal`
--
ALTER TABLE `krs_awal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuan_tambah_kelas`
--
ALTER TABLE `pengajuan_tambah_kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reminder_kprs`
--
ALTER TABLE `reminder_kprs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
