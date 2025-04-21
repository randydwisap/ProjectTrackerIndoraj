-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 05:23 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_task_status` (IN `task_id_param` INT)   BEGIN
    DECLARE total_tasks INT DEFAULT 0;
    DECLARE completed_count INT DEFAULT 0;
    DECLARE not_started_count INT DEFAULT 0;
    DECLARE behind_schedule_count INT DEFAULT 0;
    DECLARE new_status VARCHAR(255);
    DECLARE tahap_baru VARCHAR(255);
    DECLARE max_jenis_task_id INT DEFAULT NULL;

    -- Hitung jumlah total task_details untuk task_id tertentu
    SELECT COUNT(*) INTO total_tasks FROM task_details WHERE task_id = task_id_param;
    
    -- Hitung jumlah masing-masing status
    SELECT 
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END),
        SUM(CASE WHEN status = 'Not Started' THEN 1 ELSE 0 END),
        SUM(CASE WHEN status = 'Behind Schedule' OR 'On Track' THEN 1 ELSE 0 END)
    INTO completed_count, not_started_count, behind_schedule_count
    FROM task_details
    WHERE task_id = task_id_param;

    -- Tentukan status berdasarkan kondisi baru
    IF total_tasks = 0 THEN
        SET new_status = 'Not Started';  -- Default jika tidak ada task_detail
    ELSEIF completed_count = total_tasks THEN
        SET new_status = 'Completed';
    ELSEIF not_started_count = total_tasks THEN
        SET new_status = 'Not Started';
    ELSEIF behind_schedule_count > (total_tasks / 2) THEN
        SET new_status = 'Behind Schedule';
    ELSE
        SET new_status = 'Far Behind Schedule';
    END IF;

    -- Cari jenis_task_id terbesar yang memiliki volume_dikerjakan > 0
    SELECT jenis_task_id 
    INTO max_jenis_task_id
    FROM task_details 
    WHERE task_id = task_id_param 
      AND volume_dikerjakan > 0
    ORDER BY jenis_task_id DESC, updated_at DESC
    LIMIT 1;

    -- Jika ada jenis_task_id yang memenuhi syarat, ambil tahap pengerjaannya
    IF max_jenis_task_id IS NOT NULL THEN
        SELECT nama_task 
        INTO tahap_baru
        FROM jenis_tasks 
        WHERE id = max_jenis_task_id;
    END IF;

    -- Update status dan tahap pengerjaan di tabel tasks
    UPDATE tasks 
    SET status = new_status,
        tahap_pengerjaan = IFNULL(tahap_baru, tahap_pengerjaan)  -- Update jika ditemukan, jika tidak biarkan
    WHERE id = task_id_param;
END$$

DELIMITER ;

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
(4, ' Pembuatan PRD', '2025-04-14 07:03:51', '2025-04-14 07:03:51'),
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
(1, 'Pemilahan', '2025-03-05 08:08:53', '2025-03-05 08:08:53'),
(2, 'Identifikasi', '2025-03-05 08:09:07', '2025-03-05 08:09:07'),
(3, 'Pemberkasan', '2025-03-05 08:09:26', '2025-03-05 08:09:26'),
(4, 'Manuver', '2025-03-05 08:09:40', '2025-03-05 08:09:40'),
(5, 'Input Data', '2025-03-05 08:09:47', '2025-03-05 08:09:47'),
(6, 'Pelabelan', '2025-03-26 13:52:25', '2025-03-26 13:52:26'),
(7, 'Penataan', '2025-03-26 13:52:38', '2025-03-26 13:52:37');

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
  `manajer_operasional` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketings`
--

INSERT INTO `marketings` (`id`, `nama_pekerjaan`, `jenis_pekerjaan`, `nama_klien`, `lokasi`, `tahap_pengerjaan`, `total_volume`, `nama_pic`, `project_manager`, `status`, `durasi_proyek`, `jumlah_sdm`, `nilai_proyek`, `link_rab`, `note`, `tgl_mulai`, `tgl_selesai`, `nilai_akhir_proyek`, `terms_of_payment`, `status_pembayaran`, `dokumentasi_foto`, `lampiran`, `created_at`, `updated_at`, `manajer_operasional`) VALUES
(1, 'Test Pekerjaan 1', 'Pengolahan Arsip', 'Diskominfo', 'Kota Surabaya', 'Kontrak', 150, 2, 2, 'On Hold', 1, 1, 150000.00, NULL, 'Tolong Ubah', '2025-03-12', '2025-03-25', 150000.00, 60, 'Lunas', '[\"01JP5HPHXTQ8VRGKXHXCTNEMWR.jpg\"]', '01JP5HPHXYVMQ0CX0EFTA4EKJP.pdf', '2025-03-12 09:00:34', '2025-04-09 05:23:12', 1),
(2, 'Test Pekerjaan 2', 'Fumigasi', 'Klien Pekerjaan 2', 'Kota Surabaya', 'Kontrak', 200, 2, 2, 'Completed', 1, 1, 200000000.00, NULL, 'Tolong Ubah', '2025-03-13', '2025-03-20', 300000000.00, 60, 'Lunas', '[\"01JP87V355WEN21NPWRCDZDGQS.jpg\",\"01JPC5NK6GNDCDZJ9KST6Q5MGK.png\"]', '01JP87V359ARVV7BWQMBTZPDEJ.pdf', '2025-03-13 10:11:41', '2025-04-18 10:03:39', 1),
(3, 'Bank Jateng', 'Aplikasi', 'Bank Jateng', 'Kota Semarang', 'Kontrak', 685, 2, 2, 'On Hold', 4, 11, 200000000.00, NULL, 'Coba Lagi', '2025-03-17', '2025-04-17', 200000000.00, 30, 'Belum Lunas', '[\"01JPJ4JK889Q19WH30QEYT6M4B.png\"]', '01JPJ4JK979GR58PPAP84M8N6E.pdf', '2025-03-17 06:27:02', '2025-04-09 04:31:58', 1);

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
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5);

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
-- Table structure for table `project_details`
--

CREATE TABLE `project_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `pelaksana` varchar(255) NOT NULL,
  `volume_arsip` int(11) NOT NULL,
  `jenis_arsip` varchar(255) NOT NULL,
  `deskripsi_pekerjaan` text DEFAULT NULL,
  `lama_pekerjaan_hari` int(11) NOT NULL,
  `lama_pekerjaan_minggu` int(11) NOT NULL,
  `target_perminggu` int(11) NOT NULL,
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

--
-- Dumping data for table `report_fumigasis`
--

INSERT INTO `report_fumigasis` (`id`, `task_fumigasi_id`, `tanggal`, `jenistahapfumigasi_id`, `keterangan`, `gambar`, `lampiran`, `updated_at`, `created_at`) VALUES
(11, 57, '2025-04-18', 1, 'Test', NULL, NULL, '2025-04-18 10:11:54', '2025-04-18 10:11:54'),
(12, 57, '2025-04-18', 2, 'Test', NULL, NULL, '2025-04-18 10:12:03', '2025-04-18 10:12:03');

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
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `resiko_keterlambatan` enum('Low','Moderate','High','Completed') NOT NULL DEFAULT 'Low',
  `durasi_proyek` int(11) DEFAULT NULL,
  `jumlah_sdm` int(11) NOT NULL,
  `project_manager` varchar(255) NOT NULL,
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
  `hasil_pemilahan` int(11) DEFAULT NULL,
  `jenis_arsip` varchar(255) DEFAULT NULL,
  `deskripsi_pekerjaan` text DEFAULT NULL,
  `lama_pekerjaan` int(11) DEFAULT NULL,
  `target_perminggu` int(11) DEFAULT NULL,
  `target_perday` int(11) DEFAULT NULL,
  `marketing_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `pekerjaan`, `klien`, `tahap_pengerjaan`, `status`, `resiko_keterlambatan`, `durasi_proyek`, `jumlah_sdm`, `project_manager`, `no_telp_pm`, `nilai_proyek`, `link_rab`, `tgl_mulai`, `tgl_selesai`, `created_at`, `updated_at`, `lokasi`, `pelaksana`, `volume_arsip`, `hasil_pemilahan`, `jenis_arsip`, `deskripsi_pekerjaan`, `lama_pekerjaan`, `target_perminggu`, `target_perday`, `marketing_id`) VALUES
(56, 'Test Pekerjaan 1', 'Diskominfo', 'Pemberkasan', 'Far Behind Schedule', 'Low', 2, 1, '1', '085856440997', 150000.00, NULL, '2025-03-12', '2025-03-25', '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Kota Surabaya', '[{\"nama\":\"tesr\"}]', 150, 325, 'Campuran', 'Test', 13, 75, 12, 1);

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
  `resiko_keterlambatan` enum('Low','Moderate','High','Completed') NOT NULL DEFAULT 'Low',
  `durasi_proyek` int(11) DEFAULT NULL,
  `jumlah_sdm` int(11) NOT NULL,
  `project_manager` varchar(255) NOT NULL,
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
(55, 'Bank Jateng', 'Bank Jateng', 'Requirement Gathering', 'Behind Schedule', 'Low', 5, 1, '1', '085856440997', 200000000.00, NULL, '2025-03-17', '2025-04-17', '2025-04-09 04:31:58', '2025-04-09 06:57:44', 'Kota Semarang', '[{\"nama\":\"Test\"}]', 685, 'Testt', 31, 137, 22, 3);

-- --------------------------------------------------------

--
-- Table structure for table `task_day_details`
--

CREATE TABLE `task_day_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_detail_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` varchar(255) NOT NULL,
  `output` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `hasil` int(11) DEFAULT NULL,
  `jenis_task_id` bigint(20) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_day_details`
--

INSERT INTO `task_day_details` (`id`, `task_detail_id`, `tanggal`, `output`, `status`, `created_at`, `updated_at`, `task_id`, `hasil`, `jenis_task_id`) VALUES
(86, 358, 'Day 1', 150, 'On Track', '2025-04-09 05:24:04', '2025-04-09 05:24:04', 56, 150, 1),
(92, 365, 'Day 1', 150, 'On Track', '2025-04-09 05:40:48', '2025-04-09 05:40:48', 56, 125, 1),
(93, 367, 'Day 1', 200, 'On Track', '2025-04-09 05:41:15', '2025-04-09 05:41:15', 56, NULL, 3),
(94, 358, 'Day 3', 50, 'On Track', '2025-04-09 07:56:38', '2025-04-09 07:56:38', 56, 50, 1),
(96, 365, 'Day 2', 50, 'On Track', '2025-04-09 07:57:12', '2025-04-09 07:57:12', 56, 50, 1);

--
-- Triggers `task_day_details`
--
DELIMITER $$
CREATE TRIGGER `after_task_day_detail_delete` AFTER DELETE ON `task_day_details` FOR EACH ROW BEGIN
    -- Update volume_dikerjakan setelah penghapusan data di task_day_details
    UPDATE task_details
    SET volume_dikerjakan = (
        SELECT COALESCE(SUM(output), 0) 
        FROM task_day_details
        WHERE task_detail_id = OLD.task_detail_id AND task_id = OLD.task_id
    )
    WHERE id = OLD.task_detail_id;

    -- Jika jenis_task_id = 1, update hasil terbaru setelah penghapusan data di task_day_details
    IF (SELECT jenis_task_id FROM task_details WHERE id = OLD.task_detail_id) = 1 THEN
        UPDATE task_details
        SET hasil = (
            SELECT COALESCE(MAX(hasil), 0) -- Ambil nilai hasil terbaru setelah penghapusan
            FROM task_day_details
            WHERE task_detail_id = OLD.task_detail_id
        )
        WHERE id = OLD.task_detail_id;
    ELSE
        -- Jika bukan jenis_task_id = 1, update hasil dengan total hasil dari task_day_details
        UPDATE task_details
        SET hasil = (
            SELECT COALESCE(SUM(hasil), 0)
            FROM task_day_details
            WHERE task_detail_id = OLD.task_detail_id AND nama_week = OLD.tanggal
        )
        WHERE id = OLD.task_detail_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_task_day_detail_insert` AFTER INSERT ON `task_day_details` FOR EACH ROW BEGIN
    -- Update volume_dikerjakan di task_details berdasarkan total output dari task_day_details
    UPDATE task_details
    SET volume_dikerjakan = (
        SELECT COALESCE(SUM(output), 0)
        FROM task_day_details
        WHERE task_detail_id = NEW.task_detail_id AND task_id = NEW.task_id
    )
    WHERE id = NEW.task_detail_id;

    -- Jika jenis_task_id = 1, simpan hasil terbaru ke task_details
    IF (SELECT jenis_task_id FROM task_details WHERE id = NEW.task_detail_id) = 1 THEN
        UPDATE task_details
           SET hasil = (
        SELECT COALESCE(SUM(hasil), 0)
        FROM task_day_details
        WHERE task_detail_id = NEW.task_detail_id
    )
    WHERE id = NEW.task_detail_id;
    ELSE
        -- Jika bukan jenis_task_id = 1, update hasil dengan total hasil dari task_day_details
        UPDATE task_details
        SET hasil = (
            SELECT COALESCE(SUM(hasil), 0)
            FROM task_day_details
            WHERE task_detail_id = NEW.task_detail_id AND nama_week = NEW.tanggal
        )
        WHERE id = NEW.task_detail_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_task_day_detail_update` AFTER UPDATE ON `task_day_details` FOR EACH ROW BEGIN
    -- Update volume_dikerjakan di task_details berdasarkan total output dari task_day_details
    UPDATE task_details
    SET volume_dikerjakan = (
        SELECT COALESCE(SUM(output), 0)
        FROM task_day_details
        WHERE task_detail_id = NEW.task_detail_id AND task_id = NEW.task_id
    )
    WHERE id = NEW.task_detail_id;

    -- Jika jenis_task_id = 1, update hasil terbaru ke task_details
    IF (SELECT jenis_task_id FROM task_details WHERE id = NEW.task_detail_id) = 1 THEN
        UPDATE task_details
            SET hasil = (
        SELECT COALESCE(SUM(hasil), 0)
        FROM task_day_details
        WHERE task_detail_id = NEW.task_detail_id
    )
    WHERE id = NEW.task_detail_id;
    ELSE
        -- Jika bukan jenis_task_id = 1, update hasil dengan total hasil dari task_day_details
        UPDATE task_details
        SET hasil = (
            SELECT COALESCE(SUM(hasil), 0)
            FROM task_day_details
            WHERE task_detail_id = NEW.task_detail_id AND nama_week = NEW.tanggal
        )
        WHERE id = NEW.task_detail_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_task_day_details` BEFORE INSERT ON `task_day_details` FOR EACH ROW BEGIN
    DECLARE target INT;

    -- Ambil target_perday dari tabel tasks berdasarkan task_id
    SELECT COALESCE(target_perday, 0) INTO target
    FROM tasks 
    WHERE id = NEW.task_id;

    -- Tentukan status berdasarkan nilai `output`
    IF target = 0 THEN
        SET NEW.status = 'Behind Schedule';
    ELSEIF NEW.output = target THEN
        SET NEW.status = 'Completed';
    ELSEIF NEW.output > target THEN
        SET NEW.status = 'On Track';
    ELSE
        SET NEW.status = 'Behind Schedule';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_task_day_details` BEFORE UPDATE ON `task_day_details` FOR EACH ROW BEGIN
    DECLARE target INT;

    -- Ambil target_perday dari tabel tasks berdasarkan task_id
    SELECT COALESCE(target_perday, 0) INTO target
    FROM tasks 
    WHERE id = NEW.task_id;

    -- Update status langsung ke nilai NEW.status tanpa UPDATE query
    IF target = 0 THEN
        SET NEW.status = 'Behind Schedule';
    ELSEIF NEW.output = target THEN
        SET NEW.status = 'Completed';
    ELSEIF NEW.output > target THEN
        SET NEW.status = 'On Track';
    ELSE
        SET NEW.status = 'Behind Schedule';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `task_details`
--

CREATE TABLE `task_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_task_id` bigint(20) UNSIGNED NOT NULL,
  `nama_week` varchar(255) NOT NULL,
  `total_volume` int(11) NOT NULL,
  `volume_dikerjakan` int(11) NOT NULL DEFAULT 0,
  `sisa_volume` int(11) NOT NULL DEFAULT 0,
  `hasil` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `resiko_keterlambatan` varchar(10) GENERATED ALWAYS AS (case when `status` = 'Completed' then 'Completed' when `volume_dikerjakan` / `total_volume` >= 1 then 'Low' when `volume_dikerjakan` / `total_volume` >= 0.8 then 'Moderate' else 'High' end) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_details`
--

INSERT INTO `task_details` (`id`, `task_id`, `jenis_task_id`, `nama_week`, `total_volume`, `volume_dikerjakan`, `sisa_volume`, `hasil`, `created_at`, `updated_at`, `status`) VALUES
(358, 56, 1, 'Week 1', 75, 200, -125, 150, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(359, 56, 2, 'Week 1', 150, 0, 150, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(360, 56, 3, 'Week 1', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(361, 56, 4, 'Week 1', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(362, 56, 5, 'Week 1', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(363, 56, 6, 'Week 1', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(364, 56, 7, 'Week 1', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(365, 56, 1, 'Week 2', 75, 200, -125, 175, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(366, 56, 2, 'Week 2', 150, 0, 150, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(367, 56, 3, 'Week 2', 275, 200, 75, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Behind Schedule'),
(368, 56, 4, 'Week 2', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(369, 56, 5, 'Week 2', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(370, 56, 6, 'Week 2', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started'),
(371, 56, 7, 'Week 2', 0, 0, 0, 0, '2025-04-09 05:23:12', '2025-04-09 05:23:12', 'Not Started');

--
-- Triggers `task_details`
--
DELIMITER $$
CREATE TRIGGER `after_task_detail_delete` AFTER DELETE ON `task_details` FOR EACH ROW BEGIN
    CALL update_task_status(OLD.task_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_task_detail_insert` AFTER INSERT ON `task_details` FOR EACH ROW BEGIN
    CALL update_task_status(NEW.task_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_task_detail_update` AFTER UPDATE ON `task_details` FOR EACH ROW BEGIN
    CALL update_task_status(NEW.task_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_task_details_delete` AFTER DELETE ON `task_details` FOR EACH ROW BEGIN
    -- Hapus rekap lama terkait task_id dan week_number
    DELETE FROM task_week_overviews 
    WHERE task_id = OLD.task_id AND nama_week = CONCAT('Week ', OLD.nama_week);
    
        UPDATE tasks 
    SET hasil_pemilahan = (
        SELECT COALESCE(SUM(hasil), 0) FROM task_details WHERE task_id = OLD.task_id
    )
    WHERE id = OLD.task_id;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_task_details_insert` AFTER INSERT ON `task_details` FOR EACH ROW BEGIN
    -- Cek apakah data sudah ada di task_week_overviews
    IF EXISTS (
        SELECT 1 FROM task_week_overviews 
        WHERE task_id = NEW.task_id AND nama_week = NEW.nama_week
    ) THEN
        -- Jika ada, lakukan UPDATE
        UPDATE task_week_overviews 
        SET status = (
            SELECT status FROM task_details 
            WHERE task_id = NEW.task_id AND nama_week = NEW.nama_week
            GROUP BY status 
            ORDER BY COUNT(status) DESC 
            LIMIT 1
        ),
        resiko_keterlambatan = (
            SELECT MAX(resiko_keterlambatan) 
            FROM task_details 
            WHERE task_id = NEW.task_id AND nama_week = NEW.nama_week
        )
        WHERE task_id = NEW.task_id AND nama_week = NEW.nama_week;
    ELSE
        -- Jika tidak ada, lakukan INSERT
        INSERT INTO task_week_overviews (task_id, nama_week, status, resiko_keterlambatan)
        SELECT 
            td.task_id, 
            td.nama_week,
            (SELECT status FROM task_details 
             WHERE task_id = td.task_id AND nama_week = td.nama_week 
             GROUP BY status 
             ORDER BY COUNT(status) DESC 
             LIMIT 1) AS status,
            MAX(td.resiko_keterlambatan) AS resiko_keterlambatan
        FROM task_details td
        WHERE td.task_id = NEW.task_id AND td.nama_week = NEW.nama_week
        GROUP BY td.task_id, td.nama_week;
    END IF;
    
        UPDATE tasks 
    SET hasil_pemilahan = (
        SELECT COALESCE(SUM(hasil), 0) FROM task_details WHERE task_id = NEW.task_id
    )
    WHERE id = NEW.task_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_task_details_update` AFTER UPDATE ON `task_details` FOR EACH ROW BEGIN
    -- Cek apakah data sudah ada di task_week_overviews
    IF EXISTS (
        SELECT 1 FROM task_week_overviews 
        WHERE task_id = NEW.task_id AND nama_week = NEW.nama_week
    ) THEN
        -- Jika ada, lakukan UPDATE
        UPDATE task_week_overviews 
        SET status = (
            SELECT status FROM task_details 
            WHERE task_id = NEW.task_id AND nama_week = NEW.nama_week
            GROUP BY status 
            ORDER BY COUNT(status) DESC 
            LIMIT 1
        ),
        resiko_keterlambatan = (
            SELECT MAX(resiko_keterlambatan) 
            FROM task_details 
            WHERE task_id = NEW.task_id AND nama_week = NEW.nama_week
        )
        WHERE task_id = NEW.task_id AND nama_week = NEW.nama_week;
    ELSE
        -- Jika tidak ada, lakukan INSERT
        INSERT INTO task_week_overviews (task_id, nama_week, status, resiko_keterlambatan)
        SELECT 
            td.task_id, 
            td.nama_week,
            (SELECT status FROM task_details 
             WHERE task_id = td.task_id AND nama_week = td.nama_week 
             GROUP BY status 
             ORDER BY COUNT(status) DESC 
             LIMIT 1) AS status,
            MAX(td.resiko_keterlambatan) AS resiko_keterlambatan
        FROM task_details td
        WHERE td.task_id = NEW.task_id AND td.nama_week = NEW.nama_week
        GROUP BY td.task_id, td.nama_week;
    END IF;
    
        UPDATE tasks 
    SET hasil_pemilahan = (
        SELECT COALESCE(SUM(hasil), 0) FROM task_details WHERE task_id = NEW.task_id
    )
    WHERE id = NEW.task_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_task` BEFORE INSERT ON `task_details` FOR EACH ROW BEGIN
    SET NEW.sisa_volume = NEW.total_volume - NEW.volume_dikerjakan;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_task_details` BEFORE INSERT ON `task_details` FOR EACH ROW BEGIN
     IF NEW.jenis_task_id <> 1 THEN
        SET NEW.total_volume = (
            SELECT COALESCE(SUM(hasil), 0) 
            FROM task_details 
            WHERE jenis_task_id = 1
        );
    END IF;
    -- Hitung sisa_volume
    SET NEW.sisa_volume = NEW.total_volume - NEW.volume_dikerjakan;

    -- Update status berdasarkan sisa_volume
    IF NEW.volume_dikerjakan = 0 THEN
        SET NEW.status = 'Not Started'; 
    ELSEIF NEW.sisa_volume = 0 THEN
        SET NEW.status = 'Completed';
    ELSEIF NEW.volume_dikerjakan < NEW.total_volume THEN
        SET NEW.status = 'Behind Schedule';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_task` BEFORE UPDATE ON `task_details` FOR EACH ROW BEGIN
    SET NEW.sisa_volume = NEW.total_volume - NEW.volume_dikerjakan;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_task_details` BEFORE UPDATE ON `task_details` FOR EACH ROW BEGIN

     IF NEW.jenis_task_id <> 1 THEN
        SET NEW.total_volume = (
            SELECT COALESCE(SUM(hasil), 0) 
            FROM task_details 
            WHERE jenis_task_id = 1
        );
    END IF;
    -- Hitung ulang sisa_volume jika total_volume atau volume_dikerjakan berubah
    SET NEW.sisa_volume = NEW.total_volume - NEW.volume_dikerjakan;

    -- Update status berdasarkan sisa_volume
    IF NEW.volume_dikerjakan = 0 THEN
        SET NEW.status = 'Not Started'; 
    ELSEIF NEW.sisa_volume = 0 THEN
        SET NEW.status = 'Completed';
    ELSEIF NEW.volume_dikerjakan < NEW.total_volume THEN
        SET NEW.status = 'Behind Schedule';
    END IF;
END
$$
DELIMITER ;

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
  `resiko_keterlambatan` enum('Low','Moderate','High','Completed') NOT NULL DEFAULT 'Low',
  `durasi_proyek` int(11) DEFAULT NULL,
  `jumlah_sdm` int(11) NOT NULL,
  `project_manager` varchar(255) NOT NULL,
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
(57, 'Test Pekerjaan 2', 'Klien Pekerjaan 2', 'Penyegelan', 'Behind Schedule', 'Low', 1, 1, '1', '085856440997', 300000000.00, NULL, '2025-03-13', '2025-03-20', '2025-04-17 07:02:02', '2025-04-18 10:12:04', 'Kota Surabaya', '[{\"nama\":\"Test\"}]', 200, 'Test', 7, 200, 29, 2);

-- --------------------------------------------------------

--
-- Table structure for table `task_week_overviews`
--

CREATE TABLE `task_week_overviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `nama_week` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `resiko_keterlambatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_week_overviews`
--

INSERT INTO `task_week_overviews` (`id`, `task_id`, `nama_week`, `status`, `resiko_keterlambatan`, `created_at`, `updated_at`) VALUES
(40, 56, 'Week 1', 'Not Started', 'Low', NULL, NULL),
(41, 56, 'Week 2', 'Not Started', 'Low', NULL, NULL);

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
(1, 'Super Admin', '', '', 'admin@email.com', NULL, '$2y$12$mOJsDcuDUOezXhHMazkX.OIy2crsfy7qKE7tAoIUCJob4.2bQUCj.', '2PRl49j3IL3t2w3CuufhCdTOasovxGRyzf7uTgDLxrAFM8ASVDT8oga8N0IG', '2025-03-03 16:51:43', '2025-03-15 01:05:59', NULL, '085856440997'),
(2, 'Randy Dwi Saputra', '', '', 'randy@email.com', NULL, '$2y$12$gqb.X0rbm99a2WcNt1FBZeYk7O99ZgqYAi/vLK/rQ/460tH6hbGOi', NULL, '2025-03-03 16:54:50', '2025-03-04 08:53:48', NULL, '085856440997'),
(3, 'rundoy11', '', '', 'rundoy@gmail.com', NULL, '$2y$12$vNY.t5sRlOzdVIES6MAgxuUzGWtbKYzl3F4EtOCLxSVW/gEtHkdLO', NULL, '2025-03-03 18:19:47', '2025-03-03 18:19:47', NULL, '085856440997'),
(4, 'rundoy12', '', '', 'rundoy12@gmail.com', NULL, '$2y$12$mtzo8G8JX8WsHT7RGJuHG.t8tLrnHq7VNAtltUV/wR9fHr4hKZfOO', NULL, '2025-03-03 18:21:07', '2025-03-03 18:21:07', NULL, '085856440997'),
(5, 'randy', '', '', 'rundoyyyy@gmail.com', NULL, '$2y$12$wgY3./ygZ07/FuUJsE702OF6saMLQ/tFpLvHLlegiR0RmdTzo3MDK', NULL, '2025-03-03 18:21:30', '2025-03-03 18:21:30', NULL, '085856440997');

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
-- Indexes for table `marketings`
--
ALTER TABLE `marketings`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `project_details`
--
ALTER TABLE `project_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_details_task_id_foreign` (`task_id`);

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
  ADD KEY `marketing_marketingl_id_foreign` (`marketing_id`);

--
-- Indexes for table `task_aplikasis`
--
ALTER TABLE `task_aplikasis`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `marketing_marketingl_id_foreign` (`marketing_id`) USING BTREE;

--
-- Indexes for table `task_day_details`
--
ALTER TABLE `task_day_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_day_details_task_detail_id_foreign` (`task_detail_id`),
  ADD KEY `task_day_details_task_id_foreign` (`task_id`),
  ADD KEY `FK_task_day_details_task_details` (`jenis_task_id`);

--
-- Indexes for table `task_details`
--
ALTER TABLE `task_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_details_task_id_foreign` (`task_id`),
  ADD KEY `task_details_jenis_task_id_foreign` (`jenis_task_id`);

--
-- Indexes for table `task_fumigasis`
--
ALTER TABLE `task_fumigasis`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `marketing_marketingl_id_foreign` (`marketing_id`) USING BTREE;

--
-- Indexes for table `task_week_overviews`
--
ALTER TABLE `task_week_overviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_week_overviews_task_id_foreign` (`task_id`);

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
-- AUTO_INCREMENT for table `marketings`
--
ALTER TABLE `marketings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `project_details`
--
ALTER TABLE `project_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_aplikasis`
--
ALTER TABLE `report_aplikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `report_fumigasis`
--
ALTER TABLE `report_fumigasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `task_aplikasis`
--
ALTER TABLE `task_aplikasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `task_day_details`
--
ALTER TABLE `task_day_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `task_details`
--
ALTER TABLE `task_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=372;

--
-- AUTO_INCREMENT for table `task_fumigasis`
--
ALTER TABLE `task_fumigasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `task_week_overviews`
--
ALTER TABLE `task_week_overviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `project_details`
--
ALTER TABLE `project_details`
  ADD CONSTRAINT `project_details_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `marketing_marketingl_id_foreign` FOREIGN KEY (`marketing_id`) REFERENCES `marketings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_aplikasis`
--
ALTER TABLE `task_aplikasis`
  ADD CONSTRAINT `task_aplikasis_ibfk_1` FOREIGN KEY (`marketing_id`) REFERENCES `marketings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_day_details`
--
ALTER TABLE `task_day_details`
  ADD CONSTRAINT `FK_task_day_details_task_details` FOREIGN KEY (`jenis_task_id`) REFERENCES `task_details` (`jenis_task_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_day_details_task_detail_id_foreign` FOREIGN KEY (`task_detail_id`) REFERENCES `task_details` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_day_details_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_details`
--
ALTER TABLE `task_details`
  ADD CONSTRAINT `task_details_jenis_task_id_foreign` FOREIGN KEY (`jenis_task_id`) REFERENCES `jenis_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_details_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_fumigasis`
--
ALTER TABLE `task_fumigasis`
  ADD CONSTRAINT `task_fumigasis_ibfk_1` FOREIGN KEY (`marketing_id`) REFERENCES `marketings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_week_overviews`
--
ALTER TABLE `task_week_overviews`
  ADD CONSTRAINT `task_week_overviews_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
