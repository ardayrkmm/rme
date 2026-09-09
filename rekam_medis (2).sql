-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Generation Time: Sep 08, 2026 at 05:52 AM
-- Server version: 8.0.46
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rekam_medis`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subject_type` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subject_id` bigint DEFAULT NULL,
  `causer_type` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `causer_id` bigint DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `visit_number` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `patient_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physiotherapist_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_master_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appointment_date` datetime(3) DEFAULT NULL,
  `appointment_time` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `complaint` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `visit_number`, `patient_id`, `physiotherapist_id`, `service_master_id`, `appointment_date`, `appointment_time`, `complaint`, `status`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
('112e5bd0-fdc6-4bd0-a142-ba69d39ef0c4', 'VIS-20260815141405', '31cc0dc8-a836-4e07-8e95-fce656c590d0', 'cb95071c-66de-4a05-9ba7-7946201104a5', '6c197fd5-8076-4164-882e-594fcd91fc33', '2026-08-15 00:00:00.000', '09:00', 'Tumbuh kembang', 'completed', '', '2026-08-15 14:14:05.289', '2026-08-15 14:25:39.083', NULL),
('1c74aa4b-dee5-4c7f-8911-e0446ea7bc67', 'VIS-20260815142340', '624cedb7-2fe4-48dc-acc4-ed1e0b6db39b', 'cb95071c-66de-4a05-9ba7-7946201104a5', 'f6917731-067d-4df1-96cc-444f9c769909', '2026-08-15 00:00:00.000', '13:00', 'SIJ', 'cancelled', '', '2026-08-15 14:23:40.021', '2026-08-15 14:23:48.602', NULL),
('406de6e4-0a92-4097-abf1-c29c198decf3', 'VIS-20260815141518', '519f4084-c650-4ac4-81d0-cea245c2fead', 'cb95071c-66de-4a05-9ba7-7946201104a5', '6c197fd5-8076-4164-882e-594fcd91fc33', '2026-08-15 00:00:00.000', '14:00', 'Tumbuh kembang', 'completed', '', '2026-08-15 14:15:18.345', '2026-08-15 14:27:25.835', NULL),
('6919dd0f-e8d9-49dc-9467-4143e97c94fc', 'VIS-20260815141339', '7e2b6a4e-3a57-483e-b393-7ac103fce18d', 'cb95071c-66de-4a05-9ba7-7946201104a5', '6c197fd5-8076-4164-882e-594fcd91fc33', '2026-08-15 00:00:00.000', '08:00', 'Postural', 'completed', '', '2026-08-15 14:13:39.417', '2026-08-15 14:17:48.871', NULL),
('8bc32aa8-7d82-427a-be27-baa8ed5de085', 'VIS-20260815141459', '8db78ac2-84c3-4ea1-8de5-3e853045d7e5', '8d9a532f-e42a-4776-88c1-30b9c6289af7', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '2026-08-15 00:00:00.000', '11:00', 'Nyeri pinggang', 'completed', '', '2026-08-15 14:14:59.997', '2026-08-15 14:26:41.323', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `exercise_programs`
--

CREATE TABLE `exercise_programs` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `therapy_session_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exercise_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `repetitions` bigint DEFAULT '0',
  `sets` bigint DEFAULT '0',
  `frequency` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `name`, `created_at`, `updated_at`) VALUES
('9d4475e5-93a4-11f1-88fb-5254005bb1a8', 'Laki-laki', '2026-08-09 11:44:24.000', '2026-08-09 11:44:24.000'),
('9d447944-93a4-11f1-88fb-5254005bb1a8', 'Perempuan', '2026-08-09 11:44:24.000', '2026-08-09 11:44:24.000');

-- --------------------------------------------------------

--
-- Table structure for table `jwt_blocklists`
--

CREATE TABLE `jwt_blocklists` (
  `token` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jwt_blocklists`
--

INSERT INTO `jwt_blocklists` (`token`, `expires_at`) VALUES
('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3ODc4Nzg0NzEsImlhdCI6MTc4NzI3MzY3MSwicm9sZSI6ImZpc2lvdGVyYXBpcyIsInN1YiI6IjliYmUwMzI0LWMyZTUtNDFmOS1hYmUzLWZjYWEyMzhjM2NjZiJ9.zwyzHcZ2RN8ijo6EVLRnnqrrjNBtzV8ZchWeNVqvDnU', '2026-08-28 00:54:31.000'),
('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3ODcwNjc5MjEsImlhdCI6MTc4NjQ2MzEyMSwicm9sZSI6ImFkbWluIiwic3ViIjoiOGNhZDk2YjQtOTQ3NS0xMWYxLTg4ZmItNTI1NDAwNWJiMWE4In0.fwS90Gwnw8f2a1vvMsXyxrz3DvD4LT5DgkUanSgUljQ', '2026-08-18 23:45:21.000'),
('eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3ODY5MzUzOTYsImlhdCI6MTc4NjMzMDU5Niwicm9sZSI6ImFkbWluIiwic3ViIjoiOWVkZDA5MjAtZGIyZS00ZWFjLWFhYmQtY2RkMDQ3OTliZTVlIn0.2Y8N23x0V4BEkEYqEBEOK_d96j5ZylwFtpxC3CyNvrg', '2026-08-17 10:56:36.000');

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `visit_number` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `patient_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physiotherapist_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appointment_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `examination_date` datetime(3) DEFAULT NULL,
  `anamnesis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `diagnosis` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `therapy` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `visit_number`, `patient_id`, `service_id`, `physiotherapist_id`, `appointment_id`, `examination_date`, `anamnesis`, `diagnosis`, `therapy`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0144c70e-803c-474b-adee-2bedaaecf352', 'VIS-20260813154115', '6176d9bc-5935-4bf5-bd9f-525ce81f8ed5', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-31 00:00:00.000', 'sakit bakian bokong kiri sampai betis, lutut kiri sakit', 'OA knee bilateral', 'IR, tens, massage', '', '2026-08-13 15:41:15.293', '2026-08-13 15:41:15.293', NULL),
('0229b1d6-e296-450b-af93-9fb7eaeb5a5a', 'VIS-20260813134826', '22356324-ae52-4b1b-a243-a066e6f39509', NULL, 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-06-04 00:00:00.000', 'sakit di pinggang dan panggul', 'sacroilliac joint disfunction', 'IR, tens, latihan', '', '2026-08-13 13:48:26.965', '2026-08-13 13:48:26.965', NULL),
('025bcfc7-b053-427e-8f7e-5969aff1c05e', 'VIS-20260815140526', '8db78ac2-84c3-4ea1-8de5-3e853045d7e5', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-05 00:00:00.000', 'nyeri kedua tungkai', 'HNP', 'IR, tens, latihan, massage', '', '2026-08-15 14:05:26.686', '2026-08-15 14:05:26.686', NULL),
('02605e33-9859-45e4-a115-69838fec5a06', 'VIS-20260817165057', '31cc0dc8-a836-4e07-8e95-fce656c590d0', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-15 00:00:00.000', 'sudah mampu berjalan mandiri', 'GDD', 'IR, tens, latihan, massage', '', '2026-08-17 16:50:57.598', '2026-08-17 16:50:57.598', NULL),
('02f77a47-ef46-4500-bbda-41bacafad78a', 'VIS-20260813154834', 'b4a69560-f33e-4562-9c5f-7b789567b707', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-20 00:00:00.000', 'bahu kiri terasa sakit kadang disertai rasa pegal sampai siku, tumit kanan terasa nyeri', 'shoulder impigment', 'IR, tens, latihan, massage', '', '2026-08-13 15:48:34.839', '2026-08-13 15:48:34.839', NULL),
('03e045b7-06fb-4f31-ab4a-fe4ff6a6f6b5', 'VIS-20260819032814', 'f0ca4dfa-9824-4794-a1a9-036048b186bf', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-07 00:00:00.000', 'odema dx, nyeri punggung menjalar ke kaki kiri', 'OA genu dx sn, L4-S1 LBP', 'IR, tens, tapping, latihan', '', '2026-08-19 03:28:14.004', '2026-08-19 03:28:14.004', NULL),
('0536f223-7633-4779-ba71-55fbff1f7353', 'VIS-20260817162423', '2bf613e1-3b79-464e-b22e-ebbffcfe0432', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-22 00:00:00.000', 'kurva skolio mendekati sempurna, thight rhomboid masih sedikit, kosong area dalam lutut', 'skoliosis tipe S', 'IR, tens, latihan, massage', '', '2026-08-17 16:24:23.318', '2026-08-17 16:24:23.318', NULL),
('07283f1c-41d7-42fb-95f9-89d60bdee6c3', 'VIS-20260819025137', '6d8ae23c-3956-473f-9e0a-17fc9ab609f6', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-01-16 00:00:00.000', 'frozen shoulder dextra, kencang', 'OA kneedextra sinistra, frozen shoulder', 'IR, tens, latihan', '', '2026-08-19 02:51:37.249', '2026-08-19 02:51:37.249', NULL),
('082f53cb-c8f0-4f7f-9862-62e8723ff103', 'VIS-20260815144658', 'b479e9ae-fe99-47bc-bca3-67fd1f200740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-16 00:00:00.000', 'kebas tangan kanan, spasme lengan atas dan lengan bawah, ujung jari jempol', 'post op', 'ir,tens,tl', '', '2026-08-15 14:46:58.383', '2026-08-15 14:46:58.383', NULL),
('09843393-e306-4bc3-87bb-49a30556dab5', 'VIS-20260817165223', '512dbc6b-039a-4093-a0f5-889829b81ea2', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-10 00:00:00.000', 'CP spsatik', 'CP', 'tens, us, manual', '', '2026-08-17 16:52:23.379', '2026-08-17 16:52:23.379', NULL),
('098b48d2-b516-4382-8cf0-4ce9f42cf539', 'VIS-20260821014612', 'db0c34df-2681-4d0b-867f-41b60c7d3912', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-19 00:00:00.000', 'kiri kebas sakir', 'post stroke', 'IR, tens, latihan', '', '2026-08-21 01:46:12.986', '2026-08-21 01:46:12.986', NULL),
('098bde3d-1c23-4399-8e2a-fe78c56af9d0', 'VIS-20260815150903', '01086372-ce8e-45b5-beca-49d11d41112f', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-04 00:00:00.000', 'mmt 4, kekuatan pada alis sudah meningkat, untuk kumur sudah meningkat', 'Bell\'s palsy', 'IR, tens, latihan, massage', '', '2026-08-15 15:09:03.362', '2026-08-15 15:09:03.362', NULL),
('0e7759eb-38da-46ac-954f-950cf2401964', 'VIS-20260819031257', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-17 00:00:00.000', 'nyeri leher menjalar', 'LBP, cervical stenosis', 'IR, tens, latihan, massage', '', '2026-08-19 03:12:57.942', '2026-08-19 03:12:57.942', NULL),
('0fe8acd9-1948-4dfd-b696-c7f8239eecd3', 'VIS-20260815151809', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-11-20 00:00:00.000', 'Spasme erectorspine, siatic, gastroc nemeus', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-15 15:18:09.245', '2026-08-15 15:18:09.245', NULL),
('118239df-e70a-494d-8033-e4de1e2cf7e7', 'VIS-20260815155919', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-22 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:59:19.627', '2026-08-15 15:59:19.627', NULL),
('12b172f9-a33f-403f-b4c7-0c3736f251ca', 'VIS-20260817160552', '230afbde-f27d-472a-8033-3b420cff7fa6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-01 00:00:00.000', 'Spondylosis lumbalis, spasme paravertebra hingga tungkai kanan, nyeri menjalar', 'spondylosis lumbalis', 'IR, tens, massage, streching', '', '2026-08-17 16:05:52.135', '2026-08-17 16:05:52.135', NULL),
('13043c4b-9871-4fff-b045-2a7596c2c17d', 'VIS-20260815155203', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-26 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:52:03.616', '2026-08-15 15:52:03.616', NULL),
('14e512ab-4209-485d-88b6-854887627ef5', 'VIS-20260817161425', 'ddae9cb1-6529-4632-8fb1-3f34cf913fbe', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-21 00:00:00.000', 'belum mampu duduk tegak, jongkok, posisi merangkak, duduk mandiri terlentang dan berdiri', 'GDD', 'tens, IR, massage, latihan', '', '2026-08-17 16:14:25.356', '2026-08-17 16:14:25.356', NULL),
('189f87e1-387d-4fd1-9abc-46438fb10318', 'VIS-20260815155643', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-07 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:56:43.921', '2026-08-15 15:56:43.921', NULL),
('19259735-f52a-48f5-95e4-8a9f0992da92', 'VIS-20260813135501', '4ff67d8c-f6f9-4818-b2d0-686efa3965db', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-18 00:00:00.000', 'spasme flexor elbow, LGS aktif: elbow: s.5*-0*-115*. pasif: s.5*-0*-130*', 'Post Operasi', 'IR, tens, latihan', '', '2026-08-13 13:55:01.106', '2026-08-13 13:55:01.106', NULL),
('19663d09-210a-4550-b215-ff40d2b120b5', 'VIS-20260815151113', '01086372-ce8e-45b5-beca-49d11d41112f', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-11 00:00:00.000', 'mmt 4, spasme -, ligo fish scale', 'Bell\'s palsy', 'IR, tens, latihan, massage, mirror', '', '2026-08-15 15:11:13.835', '2026-08-15 15:11:13.835', NULL),
('1987491a-5b3a-4034-84ea-304fd694d877', 'VIS-20260815140339', '8db78ac2-84c3-4ea1-8de5-3e853045d7e5', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-18 00:00:00.000', 'nyeri seluruh badan, spasme', 'HNP', 'IR, tens, latihan, massage', '', '2026-08-15 14:03:39.176', '2026-08-15 14:03:39.176', NULL),
('1b8465bb-5741-4967-90f8-2341a393ada1', 'VIS-20260815155245', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', NULL, 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-02 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:52:45.836', '2026-08-15 15:52:45.836', NULL),
('1bd10ea7-f779-4955-a592-86a459536abc', 'VIS-20260817162251', '2bf613e1-3b79-464e-b22e-ebbffcfe0432', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-17 00:00:00.000', 'spasme, kurva skolio sudah mobile', 'skolliosis tipe S', 'IR, tens, latihan, massage', '', '2026-08-17 16:22:51.635', '2026-08-17 16:22:51.635', NULL),
('1c843952-8f5b-4e33-bf7b-0fc0ab837496', 'VIS-20260819033712', 'fad1eeb9-2d5f-488a-9e21-a1185371645a', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-01 00:00:00.000', 'hemiparase, nyeri punggung bagian bawah menjalar', 'LBP, CRS', 'IR, tens, latihan', '', '2026-08-19 03:37:12.840', '2026-08-19 03:37:12.840', NULL),
('1dcc7124-c08c-4741-8b88-32e74ee4c9a8', 'VIS-20260813150146', '50e9848c-3a3c-4598-8220-4ba0969ddcfc', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-19 00:00:00.000', 'nyeri area jempol, bunyik klik, unstabil jempol', 'trigger finger, dislokasi', 'reposisi, IR, tens, latihan, tapping', '', '2026-08-13 15:01:46.016', '2026-08-13 15:01:46.016', NULL),
('1f8d51db-3fd4-4aa7-b271-95bce2d2afa8', 'VIS-20260817163057', '663f1026-4506-42d0-aa85-b28123c2bfef', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-08-13 00:00:00.000', 'keterbatasan rom knee, hip', 'post op THR', 'IR, tens, massage, streaching', '', '2026-08-17 16:30:57.918', '2026-08-17 16:30:57.918', NULL),
('20d0cd4c-b934-4142-853f-fde861bc17ed', 'VIS-20260813152140', '9b1ad0d6-d57d-45b8-81ec-ee00ff3fa1cc', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-02-16 00:00:00.000', 'nyeri pinggul menjalar hingga ke kaki, spasme gastroc', 'gluteus LBP', 'IR, tens, latihan', '', '2026-08-13 15:21:40.396', '2026-08-13 15:21:40.396', NULL),
('2353a1a1-6b43-4ec4-8f50-1bd0ccd56b31', 'VIS-20260819031429', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-07 00:00:00.000', 'nyeri leher menjalar', 'cervical stenosis', 'IR, tens, latihan, massage', '', '2026-08-19 03:14:29.561', '2026-08-19 03:14:29.561', NULL),
('236eae61-c73f-4e7e-98c9-e95b395c4c80', 'VIS-20260815155017', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-20 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:50:17.360', '2026-08-15 15:50:17.360', NULL),
('25c1fbf9-ddf8-4725-bc9d-e5629bfe4a49', 'VIS-20260813155059', 'd99329c1-03ca-4da5-848e-c4a225f95534', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-20 00:00:00.000', 'riwayat penyakit miningitis, tb, epilepsi, belum bisa berdiri dan berjalan', 'GDD', 'latihan', '', '2026-08-13 15:50:59.935', '2026-08-13 15:50:59.935', NULL),
('25e202ae-1268-426b-87f8-ebef446b5f19', 'VIS-20260813155753', '519f4084-c650-4ac4-81d0-cea245c2fead', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-01 00:00:00.000', 'belum bisa duduk tegak, belum mampu berdiri mandiri', 'skoliosis type c, cp spastik', 'tens. IR, latihan', '', '2026-08-13 15:57:53.954', '2026-08-13 15:57:53.954', NULL),
('2696529b-6d48-4e80-a3bb-03e9b1fc3678', 'VIS-20260813160355', '16ee2292-9842-4aa4-a1a3-ca986d95a649', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-12 00:00:00.000', 'nyeri dan sakit pada kedua kaki dan tangan kanan, riwayat jatuh kecelakaan 2 hari yang lalu, pemeriksaan (dislokasi angkle kanan dan kiri, dislokasi hip sinistra, dislokasi tangan dextra)', 'dislokasi angkle,hip, tangan', 'reposisi, kompres es, tens, tapping', '', '2026-08-13 16:03:55.991', '2026-08-13 16:03:55.991', NULL),
('26e49696-6310-4b1b-8e36-102d257194b5', 'VIS-20260815154437', '0355c51b-195a-4cf3-9521-18441dfc4b34', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-13 00:00:00.000', 'nyeri lutut kanan kiri\nodema +\ntidak mampu berjalan tanpa alat bantu', 'oa knee bilateral', 'ir,tens,tl', '', '2026-08-15 15:44:37.979', '2026-08-15 15:44:37.979', NULL),
('277d976b-39f5-4d38-832b-6c9104e54a91', 'VIS-20260817165017', '31cc0dc8-a836-4e07-8e95-fce656c590d0', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-19 00:00:00.000', 'sudah mampu duduk ke berdiri, sudah mampu jalan beberapa langkah tanpa pegangan', 'GDD', 'tens, latihan', '', '2026-08-17 16:50:17.238', '2026-08-17 16:50:17.238', NULL),
('27bd5f7e-d429-47ab-b1fc-5e6202cd1281', 'VIS-20260815152649', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-01-23 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'spondylosis HNP', 'IR, tens, latihan', '', '2026-08-15 15:26:49.360', '2026-08-15 15:26:49.360', NULL),
('297985e5-a60f-49de-90e8-5fbcfc7c2aca', 'VIS-20260821015214', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-22 00:00:00.000', 'nyeri lutut kanan, nyeri punggung atas', 'OA dx ,kifosis', 'IR, tens, latihan, massage', '', '2026-08-21 01:52:14.682', '2026-08-21 01:52:14.682', NULL),
('29c7a3e4-c361-4314-938e-c6e598250f11', 'VIS-20260821014517', 'd963396d-dcfa-4456-b61b-419e2762491b', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-19 00:00:00.000', 'menjalar area betis hingga jari L1-S1, spasme area erector spine, quadric, gastroc nemeus, lumbal lordosis, C3 sublukasi menjalar ke lengan', 'dexta HNP lumbal', 'IR, tens, massage, streching', '', '2026-08-21 01:45:17.546', '2026-08-21 01:45:17.546', NULL),
('2a9da133-ab19-473f-8fd7-938ec41db3b6', 'VIS-20260813140141', '9998c170-e052-41b0-b71e-b99e84f944a6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-25 00:00:00.000', 'Nyeri di bagian kedua pantat, saat duduk lama, dan saat berjalan', 'Sciatica, winging scapula', 'tens, IR, latihan, massage', '', '2026-08-13 14:01:41.766', '2026-08-13 14:01:41.766', NULL),
('2baf1777-1428-485d-bd2c-9f6791d7caad', 'VIS-20260817163442', '81a3dda4-ae33-4515-a004-93729361b235', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-05-02 00:00:00.000', 'fase 2', 'post op acl meniscus', 'tens, latihan', '', '2026-08-17 16:34:42.227', '2026-08-17 16:34:42.227', NULL),
('2cbe0077-031e-4a64-9b44-a8bdfbba26b9', 'VIS-20260815144210', '15d902ee-0133-4af9-a67b-f08992ba2bae', 'f6917731-067d-4df1-96cc-444f9c769909', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-19 00:00:00.000', 'sudah mampu duduk seimbang tanpa sandaran, belum mampu mempertahankan kaki kiri saat duduk, belum mampu menggerakkan ekstremitas atas', 'Hemiparase', 'IR, tens, latihan', '', '2026-08-15 14:42:10.776', '2026-08-15 14:42:10.776', NULL),
('2dae3140-4732-4e0c-997f-5ac83c2812e6', 'VIS-20260821015246', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-31 00:00:00.000', 'nyeri lutut kanan', 'OA dx', 'IR, tens, latihan, massage', '', '2026-08-21 01:52:46.475', '2026-08-21 01:52:46.475', NULL),
('2e3fde59-209d-4092-b357-73f51751828e', 'VIS-20260815141518', '519f4084-c650-4ac4-81d0-cea245c2fead', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', '406de6e4-0a92-4097-abf1-c29c198decf3', '2026-08-15 00:00:00.000', 'Tumbuh kembang', 'CP', 'IR, tens, latihan', '', '2026-08-15 14:27:25.531', '2026-08-15 14:27:25.531', NULL),
('2f515dab-4077-4671-aecd-fdf798c71c8a', 'VIS-20260821014653', '77659db2-68f8-49d4-b9ab-f5f62f1dc589', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-14 00:00:00.000', 'spasme otot', 'HNP', 'IR, tens, massage, latihan', '', '2026-08-21 01:46:53.702', '2026-08-21 01:46:53.702', NULL),
('322eac63-e25b-4164-9f5b-9df3314b8389', 'VIS-20260813153449', 'e724a546-fe7d-4043-ab0f-2569601427c2', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-11 00:00:00.000', 'lutut kanan sakit menjalar', 'post stroke, hemiparase', 'IR, tens, massage, penguatan lutut', '', '2026-08-13 15:34:49.211', '2026-08-13 15:34:49.211', NULL),
('32c819ef-0d3b-40b7-b272-1a6394097e22', 'VIS-20260815154816', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-14 00:00:00.000', 'belum mampu fleksi ekstensi siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:48:16.999', '2026-08-15 15:48:16.999', NULL),
('349dd73c-003d-4ede-b4b6-523e0d90a4b4', 'VIS-20260815151851', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-11-24 00:00:00.000', 'Spasme erectorspine, siatic, gastroc nemeus', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-15 15:18:51.359', '2026-08-15 15:18:51.359', NULL),
('34b47134-d23c-409a-8a25-c0b8bca109c7', 'VIS-20260815150345', '36f73a94-af14-4f08-bae5-63320a2ef4f4', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-17 00:00:00.000', 'mmt 5, plantar fasitic', 'plantar fasitic', 'IR, tens, latihan', '', '2026-08-15 15:03:45.050', '2026-08-15 15:03:45.050', NULL),
('34c2405f-b470-4646-8c00-695123a02fb9', 'VIS-20260813142259', 'a87201f0-6bad-4234-9a9c-601aebafec8b', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-07 00:00:00.000', 'hipo mobile c3, sakit bagian TMJ', 'post dislokasi tmj', 'IR, tens, latihan, massage', '', '2026-08-13 14:22:59.871', '2026-08-13 14:22:59.871', NULL),
('35cebc72-e046-445c-aeb3-f4c37ddfb330', 'VIS-20260817161006', '3a36756e-04b0-4cd2-9f3c-b2de3c94bc1f', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-15 00:00:00.000', '2 bulan kecelakaan, 2x urut, 2x fisio di tegal, wrtist dx, keterbatasan gerak fleksi-ekstensi', 'dislokasi wrist', 'IR, tens, reposisi, massage', '', '2026-08-17 16:10:06.036', '2026-08-17 16:10:06.036', NULL),
('35d90e8c-8aee-41ec-aa62-88b9702b9165', 'VIS-20260815144737', 'b479e9ae-fe99-47bc-bca3-67fd1f200740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-18 00:00:00.000', 'kebas tangan kanan, spasme lengan atas dan lengan bawah, ujung jari jempol', 'post op', 'ir,tens,tl', '', '2026-08-15 14:47:37.687', '2026-08-15 14:47:37.687', NULL),
('36c7da50-21a4-4332-ba68-5955723dbe15', 'VIS-20260813144938', '693afc6a-7ac4-4ed0-9acd-1b563c43c861', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-26 00:00:00.000', '2 bulan yang lalu nyeri bagian jempol saat bangun tidur', 'DQS', 'IR, tens, us, latihan', '', '2026-08-13 14:49:38.447', '2026-08-13 14:49:38.447', NULL),
('38137c96-dddc-44d0-bc70-6510ae0ba914', 'VIS-20260813145226', 'a1fe61c8-19c6-411b-8bb8-690d90c461ff', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-02 00:00:00.000', 'nyeri pergelangan kaki dan nyeri punggung belakang angkle', 'odema sinistra', 'IR, tens, latihan', '', '2026-08-13 14:52:26.038', '2026-08-13 14:52:26.038', NULL),
('38cf27d8-71ed-4e41-881c-d5b9d53f466b', 'VIS-20260815153654', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-08 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens,tl', '', '2026-08-15 15:36:55.000', '2026-08-15 15:36:55.000', NULL),
('38fe3a01-70ee-419c-90fb-d09d5689a85d', 'VIS-20260815152021', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-01 00:00:00.000', 'Spasme erectorspine, siatic, gastroc nemeus', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-15 15:20:21.359', '2026-08-15 15:20:21.359', NULL),
('3a63385c-250d-4c1e-917f-4c1f472975cc', 'VIS-20260815160231', '6587d397-c3d5-49f7-9a14-283d5e8d665a', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-06-25 00:00:00.000', 'kelemahan anggota gerak kiri', 'stroke hemiparase sn', 'ir,tens,tl', '', '2026-08-15 16:02:31.408', '2026-08-15 16:02:31.408', NULL),
('3b5e5771-26ce-43c1-bbf2-d9dbc6a6767d', 'VIS-20260819032638', 'f0ca4dfa-9824-4794-a1a9-036048b186bf', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-02-28 00:00:00.000', 'OA genu dx sn, nyeri lutut kanan dan kiri menjalar hingga ke telapak kaki, punggung bawah nyeri menjalar ke kaki', 'OA genu dx sn, LBP L4-L5', 'IR, tens, tapping, latihan', '', '2026-08-19 03:26:38.131', '2026-08-19 03:26:38.131', NULL),
('3c4d2705-ef18-47c0-b4ad-ccddf8901055', 'VIS-20260813152731', '44d982bd-0a8b-48b6-b583-c56e7fd58f4c', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-06-08 00:00:00.000', 'pinggang bawah sampai kaki nyeri dan kesemutan serta baal menjalar', 'LBP, spondylosis, sacroilliac joint disfunction, skolio', 'IR, tens, latihan', '', '2026-08-13 15:27:31.474', '2026-08-13 15:27:31.474', NULL),
('3fe76c46-b346-4980-a2d3-47380209f5b9', 'VIS-20260813142158', 'a87201f0-6bad-4234-9a9c-601aebafec8b', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-05 00:00:00.000', 'sakit bagian TMJ, sakit telapak kaki dextra, hipo mobile c3', 'dislokasi TMJ', 'IR, tens, reposisi, massage', '', '2026-08-13 14:21:58.538', '2026-08-13 14:21:58.538', NULL),
('407dde3b-ab7e-4d87-b2b7-81e29225f8e6', 'VIS-20260815152426', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-30 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'spondylosis HNp', 'IR, tens, latihan', '', '2026-08-15 15:24:26.455', '2026-08-15 15:24:26.455', NULL),
('41698592-54d9-4ef9-a0c7-1a82f3545820', 'VIS-20260815140444', '8db78ac2-84c3-4ea1-8de5-3e853045d7e5', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-22 00:00:00.000', 'Nyeri kaki kanan dan kiri tapi sudah berkurang', 'HNP', 'IR, tens, latihan, massage', '', '2026-08-15 14:04:44.155', '2026-08-15 14:04:44.155', NULL),
('41e3867e-4b84-49fa-8191-cf1c77dd4ad6', 'VIS-20260815152613', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-01-19 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'spondylosis HNP', 'IR, tens, latihan', '', '2026-08-15 15:26:13.359', '2026-08-15 15:26:13.359', NULL),
('43620e02-310d-4c67-b2ce-3fd13a8b5da3', 'VIS-20260817162611', '2bf613e1-3b79-464e-b22e-ebbffcfe0432', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-07 00:00:00.000', 'penguatan kaki kiri', 'skoliosis tipe S', 'IR, tens, latihan, massage', '', '2026-08-17 16:26:11.158', '2026-08-17 16:26:11.158', NULL),
('4580bd44-65c8-456d-b75e-29416de0886d', 'VIS-20260815143704', 'a7994e20-0dcd-4652-83b6-890c1fffabc1', 'f6917731-067d-4df1-96cc-444f9c769909', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-19 00:00:00.000', 'hiper reflek tangan ,reflek kaki tidak ada, mmt tangan kiri 1 kaki kiri 2', 'hemiparase', 'IR,tens, latihan', '', '2026-08-15 14:37:04.260', '2026-08-15 14:37:04.260', NULL),
('47a21403-324e-43b4-a431-37ad16a63ea9', 'VIS-20260817161859', 'ddae9cb1-6529-4632-8fb1-3f34cf913fbe', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-14 00:00:00.000', 'penurunan kontraktur pada kaki kiri', 'GDD', 'IR, tens, latihan, massage', '', '2026-08-17 16:18:59.798', '2026-08-17 16:18:59.798', NULL),
('47b379a6-c911-438d-b786-75ccf547908f', 'VIS-20260815155956', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-27 00:00:00.000', 'keterbatsan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:59:56.907', '2026-08-15 15:59:56.907', NULL),
('48c2874e-6de0-45ea-af4d-7be1af57bbd4', 'VIS-20260817163404', '81a3dda4-ae33-4515-a004-93729361b235', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-04-28 00:00:00.000', 'fase 2', 'post op acl meniscus', 'tens, latihan', '', '2026-08-17 16:34:04.126', '2026-08-17 16:34:04.126', NULL),
('48ff1615-4078-4ca8-96a3-89a3bcea4ea8', 'VIS-20260815152841', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-13 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'spondylosis HNP', 'IR, tens, latihan', '', '2026-08-15 15:28:41.329', '2026-08-15 15:28:41.329', NULL),
('49d9d7a4-6b65-472b-913a-aada2b77047b', 'VIS-20260815141459', '8db78ac2-84c3-4ea1-8de5-3e853045d7e5', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', '8bc32aa8-7d82-427a-be27-baa8ed5de085', '2026-08-15 00:00:00.000', 'Sakit pinggang', 'HNP', 'IR, tens, latihan, massage', '', '2026-08-15 14:26:41.063', '2026-08-15 14:26:41.063', NULL),
('4a5ac7e6-ab4d-42b9-963f-98471df3ad7e', 'VIS-20260815155515', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-16 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:55:15.787', '2026-08-15 15:55:15.787', NULL),
('4a8211bd-bf8d-4f55-80f8-43c4dfa231b0', 'VIS-20260815145335', '36f73a94-af14-4f08-bae5-63320a2ef4f4', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-13 00:00:00.000', 'nyeri berdiri lama', 'subluksasi', 'ir,tens,tl', '', '2026-08-15 14:53:35.648', '2026-08-15 14:53:35.648', NULL),
('4b6ba0be-790f-47b0-9193-a4c80e71a78b', 'VIS-20260815151508', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-11-13 00:00:00.000', 'HNP lumbal', 'Spasme, erector spine, sciatic, gastroc nemeus', 'IR, tens, latihan', '', '2026-08-15 15:15:08.474', '2026-08-15 15:15:08.474', NULL),
('4cdfd8d7-cdcb-4073-b150-001d493f84b7', 'VIS-20260817163323', '81a3dda4-ae33-4515-a004-93729361b235', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-04-25 00:00:00.000', 'post op acl meniscus', 'post op acl meniscus', 'tens, latihan', '', '2026-08-17 16:33:23.297', '2026-08-17 16:33:23.297', NULL),
('4d8ed301-f6f5-4698-9f33-2f87ac3f615d', 'VIS-20260815153254', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-26 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens,tl', '', '2026-08-15 15:32:54.670', '2026-08-15 15:32:54.670', NULL),
('4dd85cb9-6ca1-49c0-a837-a83c5813cea9', 'VIS-20260813150915', '8708b28e-bbc4-4eb4-afc0-c376db31b5fb', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-09-28 00:00:00.000', 'sakit bagian jempol', 'trigger finger', 'IR, tens, latihan, tapping', '', '2026-08-13 15:09:15.190', '2026-08-13 15:09:15.190', NULL),
('4df8a347-f66d-41d1-8742-7e210043427c', 'VIS-20260815153057', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-11 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens,tl', '', '2026-08-15 15:30:57.710', '2026-08-15 15:30:57.710', NULL),
('4eda14d6-401a-4743-aba1-e393f91c40d1', 'VIS-20260815151215', '01086372-ce8e-45b5-beca-49d11d41112f', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-16 00:00:00.000', 'mmt 4, spasme-, ugo fish scale', 'Bell\'s palsy', 'IR, tens, latihan, massage, mirror', '', '2026-08-15 15:12:15.362', '2026-08-15 15:12:15.362', NULL),
('55a7c750-93a0-497c-bb6e-686443158125', 'VIS-20260817164946', '31cc0dc8-a836-4e07-8e95-fce656c590d0', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-09 00:00:00.000', 'sudah mampu duduk ke berdiri, sudah mampu jalan beberapa langkah tanpa pegangan', 'GDD', 'tens, latihan', '', '2026-08-17 16:49:46.347', '2026-08-17 16:49:46.347', NULL),
('56518305-9f1b-4cca-8dbe-129d1a8b907a', 'VIS-20260815153512', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-11 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens,tl', '', '2026-08-15 15:35:12.134', '2026-08-15 15:35:12.134', NULL),
('56d3d2b1-b36a-41fd-b4c9-6f661efa0fc9', 'VIS-20260815152719', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-01-31 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'spondylosis HNP', 'IR, tens, latihan', '', '2026-08-15 15:27:19.679', '2026-08-15 15:27:19.679', NULL),
('575aa60b-e22f-4e81-be36-8ce0ed32bd66', 'VIS-20260815155811', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-13 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:58:11.536', '2026-08-15 15:58:11.536', NULL),
('5842b681-55f0-4a59-853c-d72ef9cad2e3', 'VIS-20260815154008', '0355c51b-195a-4cf3-9521-18441dfc4b34', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-22 00:00:00.000', 'nyeri lutut kanan kiri\nodema +\ntidak mampu berjalan tanpa alat bantu', 'oa knee bilateral', 'ir,tens,tl', '', '2026-08-15 15:40:08.670', '2026-08-15 15:40:08.670', NULL),
('58449736-aa5c-4bec-8dd3-0f4a2ac4bc31', 'VIS-20260817160726', '230afbde-f27d-472a-8033-3b420cff7fa6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-12 00:00:00.000', 'kedua kaki sakit dibawah pantat', 'spondylosis lumbalis', 'IR, tens, massage, streaching', '', '2026-08-17 16:07:26.895', '2026-08-17 16:07:26.895', NULL),
('595b1360-b6b3-4760-a7fb-8ab793d1c08b', 'VIS-20260815154317', '0355c51b-195a-4cf3-9521-18441dfc4b34', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-01 00:00:00.000', 'nyeri lutut kanan kiri\nodema +\ntidak mampu berjalan tanpa alat bantu', 'oa knee bilateral', 'ir,tens,tl', '', '2026-08-15 15:43:17.424', '2026-08-15 15:43:17.424', NULL),
('5971d615-fcbb-4d58-870d-2cea630b6c1c', 'VIS-20260813151231', 'da90990b-64b0-40fe-afc7-2de4e3427d9c', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-10-28 00:00:00.000', 'nyeri punggung', 'HNP', 'IR, tens, latihan', '', '2026-08-13 15:12:31.791', '2026-08-13 15:12:31.791', NULL),
('5b757bad-a410-4ec6-81bd-ef5f937c9ecd', 'VIS-20260817162700', '2bf613e1-3b79-464e-b22e-ebbffcfe0432', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-14 00:00:00.000', 'sakit bagian leher', 'spasme otot', 'IR, tens, latihan, massage', '', '2026-08-17 16:27:00.607', '2026-08-17 16:27:00.607', NULL),
('5c27ace6-ecd8-4dc1-b160-a620a5b5b233', 'VIS-20260815152542', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-01-16 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'spondylosis HNP', 'IR, tens, latihan', '', '2026-08-15 15:25:42.010', '2026-08-15 15:25:42.010', NULL),
('5e1febd6-ae3b-47d6-969e-f1e1e284c92e', 'VIS-20260819031049', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-13 00:00:00.000', 'nyeri punggung menjalar', 'LBP, cervical stenosis', 'IR, tens, latihan', '', '2026-08-19 03:10:49.699', '2026-08-19 03:10:49.699', NULL),
('600c2cf0-47f4-4744-89a7-a412680c9059', 'VIS-20260813153034', '44d982bd-0a8b-48b6-b583-c56e7fd58f4c', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-11 00:00:00.000', 'bahu kanan sakit, lutut sakit, pernah jatuh 1 tahun lalu', 'dislokasi caput of humeri', 'IR, tens, massage, latihan, penguatan', '', '2026-08-13 15:30:34.183', '2026-08-13 15:30:34.183', NULL),
('602c96a8-2e78-4e30-94c3-49ec604e61a8', 'VIS-20260817160318', '5039a998-ce57-4730-810e-09ffffd80636', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-12 00:00:00.000', 'sudah merasa enteng, kebas sudah berkurang', 'Bell\'s palsy', 'IR, tens, massage, mirror exercise', '', '2026-08-17 16:03:18.616', '2026-08-17 16:03:18.616', NULL),
('607c251a-c073-4e99-ac7a-3e2723c08df7', 'VIS-20260819025001', '6d8ae23c-3956-473f-9e0a-17fc9ab609f6', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-01-10 00:00:00.000', 'Ibadahnya duduk, untuk jalan sakit, krepitasi (+), frozen shoulder dextra', 'OA knee dextra, sinistra', 'IR, tens, latihan', '', '2026-08-19 02:50:01.927', '2026-08-19 02:50:01.927', NULL),
('62e0c179-cc6d-4674-bc7b-111a75c101a5', 'VIS-20260819033946', 'fad1eeb9-2d5f-488a-9e21-a1185371645a', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-10-22 00:00:00.000', 'HNP cerevical, lumbal, vertigo', 'HNP cerevical, lumbal, vertigo', 'tens, us, latihan, tapping', '', '2026-08-19 03:39:46.425', '2026-08-19 03:39:46.425', NULL),
('63a88dd2-4fd9-4ebc-86cc-419855dbebe6', 'VIS-20260815144854', 'b479e9ae-fe99-47bc-bca3-67fd1f200740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-08-08 00:00:00.000', 'kebas tangan kanan, spasme lengan atas dan lengan bawah, ujung jari jempol', 'post op', 'ir,tens tl', '', '2026-08-15 14:48:54.181', '2026-08-15 14:48:54.181', NULL),
('644a9248-ee01-4f53-8f57-3d77817ce5be', 'VIS-20260815150941', '01086372-ce8e-45b5-beca-49d11d41112f', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-07 00:00:00.000', 'mmt 4, kekuatan pada alis sudah meningkat, untuk kumur sudah meningkat', 'Bell\'s palsy', 'IR, tens, massage, latihan', '', '2026-08-15 15:09:41.406', '2026-08-15 15:09:41.406', NULL),
('653d1a95-66e6-498a-a63a-74275a119468', 'VIS-20260821015322', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-05 00:00:00.000', 'nyeri lutut kanan', 'OA dx', 'IR, tens, latihan, massage', '', '2026-08-21 01:53:22.858', '2026-08-21 01:53:22.858', NULL),
('657ec641-9e9d-4e8e-941f-c47dc3f3a00e', 'VIS-20260815135837', '5b5c9583-5878-4fa7-94e6-3d84a6102740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-21 00:00:00.000', 'Pergelangan-jari kaki kanan sakit dan susah untuk gerak naik turun, lutut kanan sering kram, tangan kanan masih sering lamas', 'Post stroke hemiparase dx 2023', 'IR, tens, latihan', '', '2026-08-15 13:58:37.152', '2026-08-15 13:58:37.152', NULL),
('66cd72de-d47f-4c11-a4b1-1e7dfbb65e26', 'VIS-20260819030442', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-20 00:00:00.000', 'nyeri leher menjalar hingga ke tangan, kesemutan, jari-jari kaku, nyari betis, mmt 4', 'cervical stenosis', 'IR, tens, latihan', '', '2026-08-19 03:04:42.430', '2026-08-19 03:04:42.430', NULL),
('6970474d-7009-4270-9b58-e37bc0612533', 'VIS-20260815154054', '0355c51b-195a-4cf3-9521-18441dfc4b34', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-06-25 00:00:00.000', 'nyeri lutut kanan kiri\nodema +\ntidak mampu berjalan tanpa alat bantu', 'oa knee bilateral', 'ir,tens,tl', '', '2026-08-15 15:40:54.011', '2026-08-15 15:40:54.011', NULL),
('6ad7f1ce-e250-424c-a2b9-f5fcedd908ad', 'VIS-20260819031130', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-18 00:00:00.000', 'nyeri leher menjalar', 'LBP, cervical stenosis', 'IR, tens, latihan', '', '2026-08-19 03:11:30.127', '2026-08-19 03:11:30.127', NULL),
('6c81d853-0d3d-4b04-b344-208cb18986c4', 'VIS-20260815145424', '36f73a94-af14-4f08-bae5-63320a2ef4f4', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-06 00:00:00.000', 'nyeri berdiri lama', 'subluksasi', 'ir,tens,tl', '', '2026-08-15 14:54:24.895', '2026-08-15 14:54:24.895', NULL),
('6dae2160-c686-4fec-a43e-48eb90f0718b', 'VIS-20260813153650', 'e724a546-fe7d-4043-ab0f-2569601427c2', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-03 00:00:00.000', 'lutut kanan sakit', 'OA dexta', 'IR, tens,  massage, penguatan', '', '2026-08-13 15:36:50.637', '2026-08-13 15:36:50.637', NULL),
('6f4db6bb-fc4c-4a3a-9e84-61d926a395ba', 'VIS-20260817162519', '2bf613e1-3b79-464e-b22e-ebbffcfe0432', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-31 00:00:00.000', 'kaki sebelah kiri spasse lateral, wekness medial', 'skoliosis tipe S', 'IR, tens, massage', '', '2026-08-17 16:25:19.677', '2026-08-17 16:25:19.677', NULL),
('6f93467f-4aec-47cd-ba3c-adaaf12811fa', 'VIS-20260815143557', 'a7994e20-0dcd-4652-83b6-890c1fffabc1', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-16 00:00:00.000', 'hiper reflek tangan, reflek kaki tidak ada, mmt tangan kiri 1 kaki kiri 2', 'hemiparase', 'IR, tens, latihan', '', '2026-08-15 14:35:57.992', '2026-08-15 14:35:57.992', NULL),
('6f9e39c1-f56a-4ff6-af84-69d025973bde', 'VIS-20260813134958', '4ff67d8c-f6f9-4818-b2d0-686efa3965db', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-25 00:00:00.000', '2 minggu yang lalu jatuh dari pohon', 'fraktur dan dislokasi', 'assesment awal', '', '2026-08-13 13:49:58.081', '2026-08-13 13:49:58.081', NULL),
('70bf0713-b05d-44a8-8427-3969688811da', 'VIS-20260817163237', '81a3dda4-ae33-4515-a004-93729361b235', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-04-20 00:00:00.000', '2024 cidera Ft, 2025 oktober operasi', 'post op acl meniscus', 'tens, latihan', '', '2026-08-17 16:32:37.468', '2026-08-17 16:32:37.468', NULL),
('717394ad-8ff6-4a81-a485-33846ffb5e32', 'VIS-20260817161718', 'ddae9cb1-6529-4632-8fb1-3f34cf913fbe', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-08 00:00:00.000', 'pemendekan otot pada kaki kanan sudah mulai berkurang', 'GDD', 'tens, IR, latihan, massage', '', '2026-08-17 16:17:18.737', '2026-08-17 16:17:18.737', NULL),
('72292834-5dc0-4c4f-9b3e-f8ac6f04dbbe', 'VIS-20260815154134', '0355c51b-195a-4cf3-9521-18441dfc4b34', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-16 00:00:00.000', 'nyeri lutut kanan kiri\nodema +\ntidak mampu berjalan tanpa alat bantu', 'oa knee bilateral', 'ir,tens,tl', '', '2026-08-15 15:41:34.014', '2026-08-15 15:41:34.014', NULL),
('731be1cb-7fc3-40ca-b8c1-20dbf6c301f0', 'VIS-20260815145031', 'b479e9ae-fe99-47bc-bca3-67fd1f200740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-08-13 00:00:00.000', 'kebas tangan kanan, spasme lengan atas dan lengan bawah, ujung jari jempol', 'post op', 'ir,tens,tl', '', '2026-08-15 14:50:31.151', '2026-08-15 14:50:31.151', NULL),
('7326aacc-8ba2-4415-a5da-b0110313a274', 'VIS-20260813154510', '924c205a-cb14-45e9-aefa-a4c5c00e1149', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-18 00:00:00.000', 'nyeri pantat kiri menjalar sampai kaki, nyeri punggung, sakit di bagian pantat kiri kalau duduk lama', 'penurunan kemampuan fungsional e.c tumor saraf', 'tens, IR, latihan', '', '2026-08-13 15:45:10.189', '2026-08-13 15:45:10.189', NULL),
('74b5435e-c955-4ec5-9380-3506393ad9c6', 'VIS-20260813154023', '6176d9bc-5935-4bf5-bd9f-525ce81f8ed5', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-02 00:00:00.000', 'OA knee dexta sinister, odema angkle', 'OA knee, bilateral', 'IR, tens, latihan', '', '2026-08-13 15:40:23.275', '2026-08-13 15:40:23.275', NULL),
('75a6f87a-c96b-43bc-a2ae-b81d58bb37e2', 'VIS-20260813151308', 'da90990b-64b0-40fe-afc7-2de4e3427d9c', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-16 00:00:00.000', 'Nyeri pinggang', 'HNP', 'IR, tens, latihan', '', '2026-08-13 15:13:08.036', '2026-08-13 15:13:08.036', NULL),
('7669e769-19a5-4f82-bb07-18061b0d7166', 'VIS-20260817164653', '31cc0dc8-a836-4e07-8e95-fce656c590d0', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-26 00:00:00.000', 'sudah mampu berdiri dengan pegangan, berjalan dengan pegangan', 'GDD', 'tens, IR, latihan', '', '2026-08-17 16:46:53.791', '2026-08-17 16:46:53.791', NULL),
('7679af60-fd46-41e8-ae79-16754be05719', 'VIS-20260821015354', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-07 00:00:00.000', 'nyeri lutut kanan, nyeri bahu kiri', 'OA dx', 'IR, tens, latihan, massage', '', '2026-08-21 01:53:54.401', '2026-08-21 01:53:54.401', NULL),
('76c4ca46-4a97-428f-b725-d912e9039e9e', 'VIS-20260819030757', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-06 00:00:00.000', 'nyeri bahu menjalar ke lengan, rahang', 'C3-4, C5-6 (bulging ringan)', 'IR, tens, latihan', '', '2026-08-19 03:07:57.554', '2026-08-19 03:07:57.554', NULL),
('76dfb43b-43ff-4405-99e8-8516d107e2af', 'VIS-20260815140047', '5b5c9583-5878-4fa7-94e6-3d84a6102740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-13 00:00:00.000', 'plantar mengarah  ke  luar', 'post stroke hemiparase dx', 'Tens, IR, latihan', '', '2026-08-15 14:00:47.904', '2026-08-15 14:00:47.904', NULL),
('76fcf353-1ea3-48a1-b9be-1976b554448b', 'VIS-20260813134722', '22356324-ae52-4b1b-a243-a066e6f39509', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-22 00:00:00.000', 'sakit di pinggang dan panggul', 'Sacroilliac joint disfunction', 'IR, tens, latihan', '', '2026-08-13 13:47:22.177', '2026-08-13 13:47:22.177', NULL),
('77361f81-90dd-40ca-93ec-39c8d5a01889', 'VIS-20260815152133', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-26 00:00:00.000', 'Spasme erectorspine, siatic, gastroc nemeus', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-15 15:21:33.360', '2026-08-15 15:21:33.360', NULL),
('78083788-0f89-4f99-aef5-0e1c9aeff0b2', 'VIS-20260813153551', 'e724a546-fe7d-4043-ab0f-2569601427c2', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-19 00:00:00.000', 'lutut kanan sakit menjalar', 'post stroke, hemiparase', 'IR, tens, massage, penguatan', '', '2026-08-13 15:35:51.984', '2026-08-13 15:35:51.984', NULL),
('78fa8519-6e58-48d6-8670-87041a07126e', 'VIS-20260813151731', 'e2fa59b5-ade2-4587-bd00-58204f977003', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-10-18 00:00:00.000', 'hemiparase dextra spastic AGB', 'hemiparase', 'IR, tens', '', '2026-08-13 15:17:31.042', '2026-08-13 15:17:31.042', NULL),
('791add8c-0ce6-462b-8a09-cd50b8cd692c', 'VIS-20260815144428', 'b479e9ae-fe99-47bc-bca3-67fd1f200740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-11 00:00:00.000', 'kebas tangan kanan, spasme lengan atas dan lengan bawah, ujung jari jempol', 'post op radial dx', 'ir,tens,tl', '', '2026-08-15 14:44:28.612', '2026-08-15 14:44:28.612', NULL),
('79224c62-331c-4f41-9590-fe792386c3c3', 'VIS-20260813151602', '058d3054-1f40-49ac-80e4-e93ab453dd4f', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-22 00:00:00.000', 'grade 1 robek, nyeri gerak eversi 4, nyeri tekan 3, nyeri diam 0, subluk', 'sprain angkle', 'tens, us, latihan, tapping', '', '2026-08-13 15:16:02.649', '2026-08-13 15:16:02.649', NULL),
('792987bb-8600-4922-8c7b-cf1bdfc59c9f', 'VIS-20260815155440', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-12 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:54:40.028', '2026-08-15 15:54:40.028', NULL),
('795c6542-1898-4b40-8255-a72c2b308b1f', 'VIS-20260819031002', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-11 00:00:00.000', 'nyeri punggung bawah, menjalar hingga kaki, nyeri leher menjalar hingga lengan', 'LBP, cervical stenosis', 'IR, tens, latihan', '', '2026-08-19 03:10:02.678', '2026-08-19 03:10:02.678', NULL),
('7a7479be-b2a9-49a8-aea4-aca38833a0c3', 'VIS-20260821015128', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-17 00:00:00.000', 'nyeri lutut kanan, nyeri punggung atas', 'OA dx, kifosis', 'IR, tens, latihan, massage', '', '2026-08-21 01:51:28.895', '2026-08-21 01:51:28.895', NULL),
('7c47aba8-3dd7-4cf7-8936-f0ef10ccea2b', 'VIS-20260813150718', 'cc742b03-6615-49a0-912c-9e9dc38edbc7', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-08 00:00:00.000', 'nyeri punggung bawah pantat kiri, nyeri menjalar hingga selangkangan/paha kiri, kaku pada punggung bawah, berdiri dan duduk lama', 'SIJ disfunction', 'IR, tens, us, latihan', '', '2026-08-13 15:07:18.214', '2026-08-13 15:07:18.214', NULL),
('7d956f20-32f4-4dc8-b6bc-c17824a89575', 'VIS-20260815152210', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-29 00:00:00.000', 'Spasme erectorspine, siatic, gastroc nemeus', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-15 15:22:10.330', '2026-08-15 15:22:10.330', NULL),
('7df0c8f4-ec8d-4fe2-be8b-5f9e69c878d2', 'VIS-20260815145259', '36f73a94-af14-4f08-bae5-63320a2ef4f4', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-17 00:00:00.000', 'nyeri berdiri lama', 'subluksasi angkle lateral', 'ir,tens,tapping', '', '2026-08-15 14:52:59.171', '2026-08-15 14:52:59.171', NULL),
('7e8c136e-a863-42bd-91ae-5258fb9e84b9', 'VIS-20260817163757', '624cedb7-2fe4-48dc-acc4-ed1e0b6db39b', 'f6917731-067d-4df1-96cc-444f9c769909', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-05-17 00:00:00.000', '8 bulan lalu merasakan nyeri pada area lutut kanan menjalar hingga pinggul, lebih terasa nyeri area lutut kiri', 'SIJ sinistra, OA genu dextra', 'IR, tens, latihan', '', '2026-08-17 16:37:57.767', '2026-08-17 16:37:57.767', NULL),
('7fa57232-7b15-46a0-a982-06a5fb842aef', 'VIS-20260815153935', '0355c51b-195a-4cf3-9521-18441dfc4b34', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-17 00:00:00.000', 'nyeri lutut kanan kiri\nodema +\ntidak mampu berjalan tanpa alat bantu', 'oa knee bilateral', 'ir,tens,tl', '', '2026-08-15 15:39:35.546', '2026-08-15 15:39:35.546', NULL),
('801e5e7f-e462-4c9e-8da8-f924fa43ef7a', 'VIS-20260819033010', 'f0ca4dfa-9824-4794-a1a9-036048b186bf', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-14 00:00:00.000', 'nyeri punggung bagian bawah menjalar hingga kaki kiri, nyeri kedua lutut, kesemutan pada ke tiga jari kiri', 'HNP, OA genu dx sn', 'IR, tens, latihan, tapping (lumbal)', '', '2026-08-19 03:30:10.851', '2026-08-19 03:30:10.851', NULL),
('8040e658-3efa-444f-ba76-d05d3031c34f', 'VIS-20260815154937', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-16 00:00:00.000', 'keterbatasan fleksi ekstensi siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:49:37.543', '2026-08-15 15:49:37.543', NULL);
INSERT INTO `medical_records` (`id`, `visit_number`, `patient_id`, `service_id`, `physiotherapist_id`, `appointment_id`, `examination_date`, `anamnesis`, `diagnosis`, `therapy`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
('81a43111-31bd-471d-9c26-9f1ab51e0507', 'VIS-20260815153410', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-02 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens, tl', '', '2026-08-15 15:34:10.050', '2026-08-15 15:34:10.050', NULL),
('82b261f6-30fb-468f-b800-bc662c760a4a', 'VIS-20260815150758', '01086372-ce8e-45b5-beca-49d11d41112f', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-01-31 00:00:00.000', 'Bell\'s palsy, kelemahan otot dx, spasme otot sinistra, spasme otot stm, facial', 'Bell\'s palsy', 'IR, tens, massage, latihan', '', '2026-08-15 15:07:58.160', '2026-08-15 15:07:58.160', NULL),
('83433187-0d7e-4918-ad5b-e65e7e896cc9', 'VIS-20260815143745', 'a7994e20-0dcd-4652-83b6-890c1fffabc1', 'f6917731-067d-4df1-96cc-444f9c769909', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-22 00:00:00.000', 'hiper reflek tangan ,reflek kaki tidak ada, mmt tangan kiri 1 kaki kiri 2', 'Hemiparase', 'IR, tens, latihan', '', '2026-08-15 14:37:45.040', '2026-08-15 14:37:45.040', NULL),
('84cbf817-dd50-4dcf-93f6-295cddb064c1', 'VIS-20260815155323', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-07 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:53:23.013', '2026-08-15 15:53:23.013', NULL),
('84fc2558-639d-4aa1-ad13-b785f24751c7', 'VIS-20260813152037', '9b1ad0d6-d57d-45b8-81ec-ee00ff3fa1cc', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-02-12 00:00:00.000', 'nyeri pinggul menjalar hingga ke kaki kanan sisi samping, spasme', 'gluteus LBP', 'IR, tens, latihan', '', '2026-08-13 15:20:37.223', '2026-08-13 15:20:37.223', NULL),
('856ecdcb-a207-4cc9-93ab-8ffc5bb912a6', 'VIS-20260815152055', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-05 00:00:00.000', 'Spasme erectorspine, siatic, gastroc nemeus', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-15 15:20:55.136', '2026-08-15 15:20:55.136', NULL),
('858bf0f8-721f-4871-a133-78e0d7639439', 'VIS-20260819030315', '4d08b2ca-2f49-4dd9-b75d-d616d106247d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-11 00:00:00.000', 'upper body', 'asymetric postur, bahu asymetric, lumbal asymetric', 'latihan', '', '2026-08-19 03:03:15.519', '2026-08-19 03:03:15.519', NULL),
('89727c48-aef7-492a-beeb-3be5cc7d6f9e', 'VIS-20260817160639', '230afbde-f27d-472a-8033-3b420cff7fa6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-06 00:00:00.000', 'Pantat ke bawah nyeri sudah mulai berkurang', 'Spondylosis', 'IR, tens, latihan, massage', '', '2026-08-17 16:06:39.578', '2026-08-17 16:06:39.578', NULL),
('8cc16e66-c248-4a83-8217-32b0a1ac5d24', 'VIS-20260813152333', '5ef0ba0a-849e-4279-b9b9-8671fff27713', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-09 00:00:00.000', 'nyeri punggung bagian bawah menjalar hingga ke kaki L1-L5, nyeri menjalar hingga ke jari/lengan', 'HNP, spondylosis', 'IR, tens, latihan', '', '2026-08-13 15:23:33.765', '2026-08-13 15:23:33.765', NULL),
('8d0c72fc-4478-4768-a7d8-4523f4a650fe', 'VIS-20260815152338', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-26 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'Spondilosis HNP', 'IR, tens, latihan', '', '2026-08-15 15:23:38.247', '2026-08-15 15:23:38.247', NULL),
('8df40bba-532a-4b66-859a-49650cd26226', 'VIS-20260817161243', '3a36756e-04b0-4cd2-9f3c-b2de3c94bc1f', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-21 00:00:00.000', 'pergelangan tangan bunyi krek saat kemarin motoran 3 jam PP, posisi langsung nyeri', 'dislokasi sendi ulna wrist', 'reposisi, IR, us, tens, massage, latihan', '', '2026-08-17 16:12:43.914', '2026-08-17 16:12:43.914', NULL),
('8e58a415-cac0-4a8c-814e-d23488783792', 'VIS-20260819032459', 'f0ca4dfa-9824-4794-a1a9-036048b186bf', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-02-13 00:00:00.000', 'OA genu dx sn, nyeri lutut kanan dan kiri menjalar hingga ke telapak kaki, punggung bawah nyeri menjalar ke kaki', 'OA genu dx sn, LBP', 'IR, tens, latihan', '', '2026-08-19 03:24:59.112', '2026-08-19 03:24:59.112', NULL),
('92c7630c-f941-415a-9332-7ecb2eb0d0d1', 'VIS-20260817161116', '3a36756e-04b0-4cd2-9f3c-b2de3c94bc1f', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-19 00:00:00.000', 'masih terasa pegal/kebas untuk berkendara jauh, masih ada sedikit nyeri di bagian pergelangan tangan kanan', 'dislokasi wrist', 'reposisi, IR, tens, massage, latihan', '', '2026-08-17 16:11:16.628', '2026-08-17 16:11:16.628', NULL),
('9316385b-a6cf-4680-9a5b-930896fca962', 'VIS-20260817164803', '31cc0dc8-a836-4e07-8e95-fce656c590d0', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-18 00:00:00.000', 'sudah mampu berdiri dengan pegangan, belum mampu berjalan mandiri', 'GDD', 'IR, tens, latihan', '', '2026-08-17 16:48:03.159', '2026-08-17 16:48:03.159', NULL),
('93f6ab6a-46ff-4faa-ac70-f2f9b751fc74', 'VIS-20260819031520', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-13 00:00:00.000', 'nyeri leher mlai berkurang, nyeri punggung atas, nyeri bagian kedua pantat', 'LBP, cervical stenosis', 'IR, tens, latihan, massage', '', '2026-08-19 03:15:20.304', '2026-08-19 03:15:20.304', NULL),
('94236596-8d22-4eeb-be3e-1d272d080693', 'VIS-20260815141339', '7e2b6a4e-3a57-483e-b393-7ac103fce18d', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', '6919dd0f-e8d9-49dc-9467-4143e97c94fc', '2026-08-15 00:00:00.000', 'Sering jatuh', 'Asimetris hip, anterior pelvictil, flat foot, kaki x', 'IR, tens, latihan, massage', '', '2026-08-15 14:17:48.599', '2026-08-15 14:17:48.599', NULL),
('944110e5-efd4-460f-bb50-07ae93c078dc', 'VIS-20260815155559', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-30 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:55:59.296', '2026-08-15 15:55:59.296', NULL),
('988d608f-3466-4def-b988-1f16bb4dc310', 'VIS-20260815154720', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-02 00:00:00.000', 'belum mampu fleksi ekstensi siku secara maksimal ', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:47:20.854', '2026-08-15 15:47:20.854', NULL),
('99d511e5-0da0-4e15-ba8e-dcce1f2f35ce', 'VIS-20260815153621', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-20 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens,tl', '', '2026-08-15 15:36:21.361', '2026-08-15 15:36:21.361', NULL),
('9a401a08-a1af-4eda-a7d6-f065df27dcf9', 'VIS-20260813142013', 'a87201f0-6bad-4234-9a9c-601aebafec8b', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-27 00:00:00.000', 'spasme gastroc, spasme paravetebra', 'spasme dan tonjolan CS', 'IR, tens, us, latihan, massage', '', '2026-08-13 14:20:13.400', '2026-08-13 14:20:13.400', NULL),
('9acc0529-ef4a-4843-9419-454188686a85', 'VIS-20260821014923', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-22 00:00:00.000', 'nyeri area siku laateral, medial nyeri berkurang', 'dislokasi patella, golfers elbow tenis elbow', 'IR, tens, latihan', '', '2026-08-21 01:49:23.316', '2026-08-21 01:49:23.316', NULL),
('9b675cc5-d402-4993-b39e-43f63dc48955', 'VIS-20260815153859', '0355c51b-195a-4cf3-9521-18441dfc4b34', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-07 00:00:00.000', 'nyeri lutut kanan kiri\nodema +\ntidak mampu berjalan tanpa alat bantu', 'oa knee bilateral', 'ir,tens,tl', '', '2026-08-15 15:38:59.821', '2026-08-15 15:38:59.821', NULL),
('9d0d7741-617a-4e2d-a783-3c2f427cf48e', 'VIS-20260817160109', '5039a998-ce57-4730-810e-09ffffd80636', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-05 00:00:00.000', 'Wajah kaku, mata perih, kanan tebel, bagian kir tidak terasa apa-apa sudah 3 bulan, matar berair, makan makin susah', 'Bell\'s palsy', 'IR, tens, massage, mirror exercise', '', '2026-08-17 16:01:09.094', '2026-08-17 16:01:09.094', NULL),
('9f3581e8-0146-411e-a4f5-259aef092bd0', 'VIS-20260821014818', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-14 00:00:00.000', 'nyeri area siku kiri dari satu minggu lalu, nyeri saat memeras pakaian, lemas', 'golfers elbow, tenis elbow', 'IR, tens, latihan', '', '2026-08-21 01:48:18.768', '2026-08-21 01:48:18.768', NULL),
('9ff03a6b-7a27-4dbf-b39f-a1019aef86a7', 'VIS-20260821015430', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-14 00:00:00.000', 'sakit di lutut kiri', 'OA', 'IR, tens, ,massage, latihan', '', '2026-08-21 01:54:30.669', '2026-08-21 01:54:30.669', NULL),
('a0d5edc7-63e3-4f9e-9619-ab617710157e', 'VIS-20260815155102', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-23 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:51:02.669', '2026-08-15 15:51:02.669', NULL),
('a22fbcc1-ca66-414f-b19e-d9cb573dbdfd', 'VIS-20260813153234', 'c98cbbea-bb99-4bb9-a93d-d449e1a9cc37', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-01 00:00:00.000', 'pronasi terbatas, masih ada reflek, spasme', 'hemiparase dextra', 'IR, tens, mobilisasi, latihan', '', '2026-08-13 15:32:34.113', '2026-08-13 15:32:34.113', NULL),
('a3a5f629-b1c9-4b1f-a794-00984252b2fd', 'VIS-20260815153211', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-18 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens,tl', '', '2026-08-15 15:32:11.404', '2026-08-15 15:32:11.404', NULL),
('a3e96f4e-b507-465f-92ca-1af657255493', 'VIS-20260819030904', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-08 00:00:00.000', 'nyeri punggung bawah menjalar ke kaki, nyeri leher menjalar hingga lengan', 'LBP, cervival stenosis', 'IR, tens, latihan', '', '2026-08-19 03:09:04.852', '2026-08-19 03:09:04.852', NULL),
('a529a3f9-0135-461b-899c-7801874ae8af', 'VIS-20260813144537', 'c3cb3e96-c593-4a02-bf58-2ba8b5231482', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-01 00:00:00.000', 'nyeri menjalar dari leher hingga ke lengan, nyeri di area jempol dx, jari pelatuk', 'CRS, DQS', 'IR, tens, us, latihan', '', '2026-08-13 14:45:37.523', '2026-08-13 14:45:37.523', NULL),
('a5d74279-2efc-47de-81da-4dedb4117076', 'VIS-20260813135725', '4ff67d8c-f6f9-4818-b2d0-686efa3965db', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-13 00:00:00.000', 'spasme flexor elbow muscle', 'post operasi radial ulna', 'IR, Tens, latihan, massage', '', '2026-08-13 13:57:25.627', '2026-08-13 13:57:25.627', NULL),
('a7827fd6-47be-46f6-a35c-35c72315bfb9', 'VIS-20260813140634', '9998c170-e052-41b0-b71e-b99e84f944a6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-22 00:00:00.000', 'nyeri leher, nyeri di bagian kedua pantat', 'sciatica, winging scapula, CRS', 'tens, IR, latihan, massage', '', '2026-08-13 14:06:34.223', '2026-08-13 14:06:34.223', NULL),
('a93ac558-1f6c-4a7d-8bfe-35e9d5d53eff', 'VIS-20260819033348', 'fad1eeb9-2d5f-488a-9e21-a1185371645a', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-21 00:00:00.000', 'hemiparase dextra', 'hemiparase dextra', 'IR, tens, latihan', '', '2026-08-19 03:33:48.928', '2026-08-19 03:33:48.928', NULL),
('aa39f200-b55c-440c-a3fb-50960c70117d', 'VIS-20260815144603', 'b479e9ae-fe99-47bc-bca3-67fd1f200740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-16 00:00:00.000', 'kebas tangan kanan, spasme lengan atas dan lengan bawah, ujung jari jempol', 'post op radial dx', 'ir,tens,tl', '', '2026-08-15 14:46:03.002', '2026-08-15 14:46:03.002', NULL),
('ab9dc2ac-07ad-4f05-9fd3-50f67990f2bf', 'VIS-20260817164118', 'cc2900cf-379f-44e3-8791-7cec78326218', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-02 00:00:00.000', '2 minggu lalu mersakan nyeri di daerah selangkangan setelah bermain bola', 'dislokasi hip, SIJ', 'reposisi, IR, tens, latihan', '', '2026-08-17 16:41:18.115', '2026-08-17 16:41:18.115', NULL),
('ad3eec3d-e4eb-427a-961a-5b610d006474', 'VIS-20260819030700', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-01 00:00:00.000', 'nyeri leher menjalar hingga ke tangan', 'C3-4, C5-6 (bulging ringan), stenosis', 'IR, tens, latihan', '', '2026-08-19 03:07:00.196', '2026-08-19 03:07:00.196', NULL),
('afbdd0ab-4635-42c5-a2c5-5a1b8a3ca5a8', 'VIS-20260817164423', '31cc0dc8-a836-4e07-8e95-fce656c590d0', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-13 00:00:00.000', 'GDD atrofi brain, sudah mampu duduk mandiri, mampu rambatan, belum mampu jalan mandiri', 'GDD', 'IR, tens, latihan', '', '2026-08-17 16:44:23.416', '2026-08-17 16:44:23.416', NULL),
('affae3db-a54b-4bee-aa89-648020846bd6', 'VIS-20260819033513', 'fad1eeb9-2d5f-488a-9e21-a1185371645a', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-13 00:00:00.000', 'hemiparase, nyeri punggung bagian kanan, menjalar hingga ke kaki kiri, leher nyeri pusing berputar', 'hemiparase dextra, LBP, CRS', 'IR, tens, latihan', '', '2026-08-19 03:35:13.594', '2026-08-19 03:35:13.594', NULL),
('b4983b88-8a90-4b34-a8f5-3544dd3cfedd', 'VIS-20260819034032', 'fad1eeb9-2d5f-488a-9e21-a1185371645a', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-10-24 00:00:00.000', 'HNP cerevical, lumbal, vertigo', 'HNP cerevical, lumbal, vertigo', 'tens, us, latihan, tapping', '', '2026-08-19 03:40:32.889', '2026-08-19 03:40:32.889', NULL),
('b4d57ace-3fc9-41bd-a387-97a4748c15cc', 'VIS-20260817155759', '6c985fe1-b8a3-474d-9e20-3d07b7a7cb4e', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-14 00:00:00.000', 'Sakit di pergelangan tangan kanan sudah 2 bulan, sudah 3x fisio di pekalongan tapi masih tetap sakit, sakit saat melakukan aktifitas rumah tangga', 'Dislokasi', 'Us, IR, tens, reposisi', '', '2026-08-17 15:57:59.658', '2026-08-17 15:57:59.658', NULL),
('b61deffd-3086-4549-86c7-eb5286d70e76', 'VIS-20260817164851', '31cc0dc8-a836-4e07-8e95-fce656c590d0', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-02 00:00:00.000', 'sudah mampu beridri mandiri tanpa pegangan', 'GDD', 'tens, latihan', '', '2026-08-17 16:48:51.098', '2026-08-17 16:48:51.098', NULL),
('b76dab2d-0f2b-424a-9216-6da281e1add2', 'VIS-20260819025711', '4d08b2ca-2f49-4dd9-b75d-d616d106247d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-14 00:00:00.000', 'asimetris postur', 'Asimetris postur bahu, pingul, skoliosis', 'IR, tens, latihan, tapping', '', '2026-08-19 02:57:11.705', '2026-08-19 02:57:11.705', NULL),
('b7ac66ed-1388-4494-bac8-d4da64fcc76a', 'VIS-20260813151008', '8708b28e-bbc4-4eb4-afc0-c376db31b5fb', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-10-01 00:00:00.000', 'sakit bagian jempol', 'trigger finger', 'IR, tens, us, latihan, tapping', '', '2026-08-13 15:10:08.160', '2026-08-13 15:10:08.160', NULL),
('bcee9a05-4c04-4618-8baf-27c48dc5738d', 'VIS-20260813144223', '9fa0787f-1d3c-496b-bf03-44adac3f7511', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-11-14 00:00:00.000', 'sakit di lengan dan talapak kaki', 'plantar fascitis, brachialis', 'IR, us, latihan, ', '', '2026-08-13 14:42:23.831', '2026-08-13 14:42:23.831', NULL),
('bd68e314-2af3-40a7-9261-afa25a09b10b', 'VIS-20260813153913', '6176d9bc-5935-4bf5-bd9f-525ce81f8ed5', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-06-26 00:00:00.000', 'bulging gilateral L1 S1', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-13 15:39:13.737', '2026-08-13 15:39:13.737', NULL),
('bf46e746-53f1-4796-826b-1d0ca4006be6', 'VIS-20260815152801', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-02-09 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'Spondylosis HNP', 'IR, tens, latihan', '', '2026-08-15 15:28:01.354', '2026-08-15 15:28:01.354', NULL),
('c066840c-65c2-463b-b623-001cf3b31e85', 'VIS-20260815140605', '8db78ac2-84c3-4ea1-8de5-3e853045d7e5', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-12 00:00:00.000', 'tulang rusuk kiri sakit, kedua kaki sakit', 'HNP', 'IR, tens, latihan, massage', '', '2026-08-15 14:06:05.627', '2026-08-15 14:06:05.627', NULL),
('c08a68c6-33a8-47ef-a1b3-bba5432df16e', 'VIS-20260815155843', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-17 00:00:00.000', 'keterbatsan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:58:43.657', '2026-08-15 15:58:43.657', NULL),
('c08f1ead-257e-4062-bc11-5fc62f8aa8c0', 'VIS-20260819025355', '6d8ae23c-3956-473f-9e0a-17fc9ab609f6', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-04-28 00:00:00.000', 'duudk msaih dibantu, jalan menggunakan walker, kaki kanan (odema), kaki kiri (odema)', 'OA knee, bilateral, atrofi otot angkle, sublukasi sinistra kaki (Dextra)', 'IR, tens, latihan, mobilisasi angkle,  angkle tapping', '', '2026-08-19 02:53:55.837', '2026-08-19 02:53:55.837', NULL),
('c2615b3e-17cf-4cd9-8bf5-7a04cbe7fbcc', 'VIS-20260819030550', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-30 00:00:00.000', 'nyeri leher menjalar hingga ke tangan, kesemutan berkurang, betis nyeri', 'cervical stenosis', 'IR, tens, latihan', '', '2026-08-19 03:05:50.170', '2026-08-19 03:05:50.170', NULL),
('c4aae91b-d6ed-40f6-88e9-8c1f7dc0f8e4', 'VIS-20260819031757', 'f0ca4dfa-9824-4794-a1a9-036048b186bf', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-01-27 00:00:00.000', 'C3-4, 5-6 bulging discus, VL3-S1 protucio diskus stenosis, osteoartritis', 'HNP, stenosis, OA genu dx dan sn', 'IR, tens latihan', '', '2026-08-19 03:17:57.497', '2026-08-19 03:17:57.497', NULL),
('c4b687de-b21b-476a-b312-a2aacfcd323e', 'VIS-20260817163514', '81a3dda4-ae33-4515-a004-93729361b235', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-05-08 00:00:00.000', 'fase 2', 'post op acl meniscus', 'tens,latihan', '', '2026-08-17 16:35:14.757', '2026-08-17 16:35:14.757', NULL),
('c51b698b-1dff-4c76-99be-14d7ee25d5ac', 'VIS-20260819032127', 'f0ca4dfa-9824-4794-a1a9-036048b186bf', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-02-07 00:00:00.000', 'C3-4, 5-6 bulging discus, VL3-S1 protucio diskus stenosis, osteoartritis', 'OA+LBP', 'IR, tens, latihan', '', '2026-08-19 03:21:27.805', '2026-08-19 03:21:27.805', NULL),
('c8fbba3b-73b5-4362-9ac0-7320635dd384', 'VIS-20260815155357', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-09 00:00:00.000', 'keterbatasn f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:53:57.887', '2026-08-15 15:53:57.887', NULL),
('cc10b1d6-8b1f-4bc1-9113-201dca7e4179', 'VIS-20260815144958', '8708b28e-bbc4-4eb4-afc0-c376db31b5fb', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-08-13 00:00:00.000', 'kebas tangan kanan, spasme lengan atas dan lengan bawah, ujung jari jempol', 'post op', 'ir, tens, tl', '', '2026-08-15 14:49:58.257', '2026-08-15 14:49:58.257', NULL),
('cc8d2517-9f82-455c-9880-b3ec5ef47141', 'VIS-20260815154213', '0355c51b-195a-4cf3-9521-18441dfc4b34', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-22 00:00:00.000', 'nyeri lutut kanan kiri\nodema +\ntidak mampu berjalan tanpa alat bantu', 'oa knee bulateral', 'ir,tens,tl', '', '2026-08-15 15:42:13.361', '2026-08-15 15:42:13.361', NULL),
('cc9534dc-992a-4dbc-8d73-c429beffc793', 'VIS-20260813144404', 'd745e66c-a03a-4d66-adc9-3d83d35d88b8', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-12-25 00:00:00.000', 'sudah 3-4 bulan nyeri punggung bawah hingga kedua kaki', '-', 'IR, tens, latihan', '', '2026-08-13 14:44:04.132', '2026-08-13 14:44:04.132', NULL),
('ce5a7b8e-45cd-40da-b72a-3fb5cc00cc66', 'VIS-20260813150255', '50e9848c-3a3c-4598-8220-4ba0969ddcfc', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-24 00:00:00.000', 'nyeri area jempol, masih bunyi klik, non unstabil, nyeri lokal', 'trigger finger, dislokasi', 'IR, tens us, latihan, tapping', '', '2026-08-13 15:02:55.221', '2026-08-13 15:02:55.221', NULL),
('cf8dc0cb-78d6-41f5-9edb-38364506a5e3', 'VIS-20260813135612', '4ff67d8c-f6f9-4818-b2d0-686efa3965db', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-06 00:00:00.000', 'spasme flexor elbow muscle', 'post operasi', 'IR, tens, latihan', '', '2026-08-13 13:56:12.523', '2026-08-13 13:56:12.523', NULL),
('d35354d4-11a3-430a-87d4-8cffaefa9c12', 'VIS-20260821015504', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-19 00:00:00.000', 'sakit dikaki kiri dan punggung bawah', 'OA', 'IR, tens, latihan, massage', '', '2026-08-21 01:55:04.373', '2026-08-21 01:55:04.373', NULL),
('d3d2b146-d5aa-4879-9456-62c543f8a795', 'VIS-20260815152506', '2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-01-09 00:00:00.000', 'penyempitan diskus vl 3-4, nyeri menjalar leher, bahu, hingga jari-jari kebas', 'spondylosis HNP', 'IR, tens, latihan', '', '2026-08-15 15:25:06.010', '2026-08-15 15:25:06.010', NULL),
('d3e99b0e-c8dd-4b35-8a7f-dd86716e1bcc', 'VIS-20260821015034', 'c61d04e0-c101-491e-b2b8-a88ab5e18890', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-15 00:00:00.000', 'nyeri lutut kanan, nyeri punggung atas', 'OA dx, kifosis', 'IR, tens, massage, latihan', '', '2026-08-21 01:50:34.042', '2026-08-21 01:50:34.042', NULL),
('d4963dc3-2a5d-46af-9b98-4f638eda9caa', 'VIS-20260813151137', 'da90990b-64b0-40fe-afc7-2de4e3427d9c', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-10-23 00:00:00.000', 'Nyeri pinggang', 'HNP', 'IR, tens, latihan', '', '2026-08-13 15:11:37.283', '2026-08-13 15:11:37.283', NULL),
('d4e9eef5-a499-4d35-a22d-24034952c11c', 'VIS-20260819031205', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-25 00:00:00.000', 'nyeri leher menjalar', 'LBP, cervical stenosis', 'IR, tens, latihan', '', '2026-08-19 03:12:05.676', '2026-08-19 03:12:05.676', NULL),
('d4ed8168-5a73-4f12-be09-2595a38371af', 'VIS-20260815151658', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-11-18 00:00:00.000', 'Spasme erectorspine, siatic, gastroc nemeus', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-15 15:16:58.808', '2026-08-15 15:16:58.808', NULL),
('d5943017-ec82-4680-9ea7-588d1f4df626', 'VIS-20260815153131', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-26 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens,tl', '', '2026-08-15 15:31:31.362', '2026-08-15 15:31:31.362', NULL),
('d7cec731-1a74-4d29-aa5a-c31751d0361b', 'VIS-20260813154344', '924c205a-cb14-45e9-aefa-a4c5c00e1149', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-08 00:00:00.000', 'nyeri punggung bawah dengan diagnosa dokter tumor saraf', 'penurunan kemampuan fungsional e.c. tumor saraf', 'IR, tens, latihan', '', '2026-08-13 15:43:44.135', '2026-08-13 15:43:44.135', NULL),
('d909dd5e-b3b9-44c1-a0d5-dfa0bde23bf8', 'VIS-20260815151941', 'a8288fd5-e790-4bda-bcc4-037047703239', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-11-27 00:00:00.000', 'Spasme erectorspine, siatic, gastroc nemeus', 'HNP lumbal', 'IR, tens, latihan', '', '2026-08-15 15:19:41.415', '2026-08-15 15:19:41.415', NULL),
('da7b3a8d-4f20-461a-bc69-badca8771f16', 'VIS-20260815155719', '0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-10 00:00:00.000', 'keterbatasan f/e siku', 'Fraktur klomplit os ulna sinistra (Antebrachii)', 'ir,tens,tl', '', '2026-08-15 15:57:19.437', '2026-08-15 15:57:19.437', NULL),
('dacc5940-e64e-4de3-878a-f0a7dd9297c7', 'VIS-20260813155519', 'e8541123-2c8b-4195-90a9-f4b48bb6e8f1', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-07 00:00:00.000', 'duduk sholat sakit, kencang area betis, tangan kesemutan kedua tangan', 'syndrom popliteal, OA dextra grade 1', 'IR, tens, latihan', '', '2026-08-13 15:55:19.796', '2026-08-13 15:55:19.796', NULL),
('dbc99732-9f72-4c13-bd7f-9154e18331e4', 'VIS-20260815153548', '0c7f3af1-3573-48a9-a0cc-1998f0625d07', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-17 00:00:00.000', 'belum mampu merangkak\nbelum mampu duduk mandiri\nbelum mampu berdiri mandiri ', 'gdd', 'ir,tens,tl', '', '2026-08-15 15:35:48.010', '2026-08-15 15:35:48.010', NULL),
('dc282ae0-f21d-420e-a3a6-320cb1ae5ec9', 'VIS-20260813160050', '2babd10a-d12f-4970-8ca6-1fa1486cb9e1', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-06 00:00:00.000', 'jari 3 dan 4 tidak bisa menggenggam, sakit di area pip incp, akhir maret lepas pen, dikasih splinting di RS Karyadi Semarang', 'Post operasi', 'IR, tens', '', '2026-08-13 16:00:50.996', '2026-08-13 16:00:50.996', NULL),
('ddfef068-a2bc-4e6d-8e98-c5e457f56620', 'VIS-20260815135953', '5b5c9583-5878-4fa7-94e6-3d84a6102740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-07 00:00:00.000', 'lutut sakit, kalau jalan ke arah luar siku lemas', 'post stroke hemiparase dx', 'IR, tens, latihan,massage', '', '2026-08-15 13:59:53.243', '2026-08-15 13:59:53.243', NULL),
('e0787b38-a6bd-487f-9333-5abd220a9f97', 'VIS-20260813140750', '9998c170-e052-41b0-b71e-b99e84f944a6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-31 00:00:00.000', 'nyeri leher, gerakan terbatas untuk tengok kanan dan kiri, nyeri punggung bawah', 'CRS', 'tens, IR, massage', '', '2026-08-13 14:07:50.123', '2026-08-13 14:07:50.123', NULL),
('e10d568e-384c-4f2f-b43b-59297f2228ae', 'VIS-20260815150614', '01086372-ce8e-45b5-beca-49d11d41112f', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-01-29 00:00:00.000', 'Bell\'s palsy, kelemahan otot dx, spasme otot sinistra, spasme otot stm, facial', 'Bell\'s palsy', 'IR, tens, massage, latihan', '', '2026-08-15 15:06:14.391', '2026-08-15 15:06:14.391', NULL),
('e134372b-0a3a-46c2-a5ab-7fc938c51349', 'VIS-20260813155422', 'e8541123-2c8b-4195-90a9-f4b48bb6e8f1', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-31 00:00:00.000', 'syndrome poplitea dextra nyeri area lateral, spasme quadricep, hamstring, kelemahan otot hamstring, atrofi otot', 'syndrom popliteal, OA dextra grade 1', 'IR, tens, latihan', '', '2026-08-13 15:54:22.532', '2026-08-13 15:54:22.532', NULL),
('e39de2ac-43b8-4ff6-ae73-27316d36120b', 'VIS-20260817161527', 'ddae9cb1-6529-4632-8fb1-3f34cf913fbe', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-31 00:00:00.000', 'belum mampu duduk mandiri, belum mampu posisi jongkok dan merangkak', 'GDD', 'tens, massage, manual', '', '2026-08-17 16:15:27.627', '2026-08-17 16:15:27.627', NULL),
('e50326fe-e4d0-42f1-ae7f-a2f157734a6e', 'VIS-20260813140310', '9998c170-e052-41b0-b71e-b99e84f944a6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-15 00:00:00.000', 'nyeri bagian kedua pantat, saat duduk lama dan jalan', 'sciatica, winging scapula', 'tens, IR, latihan, massage', '', '2026-08-13 14:03:10.551', '2026-08-13 14:03:10.551', NULL),
('e5b01220-897c-4f0c-843d-81cd85e1f3d5', 'VIS-20260813140845', '9998c170-e052-41b0-b71e-b99e84f944a6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-07 00:00:00.000', 'dislokasi hip', 'SIJ', 'tens, IR, massage', '', '2026-08-13 14:08:45.911', '2026-08-13 14:08:45.911', NULL),
('e867fc88-5f96-428e-96a2-5d361d82474d', 'VIS-20260813152436', '2e693b0c-d006-4418-a2b6-7bfbfe207a5a', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-16 00:00:00.000', 'post operasi', 'HNP cervical type c, spine', 'IR, tens, latihan', '', '2026-08-13 15:24:36.561', '2026-08-13 15:24:36.561', NULL),
('e8d60293-d502-41a7-b18a-7e5d1da78d62', 'VIS-20260817162958', '663f1026-4506-42d0-aa85-b28123c2bfef', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-08-06 00:00:00.000', 'bahu kanan sakit, sakit di area pangkal paha', 'post op total hip replacement, shoulder impigment dx', 'IR, tens, massage, streaching', '', '2026-08-17 16:29:58.397', '2026-08-17 16:29:58.397', NULL),
('eac9bd2e-c326-488a-839a-1fb956e57740', 'VIS-20260815145505', '36f73a94-af14-4f08-bae5-63320a2ef4f4', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-04-09 00:00:00.000', 'nyeri berdiri lama', 'subluksasi', 'ir,tens,tl', '', '2026-08-15 14:55:05.220', '2026-08-15 14:55:05.220', NULL),
('eb4389fb-7136-4c26-84dc-e5babd845fe0', 'VIS-20260819033615', 'fad1eeb9-2d5f-488a-9e21-a1185371645a', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-17 00:00:00.000', 'hemiparase, nyeri punggung bagian kanan, menjalar hingga ke kaki kiri', 'LBP, CRS', 'IR, tens, latihan', '', '2026-08-19 03:36:15.840', '2026-08-19 03:36:15.840', NULL),
('ec79c421-8533-4689-804c-0bfe53f26964', 'VIS-20260817162148', '2bf613e1-3b79-464e-b22e-ebbffcfe0432', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-15 00:00:00.000', 'spasme, kurva skolio belum berkurang', 'skoliosis tipe  S', 'IR, tens, massage, latihan', '', '2026-08-17 16:21:48.325', '2026-08-17 16:21:48.325', NULL),
('edf0d2e1-5908-45a8-99fe-5566ff109041', 'VIS-20260813141714', 'a87201f0-6bad-4234-9a9c-601aebafec8b', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-15 00:00:00.000', 'pantat kiri sakit, nyeri punggung bawah, L5-S1, telapak kaki kiri kadang sakit', 'skoliosis tipe c, achilles tendinitis', 'IR, tens, latihan, massage', '', '2026-08-13 14:17:14.087', '2026-08-13 14:17:14.087', NULL),
('eef19b9e-8b40-4d6e-88af-f14e79989dfe', 'VIS-20260819031351', 'c6372875-9dbc-4b83-a724-c8cc43e243c0', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-05 00:00:00.000', 'nhyeri leher menjalar, kedua tungkai pegal-pegal', 'LBP, cervical stenosis', 'IR, tens, latihan, massage', '', '2026-08-19 03:13:51.072', '2026-08-19 03:13:51.072', NULL),
('ef25a829-1cf7-402b-9661-ca9efb781e7f', 'VIS-20260813141836', 'a87201f0-6bad-4234-9a9c-601aebafec8b', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-17 00:00:00.000', 'nyeri pada bagian telapak kaki kiri', 'achilles tendinitis', 'IR, tens, latihan, massage, us', '', '2026-08-13 14:18:36.755', '2026-08-13 14:18:36.755', NULL),
('efacd8c6-ec8b-4d9c-9a8f-2445e291e700', 'VIS-20260817160229', '5039a998-ce57-4730-810e-09ffffd80636', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-08 00:00:00.000', 'mata kiri sudah lumayan bisa menutup, pipi kanan masih terasa tebal, mata kanan masih terasa perih dan berair', 'Bell\'s palsy', 'IR, tens, massage, mirror exercise', '', '2026-08-17 16:02:29.355', '2026-08-17 16:02:29.355', NULL),
('f140ae14-31a9-4280-b721-5c6c9e95cc1c', 'VIS-20260815144822', 'b479e9ae-fe99-47bc-bca3-67fd1f200740', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-08-01 00:00:00.000', 'kebas tangan kanan, spasme lengan atas dan lengan bawah, ujung jari jempol', 'post op', 'ir,tens,tl', '', '2026-08-15 14:48:22.678', '2026-08-15 14:48:22.678', NULL),
('f1e1f3e6-1fb8-4f04-aa30-31c0566f8e00', 'VIS-20260815144031', '15d902ee-0133-4af9-a67b-f08992ba2bae', 'f6917731-067d-4df1-96cc-444f9c769909', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-11 00:00:00.000', 'Sudah mampu seimbang duduk tanpa sandaran, mampu mempertahankan kaki kiri saat duduk, belum mampu menggerakkan  ekstremitas atas', 'Hemiparase sinistra', 'IR, tens, latihan', '', '2026-08-15 14:40:31.605', '2026-08-15 14:40:31.605', NULL),
('f2879bde-b1e3-4f1d-8427-476a2b0dde97', 'VIS-20260819032538', 'f0ca4dfa-9824-4794-a1a9-036048b186bf', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-02-21 00:00:00.000', 'OA genu dx sn, nyeri lutut kanan dan kiri menjalar hingga ke telapak kaki, punggung bawah nyeri menjalar ke kaki', 'OA genu LBP', 'IR, tens, latihan', '', '2026-08-19 03:25:38.650', '2026-08-19 03:25:38.650', NULL),
('f380df08-5782-4d57-bfe0-b53c3d75f045', 'VIS-20260819032028', 'f0ca4dfa-9824-4794-a1a9-036048b186bf', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-01-31 00:00:00.000', 'C3-4, 5-6 bulging discus, VL3-S1 protucio diskus stenosis, osteoartritis', 'OA genu dx dan sn, stenosis', 'IR, latihan, tens', '', '2026-08-19 03:20:28.393', '2026-08-19 03:20:28.393', NULL),
('f5cdac2e-d4bd-4aa2-8091-3ddafabb1020', 'VIS-20260819025958', '4d08b2ca-2f49-4dd9-b75d-d616d106247d', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-03-16 00:00:00.000', 'upper body (fokus), chest press 3x10, lat pulldown 3x12, dumbell shoulder 3x10, bicep curl 3x12, tricep pushdown 3x12', 'perbaikan postur', 'IR, tens, latihan, tapping', '', '2026-08-19 02:59:58.825', '2026-08-19 02:59:58.825', NULL),
('f8b30fe7-6355-4716-bc65-ac7ac0ebd393', 'VIS-20260813151830', 'e2fa59b5-ade2-4587-bd00-58204f977003', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2025-10-24 00:00:00.000', 'hemiparase dextra AGB', 'hemiparase', 'IR, tens, latihan', '', '2026-08-13 15:18:30.915', '2026-08-13 15:18:30.915', NULL),
('f97c1749-e7b2-4031-bd11-169be31ddb0a', 'VIS-20260813150045', '50e9848c-3a3c-4598-8220-4ba0969ddcfc', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-05-17 00:00:00.000', 'nyeri area jempol, bunyik klik, anstabil jempol', 'trigger finger, dislokasi', 'reposisi, IR, tens, latihan, tapping', '', '2026-08-13 15:00:45.064', '2026-08-13 15:00:45.064', NULL),
('f9cbc6cb-900d-4041-91bf-e0f759290d6e', 'VIS-20260817162851', '663f1026-4506-42d0-aa85-b28123c2bfef', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', '8d9a532f-e42a-4776-88c1-30b9c6289af7', NULL, '2026-07-16 00:00:00.000', 'saat posisi duduk masih terasa sakit di bagian pinggang, masih berat untuk jalan, bahu kanan sakit', 'post op total, HNP', 'IR, tens, massage, streaching', '', '2026-08-17 16:28:51.556', '2026-08-17 16:28:51.556', NULL),
('fb25e873-1ac7-4bda-997d-13bc5c1ad361', 'VIS-20260817161808', 'ddae9cb1-6529-4632-8fb1-3f34cf913fbe', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-12 00:00:00.000', 'sudah mampu duduk mandirinamun masih butuh bantuan', 'GDD', 'tens, IR, latihan, massage', '', '2026-08-17 16:18:08.367', '2026-08-17 16:18:08.367', NULL),
('fcf7ac79-77b7-4bac-b193-d8f82566e91c', 'VIS-20260817161627', 'ddae9cb1-6529-4632-8fb1-3f34cf913fbe', '6c197fd5-8076-4164-882e-594fcd91fc33', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-08-06 00:00:00.000', 'belum mampu duduk mandiri, belum mampu jongkok dan merangkak', 'GDD', 'tens, massage, latihan', '', '2026-08-17 16:16:27.718', '2026-08-17 16:16:27.718', NULL),
('fdd69a00-04ce-442e-92a1-3fd0aab2efeb', 'VIS-20260813140429', '9998c170-e052-41b0-b71e-b99e84f944a6', 'f6917731-067d-4df1-96cc-444f9c769909', 'cb95071c-66de-4a05-9ba7-7946201104a5', NULL, '2026-07-17 00:00:00.000', 'nyeri bahu kanan, nyeri bagian kedua pantat', 'sciatica, winging scapula, CRS', 'tens, IR, latihan, massage', '', '2026-08-13 14:04:29.400', '2026-08-13 14:04:29.400', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notifiable_type` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notifiable_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `read_at` datetime(3) DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pain_assessments`
--

CREATE TABLE `pain_assessments` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `therapy_session_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pain_scale` bigint NOT NULL,
  `pain_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pain_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `medical_record_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` datetime(3) DEFAULT NULL,
  `patient_category_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_type` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phone` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `marital_status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `emergency_contact_name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `emergency_contact_phone` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `medical_history` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `allergies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `medical_record_number`, `nik`, `name`, `birth_date`, `patient_category_id`, `gender_id`, `blood_type`, `address`, `phone`, `email`, `occupation`, `marital_status`, `emergency_contact_name`, `emergency_contact_phone`, `medical_history`, `allergies`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01086372-ce8e-45b5-beca-49d11d41112f', 'RM-20260813032826', '0', 'Rosliana', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Klampok Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:28:26.325', '2026-08-13 03:28:26.325', NULL),
('0355c51b-195a-4cf3-9521-18441dfc4b34', 'RM-20260813034403', '0', 'Wasriah', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Larangan Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:44:03.607', '2026-08-13 03:44:03.607', NULL),
('058d3054-1f40-49ac-80e4-e93ab453dd4f', 'RM-20260813033442', '0', 'Nafis', '2004-09-20 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Kepatihan Klampok', '085771035598', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-13 03:34:42.615', '2026-08-13 15:14:24.784', NULL),
('0a741de5-ee1d-43a6-97ad-3fc8ccb02e4d', 'RM-20260813034447', '0', 'Alpah Humamah', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Kersana Brebes', '0', NULL, 'ASN Guru', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:44:47.285', '2026-08-13 03:44:47.285', NULL),
('0c7f3af1-3573-48a9-a0cc-1998f0625d07', 'RM-20260813034318', '0', 'Zhafiar', '2026-08-13 00:00:00.000', '30c836e4-1355-47ca-985c-5833c0d5a6c9', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Griya Satria Jatibarang', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-13 03:43:18.547', '2026-08-13 03:43:18.547', NULL),
('148fe8e2-de3c-45da-9d8e-34494c984d4b', 'RM-20260813032524', '0', 'Umiyati', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Tegalglagah Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:25:24.765', '2026-08-13 03:25:24.765', NULL),
('15d902ee-0133-4af9-a67b-f08992ba2bae', 'RM-20260815143852', '0', 'Tutik Hidayati', '2026-08-15 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Wangandalem Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-15 14:38:52.051', '2026-08-15 14:38:52.051', NULL),
('16ee2292-9842-4aa4-a1a3-ca986d95a649', 'RM-20260813160137', '0', 'Ozi', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Pebatan Brebes', '0', NULL, 'Pelajar', 'Belum Menikah', '', '', '', '', '2026-08-13 16:01:37.968', '2026-08-13 16:01:37.968', NULL),
('22356324-ae52-4b1b-a243-a066e6f39509', 'RM-20260812153251', '0', 'Khorisah', '1970-10-02 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Randusanga kulon', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-12 15:32:51.290', '2026-08-12 15:32:51.290', NULL),
('230afbde-f27d-472a-8033-3b420cff7fa6', 'RM-20260817160427', '0', 'Nurul Aeni', '1961-05-19 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Tanjungsari  brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-17 16:04:27.688', '2026-08-17 16:04:27.688', NULL),
('24a41572-a61f-4fcc-a2a8-51300fb21151', 'RM-20260813034720', '0', 'Khoriyah', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Larangan Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:47:20.895', '2026-08-13 03:47:20.895', NULL),
('2ac75a00-5e7d-4268-904e-e2861a0bcb00', 'RM-20260813033046', '0', 'Murtini', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Desa Sikancil Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:30:46.198', '2026-08-13 03:30:46.198', NULL),
('2babd10a-d12f-4970-8ca6-1fa1486cb9e1', 'RM-20260813155911', '0', 'Sigih', '2002-04-24 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Songgom Brebes', '0', NULL, 'Guru PJOK', 'Belum Menikah', '', '', '', '', '2026-08-13 15:59:11.393', '2026-08-13 15:59:11.393', NULL),
('2bf613e1-3b79-464e-b22e-ebbffcfe0432', 'RM-20260813034941', '0', 'Lia', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Bangsri Brebes', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-13 03:49:41.547', '2026-08-13 03:49:41.547', NULL),
('2e693b0c-d006-4418-a2b6-7bfbfe207a5a', 'RM-20260813034122', '0', 'Imbang', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Perumahan Saphire Brebes', '0', NULL, 'Anggota Dewan Brebes', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:41:22.448', '2026-08-13 03:41:22.448', NULL),
('31cc0dc8-a836-4e07-8e95-fce656c590d0', 'RM-20260813032226', '0', 'Mumtaz', '2026-08-13 00:00:00.000', '30c836e4-1355-47ca-985c-5833c0d5a6c9', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Pesantunan Brebes', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-13 03:22:26.420', '2026-08-13 03:22:26.420', NULL),
('36f73a94-af14-4f08-bae5-63320a2ef4f4', 'RM-20260813034247', '0', 'Aji Ismanto', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Banjaranyar Brebes', '0', NULL, 'Wiraswasta', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:42:47.158', '2026-08-13 03:42:47.158', NULL),
('3a36756e-04b0-4cd2-9f3c-b2de3c94bc1f', 'RM-20260817160829', '0', 'Tri Aji Purnomo', '2001-03-18 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Wanacala Songgom Brebes', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-17 16:08:29.866', '2026-08-17 16:08:29.866', NULL),
('44d982bd-0a8b-48b6-b583-c56e7fd58f4c', 'RM-20260813034800', '0', 'Sofiyan', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Bangsri Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:48:00.956', '2026-08-13 03:48:00.956', NULL),
('4d08b2ca-2f49-4dd9-b75d-d616d106247d', 'RM-20260813032627', '0', 'Irsyad', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Kota Baru Brebes', '0', NULL, 'Pelajar', 'Belum Menikah', '', '', '', '', '2026-08-13 03:26:27.179', '2026-08-13 03:26:27.179', NULL),
('4ff67d8c-f6f9-4818-b2d0-686efa3965db', 'RM-20260813031547', '0', 'Ahmad Ashif Barhaya', '2016-01-04 00:00:00.000', '30c836e4-1355-47ca-985c-5833c0d5a6c9', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Kemiriamba Jatibarang', '0', NULL, 'Pelajar', 'Belum Menikah', '', '', '', '', '2026-08-13 03:15:47.176', '2026-08-13 13:58:28.121', NULL),
('5039a998-ce57-4730-810e-09ffffd80636', 'RM-20260817155918', '0', 'Suirah', '1983-06-04 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Wlahar Larangan Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-17 15:59:18.737', '2026-08-17 15:59:18.737', NULL),
('50e9848c-3a3c-4598-8220-4ba0969ddcfc', 'RM-20260813032028', '0', 'Wawan Darmawan', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Sigambir Brebes', '081908919032', NULL, 'ASN Guru', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:20:28.424', '2026-08-13 03:20:28.424', NULL),
('512dbc6b-039a-4093-a0f5-889829b81ea2', 'RM-20260813032755', '0', 'Anis', '2026-08-13 00:00:00.000', '30c836e4-1355-47ca-985c-5833c0d5a6c9', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Bhayangkara Residence Klampok', '0', NULL, 'Pelajar', 'Belum Menikah', '', '', '', '', '2026-08-13 03:27:55.112', '2026-08-13 03:27:55.112', NULL),
('519f4084-c650-4ac4-81d0-cea245c2fead', 'RM-20260813155657', '0', 'Raya Rambu Rabani', '2020-07-24 00:00:00.000', '30c836e4-1355-47ca-985c-5833c0d5a6c9', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'kemurang kulon brebes', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-13 15:56:57.328', '2026-08-13 15:56:57.328', NULL),
('5b5c9583-5878-4fa7-94e6-3d84a6102740', 'RM-20260815135620', '0', 'Jenuddin', '2026-08-15 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Tegalglagah Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-15 13:56:20.961', '2026-08-15 13:56:20.961', NULL),
('5ef0ba0a-849e-4279-b9b9-8671fff27713', 'RM-20260813033924', '0', 'Dinda', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Pasar Batang Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:39:24.642', '2026-08-13 03:39:24.642', NULL),
('6176d9bc-5935-4bf5-bd9f-525ce81f8ed5', 'RM-20260813153815', '0', 'Kusriningsih', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Tegalglagah Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 15:38:15.150', '2026-08-13 15:38:15.150', NULL),
('624cedb7-2fe4-48dc-acc4-ed1e0b6db39b', 'RM-20260813032114', '0', 'Suryani', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Bangsri Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:21:14.535', '2026-08-13 03:21:14.535', NULL),
('6587d397-c3d5-49f7-9a14-283d5e8d665a', 'RM-20260815160040', '0', 'darto', '2026-08-15 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'larangan', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-15 16:00:40.047', '2026-08-15 16:00:40.047', NULL),
('663f1026-4506-42d0-aa85-b28123c2bfef', 'RM-20260813034602', '0', 'Daumi', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Klampok Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:46:02.396', '2026-08-13 03:46:02.396', NULL),
('693afc6a-7ac4-4ed0-9acd-1b563c43c861', 'RM-20260813032706', '0', 'Indah Maghfiroh', '1995-02-11 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Pasar Batang Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:27:06.840', '2026-08-13 14:50:10.723', NULL),
('6c985fe1-b8a3-474d-9e20-3d07b7a7cb4e', 'RM-20260817155626', '0', 'Ulfah Hayati', '1998-04-11 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Pasar Batang Brebes', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-17 15:56:26.925', '2026-08-17 15:56:26.925', NULL),
('6d8ae23c-3956-473f-9e0a-17fc9ab609f6', 'RM-20260813032401', '0', 'Warnipah', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Pakijangan Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:24:01.161', '2026-08-13 03:24:01.161', NULL),
('77659db2-68f8-49d4-b9ab-f5f62f1dc589', 'RM-20260813035145', '0', 'Uti Rina', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Kota Baru Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:51:45.011', '2026-08-13 03:51:45.011', NULL),
('7e2b6a4e-3a57-483e-b393-7ac103fce18d', 'RM-20260815141313', '0', 'Keenan', '2026-08-15 00:00:00.000', '30c836e4-1355-47ca-985c-5833c0d5a6c9', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Bhayangkara Residence Klampok', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-15 14:13:13.947', '2026-08-15 14:13:13.947', NULL),
('81a3dda4-ae33-4515-a004-93729361b235', 'RM-20260813032152', '0', 'Dian', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Ketanggungan Brebes', '0', NULL, 'Pelajar', 'Belum Menikah', '', '', '', '', '2026-08-13 03:21:52.946', '2026-08-13 03:21:52.946', NULL),
('8708b28e-bbc4-4eb4-afc0-c376db31b5fb', 'RM-20260813033312', '0', 'Puji Yuliarti', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Klampok Brebes', '089653720553', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:33:12.533', '2026-08-13 15:08:00.642', NULL),
('8db78ac2-84c3-4ea1-8de5-3e853045d7e5', 'RM-20260815140130', '0', 'Istiyanah', '2026-08-15 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Klampok Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-15 14:01:30.762', '2026-08-15 14:01:30.762', NULL),
('924c205a-cb14-45e9-aefa-a4c5c00e1149', 'RM-20260813154223', '0', 'Saskia Aditya Meka', '2006-12-14 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Dk Krasak Brebes', '', NULL, 'Pelajar', 'Belum Menikah', '', '', '', '', '2026-08-13 15:42:23.160', '2026-08-13 15:42:23.160', NULL),
('9998c170-e052-41b0-b71e-b99e84f944a6', 'RM-20260813031647', '0', 'Nana Irsalina', '1989-12-07 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Bojong Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:16:47.606', '2026-08-13 13:59:04.384', NULL),
('9b1ad0d6-d57d-45b8-81ec-ee00ff3fa1cc', 'RM-20260813033850', '0', 'Nur Khasanah', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Losari Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:38:50.066', '2026-08-13 03:38:50.066', NULL),
('9fa0787f-1d3c-496b-bf03-44adac3f7511', 'RM-20260813033234', '0', 'Ita Putri', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Perumahan Delima Klampok', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:32:34.774', '2026-08-13 03:32:34.774', NULL),
('a1fe61c8-19c6-411b-8bb8-690d90c461ff', 'RM-20260813032552', '0', 'Murniasih', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Klampok Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:25:52.883', '2026-08-13 03:25:52.883', NULL),
('a7994e20-0dcd-4652-83b6-890c1fffabc1', 'RM-20260815143347', '0', 'Dahuri', '2026-08-15 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Grinting Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-15 14:33:47.360', '2026-08-15 14:33:47.360', NULL),
('a8288fd5-e790-4bda-bcc4-037047703239', 'RM-20260813033119', '0', 'Warningsih', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Kota Baru Brebes', '0', NULL, 'Ibu rumah tangga', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:31:19.525', '2026-08-13 03:31:19.525', NULL),
('a87201f0-6bad-4234-9a9c-601aebafec8b', 'RM-20260813031744', '0', 'Ninis', '2001-05-12 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Bangsri Brebes', '0', NULL, 'mahasiswa', 'Belum Menikah', '', '', '', '', '2026-08-13 03:17:44.429', '2026-08-13 14:12:37.355', NULL),
('b479e9ae-fe99-47bc-bca3-67fd1f200740', 'RM-20260813034911', '0', 'Prita', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Jatibarang', '0', NULL, 'ASN Puskesmas', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:49:11.484', '2026-08-13 03:49:11.484', NULL),
('b4a69560-f33e-4562-9c5f-7b789567b707', 'RM-20260813154619', '0', 'Arifiani', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Perumahan Dedy jaya Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 15:46:19.132', '2026-08-13 15:46:19.132', NULL),
('c3cb3e96-c593-4a02-bf58-2ba8b5231482', 'RM-20260813032906', '0', 'Faridah', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Griya Satria Jatibarang', '0', NULL, 'ASN Guru', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:29:06.915', '2026-08-13 03:29:06.915', NULL),
('c61d04e0-c101-491e-b2b8-a88ab5e18890', 'RM-20260813031822', '0', 'Neni Cahyani', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Bangsri Brebes', '0', NULL, 'Bidan RSUD Brebes', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:18:22.725', '2026-08-13 03:18:22.725', NULL),
('c6372875-9dbc-4b83-a724-c8cc43e243c0', 'RM-20260813034524', '0', 'Kurniasih', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Pasar Batang Brebes', '0', NULL, 'Perangkat Desa', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:45:24.006', '2026-08-13 03:45:24.006', NULL),
('c98cbbea-bb99-4bb9-a93d-d449e1a9cc37', 'RM-20260813034834', '0', 'Warkiyah', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Desa Glunggung', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:48:34.221', '2026-08-13 03:48:34.221', NULL),
('cc2900cf-379f-44e3-8791-7cec78326218', 'RM-20260817155508', '0', 'Arif Gustian', '1996-08-01 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Perumahan  Delima VI Blok B19 Pesantunan Brebes', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-17 15:55:08.595', '2026-08-17 15:55:08.595', NULL),
('cc742b03-6615-49a0-912c-9e9dc38edbc7', 'RM-20260813032439', '0', 'Lisa Shelia Tifani', '2001-07-24 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Kalialang Jatibarang', '081391038565', NULL, 'Perawat RSUD Brebes', 'Belum Menikah', '', '', '', '', '2026-08-13 03:24:39.558', '2026-08-13 15:05:25.523', NULL),
('d745e66c-a03a-4d66-adc9-3d83d35d88b8', 'RM-20260813033200', '0', 'Rasim', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Luwung Gede', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:32:00.537', '2026-08-13 03:32:00.537', NULL),
('d963396d-dcfa-4456-b61b-419e2762491b', 'RM-20260821014308', '0', 'Mutia', '2026-08-19 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Petunjungan Bulakamba Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-21 01:43:08.062', '2026-08-21 01:43:08.062', NULL),
('d99329c1-03ca-4da5-848e-c4a225f95534', 'RM-20260813154952', '0', 'Rafasya Attahya', '2025-11-20 00:00:00.000', '30c836e4-1355-47ca-985c-5833c0d5a6c9', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Tanjung Brebes', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-13 15:49:52.275', '2026-08-13 15:51:15.503', NULL),
('da90990b-64b0-40fe-afc7-2de4e3427d9c', 'RM-20260813033352', '0', 'Toipah', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Pemaron Brebes', '0', NULL, 'Pedagang', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:33:52.587', '2026-08-13 03:33:52.587', NULL),
('db0c34df-2681-4d0b-867f-41b60c7d3912', 'RM-20260813031912', '0', 'Mashudi', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Jl. Ahmad Dahlan Brebes', '0', NULL, 'Pensiunan Dosen', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:19:12.817', '2026-08-13 03:19:12.817', NULL),
('ddae9cb1-6529-4632-8fb1-3f34cf913fbe', 'RM-20260813035050', '0', 'Nashwa', '2026-08-13 00:00:00.000', '30c836e4-1355-47ca-985c-5833c0d5a6c9', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Jatibarang', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-13 03:50:50.439', '2026-08-13 03:50:50.439', NULL),
('e2fa59b5-ade2-4587-bd00-58204f977003', 'RM-20260813033535', '0', 'Samsudin', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Luwung Ragi Bulakamba', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:35:35.558', '2026-08-13 03:35:35.558', NULL),
('e724a546-fe7d-4043-ab0f-2569601427c2', 'RM-20260813035018', '0', 'Subandriyo', '2026-08-13 00:00:00.000', '9d6a6088-7e47-41e7-bc47-80217de9aeaf', '9d4475e5-93a4-11f1-88fb-5254005bb1a8', '', 'Tanjung Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:50:18.325', '2026-08-13 03:50:18.325', NULL),
('e8541123-2c8b-4195-90a9-f4b48bb6e8f1', 'RM-20260813155228', '0', 'Siti Saroh', '1979-06-03 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Tanjung Brebes', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 15:52:28.145', '2026-08-13 15:52:28.145', NULL),
('ebf55c0c-4e5e-4c22-a92c-7a253302b896', 'RM-20260813034639', '0', 'Sri Budiarti', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Bulakamba', '0', NULL, '-', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:46:39.854', '2026-08-13 03:46:39.854', NULL),
('f0ca4dfa-9824-4794-a1a9-036048b186bf', 'RM-20260813034038', '0', 'Retno Sudarwati', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Perumahan KPT Brebes', '0', NULL, 'Pensiunan ASN', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:40:38.989', '2026-08-13 03:40:38.989', NULL),
('f769f9ca-28d3-4007-a122-6b975a02b994', 'RM-20260813034158', '0', 'Ratna Nur Fasha', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Pesantunan Brebes', '0', NULL, '-', 'Belum Menikah', '', '', '', '', '2026-08-13 03:41:58.011', '2026-08-13 03:41:58.011', NULL),
('fad1eeb9-2d5f-488a-9e21-a1185371645a', 'RM-20260813033809', '0', 'Nur Arina', '2026-08-13 00:00:00.000', '2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', '9d447944-93a4-11f1-88fb-5254005bb1a8', '', 'Kota Baru Brebes', '0', NULL, 'PNS RSUD Brebes', 'Sudah Menikah', '', '', '', '', '2026-08-13 03:38:09.229', '2026-08-13 03:38:09.229', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patient_categories`
--

CREATE TABLE `patient_categories` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patient_categories`
--

INSERT INTO `patient_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
('2c7f30a5-62a1-4783-a4ff-07a0abb5c12d', 'Ny', '2026-08-09 11:09:19.622', '2026-08-09 11:09:19.622'),
('30c836e4-1355-47ca-985c-5833c0d5a6c9', 'Anak', '2026-08-13 03:14:55.220', '2026-08-13 03:14:55.220'),
('4e835bc5-012c-4240-9fce-5d89692dd06c', 'Ank', '2026-08-12 13:59:51.275', '2026-08-12 13:59:51.275'),
('9d6a6088-7e47-41e7-bc47-80217de9aeaf', 'Tn', '2026-08-09 11:09:29.001', '2026-08-09 11:09:29.001');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `appointment_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `therapy_session_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patient_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patient_name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `physiotherapist_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physiotherapist_name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payment_date` datetime(3) DEFAULT NULL,
  `payment_method` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subtotal` double DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_number`, `appointment_id`, `therapy_session_id`, `patient_id`, `patient_name`, `physiotherapist_id`, `physiotherapist_name`, `payment_date`, `payment_method`, `status`, `subtotal`, `discount`, `tax`, `total`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
('2ae91d47-39be-4fdf-bcd2-791eb6f850b5', 'INV-20260815141339', '6919dd0f-e8d9-49dc-9467-4143e97c94fc', '1ae5d563-10e2-43bb-b9d9-9524ba3550e0', '7e2b6a4e-3a57-483e-b393-7ac103fce18d', 'Keenan', 'cb95071c-66de-4a05-9ba7-7946201104a5', 'Chandra Arum Pramitha, S.Kes., Ftr.', '2026-08-15 08:00:00.000', 'cash', 'Lunas', 75000, 0, 0, 75000, '', '2026-08-15 14:13:39.424', '2026-08-15 14:20:58.231', NULL),
('340cf304-cbfe-43ed-9dd4-5cf6d9011ea4', 'INV-20260815141500', '8bc32aa8-7d82-427a-be27-baa8ed5de085', '39571039-0059-400a-b1f6-5bb9ad237509', '8db78ac2-84c3-4ea1-8de5-3e853045d7e5', 'Istiyanah', '8d9a532f-e42a-4776-88c1-30b9c6289af7', 'Widya Wurry Pratiwi, S.Ft.', '2026-08-15 11:00:00.000', 'cash', 'Lunas', 100000, 0, 0, 100000, '', '2026-08-15 14:15:00.008', '2026-08-15 14:26:46.800', NULL),
('861ff76d-3a75-4f02-8fae-19b3bd951d7f', 'INV-20260815141405', '112e5bd0-fdc6-4bd0-a142-ba69d39ef0c4', '0279b723-15b1-450c-843e-e9fcbc822125', '31cc0dc8-a836-4e07-8e95-fce656c590d0', 'Mumtaz', 'cb95071c-66de-4a05-9ba7-7946201104a5', 'Chandra Arum Pramitha, S.Kes., Ftr.', '2026-08-15 09:00:00.000', 'cash', 'Lunas', 75000, 0, 0, 75000, '', '2026-08-15 14:14:05.296', '2026-08-15 14:25:48.532', NULL),
('e95a2c2e-c805-4096-99f7-fe2d85e6421d', 'INV-20260815141518', '406de6e4-0a92-4097-abf1-c29c198decf3', 'fbaa1d19-6838-4be6-82a8-f5b8e0021561', '519f4084-c650-4ac4-81d0-cea245c2fead', 'Raya Rambu Rabani', 'cb95071c-66de-4a05-9ba7-7946201104a5', 'Chandra Arum Pramitha, S.Kes., Ftr.', '2026-08-15 14:00:00.000', 'cash', 'Lunas', 75000, 0, 0, 75000, '', '2026-08-15 14:15:18.355', '2026-08-15 14:27:30.014', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_master_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` bigint DEFAULT NULL,
  `price` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_details`
--

INSERT INTO `payment_details` (`id`, `payment_id`, `service_master_id`, `item_name`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`, `deleted_at`) VALUES
('2289ec2b-db09-41cf-9bc5-43a2b4a71222', '340cf304-cbfe-43ed-9dd4-5cf6d9011ea4', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'Muskuloskeletal', 1, 100000, 100000, '2026-08-15 14:15:00.008', '2026-08-15 14:15:00.008', NULL),
('7b9e3a5a-96b5-4d47-a353-55cd0111ef0a', '861ff76d-3a75-4f02-8fae-19b3bd951d7f', '6c197fd5-8076-4164-882e-594fcd91fc33', 'Pediatric', 1, 75000, 75000, '2026-08-15 14:14:05.296', '2026-08-15 14:14:05.296', NULL),
('853babfd-db7b-4b63-9758-cc9b6ce606ce', 'e95a2c2e-c805-4096-99f7-fe2d85e6421d', '6c197fd5-8076-4164-882e-594fcd91fc33', 'Pediatric', 1, 75000, 75000, '2026-08-15 14:15:18.355', '2026-08-15 14:15:18.355', NULL),
('c30103bd-075d-4b90-8d6d-6c7a53f2137c', '2ae91d47-39be-4fdf-bcd2-791eb6f850b5', '6c197fd5-8076-4164-882e-594fcd91fc33', 'Pediatric', 1, 75000, 75000, '2026-08-15 14:13:39.424', '2026-08-15 14:13:39.424', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `physiotherapists`
--

CREATE TABLE `physiotherapists` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `specialization` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gender` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `photo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `physiotherapists`
--

INSERT INTO `physiotherapists` (`id`, `name`, `specialization`, `sip`, `phone`, `email`, `address`, `gender`, `photo`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
('8d9a532f-e42a-4776-88c1-30b9c6289af7', 'Widya Wurry Pratiwi, S.Ft.', 'Neuro, Muskuloskeletal, Sports', 'NR', '085790573430', 'Widyawurry@fisio.com', 'Perumahan Bhayangkara Residence Blok A8, Kecamatan Wanasari, Kabupaten Brebes', 'P', NULL, 'active', '2026-08-12 15:30:38.351', '2026-08-12 15:30:38.351', NULL),
('cb95071c-66de-4a05-9ba7-7946201104a5', 'Chandra Arum Pramitha, S.Kes., Ftr.', 'Neuro, Muskuloskeletal, Pediatric', 'NR33292507017313', '085790573430', 'rummitha@fisio.com', 'Perumahan Bhayangkara Residence Blok A8, Kecamatan Wanasari, Kabupaten Brebes', 'P', NULL, 'active', '2026-08-12 12:22:05.312', '2026-08-13 04:19:59.293', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
('6527918f-9472-409d-a45d-8ec315a139ef', 'anak', '2026-08-09 16:19:37.641', '2026-08-09 16:19:37.641', NULL),
('6a77cf29-2017-48d6-b456-25a5728513ff', 'Umum', '2026-08-09 11:42:25.687', '2026-08-09 11:42:25.687', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_masters`
--

CREATE TABLE `service_masters` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `duration` bigint DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `base_price` double DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_masters`
--

INSERT INTO `service_masters` (`id`, `code`, `name`, `category`, `duration`, `description`, `base_price`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
('4f000c56-1ebc-496d-b368-af1ca6760337', 'SVC-20260812140947', 'Sports', 'Umum', 60, 'Manual, Alat, Latihan', 100000, 1, '2026-08-12 14:09:47.790', '2026-08-12 14:09:47.790', NULL),
('6c197fd5-8076-4164-882e-594fcd91fc33', 'SVC-20260811235333', 'Pediatric', 'anak', 45, 'alat & manual terapi', 75000, 1, '2026-08-11 23:53:33.560', '2026-08-12 12:34:24.260', NULL),
('c98d0efc-12fc-4598-8134-f747090d2672', 'SVC-20260815142022', 'Tapping', 'Umum', 1, 'Kinesiology Tapping', 20000, 1, '2026-08-15 14:20:22.301', '2026-08-15 14:20:22.301', NULL),
('da1e6ace-ebc7-4dce-85e3-63b0cf24b132', 'SVC-20260812123514', 'Muskuloskeletal', 'Umum', 60, 'Manual, Alat, Latihan', 100000, 1, '2026-08-12 12:35:14.300', '2026-08-12 12:35:14.300', NULL),
('f6917731-067d-4df1-96cc-444f9c769909', 'SVC-20260811110518', 'Neuro', 'Umum', 60, 'Manual, Alat, Latihan', 100000, 1, '2026-08-11 11:05:18.797', '2026-08-11 11:05:18.797', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `therapy_sessions`
--

CREATE TABLE `therapy_sessions` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patient_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physiotherapist_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_master_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_master_ids` json DEFAULT NULL,
  `therapy_date` datetime(3) DEFAULT NULL,
  `complaint` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `treatment_given` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `notes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `therapy_sessions`
--

INSERT INTO `therapy_sessions` (`id`, `appointment_id`, `patient_id`, `physiotherapist_id`, `service_master_id`, `service_master_ids`, `therapy_date`, `complaint`, `treatment_given`, `status`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0279b723-15b1-450c-843e-e9fcbc822125', '112e5bd0-fdc6-4bd0-a142-ba69d39ef0c4', '31cc0dc8-a836-4e07-8e95-fce656c590d0', 'cb95071c-66de-4a05-9ba7-7946201104a5', '6c197fd5-8076-4164-882e-594fcd91fc33', NULL, '2026-08-15 00:00:00.000', 'Tumbuh kembang', '-', 'completed', '', '2026-08-15 14:24:51.995', '2026-08-15 14:25:38.991', NULL),
('1ae5d563-10e2-43bb-b9d9-9524ba3550e0', '6919dd0f-e8d9-49dc-9467-4143e97c94fc', '7e2b6a4e-3a57-483e-b393-7ac103fce18d', 'cb95071c-66de-4a05-9ba7-7946201104a5', '6c197fd5-8076-4164-882e-594fcd91fc33', NULL, '2026-08-15 00:00:00.000', 'Postural', '-', 'completed', '', '2026-08-15 14:16:18.827', '2026-08-15 14:17:48.779', NULL),
('39571039-0059-400a-b1f6-5bb9ad237509', '8bc32aa8-7d82-427a-be27-baa8ed5de085', '8db78ac2-84c3-4ea1-8de5-3e853045d7e5', '8d9a532f-e42a-4776-88c1-30b9c6289af7', 'da1e6ace-ebc7-4dce-85e3-63b0cf24b132', NULL, '2026-08-15 00:00:00.000', 'Nyeri pinggang', '-', 'completed', '', '2026-08-15 14:26:12.231', '2026-08-15 14:26:41.230', NULL),
('fbaa1d19-6838-4be6-82a8-f5b8e0021561', '406de6e4-0a92-4097-abf1-c29c198decf3', '519f4084-c650-4ac4-81d0-cea245c2fead', 'cb95071c-66de-4a05-9ba7-7946201104a5', '6c197fd5-8076-4164-882e-594fcd91fc33', NULL, '2026-08-15 00:00:00.000', 'Tumbuh kembang', '-', 'completed', '', '2026-08-15 14:26:55.881', '2026-08-15 14:27:25.734', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `role` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `photo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime(3) DEFAULT NULL,
  `updated_at` datetime(3) DEFAULT NULL,
  `deleted_at` datetime(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `photo`, `created_at`, `updated_at`, `deleted_at`) VALUES
('17eeee16-40e7-4577-be25-66015952dc0b', 'Chandra Arum Pramitha, S.Kes., Ftr.', 'rummitha@fisio.com', '$2a$14$6ryfM18rg7HdZneuNo7jruQUrZSBheFtYnpkqs9RYlVl51XnW2gQu', 'fisioterapis', NULL, '2026-08-12 12:22:06.352', '2026-08-13 04:19:59.286', NULL),
('8cad96b4-9475-11f1-88fb-5254005bb1a8', 'Pemilik', 'arummyfisioterapi@pemilik.com', '$2a$10$tMSkbGuxl6yDQ3Q6YG7mSeT7bJhiEZyUhAEtwjfPT3hMY/j0fs7ka', 'admin', NULL, '2026-08-10 12:40:01.000', '2026-08-10 12:40:01.000', NULL),
('9bbe0324-c2e5-41f9-abe3-fcaa238c3ccf', 'Widya Wurry Pratiwi, S.Ft.', 'widyawurry@fisio.com', '$2a$14$tx2NesL0FzBKmsMOkFbxwuVoKmDVrV9f5oJGlipG5qfg9aDHCW0aq', 'fisioterapis', NULL, '2026-08-12 15:30:39.549', '2026-08-12 15:30:39.549', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_appointments_patient_id` (`patient_id`),
  ADD KEY `idx_appointments_physiotherapist_id` (`physiotherapist_id`),
  ADD KEY `idx_appointments_service_master_id` (`service_master_id`);

--
-- Indexes for table `exercise_programs`
--
ALTER TABLE `exercise_programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_exercise_programs_therapy_session_id` (`therapy_session_id`),
  ADD KEY `idx_exercise_programs_deleted_at` (`deleted_at`);

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jwt_blocklists`
--
ALTER TABLE `jwt_blocklists`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_medical_records_patient_id` (`patient_id`),
  ADD KEY `idx_medical_records_physiotherapist_id` (`physiotherapist_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pain_assessments`
--
ALTER TABLE `pain_assessments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pain_assessments_therapy_session_id` (`therapy_session_id`),
  ADD KEY `idx_pain_assessments_deleted_at` (`deleted_at`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `idx_password_reset_tokens_email` (`email`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_patients_patient_category_id` (`patient_category_id`),
  ADD KEY `idx_patients_gender_id` (`gender_id`);

--
-- Indexes for table `patient_categories`
--
ALTER TABLE `patient_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payments_appointment_id` (`appointment_id`),
  ADD KEY `idx_payments_therapy_session_id` (`therapy_session_id`),
  ADD KEY `idx_payments_patient_id` (`patient_id`),
  ADD KEY `idx_payments_physiotherapist_id` (`physiotherapist_id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payment_details_payment_id` (`payment_id`),
  ADD KEY `idx_payment_details_service_master_id` (`service_master_id`);

--
-- Indexes for table `physiotherapists`
--
ALTER TABLE `physiotherapists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_masters`
--
ALTER TABLE `service_masters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `therapy_sessions`
--
ALTER TABLE `therapy_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_therapy_sessions_appointment_id` (`appointment_id`),
  ADD KEY `idx_therapy_sessions_patient_id` (`patient_id`),
  ADD KEY `idx_therapy_sessions_physiotherapist_id` (`physiotherapist_id`),
  ADD KEY `idx_therapy_sessions_service_master_id` (`service_master_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_users_email` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_patients_category` FOREIGN KEY (`patient_category_id`) REFERENCES `patient_categories` (`id`),
  ADD CONSTRAINT `fk_patients_gender_data` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
