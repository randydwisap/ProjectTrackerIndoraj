-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2025 at 05:58 AM
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
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_tahap_aplikasis`
--

CREATE TABLE `jenis_tahap_aplikasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_task` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `jenis_tahap_aplikasis`
--

INSERT INTO `jenis_tahap_aplikasis` (`id`, `nama_task`, `created_at`, `updated_at`) VALUES
(1, 'Requirement Gathering', '2025-04-14 14:03:07', '2025-04-14 14:03:08'),
(2, 'Pembuatan BRD', '2025-04-14 07:03:42', '2025-04-14 07:03:42'),
(3, 'Perencanaan Teknis', '2025-04-14 07:03:48', '2025-04-14 07:03:48'),
(4, 'Pembuatan PRD', '2025-04-14 07:03:51', '2025-04-14 07:03:51'),
(5, 'Develop', '2025-04-14 07:03:55', '2025-04-14 07:03:55'),
(6, 'Instalasi', '2025-04-14 07:03:58', '2025-04-14 07:03:58'),
(7, 'Integrasi dengan AI', '2025-04-14 07:04:01', '2025-04-14 07:04:01'),
(8, 'UAT dan Go Live', '2025-04-14 07:04:05', '2025-04-14 07:04:05'),
(9, 'Sosialisasi dan Pelatihan', '2025-04-14 07:04:10', '2025-04-14 07:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_tahap_fumigasis`
--

CREATE TABLE `jenis_tahap_fumigasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_task` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `jenis_tahap_fumigasis`
--

INSERT INTO `jenis_tahap_fumigasis` (`id`, `nama_task`, `created_at`, `updated_at`) VALUES
(1, 'Persiapan dan Pemberian Fumigan', '2025-03-05 08:08:53', '2025-03-05 08:08:53'),
(2, 'Penyegelan', '2025-03-05 08:09:07', '2025-03-05 08:09:07'),
(3, 'Pembukaan Lokasi Fumigasi dan Pembersihan Residu\n', '2025-03-05 08:09:26', '2025-03-05 08:09:26');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_tasks`
--

CREATE TABLE `jenis_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_task` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_tasks`
--

INSERT INTO `jenis_tasks` (`id`, `nama_task`, `created_at`, `updated_at`) VALUES
(1, 'Pemilahan dan Identifikasi', '2025-03-05 08:08:53', '2025-03-05 08:08:53'),
(2, 'Manuver dan Pemberkasan', '2025-03-05 08:09:07', '2025-03-05 08:09:07'),
(3, 'Input Data', '2025-03-05 08:09:26', '2025-03-05 08:09:26'),
(4, 'Pelabelan dan Penataan', '2025-03-05 08:09:40', '2025-03-05 08:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_task_alih_media`
--

CREATE TABLE `jenis_task_alih_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_task` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `jenis_task_alih_media`
--

INSERT INTO `jenis_task_alih_media` (`id`, `nama_task`, `created_at`, `updated_at`) VALUES
(1, 'Scanning', '2025-03-05 08:08:53', '2025-03-05 08:08:53'),
(2, 'Quality Control', '2025-03-05 08:09:07', '2025-03-05 08:09:07'),
(3, 'Input Data', '2025-03-05 08:09:26', '2025-03-05 08:09:26'),
(4, 'Upload Data Hyperlink', '2025-03-05 08:09:40', '2025-03-05 08:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `marketings`
--

CREATE TABLE `marketings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pekerjaan` varchar(255) NOT NULL,
  `jenis_pekerjaan` varchar(255) NOT NULL,
  `nama_klien` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `tahap_pengerjaan` varchar(255) NOT NULL,
  `total_volume` int(11) NOT NULL,
  `nama_pic` bigint(20) UNSIGNED NOT NULL,
  `project_manager` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Pending','In Progress','Completed','On Hold') NOT NULL,
  `durasi_proyek` int(11) DEFAULT NULL,
  `jumlah_sdm` int(11) NOT NULL,
  `nilai_proyek` decimal(15,2) NOT NULL,
  `link_rab` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `nilai_akhir_proyek` decimal(15,2) NOT NULL,
  `terms_of_payment` int(11) NOT NULL,
  `status_pembayaran` enum('Lunas','Belum Lunas') NOT NULL,
  `dokumentasi_foto` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`dokumentasi_foto`)),
  `lampiran` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `manajer_operasional` tinyint(1) NOT NULL DEFAULT 0,
  `manajer_keuangan` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketings`
--

INSERT INTO `marketings` (`id`, `nama_pekerjaan`, `jenis_pekerjaan`, `nama_klien`, `lokasi`, `tahap_pengerjaan`, `total_volume`, `nama_pic`, `project_manager`, `status`, `durasi_proyek`, `jumlah_sdm`, `nilai_proyek`, `link_rab`, `note`, `tgl_mulai`, `tgl_selesai`, `nilai_akhir_proyek`, `terms_of_payment`, `status_pembayaran`, `dokumentasi_foto`, `lampiran`, `created_at`, `updated_at`, `manajer_operasional`, `manajer_keuangan`) VALUES
(10, 'Test Pekerjaan Pengolahan Arsip', 'Pengolahan Arsip', 'Klien Pekerjaan Pengolahan Arsip', 'Kota Surabaya', 'Kontrak', 200, 7, 6, 'On Hold', 3, 1, 150000.00, NULL, NULL, '2025-04-29', '2025-05-20', 150000.00, 60, 'Lunas', '[\"marketing_foto\\/marketing_96da264a-1ff9-400b-b51e-02341bffa17c_fotoabsen_1.png\"]', 'marketing_lampiran/01JT0Y2X3SYRNVT4Z9GCFZD93A.pdf', '2025-04-29 14:09:18', '2025-04-29 14:25:54', 1, 1),
(11, 'Test Pekerjaan Alih Media', 'Alih Media', 'Klien Pekerjaan Alih Media', 'Kota Surabaya', 'Kontrak', 200, 7, 6, 'On Hold', 3, 1, 150000.00, NULL, NULL, '2025-04-29', '2025-05-20', 150000.00, 60, 'Lunas', '[\"marketing_foto\\/marketing_96da264a-1ff9-400b-b51e-02341bffa17c_fotoabsen_1.png\"]', 'marketing_lampiran/01JT0Y2X3SYRNVT4Z9GCFZD93A.pdf', '2025-04-29 14:09:18', '2025-04-29 14:20:07', 1, 1),
(12, 'Test Pekerjaan Fumigasi', 'Fumigasi', 'Klien Pekerjaan Fumigasi', 'Kota Surabaya', 'Kontrak', 200, 7, 11, 'On Hold', 3, 1, 150000.00, NULL, NULL, '2025-04-29', '2025-05-20', 150000.00, 60, 'Lunas', '[\"marketing_foto\\/marketing_96da264a-1ff9-400b-b51e-02341bffa17c_fotoabsen_1.png\"]', 'marketing_lampiran/01JT0Y2X3SYRNVT4Z9GCFZD93A.pdf', '2025-04-29 14:09:18', '2025-04-29 14:17:56', 1, 1),
(13, 'Test Pekerjaan Aplikasi', 'Aplikasi', 'Klien Pekerjaan Aplikasi', 'Kota Surabaya', 'Kontrak', 200, 7, 11, 'On Hold', 3, 1, 150000.00, NULL, NULL, '2025-04-29', '2025-05-20', 150000.00, 60, 'Lunas', '[\"marketing_foto\\/marketing_96da264a-1ff9-400b-b51e-02341bffa17c_fotoabsen_1.png\"]', 'marketing_lampiran/01JT0Y2X3SYRNVT4Z9GCFZD93A.pdf', '2025-04-29 14:09:18', '2025-04-29 14:16:09', 1, 1);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_03_04_000337_create_permission_tables', 2),
(6, '2025_03_04_101650_create_tasks_table', 3),
(7, '2025_03_04_115719_create_project_details_table', 4),
(8, '2025_03_04_135046_add_fields_to_tasks_table', 5),
(9, '2025_03_04_141323_change_pelaksana_column_in_tasks_table', 6),
(10, '2025_03_05_150324_create_jenis_tasks_table', 7),
(11, '2025_03_05_150454_create_task_details_table', 8),
(18, '2025_03_05_150543_create_task_day_details_table', 9),
(20, '2025_03_06_000000_add_default_status_to_tasks_table', 10),
(21, '2025_03_06_000001_change_status_to_string_in_tasks_table', 10),
(22, '2025_03_05_150620_create_task_week_overviews_table', 11),
(23, '2025_03_07_000000_add_sisa_volume_to_task_details_table', 12),
(24, '2025_03_08_000000_change_tanggal_to_string_in_task_day_details_table', 13),
(25, '2025_03_08_000002_remove_invalid_task_day_details', 14),
(26, '2025_03_08_000003_remove_task_id_from_task_day_details_table', 15),
(27, '2025_03_09_000000_create_marketing_table', 16),
(28, '2025_03_09_000001_drop_task_id_from_task_day_details_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 7),
(5, 'App\\Models\\User', 10),
(6, 'App\\Models\\User', 8);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(7, 'user.view', 'web', '2025-03-03 18:05:53', '2025-03-03 18:05:53'),
(8, 'user.create', 'web', '2025-03-03 18:06:03', '2025-03-03 18:06:03'),
(9, 'user.update', 'web', '2025-03-03 18:06:10', '2025-03-03 18:06:10'),
(10, 'user.delete', 'web', '2025-03-03 18:06:18', '2025-03-03 18:06:18'),
(27, 'role.view', 'web', '2025-03-04 01:42:54', '2025-03-04 01:42:54'),
(28, 'role.create', 'web', '2025-03-04 01:42:54', '2025-03-04 01:42:54'),
(29, 'role.update', 'web', '2025-03-04 01:42:54', '2025-03-04 01:42:54'),
(30, 'role.delete', 'web', '2025-03-04 01:42:54', '2025-03-04 01:42:54'),
(48, 'marketing.view', 'web', '2025-04-29 20:07:22', '2025-04-29 20:07:22'),
(49, 'marketing.create', 'web', '2025-04-29 20:07:22', '2025-04-29 20:07:22'),
(50, 'marketing.update', 'web', '2025-04-29 20:07:22', '2025-04-29 20:07:22'),
(51, 'marketing.delete', 'web', '2025-04-29 20:07:22', '2025-04-29 20:07:22'),
(52, 'taskAlihMedia.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(53, 'taskAlihMedia.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(54, 'taskAlihMedia.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(55, 'taskAlihMedia.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(56, 'taskDayAlihMedia.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(57, 'taskDayAlihMedia.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(58, 'taskDayAlihMedia.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(59, 'taskDayAlihMedia.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(60, 'taskWeekAlihMedia.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(61, 'taskWeekAlihMedia.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(62, 'taskWeekAlihMedia.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(63, 'taskWeekAlihMedia.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(64, 'jenisTaskAlihMedia.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(65, 'jenisTaskAlihMedia.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(66, 'jenisTaskAlihMedia.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(67, 'jenisTaskAlihMedia.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(68, 'taskAplikasi.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(69, 'taskAplikasi.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(70, 'taskAplikasi.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(71, 'taskAplikasi.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(72, 'reportAplikasi.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(73, 'reportAplikasi.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(74, 'reportAplikasi.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(75, 'reportAplikasi.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(76, 'jenisTahapAplikasi.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(77, 'jenisTahapAplikasi.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(78, 'jenisTahapAplikasi.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(79, 'jenisTahapAplikasi.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(80, 'taskFumigasi.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(81, 'taskFumigasi.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(82, 'taskFumigasi.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(83, 'taskFumigasi.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(84, 'reportFumigasi.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(85, 'reportFumigasi.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(86, 'reportFumigasi.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(87, 'reportFumigasi.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(88, 'jenisTahapFumigasi.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(89, 'jenisTahapFumigasi.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(90, 'jenisTahapFumigasi.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(91, 'jenisTahapFumigasi.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(92, 'task.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(93, 'task.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(94, 'task.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(95, 'task.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(96, 'taskDayDetail.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(97, 'taskDayDetail.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(98, 'taskDayDetail.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(99, 'taskDayDetail.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(100, 'taskWeekOverview.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(101, 'taskWeekOverview.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(102, 'taskWeekOverview.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(103, 'taskWeekOverview.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(104, 'jenisTask.view', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(105, 'jenisTask.create', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(106, 'jenisTask.update', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43'),
(107, 'jenisTask.delete', 'web', '2025-04-29 20:16:43', '2025-04-29 20:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_aplikasis`
--

CREATE TABLE `report_aplikasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_aplikasi_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jenistahapaplikasi_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `gambar` longtext DEFAULT NULL,
  `lampiran` longtext DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_aplikasis`
--

INSERT INTO `report_aplikasis` (`id`, `task_aplikasi_id`, `tanggal`, `jenistahapaplikasi_id`, `keterangan`, `gambar`, `lampiran`, `updated_at`, `created_at`) VALUES
(20, 57, '2025-04-29', 1, NULL, '[]', NULL, '2025-04-29 14:16:31', '2025-04-29 14:16:31'),
(21, 57, '2025-04-30', 2, NULL, '[]', NULL, '2025-04-29 14:16:39', '2025-04-29 14:16:39'),
(22, 57, '2025-05-01', 3, NULL, '[]', NULL, '2025-04-29 14:16:50', '2025-04-29 14:16:50'),
(23, 57, '2025-05-02', 4, NULL, '[]', NULL, '2025-04-29 14:16:55', '2025-04-29 14:16:55'),
(24, 57, '2025-05-03', 5, NULL, '[]', NULL, '2025-04-29 14:17:00', '2025-04-29 14:17:00'),
(25, 57, '2025-05-04', 6, NULL, '[]', NULL, '2025-04-29 14:17:07', '2025-04-29 14:17:07'),
(26, 57, '2025-05-06', 7, NULL, '[]', NULL, '2025-04-29 14:17:12', '2025-04-29 14:17:12'),
(27, 57, '2025-05-07', 8, NULL, '[]', NULL, '2025-04-29 14:17:20', '2025-04-29 14:17:20'),
(28, 57, '2025-05-08', 9, NULL, '[]', NULL, '2025-04-29 14:17:28', '2025-04-29 14:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `report_fumigasis`
--

CREATE TABLE `report_fumigasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_fumigasi_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date DEFAULT NULL,
  `jenistahapfumigasi_id` bigint(20) UNSIGNED NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `gambar` longtext DEFAULT NULL,
  `lampiran` longtext DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `report_fumigasis`
--

INSERT INTO `report_fumigasis` (`id`, `task_fumigasi_id`, `tanggal`, `jenistahapfumigasi_id`, `keterangan`, `gambar`, `lampiran`, `updated_at`, `created_at`) VALUES
(20, 61, '2025-04-29', 1, NULL, '[]', NULL, '2025-04-29 14:19:01', '2025-04-29 14:19:01'),
(21, 61, '2025-04-30', 2, NULL, '[]', NULL, '2025-04-29 14:19:07', '2025-04-29 14:19:07'),
(22, 61, '2025-05-01', 3, NULL, '[]', NULL, '2025-04-29 14:19:12', '2025-04-29 14:19:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-03-03 17:03:59', '2025-03-03 17:03:59'),
(2, 'Admin', 'web', '2025-03-03 17:18:30', '2025-03-03 17:18:30'),
(3, 'project_manager', 'web', '2025-04-29 09:06:33', '2025-04-29 09:06:33'),
(4, 'marketing', 'web', '2025-04-29 09:06:38', '2025-04-29 09:06:38'),
(5, 'Manajer Keuangan', 'web', '2025-04-29 09:23:15', '2025-04-29 09:23:15'),
(6, 'Manajer Operasional', 'web', '2025-04-29 09:23:24', '2025-04-29 09:23:24');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(7, 1),
(7, 2),
(7, 5),
(7, 6),
(8, 1),
(8, 2),
(8, 5),
(8, 6),
(9, 1),
(9, 2),
(9, 5),
(9, 6),
(10, 1),
(10, 2),
(10, 5),
(10, 6),
(27, 1),
(27, 2),
(27, 5),
(27, 6),
(28, 1),
(28, 2),
(28, 5),
(28, 6),
(29, 1),
(29, 2),
(29, 5),
(29, 6),
(30, 1),
(30, 2),
(30, 5),
(30, 6),
(48, 1),
(48, 2),
(48, 4),
(48, 5),
(48, 6),
(49, 1),
(49, 2),
(49, 4),
(49, 5),
(49, 6),
(50, 1),
(50, 2),
(50, 4),
(50, 5),
(50, 6),
(51, 1),
(51, 2),
(51, 4),
(51, 5),
(51, 6),
(52, 1),
(52, 2),
(52, 3),
(52, 5),
(52, 6),
(53, 1),
(53, 2),
(53, 3),
(53, 5),
(53, 6),
(54, 1),
(54, 2),
(54, 3),
(54, 5),
(54, 6),
(55, 1),
(55, 2),
(55, 3),
(55, 5),
(55, 6),
(56, 1),
(56, 2),
(56, 3),
(56, 5),
(56, 6),
(57, 1),
(57, 2),
(57, 3),
(57, 5),
(57, 6),
(58, 1),
(58, 2),
(58, 3),
(58, 5),
(58, 6),
(59, 1),
(59, 2),
(59, 3),
(59, 5),
(59, 6),
(60, 1),
(60, 2),
(60, 3),
(60, 5),
(60, 6),
(61, 1),
(61, 2),
(61, 3),
(61, 5),
(61, 6),
(62, 1),
(62, 2),
(62, 3),
(62, 5),
(62, 6),
(63, 1),
(63, 2),
(63, 3),
(63, 5),
(63, 6),
(64, 1),
(64, 2),
(64, 3),
(64, 5),
(64, 6),
(65, 1),
(65, 2),
(65, 5),
(65, 6),
(66, 1),
(66, 2),
(66, 5),
(66, 6),
(67, 1),
(67, 2),
(67, 5),
(67, 6),
(68, 1),
(68, 2),
(68, 3),
(68, 5),
(68, 6),
(69, 1),
(69, 2),
(69, 3),
(69, 5),
(69, 6),
(70, 1),
(70, 2),
(70, 3),
(70, 5),
(70, 6),
(71, 1),
(71, 2),
(71, 3),
(71, 5),
(71, 6),
(72, 1),
(72, 2),
(72, 3),
(72, 5),
(72, 6),
(73, 1),
(73, 2),
(73, 3),
(73, 5),
(73, 6),
(74, 1),
(74, 2),
(74, 3),
(74, 5),
(74, 6),
(75, 1),
(75, 2),
(75, 3),
(75, 5),
(75, 6),
(76, 1),
(76, 2),
(76, 3),
(76, 5),
(76, 6),
(77, 1),
(77, 2),
(77, 5),
(77, 6),
(78, 1),
(78, 2),
(78, 5),
(78, 6),
(79, 1),
(79, 2),
(79, 5),
(79, 6),
(80, 1),
(80, 2),
(80, 3),
(80, 5),
(80, 6),
(81, 1),
(81, 2),
(81, 3),
(81, 5),
(81, 6),
(82, 1),
(82, 2),
(82, 3),
(82, 5),
(82, 6),
(83, 1),
(83, 2),
(83, 3),
(83, 5),
(83, 6),
(84, 1),
(84, 2),
(84, 3),
(84, 5),
(84, 6),
(85, 1),
(85, 2),
(85, 3),
(85, 5),
(85, 6),
(86, 1),
(86, 2),
(86, 3),
(86, 5),
(86, 6),
(87, 1),
(87, 2),
(87, 3),
(87, 5),
(87, 6),
(88, 1),
(88, 2),
(88, 3),
(88, 5),
(88, 6),
(89, 1),
(89, 2),
(89, 5),
(89, 6),
(90, 1),
(90, 2),
(90, 5),
(90, 6),
(91, 1),
(91, 2),
(91, 5),
(91, 6),
(92, 1),
(92, 2),
(92, 3),
(92, 5),
(92, 6),
(93, 1),
(93, 2),
(93, 3),
(93, 5),
(93, 6),
(94, 1),
(94, 2),
(94, 3),
(94, 5),
(94, 6),
(95, 1),
(95, 2),
(95, 3),
(95, 5),
(95, 6),
(96, 1),
(96, 2),
(96, 3),
(96, 5),
(96, 6),
(97, 1),
(97, 2),
(97, 3),
(97, 5),
(97, 6),
(98, 1),
(98, 2),
(98, 3),
(98, 5),
(98, 6),
(99, 1),
(99, 2),
(99, 3),
(99, 5),
(99, 6),
(100, 1),
(100, 2),
(100, 3),
(100, 5),
(100, 6),
(101, 1),
(101, 2),
(101, 3),
(101, 5),
(101, 6),
(102, 1),
(102, 2),
(102, 3),
(102, 5),
(102, 6),
(103, 1),
(103, 2),
(103, 3),
(103, 5),
(103, 6),
(104, 1),
(104, 2),
(104, 3),
(104, 5),
(104, 6),
(105, 1),
(105, 2),
(105, 5),
(105, 6),
(106, 1),
(106, 2),
(106, 5),
(106, 6),
(107, 1),
(107, 2),
(107, 5),
(107, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `klien` varchar(255) NOT NULL,
  `tahap_pengerjaan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Far Behind Schedule',
  `resiko_keterlambatan` enum('Low','Medium','High','Completed') NOT NULL DEFAULT 'Low',
  `durasi_proyek` int(11) DEFAULT NULL,
  `jumlah_sdm` int(11) NOT NULL,
  `project_manager` bigint(20) UNSIGNED NOT NULL,
  `no_telp_pm` varchar(255) NOT NULL,
  `nilai_proyek` decimal(15,2) NOT NULL,
  `link_rab` varchar(255) DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `pelaksana` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pelaksana`)),
  `volume_arsip` int(11) DEFAULT NULL,
  `volume_dikerjakan` int(11) DEFAULT NULL,
  `dikerjakan_step1` int(11) DEFAULT 0,
  `dikerjakan_step2` int(11) DEFAULT 0,
  `dikerjakan_step3` int(11) DEFAULT 0,
  `dikerjakan_step4` int(11) DEFAULT 0,
  `hasil_pemilahan` int(11) DEFAULT NULL,
  `jenis_arsip` varchar(255) DEFAULT NULL,
  `deskripsi_pekerjaan` text DEFAULT NULL,
  `lama_pekerjaan` int(11) DEFAULT NULL,
  `target_perminggu` int(11) DEFAULT NULL,
  `target_perminggu_arsip` int(11) DEFAULT NULL,
  `target_perday` int(11) DEFAULT NULL,
  `target_perday_arsip` int(11) DEFAULT NULL,
  `marketing_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `pekerjaan`, `klien`, `tahap_pengerjaan`, `status`, `resiko_keterlambatan`, `durasi_proyek`, `jumlah_sdm`, `project_manager`, `no_telp_pm`, `nilai_proyek`, `link_rab`, `tgl_mulai`, `tgl_selesai`, `created_at`, `updated_at`, `lokasi`, `pelaksana`, `volume_arsip`, `volume_dikerjakan`, `dikerjakan_step1`, `dikerjakan_step2`, `dikerjakan_step3`, `dikerjakan_step4`, `hasil_pemilahan`, `jenis_arsip`, `deskripsi_pekerjaan`, `lama_pekerjaan`, `target_perminggu`, `target_perminggu_arsip`, `target_perday`, `target_perday_arsip`, `marketing_id`) VALUES
(60, 'Test Pekerjaan Pengolahan Arsip', 'Klien Pekerjaan Pengolahan Arsip', 'Pelabelan dan Penataan', 'Completed', 'Low', 3, 1, 6, '123', 150000.00, NULL, '2025-04-29', '2025-05-20', '2025-04-29 14:25:54', '2025-04-29 14:28:45', 'Kota Surabaya', '[{\"nama\":\"Test\"}]', 200, 755, 200, 185, 185, 185, 185, 'Campuran', 'Test Pengolahan Arsip', 21, 67, 62, 10, 9, 10);

-- --------------------------------------------------------

--
-- Table structure for table `task_alih_media`
--

CREATE TABLE `task_alih_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `klien` varchar(255) NOT NULL,
  `tahap_pengerjaan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Far Behind Schedule',
  `resiko_keterlambatan` enum('Low','Medium','High','Completed') NOT NULL DEFAULT 'Low',
  `durasi_proyek` int(11) DEFAULT NULL,
  `jumlah_sdm` int(11) NOT NULL,
  `project_manager` bigint(20) UNSIGNED NOT NULL,
  `no_telp_pm` varchar(255) NOT NULL,
  `nilai_proyek` decimal(15,2) NOT NULL,
  `link_rab` varchar(255) DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `pelaksana` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `volume_arsip` int(11) DEFAULT NULL,
  `volume_dikerjakan` int(11) DEFAULT NULL,
  `dikerjakan_step1` int(11) DEFAULT 0,
  `dikerjakan_step2` int(11) DEFAULT 0,
  `dikerjakan_step3` int(11) DEFAULT 0,
  `dikerjakan_step4` int(11) DEFAULT 0,
  `jenis_arsip` varchar(255) DEFAULT NULL,
  `deskripsi_pekerjaan` text DEFAULT NULL,
  `lama_pekerjaan` int(11) DEFAULT NULL,
  `target_perminggu` int(11) DEFAULT NULL,
  `target_perday` int(11) DEFAULT NULL,
  `marketing_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `task_alih_media`
--

INSERT INTO `task_alih_media` (`id`, `pekerjaan`, `klien`, `tahap_pengerjaan`, `status`, `resiko_keterlambatan`, `durasi_proyek`, `jumlah_sdm`, `project_manager`, `no_telp_pm`, `nilai_proyek`, `link_rab`, `tgl_mulai`, `tgl_selesai`, `created_at`, `updated_at`, `lokasi`, `pelaksana`, `volume_arsip`, `volume_dikerjakan`, `dikerjakan_step1`, `dikerjakan_step2`, `dikerjakan_step3`, `dikerjakan_step4`, `jenis_arsip`, `deskripsi_pekerjaan`, `lama_pekerjaan`, `target_perminggu`, `target_perday`, `marketing_id`) VALUES
(63, 'Test Pekerjaan Alih Media', 'Klien Pekerjaan Alih Media', 'Upload Data Hyperlink', 'Completed', 'Low', 3, 1, 6, '123', 150000.00, NULL, '2025-04-29', '2025-05-20', '2025-04-29 14:20:07', '2025-04-29 14:24:46', 'Kota Surabaya', '[{\"nama\":\"Test\"}]', 200, 800, 200, 200, 200, 200, 'Campuran', 'Test Alih Media', 21, 67, 10, 11);

-- --------------------------------------------------------

--
-- Table structure for table `task_aplikasis`
--

CREATE TABLE `task_aplikasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `klien` varchar(255) NOT NULL,
  `tahap_pengerjaan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Behind Schedule',
  `resiko_keterlambatan` enum('Low','Medium','High','Completed') NOT NULL DEFAULT 'Low',
  `durasi_proyek` int(11) DEFAULT NULL,
  `jumlah_sdm` int(11) NOT NULL,
  `project_manager` bigint(20) UNSIGNED NOT NULL,
  `no_telp_pm` varchar(255) NOT NULL,
  `nilai_proyek` decimal(15,2) NOT NULL,
  `link_rab` varchar(255) DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `pelaksana` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `deskripsi_pekerjaan` text DEFAULT NULL,
  `lama_pekerjaan` int(11) DEFAULT NULL,
  `target_perminggu` int(11) DEFAULT NULL,
  `target_perday` int(11) DEFAULT NULL,
  `marketing_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `task_aplikasis`
--

INSERT INTO `task_aplikasis` (`id`, `pekerjaan`, `klien`, `tahap_pengerjaan`, `status`, `resiko_keterlambatan`, `durasi_proyek`, `jumlah_sdm`, `project_manager`, `no_telp_pm`, `nilai_proyek`, `link_rab`, `tgl_mulai`, `tgl_selesai`, `created_at`, `updated_at`, `lokasi`, `pelaksana`, `volume`, `deskripsi_pekerjaan`, `lama_pekerjaan`, `target_perminggu`, `target_perday`, `marketing_id`) VALUES
(57, 'Test Pekerjaan Aplikasi', 'Klien Pekerjaan Aplikasi', 'Sosialisasi dan Pelatihan', 'Completed', 'Low', 3, 1, 11, '123', 150000.00, NULL, '2025-04-29', '2025-05-20', '2025-04-29 14:16:09', '2025-04-29 14:17:28', 'Kota Surabaya', '[{\"nama\":\"Test\"}]', 200, 'Test Fumigasi', 21, 67, 10, 13);

-- --------------------------------------------------------

--
-- Table structure for table `task_day_alih_media`
--

CREATE TABLE `task_day_alih_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_alih_media_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_task_alih_media_id` bigint(20) UNSIGNED DEFAULT NULL,
  `task_week_alih_media_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `output` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `task_day_alih_media`
--

INSERT INTO `task_day_alih_media` (`id`, `task_alih_media_id`, `jenis_task_alih_media_id`, `task_week_alih_media_id`, `tanggal`, `output`, `status`, `created_at`, `updated_at`) VALUES
(5, 63, 1, 49, 'Day 1', 100, 'On Track', '2025-04-29 14:20:36', '2025-04-29 14:20:43'),
(6, 63, 2, 49, 'Day 1', 100, 'On Track', '2025-04-29 14:20:43', '2025-04-29 14:20:43'),
(7, 63, 1, 49, 'Day 2', 100, 'On Track', '2025-04-29 14:20:54', '2025-04-29 14:21:01'),
(8, 63, 2, 49, 'Day 2', 100, 'On Track', '2025-04-29 14:21:01', '2025-04-29 14:21:01'),
(9, 63, 3, 50, 'Day 1', 100, 'On Track', '2025-04-29 14:21:38', '2025-04-29 14:21:38'),
(10, 63, 4, 50, 'Day 2', 10, 'On Track', '2025-04-29 14:21:55', '2025-04-29 14:21:55'),
(11, 63, 3, 50, 'Day 3', 5, 'Far Behind Schedule', '2025-04-29 14:22:23', '2025-04-29 14:22:23'),
(12, 63, 3, 51, 'Day 1', 35, 'On Track', '2025-04-29 14:22:46', '2025-04-29 14:22:46'),
(13, 63, 3, 51, 'Day 2', 10, 'On Track', '2025-04-29 14:23:11', '2025-04-29 14:23:11'),
(14, 63, 4, 51, 'Day 4', 140, 'On Track', '2025-04-29 14:23:34', '2025-04-29 14:23:34'),
(15, 63, 3, 51, 'Day 5', 50, 'On Track', '2025-04-29 14:24:38', '2025-04-29 14:24:38'),
(16, 63, 4, 51, 'Day 6', 50, 'On Track', '2025-04-29 14:24:46', '2025-04-29 14:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `task_day_details`
--

CREATE TABLE `task_day_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `task_week_overview_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `output` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hasil` int(11) DEFAULT NULL,
  `hasil_inarsip` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_day_details`
--

INSERT INTO `task_day_details` (`id`, `task_id`, `jenis_task_id`, `task_week_overview_id`, `tanggal`, `output`, `status`, `created_at`, `updated_at`, `hasil`, `hasil_inarsip`) VALUES
(1, 60, 1, 48, 'Day 1', 200, 'On Track', '2025-04-29 14:26:31', '2025-04-29 14:26:31', 185, 15),
(2, 60, 2, 48, 'Day 2', 185, 'On Track', '2025-04-29 14:26:59', '2025-04-29 14:26:59', 185, 0),
(3, 60, 3, 48, 'Day 3', 185, 'On Track', '2025-04-29 14:27:21', '2025-04-29 14:27:21', 185, 0),
(5, 60, 4, 50, 'Day 1', 185, 'On Track', '2025-04-29 14:27:51', '2025-04-29 14:27:51', 185, 0);

-- --------------------------------------------------------

--
-- Table structure for table `task_fumigasis`
--

CREATE TABLE `task_fumigasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `klien` varchar(255) NOT NULL,
  `tahap_pengerjaan` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Behind Schedule',
  `resiko_keterlambatan` enum('Low','Medium','High','Completed') NOT NULL DEFAULT 'Low',
  `durasi_proyek` int(11) DEFAULT NULL,
  `jumlah_sdm` int(11) NOT NULL,
  `project_manager` bigint(20) UNSIGNED NOT NULL,
  `no_telp_pm` varchar(255) NOT NULL,
  `nilai_proyek` decimal(15,2) NOT NULL,
  `link_rab` varchar(255) DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `pelaksana` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `volume` int(11) DEFAULT NULL,
  `deskripsi_pekerjaan` text DEFAULT NULL,
  `lama_pekerjaan` int(11) DEFAULT NULL,
  `target_perminggu` int(11) DEFAULT NULL,
  `target_perday` int(11) DEFAULT NULL,
  `marketing_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `task_fumigasis`
--

INSERT INTO `task_fumigasis` (`id`, `pekerjaan`, `klien`, `tahap_pengerjaan`, `status`, `resiko_keterlambatan`, `durasi_proyek`, `jumlah_sdm`, `project_manager`, `no_telp_pm`, `nilai_proyek`, `link_rab`, `tgl_mulai`, `tgl_selesai`, `created_at`, `updated_at`, `lokasi`, `pelaksana`, `volume`, `deskripsi_pekerjaan`, `lama_pekerjaan`, `target_perminggu`, `target_perday`, `marketing_id`) VALUES
(61, 'Test Pekerjaan Fumigasi', 'Klien Pekerjaan Fumigasi', 'Pembukaan Lokasi Fumigasi dan Pembersihan Residu\n', 'Completed', 'Low', 3, 1, 11, '123', 150000.00, NULL, '2025-04-29', '2025-05-20', '2025-04-29 14:17:56', '2025-04-29 14:19:12', 'Kota Surabaya', '[{\"nama\":\"Test\"}]', 200, 'Test Fumigasi', 21, 67, 10, 12);

-- --------------------------------------------------------

--
-- Table structure for table `task_week_alih_media`
--

CREATE TABLE `task_week_alih_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_alih_media_id` bigint(20) UNSIGNED NOT NULL,
  `nama_week` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT 'Not Started',
  `resiko_keterlambatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_volume` int(11) NOT NULL DEFAULT 0,
  `volume_dikerjakan` int(11) NOT NULL DEFAULT 0,
  `total_step1` int(11) DEFAULT NULL,
  `total_step2` int(11) DEFAULT NULL,
  `total_step3` int(11) DEFAULT NULL,
  `total_step4` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `task_week_alih_media`
--

INSERT INTO `task_week_alih_media` (`id`, `task_alih_media_id`, `nama_week`, `status`, `resiko_keterlambatan`, `created_at`, `updated_at`, `total_volume`, `volume_dikerjakan`, `total_step1`, `total_step2`, `total_step3`, `total_step4`) VALUES
(49, 63, 'Week 1', 'On Track', 'Medium', '2025-04-29 14:20:07', '2025-04-29 14:21:01', 67, 400, 200, 200, 0, 0),
(50, 63, 'Week 2', 'On Track', 'Medium', '2025-04-29 14:20:07', '2025-04-29 14:22:23', 67, 115, 0, 0, 105, 10),
(51, 63, 'Week 3', 'On Track', 'Medium', '2025-04-29 14:20:07', '2025-04-29 14:24:46', 67, 285, 0, 0, 95, 190);

-- --------------------------------------------------------

--
-- Table structure for table `task_week_overviews`
--

CREATE TABLE `task_week_overviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `nama_week` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT 'Not Started',
  `resiko_keterlambatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_volume` int(11) NOT NULL DEFAULT 0,
  `volume_dikerjakan` int(11) NOT NULL DEFAULT 0,
  `arsip` int(11) NOT NULL DEFAULT 0,
  `inarsip` int(11) NOT NULL DEFAULT 0,
  `total_step1` int(11) DEFAULT NULL,
  `total_step2` int(11) DEFAULT NULL,
  `total_step3` int(11) DEFAULT NULL,
  `total_step4` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_week_overviews`
--

INSERT INTO `task_week_overviews` (`id`, `task_id`, `nama_week`, `status`, `resiko_keterlambatan`, `created_at`, `updated_at`, `total_volume`, `volume_dikerjakan`, `arsip`, `inarsip`, `total_step1`, `total_step2`, `total_step3`, `total_step4`) VALUES
(48, 60, 'Week 1', 'On Track', 'Medium', '2025-04-29 14:25:54', '2025-04-29 14:27:21', 67, 570, 185, 15, 200, 185, 185, 0),
(49, 60, 'Week 2', 'Far Behind Schedule', 'High', '2025-04-29 14:25:54', '2025-04-29 14:28:45', 67, 0, 0, 0, 0, 0, 0, 0),
(50, 60, 'Week 3', 'On Track', 'High', '2025-04-29 14:25:54', '2025-04-29 14:27:51', 67, 185, 0, 0, 0, 0, 0, 185);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `NIP` varchar(255) NOT NULL,
  `NIK` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `Jabatan` varchar(255) DEFAULT NULL,
  `Telepon` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `NIP`, `NIK`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `Jabatan`, `Telepon`) VALUES
(1, 'Super Admin', '123', '123', 'admin@email.com', NULL, '$2y$12$mOJsDcuDUOezXhHMazkX.OIy2crsfy7qKE7tAoIUCJob4.2bQUCj.', 'Bf54hmh3ab0RB1JcU3Jv7wOg022u68ndpV2ndkGwsYA9yBV5rsi6Z5jvJZeh', '2025-03-03 16:51:43', '2025-03-15 01:05:59', 'Super Admin', '081'),
(6, 'Project Manager 1', '123', '123', 'pm1@gmail.com', NULL, '$2y$12$mOJsDcuDUOezXhHMazkX.OIy2crsfy7qKE7tAoIUCJob4.2bQUCj.', NULL, '2025-04-29 01:57:19', '2025-04-29 01:57:19', 'Project Manager', '123'),
(7, 'Marketing 1', '123', '123', 'marketing1@gmail.com', NULL, '$2y$12$mOJsDcuDUOezXhHMazkX.OIy2crsfy7qKE7tAoIUCJob4.2bQUCj.', NULL, '2025-04-29 09:21:55', '2025-04-29 09:21:55', 'Marketing', '123'),
(8, 'Manajer Operasional', '123', '123', 'mo@gmail.com', NULL, '$2y$12$mOJsDcuDUOezXhHMazkX.OIy2crsfy7qKE7tAoIUCJob4.2bQUCj.', NULL, '2025-04-29 09:23:56', '2025-04-29 09:23:56', 'Manajer Operasional', '123'),
(10, 'Manajer Keuangan', '123', '123', 'mk@gmail.com', NULL, '$2y$12$mOJsDcuDUOezXhHMazkX.OIy2crsfy7qKE7tAoIUCJob4.2bQUCj.', NULL, '2025-04-29 09:24:25', '2025-04-29 09:24:25', 'Manajer Keuangan', '123'),
(11, 'Project Manager 2', '123', '123', 'pm2@gmail.com', NULL, '$2y$12$mOJsDcuDUOezXhHMazkX.OIy2crsfy7qKE7tAoIUCJob4.2bQUCj.', NULL, '2025-04-29 01:57:19', '2025-04-29 01:57:19', 'Project Manager', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jenis_tahap_aplikasis`
--
ALTER TABLE `jenis_tahap_aplikasis`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `jenis_tahap_fumigasis`
--
ALTER TABLE `jenis_tahap_fumigasis`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `jenis_tasks`
--
ALTER TABLE `jenis_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_task_alih_media`
--
ALTER TABLE `jenis_task_alih_media`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `marketings`
--
ALTER TABLE `marketings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_manager_fk` (`project_manager`),
  ADD KEY `pic_fk` (`nama_pic`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `report_aplikasis`
--
ALTER TABLE `report_aplikasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tahapaplikasiid_fk` (`jenistahapaplikasi_id`) USING BTREE,
  ADD KEY `taskaplikasiid_fk` (`task_aplikasi_id`) USING BTREE;

--
-- Indexes for table `report_fumigasis`
--
ALTER TABLE `report_fumigasis`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `taskaplikasiid_fk` (`task_fumigasi_id`) USING BTREE,
  ADD KEY `tahapaplikasiid_fk` (`jenistahapfumigasi_id`) USING BTREE;

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `marketing_marketingl_id_foreign` (`marketing_id`),
  ADD KEY `project_managerTask_fk` (`project_manager`);

--
-- Indexes for table `task_alih_media`
--
ALTER TABLE `task_alih_media`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `marketing_marketingl_id_foreign` (`marketing_id`) USING BTREE,
  ADD KEY `project_managerTask_fk` (`project_manager`) USING BTREE;

--
-- Indexes for table `task_aplikasis`
--
ALTER TABLE `task_aplikasis`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `marketing_marketingl_id_foreign` (`marketing_id`) USING BTREE,
  ADD KEY `project_manager_aplikasi_fk` (`project_manager`);

--
-- Indexes for table `task_day_alih_media`
--
ALTER TABLE `task_day_alih_media`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `task_alih_media_id_fk` (`task_alih_media_id`),
  ADD KEY `task_week_alih_media_id_fk` (`task_week_alih_media_id`),
  ADD KEY `jenis_task_alih_media_fk` (`jenis_task_alih_media_id`);

--
-- Indexes for table `task_day_details`
--
ALTER TABLE `task_day_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_day_details_task_id_foreign` (`task_id`),
  ADD KEY `FK_task_day_details_task_details` (`jenis_task_id`),
  ADD KEY `FK_task_week_overviews_id` (`task_week_overview_id`) USING BTREE;

--
-- Indexes for table `task_fumigasis`
--
ALTER TABLE `task_fumigasis`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `marketing_marketingl_id_foreign` (`marketing_id`) USING BTREE,
  ADD KEY `project_manager_fumigasi_fk` (`project_manager`);

--
-- Indexes for table `task_week_alih_media`
--
ALTER TABLE `task_week_alih_media`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `task_alih_media_id_fk_week` (`task_alih_media_id`);

--
-- Indexes for table `task_week_overviews`
--
ALTER TABLE `task_week_overviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id_fk` (`task_id`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_tahap_aplikasis`
--
ALTER TABLE `jenis_tahap_aplikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `jenis_tahap_fumigasis`
--
ALTER TABLE `jenis_tahap_fumigasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jenis_tasks`
--
ALTER TABLE `jenis_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jenis_task_alih_media`
--
ALTER TABLE `jenis_task_alih_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `marketings`
--
ALTER TABLE `marketings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_aplikasis`
--
ALTER TABLE `report_aplikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `report_fumigasis`
--
ALTER TABLE `report_fumigasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `task_alih_media`
--
ALTER TABLE `task_alih_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `task_aplikasis`
--
ALTER TABLE `task_aplikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `task_day_alih_media`
--
ALTER TABLE `task_day_alih_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `task_day_details`
--
ALTER TABLE `task_day_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `task_fumigasis`
--
ALTER TABLE `task_fumigasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `task_week_alih_media`
--
ALTER TABLE `task_week_alih_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `task_week_overviews`
--
ALTER TABLE `task_week_overviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `marketings`
--
ALTER TABLE `marketings`
  ADD CONSTRAINT `pic_fk` FOREIGN KEY (`nama_pic`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_manager_fk` FOREIGN KEY (`project_manager`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_aplikasis`
--
ALTER TABLE `report_aplikasis`
  ADD CONSTRAINT `FK_report_aplikasis_jenis_tahap_aplikasis` FOREIGN KEY (`jenistahapaplikasi_id`) REFERENCES `jenis_tahap_aplikasis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_report_aplikasis_task_aplikasis` FOREIGN KEY (`task_aplikasi_id`) REFERENCES `task_aplikasis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `report_fumigasis`
--
ALTER TABLE `report_fumigasis`
  ADD CONSTRAINT `FK_report_fumigasis_jenis_tahap_fumigasis` FOREIGN KEY (`jenistahapfumigasi_id`) REFERENCES `jenis_tahap_fumigasis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_report_fumigasis_task_fumigasis` FOREIGN KEY (`task_fumigasi_id`) REFERENCES `task_fumigasis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `marketing_marketingl_id_foreign` FOREIGN KEY (`marketing_id`) REFERENCES `marketings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `project_managerTask_fk` FOREIGN KEY (`project_manager`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `task_alih_media`
--
ALTER TABLE `task_alih_media`
  ADD CONSTRAINT `task_alih_media_ibfk_1` FOREIGN KEY (`marketing_id`) REFERENCES `marketings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_alih_media_ibfk_2` FOREIGN KEY (`project_manager`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `task_aplikasis`
--
ALTER TABLE `task_aplikasis`
  ADD CONSTRAINT `project_manager_aplikasi_fk` FOREIGN KEY (`project_manager`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `task_aplikasis_ibfk_1` FOREIGN KEY (`marketing_id`) REFERENCES `marketings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_day_alih_media`
--
ALTER TABLE `task_day_alih_media`
  ADD CONSTRAINT `jenis_task_alih_media_fk` FOREIGN KEY (`jenis_task_alih_media_id`) REFERENCES `jenis_task_alih_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_alih_media_id_fk` FOREIGN KEY (`task_alih_media_id`) REFERENCES `task_alih_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_week_alih_media_id_fk` FOREIGN KEY (`task_week_alih_media_id`) REFERENCES `task_week_alih_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_day_details`
--
ALTER TABLE `task_day_details`
  ADD CONSTRAINT `FK_task_week_overviews_id` FOREIGN KEY (`task_week_overview_id`) REFERENCES `task_week_overviews` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jenis_task` FOREIGN KEY (`jenis_task_id`) REFERENCES `jenis_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_day_details_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_fumigasis`
--
ALTER TABLE `task_fumigasis`
  ADD CONSTRAINT `project_manager_fumigasi_fk` FOREIGN KEY (`project_manager`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_fumigasis_ibfk_1` FOREIGN KEY (`marketing_id`) REFERENCES `marketings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_week_alih_media`
--
ALTER TABLE `task_week_alih_media`
  ADD CONSTRAINT `task_alih_media_id_fk_week` FOREIGN KEY (`task_alih_media_id`) REFERENCES `task_alih_media` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_week_overviews`
--
ALTER TABLE `task_week_overviews`
  ADD CONSTRAINT `task_id_fk` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
