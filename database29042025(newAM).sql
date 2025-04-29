-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 10:58 AM
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
(2, 'App\\Models\\User', 6);

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
(32, 'task.view', 'web', '2025-03-06 06:52:56', '2025-03-06 06:52:56'),
(33, 'task.create', 'web', '2025-03-06 06:52:56', '2025-03-06 06:52:56'),
(34, 'task.update', 'web', '2025-03-06 06:52:56', '2025-03-06 06:52:56'),
(35, 'task.delete', 'web', '2025-03-06 06:52:56', '2025-03-06 06:52:56'),
(36, 'taskDetail.view', 'web', '2025-03-06 07:04:16', '2025-03-06 07:04:16'),
(37, 'taskDetail.create', 'web', '2025-03-06 07:04:17', '2025-03-06 07:04:17'),
(38, 'taskDetail.update', 'web', '2025-03-06 07:04:17', '2025-03-06 07:04:17'),
(39, 'taskDetail.delete', 'web', '2025-03-06 07:04:17', '2025-03-06 07:04:17'),
(40, 'taskWeekOverview.view', 'web', '2025-03-06 07:04:17', '2025-03-06 07:04:17'),
(41, 'taskWeekOverview.create', 'web', '2025-03-06 07:04:17', '2025-03-06 07:04:17'),
(42, 'taskWeekOverview.update', 'web', '2025-03-06 07:04:17', '2025-03-06 07:04:17'),
(43, 'taskWeekOverview.delete', 'web', '2025-03-06 07:04:17', '2025-03-06 07:04:17');

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
(2, 'Admin', 'web', '2025-03-03 17:18:30', '2025-03-03 17:18:30');

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
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(27, 1),
(27, 2),
(28, 1),
(28, 2),
(29, 1),
(29, 2),
(30, 1),
(30, 2),
(32, 1),
(32, 2),
(33, 1),
(33, 2),
(34, 1),
(34, 2),
(35, 1),
(35, 2),
(36, 1),
(36, 2),
(37, 1),
(37, 2),
(38, 1),
(38, 2),
(39, 1),
(39, 2),
(40, 1),
(40, 2),
(41, 1),
(41, 2),
(42, 1),
(42, 2),
(43, 1),
(43, 2);

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
(1, 'Manajer Operasional', '123', '123', 'admin@email.com', NULL, '$2y$12$mOJsDcuDUOezXhHMazkX.OIy2crsfy7qKE7tAoIUCJob4.2bQUCj.', 'Q0hdNfc7dXKgCY8PnOSP4hLuKA5THGOTk3N59IF2jmIXm3kvoeDFpQiwS2sV', '2025-03-03 16:51:43', '2025-03-15 01:05:59', 'Manajer Operasional', '081'),
(6, 'Project Manager 1', '123', '123', 'pm1@gmail.com', NULL, '12345678', NULL, '2025-04-29 01:57:19', '2025-04-29 01:57:19', 'Project Manager', '123');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_aplikasis`
--
ALTER TABLE `report_aplikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `report_fumigasis`
--
ALTER TABLE `report_fumigasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `task_alih_media`
--
ALTER TABLE `task_alih_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `task_aplikasis`
--
ALTER TABLE `task_aplikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `task_day_alih_media`
--
ALTER TABLE `task_day_alih_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `task_day_details`
--
ALTER TABLE `task_day_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_fumigasis`
--
ALTER TABLE `task_fumigasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `task_week_alih_media`
--
ALTER TABLE `task_week_alih_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `task_week_overviews`
--
ALTER TABLE `task_week_overviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
