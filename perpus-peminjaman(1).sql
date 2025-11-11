-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: db_primary
-- Generation Time: Nov 11, 2025 at 09:12 AM
-- Server version: 8.0.43
-- PHP Version: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpus-peminjaman`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `agenda_id` int UNSIGNED NOT NULL,
  `agendakategori_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_agenda` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_agenda` text COLLATE utf8mb4_unicode_ci,
  `tanggal_start_agenda` date NOT NULL,
  `tanggal_end_agenda` date DEFAULT NULL,
  `waktu_start_agenda` time DEFAULT NULL,
  `waktu_end_agenda` time DEFAULT NULL,
  `lokasi_agenda` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_agenda` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_agenda` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agenda_has_kategori`
--

CREATE TABLE `agenda_has_kategori` (
  `agenda_id` int UNSIGNED NOT NULL,
  `agendakategori_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agenda_kategori`
--

CREATE TABLE `agenda_kategori` (
  `agendakategori_id` int UNSIGNED NOT NULL,
  `kode_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_kategori` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `akreditasi`
--

CREATE TABLE `akreditasi` (
  `akreditasi_id` bigint UNSIGNED NOT NULL,
  `level` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lembaga_akreditasi` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_akreditasi` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `akreditasi` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filename_akreditasi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biblio`
--

CREATE TABLE `biblio` (
  `biblio_id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `year` int NOT NULL,
  `publisher` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:14:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"dashboard-view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:9:\"user-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"user-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:9:\"user-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:11:\"user-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:9:\"role-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:11:\"role-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:9:\"role-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:11:\"role-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:11:\"biblio-list\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:13:\"biblio-create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:11:\"biblio-edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:13:\"biblio-delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:13:\"biblio-export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:13:\"administrator\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:3:\"web\";}}}', 1762918485),
('laravel_cache_user_token_0046acdb-2f2a-4227-8aca-ee0bbaad31fa', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762849135),
('laravel_cache_user_token_02f4a325-4fe2-4e2b-8886-35c326faecfc', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762766310),
('laravel_cache_user_token_17338a59-649f-4487-89b3-a130355e3a37', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763136),
('laravel_cache_user_token_18b6bc0f-7664-4fdd-8111-fca77e82291d', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762848993),
('laravel_cache_user_token_1e7094d1-0eb3-419b-bc22-5e6b94e6536d', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763133),
('laravel_cache_user_token_27fc0291-3fa2-466a-b8e2-5fabb5f617d1', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763193),
('laravel_cache_user_token_3dad8acf-ad1e-4b55-9c3e-dfa1ce8a3d0d', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762851924),
('laravel_cache_user_token_462eb5bd-438a-4cfa-a56b-bed24578e659', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762785204),
('laravel_cache_user_token_4b92e19f-7943-4c4a-8fc9-b44cc391345e', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762784691),
('laravel_cache_user_token_4c9ab712-a075-49db-af91-b786b31d2cf6', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762762902),
('laravel_cache_user_token_4e6690a2-217a-4af2-ad2a-ca28e86672cd', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763546),
('laravel_cache_user_token_4e7ec738-0e9e-46e7-ba85-dd1f1e19dcaa', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763566),
('laravel_cache_user_token_6c0ceea8-cc6d-4d75-bb98-f0ce3190d1e2', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763037),
('laravel_cache_user_token_6f94fcc2-dd9a-4070-b90b-cef4960d9820', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763316),
('laravel_cache_user_token_733bc7e8-5eba-490d-95f7-220b8bb0e5d1', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763351),
('laravel_cache_user_token_75fd40a1-2de4-4831-b6d2-050add6f6a11', 'a:3:{s:7:\"user_id\";i:3;s:4:\"name\";s:5:\"Admin\";s:11:\"nomor_induk\";s:9:\"123456789\";}', 1762762458),
('laravel_cache_user_token_7ee88d19-88e1-4fc4-99c0-b2eb9591243e', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763661),
('laravel_cache_user_token_8504739d-ba19-42cf-95d1-46c402f0f6d1', 'a:3:{s:7:\"user_id\";i:3;s:4:\"name\";s:5:\"Admin\";s:11:\"nomor_induk\";s:9:\"123456789\";}', 1762762622),
('laravel_cache_user_token_8b6eb616-4695-4a5c-8a47-90cb062d036f', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763318),
('laravel_cache_user_token_91bf058b-1fdc-452e-ab5e-77e12337b1f7', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762832693),
('laravel_cache_user_token_93d4e599-1d03-4275-9818-9d9bc6c53568', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763578),
('laravel_cache_user_token_ae11567a-3348-44e1-b573-ec4c4a50ead0', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763645),
('laravel_cache_user_token_c0baa65a-c143-4732-92bb-9fae5353e40c', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762763581),
('laravel_cache_user_token_d24fd9d3-eb9f-4742-b71d-3d9b8825a115', 'a:3:{s:7:\"user_id\";i:1;s:4:\"name\";s:14:\"VARRENT EDBERT\";s:11:\"nomor_induk\";s:10:\"2257301132\";}', 1762832500);

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
-- Table structure for table `cart_loan`
--

CREATE TABLE `cart_loan` (
  `loan_id` bigint UNSIGNED NOT NULL,
  `member_id` int NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `list_item` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_loan`
--

INSERT INTO `cart_loan` (`loan_id`, `member_id`, `tanggal`, `list_item`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(3, 1, NULL, '[{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T12:52:10.953450Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-06T13:02:52.850890Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]', '2025-11-06 12:52:10', '2025-11-06 13:08:29', '2025-11-06 13:08:29', NULL, NULL, NULL),
(4, 1, NULL, '[{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T14:02:52.738367Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]', '2025-11-06 13:55:02', '2025-11-06 14:04:23', '2025-11-06 14:04:23', NULL, NULL, NULL),
(5, 1, NULL, '[{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T02:45:26.975891Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]', '2025-11-07 02:45:15', '2025-11-07 02:48:14', '2025-11-07 02:48:14', NULL, NULL, NULL),
(6, 1, NULL, '[{\"title\": \"Book 1\", \"author\": \"Author 1\", \"item_id\": 2, \"added_at\": \"2025-11-07T03:09:40.295259Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"Book 2\", \"author\": \"Author 2\", \"item_id\": 3, \"added_at\": \"2025-11-07T03:09:40.295321Z\", \"biblio_id\": 2, \"item_code\": \"P00003S4555\"}]', '2025-11-07 03:09:40', '2025-11-07 03:09:40', '2025-11-07 03:09:40', NULL, NULL, NULL),
(7, 1, NULL, '[{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T04:18:38.612543Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]', '2025-11-07 04:18:38', '2025-11-07 04:18:42', '2025-11-07 04:18:42', NULL, NULL, NULL),
(8, 1, NULL, '[{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T07:44:50.914866Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T08:09:21.039726Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]', '2025-11-07 07:44:50', '2025-11-07 08:16:43', '2025-11-07 08:16:43', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dm_infografis`
--

CREATE TABLE `dm_infografis` (
  `infografis_id` bigint UNSIGNED NOT NULL,
  `nama_infografis` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_infografis` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_infografis` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `seq` int NOT NULL,
  `sync_url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_log` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_jurusan`
--

CREATE TABLE `dm_jurusan` (
  `jurusan_id` bigint UNSIGNED NOT NULL,
  `alias_jurusan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jurusan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_jurusan` text COLLATE utf8mb4_unicode_ci,
  `media_id_jurusan` int UNSIGNED DEFAULT NULL,
  `sync_log` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_kurikulum`
--

CREATE TABLE `dm_kurikulum` (
  `alias_prodi` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_kurikulum` int NOT NULL,
  `nama_kurikulum` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matakuliah` text COLLATE utf8mb4_unicode_ci,
  `sync_log` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_pegawai`
--

CREATE TABLE `dm_pegawai` (
  `nip_pegawai` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nidn_pegawai` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inisial` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_pegawai` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_pegawai` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gelar_depan_pegawai` text COLLATE utf8mb4_unicode_ci,
  `gelar_belakang_pegawai` text COLLATE utf8mb4_unicode_ci,
  `homebase_pegawai` text COLLATE utf8mb4_unicode_ci,
  `jabatan_pegawai` text COLLATE utf8mb4_unicode_ci,
  `fungsional_pegawai` text COLLATE utf8mb4_unicode_ci,
  `profil_pegawai` text COLLATE utf8mb4_unicode_ci,
  `media_id_pegawai` text COLLATE utf8mb4_unicode_ci,
  `sync_log` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dm_prodi`
--

CREATE TABLE `dm_prodi` (
  `prodi_id` bigint UNSIGNED NOT NULL,
  `alias_prodi` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias_jurusan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_prodi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_prodi` text COLLATE utf8mb4_unicode_ci,
  `media_id_prodi` int UNSIGNED DEFAULT NULL,
  `sync_log` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int UNSIGNED NOT NULL,
  `eventkategori_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_event` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_event` text COLLATE utf8mb4_unicode_ci,
  `tanggal_event` date NOT NULL,
  `waktu_event` time DEFAULT NULL,
  `lokasi_event` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_event` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_event` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `media_id_event` int UNSIGNED DEFAULT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_has_kategori`
--

CREATE TABLE `event_has_kategori` (
  `event_id` int UNSIGNED NOT NULL,
  `eventkategori_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_kategori`
--

CREATE TABLE `event_kategori` (
  `eventkategori_id` int UNSIGNED NOT NULL,
  `kode_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_kategori` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event_progres`
--

CREATE TABLE `event_progres` (
  `eventprogres_id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `status_progres` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan_progres` text COLLATE utf8mb4_unicode_ci,
  `user_id_progres` int UNSIGNED NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `faq_id` bigint UNSIGNED NOT NULL,
  `faqgroup_id` bigint UNSIGNED NOT NULL,
  `faq` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jawaban_faq` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_faq` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_group`
--

CREATE TABLE `faq_group` (
  `faqgroup_id` bigint UNSIGNED NOT NULL,
  `nama_group` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_group` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `konten`
--

CREATE TABLE `konten` (
  `konten_id` bigint UNSIGNED NOT NULL,
  `page_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_id` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_specification` text COLLATE utf8mb4_unicode_ci,
  `data_values` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `media_id` bigint UNSIGNED NOT NULL,
  `mimetype_media` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_media` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filepath_media` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumb_media` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ext_media` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filesize_media` int NOT NULL,
  `info_media` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
(4, '2024_06_18_035755_create_activity_log_table', 1),
(5, '2024_06_18_035756_add_event_column_to_activity_log_table', 1),
(6, '2024_06_18_035757_add_batch_uuid_column_to_activity_log_table', 1),
(7, '2024_06_18_035941_create_permission_tables', 1),
(8, '2024_12_06_050010_create_post_label_table', 1),
(9, '2024_12_06_050013_create_post_kategori_table', 1),
(10, '2024_12_06_050015_create_post_table', 1),
(11, '2024_12_06_050018_create_post_has_label_table', 1),
(12, '2024_12_06_050020_create_post_has_kategori_table', 1),
(13, '2024_12_06_050023_create_post_progres_table', 1),
(14, '2024_12_06_050025_create_post_spotlight_table', 1),
(15, '2024_12_06_050028_create_event_kategori_table', 1),
(16, '2024_12_06_050031_create_event_table', 1),
(17, '2024_12_06_050033_create_event_progres_table', 1),
(18, '2024_12_06_050036_create_event_has_kategori_table', 1),
(19, '2024_12_09_090021_create_testi_table', 1),
(20, '2024_12_09_090024_create_testi_kategori_table', 1),
(21, '2024_12_09_090027_create_testi_has_kategori_table', 1),
(22, '2024_12_11_072727_create_media_table', 1),
(23, '2024_12_20_104138_create_konten_jurusan_table', 1),
(24, '2024_12_20_104141_create_konten_prodi_table', 1),
(25, '2025_01_31_044637_create_konten_page_table', 1),
(26, '2025_01_31_093302_create_konten_tipe_table', 1),
(27, '2025_01_31_094321_create_konten_page_config_table', 1),
(28, '2025_06_19_073157_create_konten_main_table', 1),
(29, '2025_06_20_045345_create_social_media_table', 1),
(30, '2025_06_20_045600_create_dm_jurusan_table', 1),
(31, '2025_06_20_045612_create_dm_prodi_table', 1),
(32, '2025_06_20_084705_create_konten_config_table', 1),
(33, '2025_06_20_084901_create_mst_partner_table', 1),
(34, '2025_06_20_085453_create_dm_kurikulum_table', 1),
(35, '2025_06_30_112445_create_konten_table', 1),
(36, '2025_07_13_125751_create_dm_infografis_table', 1),
(37, '2025_08_12_104714_create_table_agenda', 1),
(38, '2025_08_12_104721_create_table_agenda_kategori', 1),
(39, '2025_08_12_105105_create_agenda_has_kategori', 1),
(40, '2025_08_13_080608_create_dm_pegawai_table', 1),
(41, '2025_08_18_081926_create_mst_kontak_table', 1),
(42, '2025_08_23_074241_create_post_slug_redirect_table', 1),
(43, '2025_08_25_112938_create_akreditasi_table', 1),
(44, '2025_08_25_112958_create_faq_table', 1),
(45, '2025_08_25_113024_create_faq_group_table', 1),
(46, '2025_09_25_000002_create_biblio_table', 1),
(47, '2025_09_29_013739_add_role_and_google_id_to_users_table', 1),
(48, '2025_10_03_022434_remove_role_from_users_table', 1),
(49, '2025_10_30_074121_add_nomor_induk_to_users_table', 2),
(50, '2025_11_06_015951_create_cart_loan_table', 3),
(51, '2025_11_06_124340_add_soft_deletes_and_user_tracking_to_cart_loan_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `mst_kontak`
--

CREATE TABLE `mst_kontak` (
  `kontak_id` bigint UNSIGNED NOT NULL,
  `nama_kontak` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_kontak` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe_kontak` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_kontak` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_kontak` text COLLATE utf8mb4_unicode_ci,
  `status_kontak` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mst_partner`
--

CREATE TABLE `mst_partner` (
  `partner_id` bigint UNSIGNED NOT NULL,
  `jenis_partner` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_partner` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_partner` text COLLATE utf8mb4_unicode_ci,
  `url_partner` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filemedia_partner` text COLLATE utf8mb4_unicode_ci,
  `status_partner` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mst_social_media`
--

CREATE TABLE `mst_social_media` (
  `socialmedia_id` bigint UNSIGNED NOT NULL,
  `platform` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_social_media` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url_social_media` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_social_media` text COLLATE utf8mb4_unicode_ci,
  `status_social_media` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int UNSIGNED NOT NULL,
  `postkategori_id` int UNSIGNED NOT NULL,
  `level` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_id` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul_post` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi_post` text COLLATE utf8mb4_unicode_ci,
  `tanggal_post` datetime DEFAULT NULL,
  `user_id_author` int UNSIGNED DEFAULT NULL,
  `status_post` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_desc_post` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword_post` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug_post` text COLLATE utf8mb4_unicode_ci,
  `filename_post` text COLLATE utf8mb4_unicode_ci,
  `seq_spotlight_post` int UNSIGNED DEFAULT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_has_label`
--

CREATE TABLE `post_has_label` (
  `post_id` int UNSIGNED NOT NULL,
  `postlabel_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_kategori`
--

CREATE TABLE `post_kategori` (
  `postkategori_id` int UNSIGNED NOT NULL,
  `kode_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_kategori` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_label`
--

CREATE TABLE `post_label` (
  `postlabel_id` int UNSIGNED NOT NULL,
  `postkategori_id` int NOT NULL,
  `kode_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_label` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_label` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_progres`
--

CREATE TABLE `post_progres` (
  `postprogres_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `status_progres` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan_progres` text COLLATE utf8mb4_unicode_ci,
  `catatan_progres` text COLLATE utf8mb4_unicode_ci,
  `user_id_progres` int UNSIGNED NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_slug_redirect`
--

CREATE TABLE `post_slug_redirect` (
  `post_id` int UNSIGNED NOT NULL,
  `old_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_spotlight`
--

CREATE TABLE `post_spotlight` (
  `postspotlight` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `label_id` int UNSIGNED NOT NULL,
  `status_spotlight` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_awal_spotlight` datetime DEFAULT NULL,
  `tanggal_akhir_spotlight` datetime DEFAULT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4KPoq5Zi8VgVHa4w0VgG52u9dy4km15mZMcCVizk', 1, '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV2VvZU1FVFFsVXdlcURVWmg1Tk9OSW1Rdk1NaHhWZWZNdzRNYVA2VCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6OTAwOC9hcHAvcm9sZXMiO3M6NToicm91dGUiO3M6MTU6ImFwcC5yb2xlcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1762851499),
('a4xDE9vrIoab6lchX8LSd5eeJ3nwvTjgzCkLyqN3', 1, '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiOWFmZ3Nna3Zxa29sOE9KYWlFaktYWUVPV0J2c1Y0VVczZ013NnliSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9sb2NhbGhvc3Q6OTAwOC9iaWJsaW8iO3M6NToicm91dGUiO3M6MjE6ImZyb250ZW5kLmJpYmxpby5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1762784883),
('aAb9p0QBY8usx44xriaavY0XzrxEPJqftUsRckcC', 1, '172.19.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaG9pOUtRdTBwUEFpek13VFVQY2xjMGlDdXFTUEMxU29CWlk3NFdhdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xNzIuMTguMTYuMjMwOjkwMDgvYXBwL3VzZXIvdG9rZW4iO3M6NToicm91dGUiO3M6MTM6ImFwcC51c2VyLnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1762848592),
('kC4BMB8Nuxb8KA5OKP8FJDwZsBJibA4B4zcf4bTo', 1, '172.19.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicjBZbzhkdEpVbjFTUERjc0lmUk9QTHJmWFpQbU5aM3VFUHhTS0tkQyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0MDoiaHR0cDovLzE5Mi4xNjguMTM3LjE6OTAwOC9hcHAvdXNlci90b2tlbiI7czo1OiJyb3V0ZSI7czoxMzoiYXBwLnVzZXIuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762831911),
('R9L159KcyPhTtRPfUVJkm6xl6n17soHloXLyEnKa', 1, '172.19.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSHVPOWlrdzhWSmFIUW5XMmduWmxwS1gwSE5ydXdTRVhpZ0phV2hjbCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo0MDoiaHR0cDovLzE5Mi4xNjguMTM3LjE6OTAwOC9hcHAvdXNlci90b2tlbiI7czo1OiJyb3V0ZSI7czoxMzoiYXBwLnVzZXIuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1762784851),
('sFummwlEsO9NAgcHKhh1QSEH4an9Kc73Xz5f3Tlj', 1, '172.19.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaDd1bTlYWmI0SW9PUGgzb3ViYXZtUHFkWHhralM2eTB5N2FyWjI5YiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9sb2NhbGhvc3Q6OTAwOC9hcHAvdXNlci90b2tlbiI7czo1OiJyb3V0ZSI7czoxMzoiYXBwLnVzZXIuc2hvdyI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1762832093);

-- --------------------------------------------------------

--
-- Table structure for table `sys_activity_log`
--

CREATE TABLE `sys_activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sys_activity_log`
--

INSERT INTO `sys_activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 1, NULL, NULL, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 999, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-06 12:46:29', '2025-11-06 12:46:29'),
(2, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 2, NULL, NULL, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-06 12:47:06', '2025-11-06 12:47:06'),
(3, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 2, NULL, NULL, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T12:47:06.371933Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-06 12:47:06', '2025-11-06 12:47:06'),
(4, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 3, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-06 12:52:10', '2025-11-06 12:52:10'),
(5, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 3, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T12:52:10.953450Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-06 12:52:10', '2025-11-06 12:52:10'),
(6, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 3, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T12:52:10.953450Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T12:52:10.953450Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-06T13:02:52.850890Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-06 13:02:52', '2025-11-06 13:02:52'),
(7, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 3, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T12:52:10.953450Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-06T13:02:52.850890Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-06 13:08:29', '2025-11-06 13:08:29'),
(8, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-06 13:55:02', '2025-11-06 13:55:02'),
(9, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 4, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-06T13:55:02.999207Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-06 13:55:03', '2025-11-06 13:55:03'),
(10, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 4, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-06T13:55:02.999207Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-06T13:55:02.999207Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T14:02:52.738367Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-06 14:02:52', '2025-11-06 14:02:52'),
(11, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 4, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-06T13:55:02.999207Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T14:02:52.738367Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T14:02:52.738367Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-06 14:02:59', '2025-11-06 14:02:59'),
(12, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 4, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-06T14:02:52.738367Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-06 14:04:23', '2025-11-06 14:04:23'),
(13, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 5, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 02:45:15', '2025-11-07 02:45:15'),
(14, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 5, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T02:45:26.975891Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-07 02:45:26', '2025-11-07 02:45:26'),
(15, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 5, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T02:45:26.975891Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 02:48:14', '2025-11-07 02:48:14'),
(16, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 6, NULL, NULL, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 03:09:40', '2025-11-07 03:09:40'),
(17, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 6, NULL, NULL, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"Test Book\", \"author\": \"Test Author\", \"item_id\": 2, \"added_at\": \"2025-11-07T03:09:40.253482Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-07 03:09:40', '2025-11-07 03:09:40'),
(18, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 6, NULL, NULL, '{\"old\": {\"list_item\": [{\"title\": \"Test Book\", \"author\": \"Test Author\", \"item_id\": 2, \"added_at\": \"2025-11-07T03:09:40.253482Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}, \"attributes\": {\"list_item\": [{\"title\": \"Book 1\", \"author\": \"Author 1\", \"item_id\": 2, \"added_at\": \"2025-11-07T03:09:40.295259Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"Book 2\", \"author\": \"Author 2\", \"item_id\": 3, \"added_at\": \"2025-11-07T03:09:40.295321Z\", \"biblio_id\": 2, \"item_code\": \"P00003S4555\"}]}}', NULL, '2025-11-07 03:09:40', '2025-11-07 03:09:40'),
(19, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 6, NULL, NULL, '{\"old\": [], \"attributes\": []}', NULL, '2025-11-07 03:09:40', '2025-11-07 03:09:40'),
(20, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 6, NULL, NULL, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"Book 1\", \"author\": \"Author 1\", \"item_id\": 2, \"added_at\": \"2025-11-07T03:09:40.295259Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"Book 2\", \"author\": \"Author 2\", \"item_id\": 3, \"added_at\": \"2025-11-07T03:09:40.295321Z\", \"biblio_id\": 2, \"item_code\": \"P00003S4555\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 03:09:40', '2025-11-07 03:09:40'),
(21, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 7, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 04:18:38', '2025-11-07 04:18:38'),
(22, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 7, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T04:18:38.612543Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-07 04:18:38', '2025-11-07 04:18:38'),
(23, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 7, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T04:18:38.612543Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 04:18:42', '2025-11-07 04:18:42'),
(24, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 8, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 07:44:50', '2025-11-07 07:44:50'),
(25, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 8, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T07:44:50.914866Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-07 07:44:50', '2025-11-07 07:44:50'),
(26, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 8, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T07:44:50.914866Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T07:44:50.914866Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T08:09:21.039726Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-07 08:09:21', '2025-11-07 08:09:21'),
(27, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 8, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T07:44:50.914866Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T08:09:21.039726Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 08:16:43', '2025-11-07 08:16:43'),
(28, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 9, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 08:22:50', '2025-11-07 08:22:50'),
(29, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 9, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T08:22:50.931065Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-07 08:22:50', '2025-11-07 08:22:50'),
(30, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 9, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T08:22:50.931065Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T08:22:50.931065Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T08:23:19.174123Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-07 08:23:19', '2025-11-07 08:23:19'),
(31, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 9, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-07T08:22:50.931065Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}, {\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-07T08:23:19.174123Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-07 08:23:35', '2025-11-07 08:23:35'),
(32, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 10, NULL, NULL, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-10 06:44:17', '2025-11-10 06:44:17'),
(33, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 10, NULL, NULL, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-10T06:44:17.247376Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-10 06:44:17', '2025-11-10 06:44:17'),
(34, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 10, NULL, NULL, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-10T06:44:17.247376Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-10 06:52:52', '2025-11-10 06:52:52'),
(35, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 11, NULL, NULL, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-10 06:55:19', '2025-11-10 06:55:19'),
(36, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 11, NULL, NULL, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-10T06:55:19.054748Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-10 06:55:19', '2025-11-10 06:55:19'),
(37, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 11, NULL, NULL, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-10T06:55:19.054748Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-10 06:55:21', '2025-11-10 06:55:21'),
(38, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 12, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-10 14:16:02', '2025-11-10 14:16:02'),
(39, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 12, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-10T14:16:02.057269Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-10 14:16:02', '2025-11-10 14:16:02'),
(40, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 12, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-10T14:16:02.057269Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}, \"attributes\": {\"list_item\": []}}', NULL, '2025-11-10 14:20:40', '2025-11-10 14:20:40'),
(41, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 12, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-10T14:20:45.609829Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-10 14:20:45', '2025-11-10 14:20:45'),
(42, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 12, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-10T14:20:45.609829Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-10 14:23:07', '2025-11-10 14:23:07'),
(43, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 13, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-10 14:27:45', '2025-11-10 14:27:45'),
(44, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 13, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-10T14:27:45.991396Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-10 14:27:45', '2025-11-10 14:27:45'),
(45, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 13, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-10T14:27:45.991396Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-10 14:27:59', '2025-11-10 14:27:59'),
(46, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 14, NULL, NULL, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-11 03:32:31', '2025-11-11 03:32:31'),
(47, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 14, NULL, NULL, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-11T03:32:31.234530Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}]}}', NULL, '2025-11-11 03:32:31', '2025-11-11 03:32:31'),
(48, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 14, NULL, NULL, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 2, \"added_at\": \"2025-11-11T03:32:31.234530Z\", \"biblio_id\": 1, \"item_code\": \"P00002S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-11 03:32:39', '2025-11-11 03:32:39'),
(49, 'Laravel', ' menambahkan data  table :subject.cart_loan', 'App\\Models\\CartLoan', 'created', 15, 'App\\Models\\User', 1, '{\"attributes\": {\"tanggal\": null, \"list_item\": [], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-11 08:10:19', '2025-11-11 08:10:19'),
(50, 'Laravel', ' merubah data table :subject.cart_loan', 'App\\Models\\CartLoan', 'updated', 15, 'App\\Models\\User', 1, '{\"old\": {\"list_item\": []}, \"attributes\": {\"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-11T08:10:19.801000Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}]}}', NULL, '2025-11-11 08:10:19', '2025-11-11 08:10:19'),
(51, 'Laravel', ' menghapus data table :subject.cart_loan', 'App\\Models\\CartLoan', 'deleted', 15, 'App\\Models\\User', 1, '{\"old\": {\"tanggal\": null, \"list_item\": [{\"title\": \"etst\", \"author\": \"testing\", \"item_id\": 1, \"added_at\": \"2025-11-11T08:10:19.801000Z\", \"biblio_id\": 1, \"item_code\": \"P00001S4554\"}], \"member_id\": 1, \"created_by\": null, \"deleted_by\": null, \"updated_by\": null}}', NULL, '2025-11-11 08:12:49', '2025-11-11 08:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `sys_model_has_permissions`
--

CREATE TABLE `sys_model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sys_model_has_roles`
--

CREATE TABLE `sys_model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sys_model_has_roles`
--

INSERT INTO `sys_model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sys_permissions`
--

CREATE TABLE `sys_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sys_permissions`
--

INSERT INTO `sys_permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard-view', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(2, 'user-list', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(3, 'user-create', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(4, 'user-edit', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(5, 'user-delete', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(6, 'role-list', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(7, 'role-create', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(8, 'role-edit', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(9, 'role-delete', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(10, 'biblio-list', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(11, 'biblio-create', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(12, 'biblio-edit', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(13, 'biblio-delete', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(14, 'biblio-export', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `sys_roles`
--

CREATE TABLE `sys_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sys_roles`
--

INSERT INTO `sys_roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'administrator', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(2, 'admin', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29'),
(3, 'user', 'web', '2025-10-20 08:18:29', '2025-10-20 08:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `sys_role_has_permissions`
--

CREATE TABLE `sys_role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sys_role_has_permissions`
--

INSERT INTO `sys_role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(10, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(1, 3),
(10, 3),
(14, 3);

-- --------------------------------------------------------

--
-- Table structure for table `testi`
--

CREATE TABLE `testi` (
  `testi_id` int UNSIGNED NOT NULL,
  `prodi_id` int UNSIGNED NOT NULL,
  `angkatan` int UNSIGNED NOT NULL,
  `nama_alumni` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posisi_alumni` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_kerja_alumni` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isi_testi` text COLLATE utf8mb4_unicode_ci,
  `media_id_alumni` int UNSIGNED DEFAULT NULL,
  `status_testi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testi_has_kategori`
--

CREATE TABLE `testi_has_kategori` (
  `testi_id` int UNSIGNED NOT NULL,
  `testikategori_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testi_kategori`
--

CREATE TABLE `testi_kategori` (
  `testikategori_id` int UNSIGNED NOT NULL,
  `kode_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_kategori` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_induk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nomor_induk`, `email`, `email_verified_at`, `password`, `google_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'VARRENT EDBERT', '2257301132', 'varrent22si@mahasiswa.pcr.ac.id', NULL, '$2y$12$fopravVPIkUQm3eWLr00qugs3kLzCL9jWr7kvj/gGX.zF/7g.Phta', '114216742022767999141', 'FXUtexhpy1j6czgrXpfhDAbGnYPCbdonEQVXdNqmqO6nOIdD1zIxrcB8bW6D', '2025-10-20 08:18:29', '2025-10-20 08:19:18'),
(2, 'Varrent en', '2257301133', 'varrentedbert@gmail.com', NULL, '21232f297a57a5a743894a0e4a801fc3', '107504163367671293047', 'zAdhopONZZBfKEJTdukIHdP1sPi4DhFyParfgGTeChtpk2d6q7XSNE58mL1E', NULL, '2025-11-03 08:42:27'),
(3, 'Admin', '123456789', 'admin@test.com', NULL, '$2y$12$aut/YjwnC5wDVa1Yn2PYPud6.Vkv9frU7Rcl50WDpZkdpInZ1k.Xi', NULL, NULL, '2025-11-10 07:59:58', '2025-11-10 07:59:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`agenda_id`);

--
-- Indexes for table `agenda_has_kategori`
--
ALTER TABLE `agenda_has_kategori`
  ADD UNIQUE KEY `agenda_has_kategori_unique` (`agenda_id`,`agendakategori_id`);

--
-- Indexes for table `agenda_kategori`
--
ALTER TABLE `agenda_kategori`
  ADD PRIMARY KEY (`agendakategori_id`);

--
-- Indexes for table `akreditasi`
--
ALTER TABLE `akreditasi`
  ADD PRIMARY KEY (`akreditasi_id`);

--
-- Indexes for table `biblio`
--
ALTER TABLE `biblio`
  ADD PRIMARY KEY (`biblio_id`);

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
-- Indexes for table `cart_loan`
--
ALTER TABLE `cart_loan`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `dm_infografis`
--
ALTER TABLE `dm_infografis`
  ADD PRIMARY KEY (`infografis_id`);

--
-- Indexes for table `dm_jurusan`
--
ALTER TABLE `dm_jurusan`
  ADD PRIMARY KEY (`jurusan_id`);

--
-- Indexes for table `dm_kurikulum`
--
ALTER TABLE `dm_kurikulum`
  ADD PRIMARY KEY (`alias_prodi`,`tahun_kurikulum`);

--
-- Indexes for table `dm_pegawai`
--
ALTER TABLE `dm_pegawai`
  ADD PRIMARY KEY (`nip_pegawai`);

--
-- Indexes for table `dm_prodi`
--
ALTER TABLE `dm_prodi`
  ADD PRIMARY KEY (`prodi_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `event_has_kategori`
--
ALTER TABLE `event_has_kategori`
  ADD UNIQUE KEY `event_has_kategori_unique` (`event_id`,`eventkategori_id`);

--
-- Indexes for table `event_kategori`
--
ALTER TABLE `event_kategori`
  ADD PRIMARY KEY (`eventkategori_id`);

--
-- Indexes for table `event_progres`
--
ALTER TABLE `event_progres`
  ADD PRIMARY KEY (`eventprogres_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `faq_group`
--
ALTER TABLE `faq_group`
  ADD PRIMARY KEY (`faqgroup_id`);

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
-- Indexes for table `konten`
--
ALTER TABLE `konten`
  ADD PRIMARY KEY (`konten_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mst_kontak`
--
ALTER TABLE `mst_kontak`
  ADD PRIMARY KEY (`kontak_id`);

--
-- Indexes for table `mst_partner`
--
ALTER TABLE `mst_partner`
  ADD PRIMARY KEY (`partner_id`);

--
-- Indexes for table `mst_social_media`
--
ALTER TABLE `mst_social_media`
  ADD PRIMARY KEY (`socialmedia_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `post_has_label`
--
ALTER TABLE `post_has_label`
  ADD UNIQUE KEY `post_has_label_unique` (`post_id`,`postlabel_id`);

--
-- Indexes for table `post_kategori`
--
ALTER TABLE `post_kategori`
  ADD PRIMARY KEY (`postkategori_id`);

--
-- Indexes for table `post_label`
--
ALTER TABLE `post_label`
  ADD PRIMARY KEY (`postlabel_id`);

--
-- Indexes for table `post_progres`
--
ALTER TABLE `post_progres`
  ADD PRIMARY KEY (`postprogres_id`);

--
-- Indexes for table `post_slug_redirect`
--
ALTER TABLE `post_slug_redirect`
  ADD UNIQUE KEY `post_slug_redirect_unique` (`post_id`,`old_slug`);

--
-- Indexes for table `post_spotlight`
--
ALTER TABLE `post_spotlight`
  ADD PRIMARY KEY (`postspotlight`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sys_activity_log`
--
ALTER TABLE `sys_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `sys_activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `sys_model_has_permissions`
--
ALTER TABLE `sys_model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `sys_model_has_roles`
--
ALTER TABLE `sys_model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `sys_permissions`
--
ALTER TABLE `sys_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sys_permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `sys_roles`
--
ALTER TABLE `sys_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sys_roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `sys_role_has_permissions`
--
ALTER TABLE `sys_role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `sys_role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `testi`
--
ALTER TABLE `testi`
  ADD PRIMARY KEY (`testi_id`);

--
-- Indexes for table `testi_has_kategori`
--
ALTER TABLE `testi_has_kategori`
  ADD PRIMARY KEY (`testi_id`,`testikategori_id`);

--
-- Indexes for table `testi_kategori`
--
ALTER TABLE `testi_kategori`
  ADD PRIMARY KEY (`testikategori_id`);

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
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `agenda_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agenda_kategori`
--
ALTER TABLE `agenda_kategori`
  MODIFY `agendakategori_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `akreditasi`
--
ALTER TABLE `akreditasi`
  MODIFY `akreditasi_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `biblio`
--
ALTER TABLE `biblio`
  MODIFY `biblio_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_loan`
--
ALTER TABLE `cart_loan`
  MODIFY `loan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `dm_infografis`
--
ALTER TABLE `dm_infografis`
  MODIFY `infografis_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_jurusan`
--
ALTER TABLE `dm_jurusan`
  MODIFY `jurusan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dm_prodi`
--
ALTER TABLE `dm_prodi`
  MODIFY `prodi_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_kategori`
--
ALTER TABLE `event_kategori`
  MODIFY `eventkategori_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_progres`
--
ALTER TABLE `event_progres`
  MODIFY `eventprogres_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `faq_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_group`
--
ALTER TABLE `faq_group`
  MODIFY `faqgroup_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `konten`
--
ALTER TABLE `konten`
  MODIFY `konten_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `mst_kontak`
--
ALTER TABLE `mst_kontak`
  MODIFY `kontak_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_partner`
--
ALTER TABLE `mst_partner`
  MODIFY `partner_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mst_social_media`
--
ALTER TABLE `mst_social_media`
  MODIFY `socialmedia_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_kategori`
--
ALTER TABLE `post_kategori`
  MODIFY `postkategori_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_label`
--
ALTER TABLE `post_label`
  MODIFY `postlabel_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_progres`
--
ALTER TABLE `post_progres`
  MODIFY `postprogres_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_spotlight`
--
ALTER TABLE `post_spotlight`
  MODIFY `postspotlight` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sys_activity_log`
--
ALTER TABLE `sys_activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `sys_permissions`
--
ALTER TABLE `sys_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sys_roles`
--
ALTER TABLE `sys_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `testi`
--
ALTER TABLE `testi`
  MODIFY `testi_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testi_kategori`
--
ALTER TABLE `testi_kategori`
  MODIFY `testikategori_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `post_slug_redirect`
--
ALTER TABLE `post_slug_redirect`
  ADD CONSTRAINT `post_slug_redirect_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `sys_model_has_permissions`
--
ALTER TABLE `sys_model_has_permissions`
  ADD CONSTRAINT `sys_model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `sys_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sys_model_has_roles`
--
ALTER TABLE `sys_model_has_roles`
  ADD CONSTRAINT `sys_model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sys_role_has_permissions`
--
ALTER TABLE `sys_role_has_permissions`
  ADD CONSTRAINT `sys_role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `sys_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sys_role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `sys_roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
