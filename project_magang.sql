-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 15, 2025 at 07:31 AM
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
-- Database: `project_magang`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evidences`
--

CREATE TABLE `evidences` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `file_path` json NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evidences`
--

INSERT INTO `evidences` (`id`, `user_id`, `lokasi`, `deskripsi`, `file_path`, `status`, `catatan_admin`, `created_at`, `updated_at`) VALUES
(2, 3, 'Banjarmasin', 'ASDAD', '[\"evidences/cKzLvUPmIkY7hBm2jL4juftdFlhtYdOEoRtXyi7M.jpg\", \"evidences/B183gUIDXkoGNUY7MzrDIGx4t62Oy6CmNDgvUP52.jpg\", \"evidences/VjiT9woItnhz36cdem6cFvzpPXwXpVdomJEozUiz.jpg\"]', 'approved', NULL, '2025-10-09 19:30:12', '2025-10-09 22:24:13'),
(16, 3, 'Banjarmasin', 'dasad', '[{\"path\": \"evidences/ue1yV2cdAVOmavivirCNIHn2MYh9dmS9gOSFjmC5.jpg\", \"caption\": \"Banjarmasin\"}, {\"path\": \"evidences/bxgVowuo9h7Ht8bkIzR9SDVFDOhX6THMP1IE0wXw.jpg\", \"caption\": \"Banjarmasin\"}]', 'approved', NULL, '2025-10-13 21:41:26', '2025-10-13 22:02:41'),
(17, 3, 'Jl. A. Yani Km. 3,5 Banjarmasin,', 'DASD', '[{\"path\": \"evidences/Gyxkkh3rQ5eyCdchkbogS95uAA6QRpMzLih6j0ij.jpg\", \"caption\": \"Jl. A. Yani Km. 3,5 Banjarmasin,\"}, {\"path\": \"evidences/BCJelyHtocFmb3tEu7vG3yYK355Smqz4jAHya74N.jpg\", \"caption\": \"Jl. A. Yani Km. 3,5 Banjarmasin,\"}]', 'approved', NULL, '2025-10-13 21:41:45', '2025-10-13 22:02:39'),
(18, 3, 'Banjarmasin', 'DASDA', '[{\"path\": \"evidences/BNRz9AiyrXziswcetFfhrHETdaAaNVSvYVv7oaHE.jpg\", \"caption\": \"Banjarmasin\"}, {\"path\": \"evidences/jgvq0cKPToiJKJvCIwoXKjI20T5YM2B2WbjxO9Tp.jpg\", \"caption\": \"Banjarmasin\"}, {\"path\": \"evidences/YpRNUNIlfmp6oppvsC7xkp7eoMrwebeFRffliQ7k.jpg\", \"caption\": \"Banjarmasin\"}, {\"path\": \"evidences/RHAwT8K4lCcJslXc1rLOGDfyiqWMYiMgK1CZ1dgm.jpg\", \"caption\": \"Banjarmasin\"}, {\"path\": \"evidences/7BO6wQL0N3p13LPTrEKaNgxCQjCg8Fg3o78qi2zN.jpg\", \"caption\": \"Banjarmasin\"}, {\"path\": \"evidences/sPfBlw4VTDhvrHLGTmhwDQpJmexk0JEX7bdv8XHQ.jpg\", \"caption\": \"Banjarmasin\"}]', 'approved', NULL, '2025-10-13 22:01:42', '2025-10-13 22:02:36'),
(19, 3, 'Jl. A. Yani Km. 3,5 Banjarmasin', 'Perbaikan Pathcore 15 M', '[{\"path\": \"evidences/A4574gujmsHHnJFQkvkyOgIRJLA85XVL7TzY0gN8.jpg\", \"caption\": \"Perbaikan\"}, {\"path\": \"evidences/UMYmxfbDfsWO316JT4zVC3TbXsPTSG1IeYe47SY8.jpg\", \"caption\": \"Before\"}, {\"path\": \"evidences/bP3dhiJx9a5NZrFM64xWviHFNnG5LnVNpvOVgQaz.jpg\", \"caption\": \"After\"}]', 'approved', NULL, '2025-10-13 22:06:28', '2025-10-13 22:06:54'),
(20, 3, 'Banjarmasin', 'asafasf', '[{\"path\": \"evidences/1kZqsWjd1bAiM4QAATv99V3ixltj6De8ZJuWOzLs.jpg\", \"caption\": \"TEST\"}, {\"path\": \"evidences/SyAGOjNop7pp4IgnFj5il6ypBt5fDICcsSRNLAji.jpg\", \"caption\": \"TEST\"}, {\"path\": \"evidences/QvjIY79E6merZBH3aPrfFyRVNONuoavLKDL2X3i8.jpg\", \"caption\": \"TEST\"}, {\"path\": \"evidences/3zTa6k9orQgpzwBdBFcpKBINwuGszbOSfFJIZk0h.jpg\", \"caption\": \"TEST\"}, {\"path\": \"evidences/C9d8M8NqyWq75yfNomuBBXAVfy5MqmEV16Sf9cYz.jpg\", \"caption\": \"YTEST\"}, {\"path\": \"evidences/2pby7bESITQHiYdAXhEkhPBS6trfmvMenKemCGjk.jpg\", \"caption\": \"TEST\"}]', 'approved', NULL, '2025-10-13 22:28:51', '2025-10-13 22:30:04'),
(21, 3, 'Jl. H.Djok Mentaya Kel No.108, RT.10/RW.002, Mawar, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70112', 'sddad', '[{\"path\": \"evidences/77A94JD8gHCND1HgLFalbY6sIxTRuWmzuzIfg1mJ.jpg\", \"caption\": \"1\"}, {\"path\": \"evidences/486jPcjA23jcTyZoNXOagm14rbwilnE347cZOtox.jpg\", \"caption\": \"2\"}, {\"path\": \"evidences/cByPKivpbw03vAn3LpDfE3ntBlnsSG9P7nihe6fe.jpg\", \"caption\": \"3\"}, {\"path\": \"evidences/jh5MFVmYbEVV7lX2Ka08ovkJekHhphs6etAlnkMi.jpg\", \"caption\": \"4\"}, {\"path\": \"evidences/DvkSasHXnWZsny7LpvnUKPRJ7M3z8odVnZzC6sR5.jpg\", \"caption\": \"5\"}, {\"path\": \"evidences/MDe3gJRjkDlSO2OApEvtuNu1OErvIKBlg1x2BR7u.jpg\", \"caption\": \"6\"}]', 'approved', NULL, '2025-10-13 23:19:07', '2025-10-13 23:19:48'),
(22, 3, 'Jl.Djoke Mentaya', 'Pemsanagan Kabel PATCH CORE', '[{\"path\": \"evidences/Jx2cKzeWAzUoWg8WAPA5Gs2mvTGOaFVn7AChFZIs.jpg\", \"caption\": \"TEST\"}, {\"path\": \"evidences/7uClr82GJOvYpD0MTbOIpGbD7PmhILeZPZy4XvWr.jpg\", \"caption\": \"1\"}, {\"path\": \"evidences/oqq1qpnDuonEHbStbuHtw1orCCs2lIFIErg4vCrq.jpg\", \"caption\": \"2\"}, {\"path\": \"evidences/qLoZsU2HUx8Kv5rh9nP4WJPt8PgRnlliFiTLRL4R.jpg\", \"caption\": \"3\"}, {\"path\": \"evidences/NpOYj05eUlo9j4ytbWcvOjgDKPfhT0BNjFP0hndQ.jpg\", \"caption\": \"4\"}, {\"path\": \"evidences/zR0dXuEQst8kPnuz2MLIM1Vc85Q5H0X9mxyzNqnd.jpg\", \"caption\": \"5\"}]', 'approved', NULL, '2025-10-14 22:13:39', '2025-10-14 22:14:39'),
(23, 3, 'PT. Telkom Akses Banjarmasin', 'Bebas', '[{\"path\": \"evidences/1ALzBRFzM62idRTFjqiuzCJGOfn3LxmCgEmeWGer.jpg\", \"caption\": \"PT. Telkom Akses Banjarmasin\"}, {\"path\": \"evidences/y5zK0r6W8kqC5jO2e4oQZTuqOMNCZrAZpruDDXZR.jpg\", \"caption\": \"PT. Telkom Akses Banjarmasin\"}, {\"path\": \"evidences/HNyWdr7pGJMvII2wDdYaOs4zOg1YtcJNC3YwBQPe.jpg\", \"caption\": \"PT. Telkom Akses Banjarmasin\"}, {\"path\": \"evidences/jPr4dSSg8SllMwzXj7jX7whOgSCOjWGVFPQSyA2g.jpg\", \"caption\": \"PT. Telkom Akses Banjarmasin\"}, {\"path\": \"evidences/YTdaLFrhFOzp8Y3X3oO5o5J8FXRQGo6MdILlIdB9.jpg\", \"caption\": \"PT. Telkom Akses Banjarmasin\"}, {\"path\": \"evidences/GpvGnuPZN12GcLwsU8oFQ3AwIpPjHTN4nXNklwh0.jpg\", \"caption\": \"PT. Telkom Akses Banjarmasin\"}, {\"path\": \"evidences/0PIvkyrHqZHZBRF0jxX5GBz07ubQJpkbgH0cEW0V.jpg\", \"caption\": \"PT. Telkom Akses Banjarmasin\"}]', 'approved', NULL, '2025-10-14 23:05:53', '2025-10-14 23:06:13'),
(24, 3, 'PT. Telkom Akses Banjarmasin', 'sdaad', '[{\"path\": \"evidences/OAgdXgq03EyCefO8A6GWeHHk5qng7WJCpRgPbnew.jpg\", \"caption\": \"ODP-KPL-FX/86\"}, {\"path\": \"evidences/wWRPQTGuLpByQNdHkJBvp0igIL8Cks41NiEYiq35.jpg\", \"caption\": \"ODP-KPL-FX/87\"}, {\"path\": \"evidences/BhAo0WwGgkG6zvBR2yqi7HmVQBT7w4kQT94xWFfl.jpg\", \"caption\": \"ODP-KPL-FX/88\"}, {\"path\": \"evidences/By5LNMvRsRdZThZ6TOxGA8DWUVS9nIPmlhKZ2mo4.jpg\", \"caption\": \"ODP-KPL-FX/89\"}, {\"path\": \"evidences/6ZyWD5aXcTcsIupktCzoX5J01s0NCuEhYjP884eb.jpg\", \"caption\": \"ODP-KPL-FX/90\"}, {\"path\": \"evidences/mSdOJMqipQGwaGdCm5fKCZ1qUamlQrj8sgVCPERN.jpg\", \"caption\": \"ODP-KPL-FX/91\"}, {\"path\": \"evidences/NIHQG48BGmnFlUKMf4yGMe9oMU11zg6YpG7d3cDO.jpg\", \"caption\": \"ODP-KPL-FX/92\"}, {\"path\": \"evidences/AcewbW6NINvXnTWAvdkjFc0Ix26xhOzeZuAD2Mxj.jpg\", \"caption\": \"ODP-KPL-FX/93\"}]', 'approved', NULL, '2025-10-14 23:09:28', '2025-10-14 23:10:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_25_015448_create_evidence_table', 1),
(5, '2025_09_25_053205_add_status_to_evidences_table', 1),
(6, '2025_10_10_023002_rename_judul_to_lokasi_in_evidences_table', 1),
(7, '2025_10_14_055929_ubah_kolom_file_path_pada_tabel_evidences', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'karyawan',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Telkom', 'admin', 'admin@telkom.co.id', NULL, '$2y$12$IxZpWPWS6OnIyVNq4oTQheUQnw58OFBm8LCi44Xzv3V5JBo0nfC3q', 'admin', NULL, '2025-10-09 18:32:43', '2025-10-09 18:32:43'),
(2, 'Budi Karyawan', 'budi', 'budi@telkom.co.id', NULL, '$2y$12$8frjFPX8iZbD2FBwWmBdIuZ63KTFBBOYupkSeSw7ctsEXyXN6BvNi', 'karyawan', NULL, '2025-10-09 18:32:44', '2025-10-09 18:32:44'),
(3, 'Muhammad Riza', 'riza', 'muhammadrizaaa594@gmail.com', NULL, '$2y$12$IRnPuhrdur94FbghckclWuSccBRKlwml0AKMULcmHXNmP6abcZdXC', 'karyawan', NULL, '2025-10-09 18:40:23', '2025-10-09 18:40:23');

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
-- Indexes for table `evidences`
--
ALTER TABLE `evidences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evidences_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `evidences`
--
ALTER TABLE `evidences`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evidences`
--
ALTER TABLE `evidences`
  ADD CONSTRAINT `evidences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
