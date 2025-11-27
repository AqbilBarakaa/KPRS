-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2025 at 07:29 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
  `id` int NOT NULL,
  `nidn` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `prodi` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id`, `nidn`, `nama`, `password`, `email`, `jabatan`, `prodi`, `created_at`) VALUES
(1, '001234', 'Dr. Rika Yunitari', 'hashed_password', 'rika@univ.ac.id', 'DPA', 'Teknik Informatika', '2025-11-27 07:12:08'),
(2, '001235', 'Prof. Ahmad S.T., M.T.', 'hashed_password', 'ahmad@univ.ac.id', 'Kaprodi', 'Teknik Informatika', '2025-11-27 07:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int NOT NULL,
  `mata_kuliah_id` int DEFAULT NULL,
  `kode_kelas` varchar(10) DEFAULT NULL,
  `nama_kelas` varchar(50) DEFAULT NULL,
  `dosen_pengampu_id` int DEFAULT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `kuota` int DEFAULT NULL,
  `terisi` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kprs`
--

CREATE TABLE `kprs` (
  `id` int NOT NULL,
  `mahasiswa_id` int DEFAULT NULL,
  `jenis_aksi` enum('tambah','hapus') NOT NULL,
  `kelas_id` int DEFAULT NULL,
  `alasan` text,
  `status` enum('pending','approved','rejected','auto_approved') DEFAULT 'pending',
  `tanggal_pengajuan` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_validasi` timestamp NULL DEFAULT NULL,
  `validator_id` int DEFAULT NULL,
  `catatan_validasi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `krs_awal`
--

CREATE TABLE `krs_awal` (
  `id` int NOT NULL,
  `mahasiswa_id` int DEFAULT NULL,
  `kelas_id` int DEFAULT NULL,
  `status` enum('terdaftar','selesai','drop') DEFAULT 'terdaftar',
  `tanggal_daftar` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `prodi` varchar(50) DEFAULT NULL,
  `semester` int DEFAULT NULL,
  `dpa_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama`, `password`, `email`, `prodi`, `semester`, `dpa_id`, `created_at`) VALUES
(1, '230411100073', 'Asep Prayogi', '123', 'asep@student.univ.ac.id', 'Teknik Informatika', 3, 1, '2025-11-27 07:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int NOT NULL,
  `kode_mk` varchar(20) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` int NOT NULL,
  `semester` int DEFAULT NULL,
  `prodi_id` int DEFAULT NULL,
  `kuota` int DEFAULT '0',
  `prasyarat` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `user_type` enum('mahasiswa','dosen','tata_usaha') NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('info','warning','success','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT '0',
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_tambah_kelas`
--

CREATE TABLE `pengajuan_tambah_kelas` (
  `id` int NOT NULL,
  `mahasiswa_id` int DEFAULT NULL,
  `mata_kuliah_id` int DEFAULT NULL,
  `kelas_id` int DEFAULT NULL,
  `alasan` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `tanggal_pengajuan` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_validasi` timestamp NULL DEFAULT NULL,
  `validator_id` int DEFAULT NULL,
  `catatan_validasi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periode_akademik`
--

CREATE TABLE `periode_akademik` (
  `id` int NOT NULL,
  `tahun_akademik` varchar(10) DEFAULT NULL,
  `semester` enum('Ganjil','Genap') DEFAULT NULL,
  `tanggal_mulai_krs` date DEFAULT NULL,
  `tanggal_selesai_krs` date DEFAULT NULL,
  `tanggal_mulai_kprs` date DEFAULT NULL,
  `tanggal_selesai_kprs` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id` int NOT NULL,
  `kode_prodi` varchar(10) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  `kaprodi_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id`, `kode_prodi`, `nama_prodi`, `kaprodi_id`, `created_at`) VALUES
(1, 'TI', 'Teknik Informatika', 2, '2025-11-27 07:12:08');

-- --------------------------------------------------------

--
-- Table structure for table `reminder_kprs`
--

CREATE TABLE `reminder_kprs` (
  `id` int NOT NULL,
  `kprs_id` int DEFAULT NULL,
  `hari_reminder` int DEFAULT NULL,
  `tanggal_reminder` date DEFAULT NULL,
  `status` enum('pending','sent') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tata_usaha`
--

CREATE TABLE `tata_usaha` (
  `id` int NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nidn` (`nidn`),
  ADD KEY `idx_dosen_nidn` (`nidn`);

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
  ADD KEY `kelas_id` (`kelas_id`),
  ADD KEY `validator_id` (`validator_id`),
  ADD KEY `idx_kprs_status` (`status`),
  ADD KEY `idx_kprs_mahasiswa` (`mahasiswa_id`);

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
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `dpa_id` (`dpa_id`),
  ADD KEY `idx_mahasiswa_nim` (`nim`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifikasi_user` (`user_id`,`user_type`);

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
  ADD UNIQUE KEY `kode_prodi` (`kode_prodi`),
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kprs`
--
ALTER TABLE `kprs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `krs_awal`
--
ALTER TABLE `krs_awal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuan_tambah_kelas`
--
ALTER TABLE `pengajuan_tambah_kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `periode_akademik`
--
ALTER TABLE `periode_akademik`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reminder_kprs`
--
ALTER TABLE `reminder_kprs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tata_usaha`
--
ALTER TABLE `tata_usaha`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`dpa_id`) REFERENCES `dosen` (`id`),
  ADD CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`dpa_id`) REFERENCES `dosen` (`id`);

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
  ADD CONSTRAINT `program_studi_ibfk_1` FOREIGN KEY (`kaprodi_id`) REFERENCES `dosen` (`id`),
  ADD CONSTRAINT `program_studi_ibfk_2` FOREIGN KEY (`kaprodi_id`) REFERENCES `dosen` (`id`);

--
-- Constraints for table `reminder_kprs`
--
ALTER TABLE `reminder_kprs`
  ADD CONSTRAINT `reminder_kprs_ibfk_1` FOREIGN KEY (`kprs_id`) REFERENCES `kprs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
