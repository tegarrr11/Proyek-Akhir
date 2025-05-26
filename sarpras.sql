-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 09:01 AM
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
-- Database: `sarpras`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_peminjaman`
--

CREATE TABLE `detail_peminjaman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `peminjaman_id` bigint(20) UNSIGNED NOT NULL,
  `fasilitas_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_peminjaman`
--

INSERT INTO `detail_peminjaman` (`id`, `peminjaman_id`, `fasilitas_id`, `jumlah`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100, '2025-05-19 09:40:49', '2025-05-19 09:40:49'),
(2, 3, 1, 50, '2025-05-19 10:05:22', '2025-05-19 10:05:22');

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gedung_id` bigint(20) UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fasilitas`
--

INSERT INTO `fasilitas` (`id`, `gedung_id`, `nama_barang`, `stok`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 2, 'Meja Tamu', 0, 0, '2025-05-19 09:40:11', '2025-05-19 10:05:22');

-- --------------------------------------------------------

--
-- Table structure for table `gedungs`
--

CREATE TABLE `gedungs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `kapasitas` int(11) NOT NULL,
  `jam_operasional` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gedungs`
--

INSERT INTO `gedungs` (`id`, `slug`, `nama`, `deskripsi`, `kapasitas`, `jam_operasional`, `created_at`, `updated_at`) VALUES
(1, 'auditorium', 'Auditorium', 'Untuk seminar dan workshop', 200, '08:00 - 17:00', '2025-05-19 09:39:53', '2025-05-19 09:39:53'),
(2, 'gsg', 'Gedung Serbaguna', 'Gedung Serbaguna untuk berbagai acara', 500, '07:00 - 18:00', '2025-05-19 09:39:53', '2025-05-19 09:39:53'),
(3, 'gor', 'GOR', 'Gedung olahraga indoor', 300, '07:00 - 20:00', '2025-05-19 09:39:53', '2025-05-19 09:39:53'),
(4, 'fasilitas-lainnya', 'Fasilitas Lainnya', 'Menampung fasilitas umum di luar gedung utama', 0, '00:00 - 23:59', '2025-05-19 09:39:53', '2025-05-19 09:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2025_05_17_083252_create_gedungs_table', 1),
(3, '2025_05_17_083252_create_roles_table', 1),
(4, '2025_05_17_083253_create_fasilitas_table', 1),
(5, '2025_05_17_083253_create_peminjaman_table', 1),
(6, '2025_05_17_083322_create_detail_peminjaman_table', 1),
(7, '2025_05_17_083322_create_pemeliharaan_table', 1),
(8, '2025_05_19_050352_add_verifikasi_columns_to_peminjaman_table', 1),
(9, '2025_05_19_061032_create_cache_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pemeliharaan`
--

CREATE TABLE `pemeliharaan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fasilitas_id` bigint(20) UNSIGNED NOT NULL,
  `tgl_pemeliharaan` date NOT NULL,
  `deskripsi` text NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul_kegiatan` varchar(255) NOT NULL,
  `tgl_kegiatan` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_berakhir` time NOT NULL,
  `aktivitas` varchar(50) NOT NULL,
  `organisasi` varchar(255) NOT NULL,
  `penanggung_jawab` varchar(255) NOT NULL,
  `deskripsi_kegiatan` text NOT NULL,
  `status` enum('menunggu','diterima','ditolak') NOT NULL,
  `gedung_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `approver_dosen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `approver_rt_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verifikasi_bem` enum('diajukan','diterima','ditolak') NOT NULL DEFAULT 'diajukan',
  `verifikasi_sarpras` enum('diajukan','diterima','ditolak') NOT NULL DEFAULT 'diajukan',
  `status_peminjaman` enum('ambil','kembalikan') DEFAULT NULL,
  `status_pengembalian` enum('proses','selesai') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `judul_kegiatan`, `tgl_kegiatan`, `waktu_mulai`, `waktu_berakhir`, `aktivitas`, `organisasi`, `penanggung_jawab`, `deskripsi_kegiatan`, `status`, `gedung_id`, `user_id`, `approver_dosen_id`, `approver_rt_id`, `created_at`, `updated_at`, `verifikasi_bem`, `verifikasi_sarpras`, `status_peminjaman`, `status_pengembalian`) VALUES
(1, 'CB', '2025-05-22', '23:41:00', '23:43:00', 'Capacity Building', 'HIMA', 'AAZ - Alvin Alvarez', 'jalan', 'menunggu', 2, 2, NULL, NULL, '2025-05-19 09:40:49', '2025-05-19 11:40:32', 'diterima', 'diterima', NULL, NULL),
(2, 'CB', '2025-05-22', '23:41:00', '23:43:00', 'Capacity Building', 'HIMA', 'AAZ - Alvin Alvarez', 'jalan', 'menunggu', 2, 2, NULL, NULL, '2025-05-19 09:42:34', '2025-05-19 11:40:30', 'diterima', 'diterima', NULL, NULL),
(3, 'CB', '2025-05-22', '00:07:00', '00:09:00', 'Capacity Building', 'BEM', 'AAZ - Alvin Alvarez', 'jalan', 'menunggu', 2, 2, NULL, NULL, '2025-05-19 10:05:22', '2025-05-19 12:00:50', 'diterima', 'diterima', NULL, 'selesai');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` enum('admin','bem','mahasiswa','dosen') NOT NULL,
  `permission` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','bem','mahasiswa','dosen') NOT NULL DEFAULT 'mahasiswa',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', 'admin', NULL, '$2y$12$VLenzsKvkEd2/lynF5ohm.JQyEP99ypZkrj6xnuD86KeFTvr6tCUK', NULL, '2025-05-19 09:40:03', '2025-05-19 09:40:03'),
(2, 'Mahasiswa User', 'mahasiswa@example.com', 'mahasiswa', NULL, '$2y$12$Zhpykh43Vo8VRCBoqI/mNOLKZWvb0K9t0XIWCO4WrUxDX8jwS26ZS', NULL, '2025-05-19 09:40:19', '2025-05-19 09:40:19'),
(3, 'Bem User', 'bem@example.com', 'bem', NULL, '$2y$12$84FIvZIpRiIsBp26yp/9ue7EI/kJVUM0.R75ohKcBXUkJcapQ5oLC', NULL, '2025-05-19 10:11:14', '2025-05-19 10:11:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_peminjaman_peminjaman_id_foreign` (`peminjaman_id`),
  ADD KEY `detail_peminjaman_fasilitas_id_foreign` (`fasilitas_id`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fasilitas_gedung_id_foreign` (`gedung_id`);

--
-- Indexes for table `gedungs`
--
ALTER TABLE `gedungs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gedungs_slug_unique` (`slug`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pemeliharaan`
--
ALTER TABLE `pemeliharaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pemeliharaan_fasilitas_id_foreign` (`fasilitas_id`),
  ADD KEY `pemeliharaan_user_id_foreign` (`user_id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_gedung_id_foreign` (`gedung_id`),
  ADD KEY `peminjaman_user_id_foreign` (`user_id`),
  ADD KEY `peminjaman_approver_dosen_id_foreign` (`approver_dosen_id`),
  ADD KEY `peminjaman_approver_rt_id_foreign` (`approver_rt_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gedungs`
--
ALTER TABLE `gedungs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pemeliharaan`
--
ALTER TABLE `pemeliharaan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_peminjaman`
--
ALTER TABLE `detail_peminjaman`
  ADD CONSTRAINT `detail_peminjaman_fasilitas_id_foreign` FOREIGN KEY (`fasilitas_id`) REFERENCES `fasilitas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_peminjaman_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjaman` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD CONSTRAINT `fasilitas_gedung_id_foreign` FOREIGN KEY (`gedung_id`) REFERENCES `gedungs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pemeliharaan`
--
ALTER TABLE `pemeliharaan`
  ADD CONSTRAINT `pemeliharaan_fasilitas_id_foreign` FOREIGN KEY (`fasilitas_id`) REFERENCES `fasilitas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemeliharaan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_approver_dosen_id_foreign` FOREIGN KEY (`approver_dosen_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `peminjaman_approver_rt_id_foreign` FOREIGN KEY (`approver_rt_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `peminjaman_gedung_id_foreign` FOREIGN KEY (`gedung_id`) REFERENCES `gedungs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
