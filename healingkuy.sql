-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2025 at 04:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `healingkuy`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'system_init', 'Database initialized with sample data', NULL, NULL, '2025-11-03 18:51:33'),
(2, 2, 'reservation_create', 'New reservation created for Gunung Bromo', NULL, NULL, '2025-11-03 18:51:33'),
(3, 3, 'user_registration', 'New user registered: sari@healingkuy.id', NULL, NULL, '2025-11-03 18:51:33'),
(4, 2, 'profile_update', 'User profile updated', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 18:57:09'),
(5, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 18:59:29'),
(6, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 19:06:05'),
(7, 4, 'reservation', 'New reservation created: HK20251103ADBA99', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 19:06:18'),
(8, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 19:06:48'),
(9, 1, 'update_reservation', 'Updated reservation ID: 3 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 19:07:17'),
(10, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 19:07:32'),
(11, 4, 'checkin', 'Check-in successful for booking: HK20251103ADBA99', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 19:11:40'),
(12, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 19:24:52'),
(13, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 06:59:24'),
(14, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:00:06'),
(15, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:01:21'),
(16, 4, 'reservation', 'New reservation created: HK20251104102B1F', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:03:13'),
(17, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:04:38'),
(18, 1, 'update_reservation', 'Updated reservation ID: 4 to pending', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:04:59'),
(19, 1, 'update_reservation', 'Updated reservation ID: 4 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:05:07'),
(20, 4, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:05:37'),
(21, 4, 'checkin', 'Check-in successful for booking: HK20251104102B1F', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:05:54'),
(22, 5, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 07:11:41'),
(23, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-17 13:40:08'),
(24, 1, 'edit_service', 'Updated service: Gunung Bromo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-17 13:42:14'),
(25, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-18 07:03:01'),
(26, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-18 07:05:45'),
(27, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:10:45'),
(28, 1, 'edit_service', 'Updated service: Pantai Kuta Bali', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:12:02'),
(29, 1, 'edit_service', 'Updated service: Candi Borobudur', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:13:10'),
(30, 1, 'edit_service', 'Updated service: Raja Ampat', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:14:08'),
(31, 1, 'edit_service', 'Updated service: Danau Toba', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:15:09'),
(32, 1, 'edit_service', 'Updated service: Ubud', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:16:25'),
(33, 1, 'edit_service', 'Updated service: Komodo Island', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:18:02'),
(34, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:19:09'),
(35, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:37:58'),
(36, 1, 'edit_service', 'Updated service: Tana Toraja', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:39:19'),
(37, 1, 'edit_service', 'Updated service: Gunung Rinjani', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:40:07'),
(38, 1, 'edit_service', 'Updated service: Bunaken', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:42:42'),
(39, 1, 'edit_service', 'Updated service: Prambanan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:43:17'),
(40, 1, 'edit_service', 'Updated service: Derawan Islands', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:43:59'),
(41, 1, 'edit_service', 'Updated service: Kelimutu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:45:02'),
(42, 1, 'edit_service', 'Updated service: Tanjung Putting', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:45:43'),
(43, 1, 'edit_service', 'Updated service: Wae Rebo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:46:16'),
(44, 1, 'edit_service', 'Updated service: Kawah Putih', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:47:19'),
(45, 1, 'edit_service', 'Updated service: Taman Mini Indonesia Indah', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:49:23'),
(46, 1, 'edit_service', 'Updated service: Gili Trawangan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:52:09'),
(47, 1, 'edit_service', 'Updated service: Karimunjawa', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:53:04'),
(48, 1, 'edit_service', 'Updated service: Labuan Bajo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 01:53:47'),
(49, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:10:58'),
(50, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:23:37'),
(51, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:25:24'),
(52, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:42:54'),
(53, 6, 'reservation', 'New reservation created: HK20251120417FA1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:43:16'),
(54, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:44:23'),
(55, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:48:30'),
(56, 6, 'cancellation_request', 'Cancellation requested for reservation: 5', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:49:06'),
(57, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:49:52'),
(58, 1, 'approve_cancellation', 'Approved cancellation ID: 1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:50:09'),
(59, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 02:50:33'),
(60, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:33:47'),
(61, 1, 'edit_blog', 'Updated blog post: 10 Destinasi Wisata Alam Terbaik di Indonesia 2025', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:34:49'),
(62, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:35:35'),
(63, 6, 'reservation', 'New reservation created: HK2025112064EC5A', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:36:38'),
(64, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:37:00'),
(65, 1, 'update_reservation', 'Updated reservation ID: 6 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:37:15'),
(66, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:37:32'),
(67, 6, 'checkin', 'Check-in successful for booking: HK2025112064EC5A', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:37:47'),
(68, 6, 'reservation', 'New reservation created: HK2025112059493D', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:38:45'),
(69, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:38:59'),
(70, 1, 'update_reservation', 'Updated reservation ID: 7 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:39:11'),
(71, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:39:34'),
(72, 6, 'checkin', 'Check-in successful for booking: HK2025112059493D', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:39:44'),
(73, 6, 'profile_update', 'User profile updated', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 06:40:40'),
(74, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 09:38:16'),
(75, 6, 'reservation', 'New reservation created: HK202511200BC363', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 09:39:28'),
(76, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 09:39:42'),
(77, 1, 'update_reservation', 'Updated reservation ID: 8 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 09:40:12'),
(78, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 09:40:37'),
(79, 6, 'checkin', 'Check-in successful for booking: HK202511200BC363', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 09:40:50'),
(80, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 10:15:15'),
(81, 6, 'reservation', 'New reservation created: HK20251120858EBF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 11:41:28'),
(82, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 11:48:45'),
(83, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 11:53:34'),
(84, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 12:38:18'),
(85, 6, 'reservation', 'New reservation created: HK20251120AE4168', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 12:53:46'),
(86, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 12:54:08'),
(87, 1, 'update_reservation', 'Updated reservation ID: 10 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 12:54:21'),
(88, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 12:55:08'),
(89, 6, 'checkin', 'Check-in successful for booking: HK20251120AE4168', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 12:55:24'),
(90, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 12:56:01'),
(91, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:08:33'),
(92, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:09:24'),
(93, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:15:15'),
(94, 6, 'reservation', 'New reservation created: HK2025112023C9D0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:15:46'),
(95, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:16:00'),
(96, 1, 'update_reservation', 'Updated reservation ID: 11 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:16:11'),
(97, 1, 'update_reservation', 'Updated reservation ID: 9 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:16:16'),
(98, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:16:49'),
(99, 6, 'checkin', 'Check-in successful for booking: HK20251120858EBF', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:17:02'),
(100, 6, 'checkin', 'Check-in successful for booking: HK2025112023C9D0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:17:05'),
(101, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:17:24'),
(102, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:41:46'),
(103, 6, 'reservation', 'New reservation created: HK2025112029391C', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:42:10'),
(104, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:42:28'),
(105, 1, 'update_reservation', 'Updated reservation ID: 12 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:42:49'),
(106, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:50:58'),
(107, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:58:22'),
(108, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 13:59:17'),
(109, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 14:18:02'),
(110, 1, 'edit_hotel', 'Updated hotel: Hotel Bromo Permai', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 14:18:39'),
(111, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-20 14:19:00'),
(112, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 12:52:20'),
(113, 6, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 12:53:43'),
(114, 6, 'reservation', 'New reservation created: HK20251121B498A0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 12:55:07'),
(115, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 12:55:21'),
(116, 1, 'update_reservation', 'Updated reservation ID: 13 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 12:56:04'),
(117, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 10:34:54'),
(118, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:37:17'),
(119, 7, 'reservation', 'New reservation created: HK2025120557B4FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:39:01'),
(120, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:39:34'),
(121, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:42:40'),
(122, 7, 'checkin', 'Check-in successful for booking: HK2025120557B4FA', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:42:54'),
(123, 7, 'reservation', 'New reservation created: HK20251205F7D262', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:53:19'),
(124, 7, 'cancellation_request', 'Cancellation requested for reservation: 15', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:54:07'),
(125, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:54:45'),
(126, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:55:55'),
(127, 7, 'reservation', 'New reservation created: HK2025120520DA26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:57:06'),
(128, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:57:29'),
(129, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:58:43'),
(130, 7, 'reservation', 'New reservation created: HK2025120556BF51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 01:59:33'),
(131, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:00:01'),
(132, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:00:50'),
(133, 7, 'reservation', 'New reservation created: HK2025120567A311', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:01:26'),
(134, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:01:42'),
(135, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:02:27'),
(136, 7, 'checkin', 'Check-in successful for booking: HK2025120567A311', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:02:41'),
(137, 7, 'reservation', 'New reservation created: HK202512050062B0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:41:04'),
(138, 7, 'profile_update', 'User profile updated', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:41:55'),
(139, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:42:53'),
(140, 1, 'approve_cancellation', 'Approved cancellation ID: 2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:44:05'),
(141, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:45:12'),
(142, 7, 'checkin', 'Check-in successful for booking: HK202512050062B0', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:45:25'),
(143, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:46:39'),
(144, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:47:32'),
(145, 7, 'reservation', 'New reservation created: HK20251205D852D2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:47:57'),
(146, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:48:31'),
(147, 1, 'update_reservation', 'Updated reservation ID: 20 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:48:47'),
(148, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:49:22'),
(149, 7, 'checkin', 'Check-in successful for booking: HK20251205D852D2', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 02:49:30'),
(150, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:09:13'),
(151, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:14:09'),
(152, 7, 'reservation', 'New reservation created: HK202512051AA0CE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:14:25'),
(153, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:15:21'),
(154, 1, 'update_reservation', 'Updated reservation ID: 21 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:16:07'),
(155, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:16:34'),
(156, 7, 'checkin', 'Check-in successful for booking: HK202512051AA0CE', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:16:49'),
(157, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:17:27'),
(158, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:18:59'),
(159, 7, 'reservation', 'New reservation created: HK2025120517CE24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:19:13'),
(160, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:19:33'),
(161, 1, 'update_reservation', 'Updated reservation ID: 22 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:19:48'),
(162, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:20:47'),
(163, 7, 'reservation', 'New reservation created: HK2025120522AD25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:21:22'),
(164, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:21:58'),
(165, 1, 'update_reservation', 'Updated reservation ID: 23 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:22:11'),
(166, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 03:23:01'),
(167, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 06:14:58'),
(168, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 06:16:02'),
(169, 7, 'checkin', 'Check-in successful for booking: HK2025120517CE24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 06:17:03'),
(170, 7, 'checkin', 'Check-in successful for booking: HK2025120522AD25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 06:17:07'),
(171, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:26:48'),
(172, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:39:09'),
(173, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:44:58'),
(174, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:46:12'),
(175, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:51:58'),
(176, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:52:33'),
(177, 7, 'reservation', 'New reservation created: HK20251222728E98', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:53:11'),
(178, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:53:30'),
(179, 1, 'update_reservation', 'Updated reservation ID: 24 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:53:54'),
(180, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:54:42'),
(181, 7, 'checkin', 'Check-in successful for booking: HK20251222728E98', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:55:02'),
(182, 7, 'profile_update', 'User profile updated', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:55:15'),
(183, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:56:04'),
(184, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 08:57:03'),
(185, 1, 'edit_hotel', 'Updated hotel: Hotel Bromo Permai', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:09:30'),
(186, 1, 'edit_hotel', 'Updated hotel: Cemara Indah Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:10:08'),
(187, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:14:58'),
(188, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:15:48'),
(189, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:17:10'),
(190, 7, 'reservation', 'New reservation created: HK20251222F034C7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:17:35'),
(191, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:17:51'),
(192, 1, 'update_reservation', 'Updated reservation ID: 25 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:20:36'),
(193, 1, 'edit_hotel', 'Updated hotel: Lava View Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:26:58'),
(194, 1, 'edit_hotel', 'Updated hotel: Tabo Cottages', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:27:19'),
(195, 1, 'edit_hotel', 'Updated hotel: Samosir Village Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:27:37'),
(196, 1, 'edit_hotel', 'Updated hotel: Toba Heritage Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:28:02'),
(197, 1, 'edit_hotel', 'Updated hotel: Manohara Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:28:19'),
(198, 1, 'edit_hotel', 'Updated hotel: Amanjiwo Borobudur', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:28:39'),
(199, 1, 'edit_hotel', 'Updated hotel: Borobudur Village', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:28:55'),
(200, 1, 'edit_hotel', 'Updated hotel: Yogyakarta Palace Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:29:20'),
(201, 1, 'edit_hotel', 'Updated hotel: Yogyakarta Palace Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:29:40'),
(202, 1, 'edit_hotel', 'Updated hotel: Ramayana Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:30:04'),
(203, 1, 'edit_hotel', 'Updated hotel: Kuta Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:30:19'),
(204, 1, 'edit_hotel', 'Updated hotel: Bali Dynasty Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:30:39'),
(205, 1, 'edit_hotel', 'Updated hotel: Hard Rock Hotel Bali', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:30:56'),
(206, 1, 'edit_hotel', 'Updated hotel: Rinjani Mountain Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:31:18'),
(207, 1, 'edit_hotel', 'Updated hotel: Sembalun Valley Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:31:49'),
(208, 1, 'edit_hotel', 'Updated hotel: Rinjani View Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:32:13'),
(209, 1, 'edit_hotel', 'Updated hotel: Ayodya Resort Komodo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:32:30'),
(210, 1, 'edit_hotel', 'Updated hotel: Komodo Adventure Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:32:59'),
(211, 1, 'edit_hotel', 'Updated hotel: Misool Eco Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:33:28'),
(212, 1, 'edit_hotel', 'Updated hotel: Raja Ampat Dive Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:33:59'),
(213, 1, 'edit_hotel', 'Updated hotel: Piaynemo Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:34:18'),
(214, 1, 'edit_hotel', 'Updated hotel: Puri Sari Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:34:45'),
(215, 1, 'edit_hotel', 'Updated hotel: Puri Sari Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:35:09'),
(216, 1, 'edit_hotel', 'Updated hotel: Komodo Resort Labuan Bajo', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:35:23'),
(217, 1, 'edit_hotel', 'Updated hotel: Kelimutu View Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:35:38'),
(218, 1, 'edit_hotel', 'Updated hotel: Moni Village Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:35:56'),
(219, 1, 'edit_hotel', 'Updated hotel: Kelimutu Mountain Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:36:13'),
(220, 1, 'edit_hotel', 'Updated hotel: Bunaken Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:36:37'),
(221, 1, 'edit_hotel', 'Updated hotel: Manado Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:37:13'),
(222, 1, 'edit_hotel', 'Updated hotel: Manado Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:37:30'),
(223, 1, 'edit_hotel', 'Updated hotel: Derawan Beach Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:37:45'),
(224, 1, 'edit_hotel', 'Updated hotel: Sangalaki Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:38:04'),
(225, 1, 'edit_hotel', 'Updated hotel: Derawan Dive Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:38:19'),
(226, 1, 'edit_hotel', 'Updated hotel: Karimunjawa Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:38:35'),
(227, 1, 'edit_hotel', 'Updated hotel: Gili Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:38:54'),
(228, 1, 'edit_hotel', 'Updated hotel: Gili Beach Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:39:12'),
(229, 1, 'edit_hotel', 'Updated hotel: Gili Paradise Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:39:27'),
(230, 1, 'edit_hotel', 'Updated hotel: TMII View Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:39:52'),
(231, 1, 'edit_hotel', 'Updated hotel: Jakarta Cultural Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:40:07'),
(232, 1, 'edit_hotel', 'Updated hotel: Mini Indonesia Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:40:26'),
(233, 1, 'edit_hotel', 'Updated hotel: Ubud Village Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:40:43'),
(234, 1, 'edit_hotel', 'Updated hotel: Ubud Village Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:41:21'),
(235, 1, 'edit_hotel', 'Updated hotel: Sacred Monkey Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:41:35'),
(236, 1, 'edit_hotel', 'Updated hotel: Ubud Palace Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:41:54'),
(237, 1, 'edit_hotel', 'Updated hotel: Toraja Heritage Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:42:35'),
(238, 1, 'edit_hotel', 'Updated hotel: Rantepao Valley Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:43:04'),
(239, 1, 'edit_hotel', 'Updated hotel: Toraja Cultural Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:43:19'),
(240, 1, 'edit_hotel', 'Updated hotel: Batur Mountain Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:43:39'),
(241, 1, 'edit_hotel', 'Updated hotel: Kintamani View Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:43:53'),
(242, 1, 'edit_hotel', 'Updated hotel: Batur Volcano Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:44:06'),
(243, 1, 'edit_hotel', 'Updated hotel: Sanur Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:44:23'),
(244, 1, 'edit_hotel', 'Updated hotel: Sindhu Beach Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:44:51'),
(245, 1, 'edit_hotel', 'Updated hotel: Sanur Paradise Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:45:09'),
(246, 1, 'edit_hotel', 'Updated hotel: Sentani Lake Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:45:21'),
(247, 1, 'edit_hotel', 'Updated hotel: Jayapura Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:45:40'),
(248, 1, 'edit_hotel', 'Updated hotel: Sentani Cultural Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:45:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(249, 1, 'edit_hotel', 'Updated hotel: Hotel Santika Premiere', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:46:09'),
(250, 1, 'edit_hotel', 'Updated hotel: Hotel Ibis Styles', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:46:20'),
(251, 1, 'edit_hotel', 'Updated hotel: Villa Pribadi', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:46:33'),
(252, 1, 'edit_hotel', 'Updated hotel: Sanur Beach Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:46:45'),
(253, 7, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:47:00'),
(254, 7, 'checkin', 'Check-in successful for booking: HK20251222F034C7', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 09:47:12'),
(255, 8, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 04:46:07'),
(256, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 04:51:39'),
(257, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:40:03'),
(258, 1, 'edit_hotel', 'Updated hotel: Kuta Breeze Cottages', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:44:29'),
(259, 1, 'edit_hotel', 'Updated hotel: Kuta Oceanview Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:45:40'),
(260, 1, 'edit_hotel', 'Updated hotel: Kuta Sunwave Hotel &amp; Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:48:14'),
(261, 1, 'edit_hotel', 'Updated hotel: Raja Ampat Seaside Inn', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:49:40'),
(262, 1, 'edit_hotel', 'Updated hotel: Raja Ampat Lagoon Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:50:49'),
(263, 1, 'edit_hotel', 'Updated hotel: Raja Ampat Coral Bay Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:51:46'),
(264, 1, 'edit_hotel', 'Updated hotel: Samosir Lakeside Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:52:45'),
(265, 1, 'edit_hotel', 'Updated hotel: Toba Lake Grand Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:53:58'),
(266, 1, 'edit_hotel', 'Updated hotel: Toba Highland Signature Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:55:10'),
(267, 1, 'edit_hotel', 'Updated hotel: Ubud Forest View Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:56:05'),
(268, 1, 'edit_hotel', 'Updated hotel: Ubud Valley Serenity Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:56:53'),
(269, 1, 'edit_hotel', 'Updated hotel: Ubud Ridge Panorama Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:57:45'),
(270, 1, 'edit_hotel', 'Updated hotel: Toraja Highland Eco Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 08:59:18'),
(271, 1, 'edit_hotel', 'Updated hotel: Toraja Mountain View Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:00:29'),
(272, 1, 'edit_hotel', 'Updated hotel: Toraja Grand Highland Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:01:29'),
(273, 1, 'edit_hotel', 'Updated hotel: Rinjani Highland Nature Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:02:44'),
(274, 1, 'edit_hotel', 'Updated hotel: Rinjani Forest Heritage Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:03:37'),
(275, 1, 'edit_hotel', 'Updated hotel: Rinjani Summit View Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:04:25'),
(276, 1, 'edit_hotel', 'Updated hotel: Bunaken Coral View Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:22:32'),
(277, 1, 'edit_hotel', 'Updated hotel: Bunaken Island Village Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:23:40'),
(278, 1, 'edit_hotel', 'Updated hotel: Bunaken Seaview Boutique Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:25:02'),
(279, 1, 'edit_hotel', 'Updated hotel: Prambanan Garden Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:26:01'),
(280, 1, 'edit_hotel', 'Updated hotel: Prambanan Heritage View Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:27:09'),
(281, 1, 'edit_hotel', 'Updated hotel: Prambanan Royal Garden Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:28:37'),
(282, 1, 'edit_hotel', 'Updated hotel: Derawan Island Beach Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:30:25'),
(283, 1, 'edit_hotel', 'Updated hotel: Sangalaki Bay Private Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:31:17'),
(284, 1, 'edit_hotel', 'Updated hotel: Kelimutu Lakeside Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:33:35'),
(285, 1, 'edit_hotel', 'Updated hotel: Tanjung Puting Riverside Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:35:50'),
(286, 1, 'edit_hotel', 'Updated hotel: Sekonyer River Eco Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:36:44'),
(287, 1, 'edit_hotel', 'Updated hotel: Tanjung Puting Forest Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:40:06'),
(288, 1, 'edit_hotel', 'Updated hotel: Wae Rebo Mountain Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:40:57'),
(289, 1, 'edit_hotel', 'Updated hotel: Wae Rebo Cultural Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:41:38'),
(290, 1, 'edit_hotel', 'Updated hotel: Mbaru Niang Experience Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:42:46'),
(291, 1, 'edit_hotel', 'Updated hotel: Kawah Putih Highland Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:44:50'),
(292, 1, 'edit_hotel', 'Updated hotel: Ciwidey Volcano Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:45:35'),
(293, 1, 'edit_hotel', 'Updated hotel: Kawah Putih Grand Mountain Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:46:22'),
(294, 1, 'edit_hotel', 'Updated hotel: Nusantara Culture Hotel TMII', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:47:05'),
(295, 1, 'edit_hotel', 'Updated hotel: TMII Garden Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:48:17'),
(296, 1, 'edit_hotel', 'Updated hotel: Archipelago Experience Lodge TMII', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:48:56'),
(297, 1, 'edit_hotel', 'Updated hotel: Gili Trawangan Seaside Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:49:33'),
(298, 1, 'edit_hotel', 'Updated hotel: Gili Trawangan Ocean View Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:50:26'),
(299, 1, 'edit_hotel', 'Updated hotel: Gili Trawangan Island Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:51:09'),
(300, 1, 'edit_hotel', 'Updated hotel: Karimunjawa Seaside Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:51:57'),
(301, 1, 'edit_hotel', 'Updated hotel: Karimunjawa Sunrise Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:58:54'),
(302, 1, 'edit_hotel', 'Updated hotel: Karimunjawa Island Paradise Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 09:59:32'),
(303, 1, 'edit_hotel', 'Updated hotel: Labuan Bajo Harbor View Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:00:11'),
(304, 1, 'edit_hotel', 'Updated hotel: Labuan Bajo Beachfront Hotel', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:00:53'),
(305, 1, 'edit_hotel', 'Updated hotel: Komodo Bay Lodge', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:10:29'),
(306, 1, 'edit_hotel', 'Updated hotel: Bromo Highland Premiere', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:11:01'),
(307, 1, 'edit_hotel', 'Updated hotel: Bromo Style Retreat', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:11:47'),
(308, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:28:23'),
(309, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:30:06'),
(310, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:32:36'),
(311, 1, 'edit_hotel', 'Updated hotel: Raja Ampat Seaside Inn', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:33:00'),
(312, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:33:14'),
(313, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:35:12'),
(314, 1, 'edit_hotel', 'Updated hotel: Komodo Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:35:31'),
(315, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:35:40'),
(316, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:36:37'),
(317, 1, 'edit_hotel', 'Updated hotel: Rinjani Highland Nature Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:36:59'),
(318, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:37:13'),
(319, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:37:58'),
(320, 1, 'edit_hotel', 'Updated hotel: Prambanan Royal Garden Resort', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:38:18'),
(321, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-24 10:39:40'),
(322, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-25 09:14:41'),
(323, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:37:25'),
(324, 9, 'profile_update', 'User profile updated', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:38:19'),
(325, 9, 'reservation', 'New reservation created: HK2025122634C3ED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:40:03'),
(326, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:41:36'),
(327, 1, 'update_reservation', 'Updated reservation ID: 26 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:41:58'),
(328, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:43:26'),
(329, 9, 'checkin', 'Check-in successful for booking: HK2025122634C3ED', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:43:50'),
(330, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:44:32'),
(331, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:45:16'),
(332, 9, 'reservation', 'New reservation created: HK20251226992E03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:46:01'),
(333, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:46:13'),
(334, 1, 'update_reservation', 'Updated reservation ID: 27 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:46:24'),
(335, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:46:33'),
(336, 9, 'checkin', 'Check-in successful for booking: HK20251226992E03', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:46:40'),
(337, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:46:57'),
(338, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:56:02'),
(339, 9, 'reservation', 'New reservation created: HK202512262DDFAB', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:56:34'),
(340, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:56:46'),
(341, 1, 'update_reservation', 'Updated reservation ID: 28 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:57:04'),
(342, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:57:13'),
(343, 9, 'checkin', 'Check-in successful for booking: HK202512262DDFAB', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:57:48'),
(344, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 02:58:03'),
(345, 1, 'add_hotel', 'Added new hotel: azizgosong', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:18:29'),
(346, 1, 'delete_hotel', 'Deleted hotel ID: 62', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:18:48'),
(347, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:19:50'),
(348, 9, 'reservation', 'New reservation created: HK20251226ACE366', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:20:10'),
(349, 9, 'cancellation_request', 'Cancellation requested for reservation: 29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:20:35'),
(350, 1, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:20:46'),
(351, 1, 'update_reservation', 'Updated reservation ID: 29 to confirmed', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:21:04'),
(352, 1, 'approve_cancellation', 'Approved cancellation ID: 3', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:21:29'),
(353, 9, 'login', 'User logged in successfully', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 03:21:41');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `status` enum('published','draft') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `content`, `excerpt`, `image`, `author_id`, `status`, `created_at`, `updated_at`) VALUES
(1, '7 Tips Berwisata Aman di Destinasi Alam Indonesia', '<p>Indonesia memiliki kekayaan alam yang luar biasa, dari gunung hingga laut. Namun, berwisata ke destinasi alam memerlukan persiapan khusus untuk memastikan keamanan dan kenyamanan.</p>\r\n\r\n<h3>1. Riset Kondisi Destinasi</h3>\r\n<p>Selalu cek informasi terbaru tentang kondisi destinasi, cuaca, dan peraturan setempat sebelum berangkat.</p>\r\n\r\n<h3>2. Persiapan Fisik yang Matang</h3>\r\n<p>Destinasi alam sering membutuhkan stamina yang baik. Lakukan olahraga rutin sebelum trip.</p>\r\n\r\n<h3>3. Bawa Perlengkapan yang Tepat</h3>\r\n<p>Pakaian yang sesuai, sepatu trekking, obat-obatan pribadi, dan alat komunikasi adalah wajib.</p>\r\n\r\n<h3>4. Gunakan Jasa Pemandu Lokal</h3>\r\n<p>Pemandu lokal tidak hanya menunjukkan jalan, tetapi juga memahami kondisi dan budaya setempat.</p>\r\n\r\n<h3>5. Patuhi Aturan dan Adat Setempat</h3>\r\n<p>Hormati budaya dan tradisi lokal untuk pengalaman yang lebih bermakna.</p>\r\n\r\n<h3>6. Jaga Kelestarian Alam</h3>\r\n<p>Bawa pulang sampahmu, jangan merusak flora dan fauna, dan ikuti prinsip leave no trace.</p>\r\n\r\n<h3>7. Siapkan Emergency Plan</h3>\r\n<p>Selalu punya rencana cadangan dan kontak emergency untuk berjaga-jaga.</p>\r\n\r\n<p>Dengan persiapan yang matang, petualanganmu di alam Indonesia akan menjadi pengalaman yang tak terlupakan!</p>', 'Panduan lengkap untuk berwisata dengan aman dan nyaman di berbagai destinasi alam Indonesia', NULL, 1, 'published', '2025-11-03 18:51:33', '2025-11-03 18:51:33'),
(2, '10 Destinasi Wisata Alam Terbaik di Indonesia 2025', '<p>Indonesia adalah surga bagi pecinta alam dengan ribuan destinasi menakjubkan. Berikut 10 destinasi terbaik yang wajib dikunjungi:</p>\r\n\r\n<h3>1. Raja Ampat, Papua Barat</h3>\r\n<p>Surga biodiversitas laut dengan spot diving terbaik di dunia. Gugusan pulau karst dan perairan jernih.</p>\r\n\r\n<h3>2. Gunung Bromo, Jawa Timur</h3>\r\n<p>Sunrise dari Penanjakan dan lautan pasir yang memesona. Pengalaman spiritual yang mendalam.</p>\r\n\r\n<h3>3. Danau Toba, Sumatera Utara</h3>\r\n<p>Danau vulkanik terbesar di dunia dengan budaya Batak yang kaya di Pulau Samosir.</p>\r\n\r\n<h3>4. Komodo Island, NTT</h3>\r\n<p>Bertemu komodo di habitat alaminya dan snorkeling di Pink Beach yang memukau.</p>\r\n\r\n<h3>5. Bunaken, Sulawesi Utara</h3>\r\n<p>Wall diving spektakuler dengan terumbu karang vertikal dan kehidupan laut yang beragam.</p>\r\n\r\n<h3>6. Tana Toraja, Sulawesi Selatan</h3>\r\n<p>Budaya unik dengan rumah adat Tongkonan dan ritual pemakaman yang mendalam.</p>\r\n\r\n<h3>7. Gili Trawangan, Lombok</h3>\r\n<p>Pulau tanpa kendaraan dengan sunset memukau dan spot diving dengan penyu.</p>\r\n\r\n<h3>8. Wae Rebo, Flores</h3>\r\n<p>Desa tradisional dengan rumah kerucut di ketinggian, pengalaman budaya autentik.</p>\r\n\r\n<h3>9. Karimunjawa, Jawa Tengah</h3>\r\n<p>Kepulauan tropis dengan perairan biru kehijauan dan atmosfer yang masih alami.</p>\r\n\r\n<h3>10. Kelimutu, Flores</h3>\r\n<p>Danau kawah tiga warna yang bisa berubah, fenomena alam yang langka.</p>\r\n\r\n<p>Setiap destinasi menawarkan keunikan dan pengalaman yang berbeda. Mana yang akan jadi tujuan berikutnya?</p>', 'Jelajahi keindahan alam Indonesia dari Sabang sampai Merauke dengan destinasi terbaik 2025', NULL, 1, 'published', '2025-11-03 18:51:33', '2025-11-20 06:34:49'),
(3, 'Panduan Memilih Akomodasi untuk Liburan di Indonesia', '<p>Memilih akomodasi yang tepat dapat membuat perbedaan besar dalam pengalaman liburan Anda. Berikut panduan lengkapnya:</p>\r\n\r\n<h3>1. Tentukan Budget dan Prioritas</h3>\r\n<p>Tetapkan budget yang realistis dan tentukan apa yang paling penting: lokasi, fasilitas, atau pengalaman.</p>\r\n\r\n<h3>2. Pertimbangkan Lokasi</h3>\r\n<p>Pilih akomodasi yang strategis, dekat dengan atraksi utama atau transportasi umum.</p>\r\n\r\n<h3>3. Baca Review dari Traveler Lain</h3>\r\n<p>Review di platform terpercaya memberikan gambaran real tentang kualitas akomodasi.</p>\r\n\r\n<h3>4. Cek Fasilitas yang Ditawarkan</h3>\r\n<p>Pastikan fasilitas sesuai kebutuhan: WiFi, breakfast, pool, atau layanan khusus lainnya.</p>\r\n\r\n<h3>5. Homestay vs Hotel vs Villa</h3>\r\n<p>• Homestay: Pengalaman lokal dan harga terjangkau<br>\r\n• Hotel: Fasilitas lengkap dan layanan profesional<br>\r\n• Villa: Privasi dan ruang yang lebih luas</p>\r\n\r\n<h3>6. Perhatikan Kebijakan Pembatalan</h3>\r\n<p>Pilih yang memiliki kebijakan pembatalan fleksibel, terutama untuk trip yang berisiko.</p>\r\n\r\n<h3>7. Hubungi Langsung untuk Konfirmasi</h3>\r\n<p>Kadang harga langsung lebih murah dan Anda bisa negosiasi fasilitas tambahan.</p>\r\n\r\n<h3>8. Pertimbangkan Sustainable Tourism</h3>\r\n<p>Pilih akomodasi yang peduli lingkungan dan mendukung masyarakat lokal.</p>\r\n\r\n<p>Dengan panduan ini, Anda bisa menemukan akomodasi perfect untuk liburan impian di Indonesia!</p>', 'Tips memilih hotel, homestay, dan penginapan yang sesuai kebutuhan dan budget untuk liburan', NULL, 1, 'published', '2025-11-03 18:51:33', '2025-11-03 18:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `cancellations`
--

CREATE TABLE `cancellations` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `refund_amount` decimal(10,2) DEFAULT 0.00,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancellations`
--

INSERT INTO `cancellations` (`id`, `reservation_id`, `reason`, `status`, `admin_notes`, `refund_amount`, `processed_at`, `created_at`) VALUES
(1, 5, 'tidak jadi maaf', 'approved', 'awas kau', 1575000.00, '2025-11-20 02:50:08', '2025-11-20 02:49:06'),
(2, 15, 'tidak jadi', 'approved', 'awaskau', 2250000.00, '2025-12-05 02:44:05', '2025-12-05 01:54:07'),
(3, 29, 'kepencet', 'approved', '', 1800000.00, '2025-12-26 03:21:29', '2025-12-26 03:20:35');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `service_id`, `name`, `description`, `price_per_night`, `image`, `status`, `created_at`) VALUES
(1, 1, 'Bromo Highland Premiere', 'Bromo Highland Premiere adalah hotel bintang 4 yang berlokasi strategis di kawasan Probolinggo dengan akses mudah menuju Gunung Bromo. Hotel ini menawarkan fasilitas lengkap seperti kolam renang, spa, dan restoran dengan menu lokal serta internasional. Cocok bagi wisatawan yang ingin menikmati keindahan matahari terbit Bromo, wisata alam pegunungan, serta kenyamanan akomodasi modern setelah beraktivitas.', 500000.00, '6949136175a8e.jpg', 'active', '2025-11-20 12:43:29'),
(2, 1, 'Bromo Style Retreat', 'Bromo Style Retreat hotel modern bergaya kontemporer dengan desain stylish dan lokasi strategis di area Probolinggo, ideal untuk tamu yang akan mengunjungi Gunung Bromo. Menawarkan kamar nyaman, lounge berseni, layanan resepsionis 24 jam, serta akses mudah ke tour sunrise Bromo, jeep trip, dan titik-titik foto populer. Pilihan tepat untuk wisatawan muda dan pasangan yang mencari akomodasi rapi, Instagrammable, dan terjangkau sebelum berangkat ke pegunungan.', 350000.00, '6949136c60220.jpg', 'active', '2025-11-20 12:43:29'),
(3, 1, 'Villa Pribadi', 'Villa eksklusif dengan pemandangan laut dan layanan pribadi.', 750000.00, '69491379c9752.jpg', 'active', '2025-11-20 12:43:29'),
(4, 1, 'Hotel Bromo Permai', 'Hotel bintang 4 dengan pemandangan Gunung Bromo yang spektakuler. Fasilitas lengkap termasuk kolam renang, spa, dan restoran dengan menu lokal.', 350000.00, '69490acaef7bd.jpg', 'active', '2025-11-20 12:51:52'),
(5, 1, 'Cemara Indah Hotel', 'Hotel modern dengan desain arsitektur Jawa Timur. Lokasi strategis dekat dengan spot sunrise Bromo dengan layanan shuttle.', 500000.00, '69490af094b8c.jpg', 'active', '2025-11-20 12:51:52'),
(6, 1, 'Lava View Lodge', 'Lodge eksklusif dengan balkon pribadi menghadap lautan pasir Bromo. Pengalaman menginap yang mewah dengan layanan personal.', 750000.00, '69490ee2ef52f.jpg', 'active', '2025-11-20 12:51:52'),
(7, 2, 'Kuta Breeze Cottages', 'Cottage bergaya tropis dekat Pantai Kuta, menawarkan kamar nyaman dengan teras pribadi, taman hijau, dan fasilitas modern. Pilihan ideal untuk liburan santai, selancar, dan menikmati matahari terbenam di Kuta.', 400000.00, '69490ef734d90.jpg', 'active', '2025-11-20 12:51:52'),
(8, 2, 'Kuta Oceanview Resort', 'Resort bintang 4 yang berlokasi strategis di kawasan Pantai Kuta, Bali. Menawarkan kolam renang infinity dengan pemandangan laut, layanan spa untuk relaksasi, serta restoran yang menyajikan hidangan seafood dan kuliner khas Bali. Cocok untuk wisatawan yang menginginkan kenyamanan, kemewahan, dan akses mudah ke pantai serta pusat hiburan Kuta.', 650000.00, '69490f096a9a4.jpg', 'active', '2025-11-20 12:51:52'),
(9, 2, 'Kuta Sunwave Hotel &amp; Resort', 'Hotel modern yang berlokasi strategis di kawasan Pantai Kuta, Bali. Kuta Sunwave Hotel &amp; Resort menawarkan kamar nyaman dengan desain tropis kontemporer, kolam renang outdoor, rooftop lounge untuk menikmati matahari terbenam, serta restoran dengan pilihan menu lokal dan internasional. Cocok untuk wisatawan yang menginginkan kenyamanan, suasana santai, dan akses mudah ke pantai, pusat perbelanjaan, serta hiburan malam di Kuta.', 800000.00, '69490f222eeb3.jpg', 'active', '2025-11-20 12:51:52'),
(10, 3, 'Manohara Resort', 'Resort mewah dengan desain arsitektur Jawa klasik. Lokasi strategis dekat Candi Borobudur dengan shuttle service.', 300000.00, '69490f33591ec.jpg', 'active', '2025-11-20 12:51:52'),
(11, 3, 'Amanjiwo Borobudur', 'Hotel bintang 5 dengan kolam renang infinity menghadap candi. Spa, restoran fine dining, dan tur pribadi Borobudur.', 600000.00, '69490f4760aaf.jpg', 'active', '2025-11-20 12:51:52'),
(12, 3, 'Borobudur Village', 'Hotel budaya dengan replika candi di area hotel. Pengalaman budaya Jawa lengkap dengan pertunjukan wayang.', 450000.00, '69490f5794640.jpg', 'active', '2025-11-20 12:51:52'),
(13, 4, 'Raja Ampat Seaside Inn', 'Penginapan nyaman yang berlokasi dekat pesisir Raja Ampat, Papua Barat. Raja Ampat Seaside Inn menawarkan kamar sederhana dengan suasana tropis, pemandangan laut atau taman, serta akses mudah ke dermaga dan spot wisata bahari. Cocok bagi wisatawan backpacker dan pecinta alam yang ingin menjelajahi keindahan bawah laut Raja Ampat dengan harga terjangkau.', 250000.00, '694bc15cd1a43.jpg', 'active', '2025-11-20 12:51:52'),
(14, 4, 'Raja Ampat Lagoon Hotel', 'Hotel bergaya tropis yang berlokasi di kawasan Raja Ampat, Papua Barat. Raja Ampat Lagoon Hotel menawarkan kamar nyaman dengan desain alami, pemandangan laguna atau laut, restoran dengan menu lokal, serta akses mudah ke aktivitas snorkeling dan island hopping. Pilihan ideal bagi wisatawan yang menginginkan kenyamanan dengan suasana alam Raja Ampat yang tenang.', 400000.00, '69490f84938c7.jpg', 'active', '2025-11-20 12:51:52'),
(15, 4, 'Raja Ampat Coral Bay Resort', 'Resort tropis yang terletak di kawasan pesisir Raja Ampat, Papua Barat. Raja Ampat Coral Bay Resort menawarkan kolam renang outdoor dengan pemandangan laut, taman hijau yang asri, serta restoran yang menyajikan menu nusantara dan hidangan laut segar. Cocok untuk wisatawan yang mencari kenyamanan, ketenangan, dan akses mudah ke spot snorkeling, diving, serta wisata pulau di Raja Ampat.', 550000.00, '69490f9c1a23e.jpg', 'active', '2025-11-20 12:51:52'),
(16, 5, 'Samosir Lakeside Hotel', 'Hotel yang berlokasi di tepi Danau Toba, Samosir, dengan pemandangan danau yang menenangkan dan udara sejuk pegunungan. Samosir Lakeside Hotel menawarkan kamar nyaman dengan balkon menghadap danau, area bersantai di tepi air, serta restoran yang menyajikan hidangan lokal dan nusantara. Cocok untuk wisatawan yang mencari ketenangan, liburan keluarga, maupun pasangan yang ingin menikmati keindahan alam Danau Toba.', 500000.00, '69490fab15835.jpg', 'active', '2025-11-20 12:51:52'),
(17, 5, 'Toba Lake Grand Resort', 'Resort eksklusif yang terletak di kawasan Danau Toba, Samosir, dengan pemandangan danau vulkanik yang spektakuler. Toba Lake Grand Resort menawarkan kolam renang infinity menghadap Danau Toba, layanan spa untuk relaksasi, restoran dengan menu lokal dan internasional, serta layanan concierge untuk kebutuhan wisata tamu. Pilihan ideal bagi wisatawan yang menginginkan pengalaman menginap mewah dengan suasana alam yang tenang dan sejuk.', 800000.00, '69490fbf1db50.jpg', 'active', '2025-11-20 12:51:52'),
(18, 5, 'Toba Highland Signature Hotel', 'Hotel premium yang berlokasi di kawasan perbukitan Danau Toba, Samosir, dengan pemandangan panorama danau yang luas. Toba Highland Signature Hotel menawarkan desain modern elegan, kolam renang outdoor dengan view danau, restoran berkonsep internasional, lounge malam dengan hiburan live music, serta fasilitas lengkap untuk kenyamanan tamu. Cocok bagi wisatawan yang menginginkan pengalaman menginap eksklusif dengan suasana alam yang megah.', 900000.00, '69490fd0bdd06.jpg', 'active', '2025-11-20 12:51:52'),
(19, 6, 'Ubud Forest View Lodge', 'Lodge bernuansa alam yang terletak di kawasan hijau Ubud, Gianyar. Ubud Forest View Lodge menawarkan suasana tenang dengan pemandangan hutan dan lembah, kamar bergaya tropis alami, area yoga dan relaksasi, serta restoran kecil yang menyajikan menu sehat dan lokal. Cocok bagi wisatawan yang mencari ketenangan, healing, dan pengalaman menyatu dengan alam khas Ubud.', 450000.00, '69490fe6c990b.jpg', 'active', '2025-11-20 12:51:52'),
(20, 6, 'Ubud Valley Serenity Resort', 'Resort bernuansa alam yang terletak di lembah hijau Ubud, Gianyar. Ubud Valley Serenity Resort menawarkan kamar dan vila dengan pemandangan lembah dan persawahan, kolam renang infinity yang menyatu dengan alam, spa untuk relaksasi, serta restoran dengan menu sehat dan lokal. Pilihan ideal bagi wisatawan yang mencari ketenangan, kenyamanan, dan pengalaman menginap khas Ubud.', 650000.00, '6949100549e15.jpg', 'active', '2025-11-20 12:51:52'),
(21, 6, 'Ubud Ridge Panorama Hotel', 'Hotel premium yang terletak di area perbukitan Ubud, Gianyar, dengan panorama alam hijau khas Bali. Ubud Ridge Panorama Hotel menawarkan kamar elegan dengan balkon menghadap lembah dan hutan tropis, restoran yang menyajikan menu lokal dan internasional, serta area relaksasi seperti spa dan lounge terbuka. Pilihan ideal bagi wisatawan yang menginginkan ketenangan, kenyamanan, dan pemandangan alam Ubud yang menawan.', 850000.00, '6949101d4fe91.jpg', 'active', '2025-11-20 12:51:52'),
(22, 7, 'Komodo Resort', 'Resort eksklusif di Pulau Komodo dengan akses langsung ke pantai. Snorkeling, diving, dan tur komodo.', 600000.00, '694bc1f390aa7.jpg', 'active', '2025-11-20 12:51:52'),
(23, 7, 'Ayodya Resort Komodo', 'Resort bintang 5 dengan villa pribadi. Kolam renang infinity, spa, dan restoran seafood premium.', 1000000.00, '6949102ee7a9c.jpg', 'active', '2025-11-20 12:51:52'),
(24, 7, 'Komodo Adventure Lodge', 'Lodge petualang dengan fasilitas diving center. Pemandangan teluk yang indah dan layanan guide lokal.', 1200000.00, '6949104b4af4e.jpg', 'active', '2025-11-20 12:51:52'),
(25, 8, 'Toraja Highland Eco Resort', 'Eco-resort yang terletak di dataran tinggi Tana Toraja, Sulawesi Selatan, dengan panorama pegunungan dan persawahan terasering. Toraja Highland Eco Resort mengusung konsep ramah lingkungan dengan bangunan menyatu dengan alam, penggunaan material alami, serta aktivitas wisata budaya dan alam. Tamu dapat menikmati udara sejuk pegunungan, kuliner lokal Toraja, dan suasana tenang yang cocok untuk relaksasi dan eksplorasi budaya.', 700000.00, '694910685e22a.jpg', 'active', '2025-11-20 12:51:52'),
(26, 8, 'Toraja Mountain View Lodge', 'Lodge premium di Tana Toraja dengan panorama pegunungan dan layanan eksklusif.', 1100000.00, '69491087966c4.jpg', 'active', '2025-11-20 12:51:52'),
(27, 8, 'Toraja Grand Highland Resort', 'Resort mewah di dataran tinggi Tana Toraja dengan vila eksklusif, spa, dan panorama alam pegunungan.', 1500000.00, '6949109aa0e34.jpg', 'active', '2025-11-20 12:51:52'),
(28, 9, 'Rinjani Highland Nature Resort', 'Resort bernuansa alam yang terletak di kawasan pegunungan Gunung Rinjani, Lombok, NTB. Rinjani Highland Nature Resort menawarkan kamar dengan pemandangan gunung dan hutan tropis, udara sejuk, serta fasilitas pendukung wisata alam seperti area api unggun, restoran dengan menu lokal, dan layanan informasi trekking. Cocok bagi wisatawan yang ingin menikmati keindahan alam Rinjani dengan kenyamanan resort.', 550000.00, '694bc24be904f.jpg', 'active', '2025-11-20 12:51:52'),
(29, 9, 'Rinjani Forest Heritage Hotel', 'Hotel bernuansa alam yang terletak di kawasan hutan pegunungan Gunung Rinjani, Lombok, NTB. Rinjani Forest Heritage Hotel memadukan desain tradisional Lombok dengan sentuhan modern, menawarkan kamar nyaman dengan suasana sejuk, area relaksasi terbuka, serta restoran yang menyajikan hidangan lokal dan nusantara. Pilihan ideal bagi wisatawan yang ingin menikmati ketenangan alam Rinjani dengan kenyamanan hotel berkelas.', 750000.00, '694910cde8772.jpg', 'active', '2025-11-20 12:51:52'),
(30, 9, 'Rinjani Summit View Resort', 'Resort modern yang berlokasi di kawasan pegunungan Gunung Rinjani, Lombok, NTB. Rinjani Summit View Resort menawarkan kamar dan vila dengan pemandangan pegunungan, kolam renang infinity menghadap lanskap alam Rinjani, restoran dengan menu lokal dan internasional, serta layanan tur dan shuttle untuk mendukung aktivitas wisata alam. Cocok bagi wisatawan yang menginginkan kenyamanan premium dengan panorama Rinjani yang spektakuler.', 1100000.00, '694910db9ec4f.jpg', 'active', '2025-11-20 12:51:52'),
(31, 10, 'Bunaken Coral View Lodge', 'Lodge sederhana yang berlokasi di kawasan Bunaken, Manado, Sulawesi Utara, dengan pemandangan laut dan terumbu karang yang indah. Bunaken Coral View Lodge menawarkan kamar nyaman dengan suasana tropis, akses mudah ke spot snorkeling dan diving, serta layanan ramah untuk wisatawan yang ingin menjelajahi keindahan bawah laut Bunaken. Cocok bagi backpacker dan pecinta wisata bahari.', 350000.00, '694910ea848bb.jpg', 'active', '2025-11-20 12:51:52'),
(32, 10, 'Bunaken Island Village Resort', 'Resort bernuansa tropis yang berlokasi di Pulau Bunaken, Manado, Sulawesi Utara. Bunaken Island Village Resort menawarkan akomodasi nyaman dengan konsep kampung pesisir, taman hijau, serta fasilitas pendukung wisata bahari. Tamu dapat menikmati aktivitas snorkeling, diving, dan tur pulau, sekaligus merasakan suasana santai khas Bunaken. Cocok untuk wisatawan yang ingin berlibur dengan nyaman di tengah keindahan laut Sulawesi Utara.', 500000.00, '694910fc6a689.jpg', 'active', '2025-11-20 12:51:52'),
(33, 10, 'Bunaken Seaview Boutique Hotel', 'Hotel butik yang berlokasi di kawasan pesisir Bunaken, Manado, Sulawesi Utara. Bunaken Seaview Boutique Hotel menawarkan kamar elegan dengan balkon menghadap laut, restoran dengan panorama samudra, serta layanan wisata bahari seperti snorkeling dan diving. Pilihan tepat bagi wisatawan yang menginginkan kenyamanan lebih dengan pemandangan laut Bunaken yang menawan.', 650000.00, '6949110d85b7d.jpg', 'active', '2025-11-20 12:51:52'),
(34, 11, 'Prambanan Garden Resort', 'Resort bernuansa alam dan budaya yang berlokasi di kawasan Prambanan, Sleman, Yogyakarta. Prambanan Garden Resort menawarkan kamar nyaman dengan taman hijau, suasana tenang, serta akses mudah ke kompleks Candi Prambanan dan objek wisata budaya sekitarnya. Dilengkapi dengan restoran lokal dan area relaksasi terbuka, resort ini cocok bagi wisatawan yang ingin menikmati keindahan budaya Jawa dengan kenyamanan modern.', 400000.00, '6949112521500.jpg', 'active', '2025-11-20 12:51:52'),
(35, 11, 'Prambanan Heritage View Hotel', 'Hotel bernuansa budaya yang berlokasi di kawasan Prambanan, Sleman, Yogyakarta. Prambanan Heritage View Hotel menawarkan kamar modern dengan sentuhan arsitektur Jawa, area bersantai dengan pemandangan taman dan kompleks candi, serta restoran yang menyajikan hidangan lokal dan nusantara. Pilihan ideal bagi wisatawan yang ingin menikmati suasana budaya Prambanan dengan kenyamanan hotel berkelas.', 600000.00, '6949115a65e0a.jpg', 'active', '2025-11-20 12:51:52'),
(36, 11, 'Prambanan Royal Garden Resort', 'Resort elegan yang berlokasi di kawasan Prambanan, Sleman, Yogyakarta, dengan nuansa budaya Jawa yang kental. Prambanan Royal Garden Resort menawarkan kamar dan vila berdesain tradisional-modern, taman luas yang asri, kolam renang outdoor, serta restoran dengan sajian khas Jawa dan nusantara. Cocok bagi wisatawan yang menginginkan pengalaman menginap mewah dan tenang dengan akses mudah ke kompleks Candi Prambanan.', 800000.00, '694bc29a8d394.jpg', 'active', '2025-11-20 12:51:52'),
(37, 12, 'Derawan Island Beach Resort', 'Resort tropis yang terletak di Pulau Derawan, Berau, Kalimantan Timur, dengan pantai pasir putih dan air laut yang jernih. Derawan Island Beach Resort menawarkan kamar nyaman bernuansa tropis, akses langsung ke pantai, serta aktivitas wisata bahari seperti snorkeling, island hopping, dan tur melihat lumba-lumba. Cocok bagi wisatawan yang ingin menikmati keindahan laut Derawan dengan suasana santai dan harga terjangkau.', 450000.00, '69491169939bd.jpg', 'active', '2025-11-20 12:51:52'),
(38, 12, 'Sangalaki Bay Private Resort', 'Resort eksklusif yang berlokasi di kawasan Kepulauan Derawan, Berau, Kalimantan Timur. Sangalaki Bay Private Resort menawarkan vila pribadi dengan pemandangan teluk yang tenang, desain tropis modern, serta layanan premium untuk kenyamanan tamu. Dilengkapi dengan restoran, area bersantai tepi laut, dan aktivitas wisata bahari, resort ini cocok bagi wisatawan yang menginginkan privasi dan pengalaman menginap yang lebih mewah di Derawan.', 700000.00, '6949117ccb3b7.jpg', 'active', '2025-11-20 12:51:52'),
(39, 12, 'Derawan Dive Lodge', 'Lodge khusus diving dengan fasilitas lengkap. Komunitas diving internasional.', 850000.00, '6949118bb2bf5.jpg', 'active', '2025-11-20 12:51:52'),
(40, 13, 'Kelimutu Lakeside Hotel', 'Kelimutu Lakeside Hotel merupakan hotel bernuansa alam yang berlokasi di kawasan Kelimutu, Ende, Nusa Tenggara Timur. Hotel ini menawarkan suasana tenang dengan pemandangan pegunungan dan danau, cocok bagi wisatawan yang ingin menikmati keindahan alam Flores. Fasilitas yang tersedia meliputi kamar nyaman, area bersantai, serta layanan tur menuju Danau Kelimutu dan destinasi wisata sekitar.', 350000.00, '6949119b32f7a.jpg', 'active', '2025-11-20 12:51:52'),
(41, 14, 'Tanjung Puting Riverside Lodge', 'Tanjung Puting Riverside Lodge adalah penginapan bernuansa alam yang terletak di kawasan Taman Nasional Tanjung Puting, Kalimantan Tengah. Hotel ini menawarkan suasana tenang di tepi sungai dengan pemandangan hutan tropis yang asri. Cocok bagi wisatawan pecinta alam, lodge ini menyediakan akses mudah ke wisata susur sungai, observasi orangutan, serta paket tur ekowisata dengan pemandu lokal berpengalaman.', 300000.00, '694911ae4d491.jpg', 'active', '2025-11-20 12:51:52'),
(42, 14, 'Sekonyer River Eco Lodge', 'Sekonyer River Eco Lodge adalah penginapan bernuansa alam yang terletak di tepi sungai kawasan Taman Nasional Tanjung Puting. Lodge ini menawarkan suasana tenang dengan pemandangan hutan tropis dan kehidupan satwa liar khas Kalimantan. Tamu dapat menikmati aktivitas susur sungai, pengamatan orangutan, serta paket ekowisata yang ramah lingkungan dengan pemandu lokal.', 450000.00, '694911c01f2c0.jpg', 'active', '2025-11-20 12:51:52'),
(43, 14, 'Tanjung Puting Forest Resort', 'Tanjung Puting Forest Resort merupakan resort bernuansa alam yang menawarkan pengalaman menginap nyaman di tengah hutan hujan tropis Kalimantan. Resort ini dilengkapi fasilitas premium seperti kolam renang dengan pemandangan alam, spa relaksasi, serta restoran yang menyajikan hidangan lokal dan internasional. Lokasinya strategis untuk akses wisata susur sungai, observasi orangutan, dan paket ekowisata eksklusif dengan pemandu profesional.', 600000.00, '694911cf172a3.jpg', 'active', '2025-11-20 12:51:52'),
(44, 15, 'Wae Rebo Mountain Lodge', 'Wae Rebo Mountain Lodge adalah penginapan sederhana yang terletak di kawasan pegunungan Desa Wae Rebo, Manggarai. Dikelilingi oleh alam hijau dan kabut pegunungan, lodge ini menawarkan suasana tenang dan sejuk yang cocok bagi wisatawan pencinta budaya dan alam. Akses mudah menuju desa adat Wae Rebo menjadikannya pilihan ideal untuk menikmati pengalaman trekking, budaya lokal, dan kehidupan tradisional masyarakat setempat.', 250000.00, '694911e8817d5.jpg', 'active', '2025-11-20 12:51:52'),
(45, 15, 'Wae Rebo Cultural Lodge', 'Wae Rebo Cultural Lodge merupakan penginapan bernuansa budaya yang terletak di kawasan pegunungan Desa Wae Rebo, Manggarai. Mengusung konsep kearifan lokal, lodge ini menghadirkan suasana tenang dengan arsitektur dan interior yang terinspirasi dari rumah adat setempat. Tamu dapat menikmati hidangan khas nusantara, berinteraksi dengan masyarakat lokal, serta merasakan pengalaman wisata budaya dan alam yang autentik.', 350000.00, '694911f7c2752.jpg', 'active', '2025-11-20 12:51:52'),
(46, 15, 'Mbaru Niang Experience Resort', 'Mbaru Niang Experience Resort adalah resort bernuansa budaya yang menghadirkan pengalaman menginap khas Desa Wae Rebo. Mengadaptasi bentuk dan filosofi rumah adat setempat, resort ini menawarkan suasana pegunungan yang sejuk dan tenang. Tamu dapat menikmati aktivitas budaya, interaksi dengan masyarakat lokal, serta pengalaman wisata alam dan tradisi Manggarai yang autentik.', 500000.00, '6949120aa20da.jpg', 'active', '2025-11-20 12:51:52'),
(47, 16, 'Kawah Putih Highland Resort', 'Kawah Putih Highland Resort merupakan resort bernuansa alam yang terletak di kawasan pegunungan Ciwidey, Bandung. Dikelilingi udara sejuk dan panorama vulkanik khas Kawah Putih, resort ini menawarkan suasana tenang untuk beristirahat. Fasilitas meliputi area relaksasi, restoran dengan pemandangan alam, serta akses mudah ke objek wisata Kawah Putih dan perkebunan teh di sekitarnya.', 400000.00, '694912410df53.jpg', 'active', '2025-11-20 12:51:52'),
(48, 16, 'Ciwidey Volcano Resort', 'Ciwidey Volcano Resort adalah resort bernuansa alam yang terletak di kawasan pegunungan Ciwidey, Bandung. Menghadirkan suasana sejuk dengan pemandangan alam vulkanik khas Kawah Putih, resort ini cocok untuk wisatawan yang mencari ketenangan. Fasilitas mencakup taman hijau, restoran dengan menu lokal dan sehat, serta area relaksasi yang menyatu dengan alam sekitar.', 650000.00, '6949124f76b9f.jpg', 'active', '2025-11-20 12:51:52'),
(49, 16, 'Kawah Putih Grand Mountain Hotel', 'Kawah Putih Grand Mountain Hotel adalah hotel kelas atas yang terletak di kawasan pegunungan Ciwidey, Bandung. Mengusung desain modern-elegan yang berpadu dengan panorama alam vulkanik, hotel ini menawarkan pengalaman menginap yang nyaman dan eksklusif. Fasilitas meliputi restoran dengan pemandangan pegunungan, lounge hangat, serta akses strategis ke Kawah Putih dan objek wisata alam di sekitarnya.', 800000.00, '6949126205df4.jpg', 'active', '2025-11-20 12:51:52'),
(50, 17, 'Nusantara Culture Hotel TMII', 'Nusantara Culture Hotel TMII adalah hotel bernuansa budaya yang berlokasi strategis di kawasan Taman Mini Indonesia Indah, Jakarta Timur. Mengusung konsep keberagaman budaya Indonesia dalam desain interior modern, hotel ini cocok untuk wisata keluarga dan edukasi. Akses mudah ke anjungan daerah, museum, dan pusat budaya TMII menjadikannya pilihan praktis untuk menginap dengan kenyamanan kota.', 350000.00, '6949128b8f4a1.jpg', 'active', '2025-11-20 12:51:52'),
(51, 17, 'TMII Garden Resort', 'TMII Garden Resort merupakan resort bernuansa hijau yang berlokasi di sekitar kawasan Taman Mini Indonesia Indah, Jakarta Timur. Dikelilingi taman dan ruang terbuka, resort ini menawarkan suasana tenang di tengah kota. Fasilitas meliputi kolam renang, restoran keluarga, serta akses cepat ke berbagai anjungan budaya, museum, dan area rekreasi TMII.', 550000.00, '694912a887ede.jpg', 'active', '2025-11-20 12:51:52'),
(52, 17, 'Archipelago Experience Lodge TMII', 'Archipelago Experience Lodge TMII menawarkan pengalaman menginap bernuansa budaya Indonesia di kawasan Taman Mini Indonesia Indah, Jakarta Timur. Lodge ini menghadirkan konsep edukatif dengan desain interior yang terinspirasi dari beragam budaya nusantara. Tamu dapat menikmati sajian kuliner khas Indonesia, pertunjukan budaya tematik, serta akses mudah ke anjungan daerah dan museum TMII.', 700000.00, '694912b7ce20d.jpg', 'active', '2025-11-20 12:51:52'),
(53, 18, 'Gili Trawangan Seaside Lodge', 'Gili Trawangan Seaside Lodge merupakan penginapan santai yang berlokasi di kawasan pantai Gili Trawangan. Lodge ini menawarkan suasana tropis dengan akses mudah ke pantai, spot snorkeling, dan area hiburan pulau. Cocok bagi backpacker maupun wisatawan yang ingin menikmati keindahan laut, sunset, serta aktivitas pantai di salah satu destinasi favorit Lombok.', 300000.00, '694912cb82b47.jpg', 'active', '2025-11-20 12:51:52'),
(54, 18, 'Gili Trawangan Ocean View Hotel', 'Gili Trawangan Ocean View Hotel adalah hotel bergaya tropis yang menawarkan pemandangan laut khas Gili Trawangan. Berlokasi dekat pantai, hotel ini memberikan akses mudah ke aktivitas snorkeling, diving, dan area hiburan pulau. Fasilitas meliputi restoran dengan menu lokal dan internasional, area santai outdoor, serta suasana nyaman untuk liburan pantai.', 450000.00, '694912d9490a1.jpg', 'active', '2025-11-20 12:51:52'),
(55, 18, 'Gili Trawangan Island Resort', 'Gili Trawangan Island Resort adalah resort tropis yang menawarkan pengalaman menginap nyaman di pulau Gili Trawangan. Dikelilingi suasana pantai dan laut biru, resort ini menyediakan fasilitas relaksasi seperti kolam renang, spa, dan area santai outdoor. Lokasinya strategis untuk menikmati aktivitas snorkeling, diving, bersepeda keliling pulau, serta menikmati panorama matahari terbenam khas Gili.', 600000.00, '694912e64a7a8.jpg', 'active', '2025-11-20 12:51:52'),
(56, 19, 'Karimunjawa Seaside Hotel', 'Karimunjawa Seaside Hotel merupakan hotel tepi pantai yang menawarkan suasana tenang khas Kepulauan Karimunjawa. Dikelilingi pasir putih dan laut jernih, hotel ini cocok untuk wisatawan yang ingin bersantai dan menikmati keindahan alam. Fasilitas meliputi area relaksasi pantai, restoran seafood segar, serta akses mudah ke aktivitas snorkeling dan island hopping.', 350000.00, '69491385a205e.jpg', 'active', '2025-11-20 12:51:52'),
(57, 19, 'Karimunjawa Sunrise Resort', 'Karimunjawa Sunrise Resort adalah resort tropis yang menawarkan pengalaman menginap premium di Kepulauan Karimunjawa. Resort ini menghadirkan kolam renang infinity dengan pemandangan laut lepas serta panorama matahari terbit yang indah. Fasilitas lengkap seperti restoran tepi pantai, layanan wisata bahari, dan area relaksasi menjadikannya pilihan ideal untuk liburan nyaman dan eksklusif.', 550000.00, '694913130f08f.jpg', 'active', '2025-11-20 12:51:52'),
(58, 19, 'Karimunjawa Island Paradise Hotel', 'Karimunjawa Island Paradise Hotel adalah hotel modern yang menawarkan kenyamanan lengkap di kawasan Kepulauan Karimunjawa. Berlokasi strategis dekat area pelabuhan dan pusat aktivitas wisata, hotel ini memudahkan akses ke pantai, spot snorkeling, dan island hopping. Fasilitas meliputi kolam renang, restoran, area santai, serta layanan wisata bahari.', 650000.00, '6949132588281.jpg', 'active', '2025-11-20 12:51:52'),
(59, 20, 'Labuan Bajo Harbor View Resort', 'Labuan Bajo Harbor View Resort adalah resort santai yang berlokasi di kawasan pesisir Labuan Bajo dengan pemandangan laut dan pelabuhan. Resort ini menawarkan suasana tenang dengan akses mudah ke aktivitas wisata bahari seperti island hopping, snorkeling, dan tur Taman Nasional Komodo. Cocok untuk wisatawan yang mencari penginapan nyaman dengan panorama laut Flores.', 300000.00, '69491331ecfa8.jpg', 'active', '2025-11-20 12:51:52'),
(60, 20, 'Labuan Bajo Beachfront Hotel', 'Labuan Bajo Beachfront Hotel merupakan hotel tepi pantai yang menawarkan pemandangan laut Flores dan akses langsung ke area pesisir. Hotel ini cocok bagi wisatawan yang ingin menikmati suasana santai dengan fasilitas nyaman. Tersedia restoran dengan menu lokal dan seafood, serta lokasi strategis untuk memulai perjalanan wisata ke Pulau Komodo dan sekitarnya.', 450000.00, '6949134450a75.jpg', 'active', '2025-11-20 12:51:52'),
(61, 20, 'Komodo Bay Lodge', 'Komodo Bay Lodge penginapan nyaman di Labuan Bajo yang menawarkan akses mudah ke Taman Nasional Komodo. Tamu dapat menikmati island-hopping ke pulau Komodo &amp; Rinca, snorkeling/diving di perairan kaya biota laut, sunset spektakuler dari Bukit Cinta, serta sajian kuliner laut segar dan tur budaya ke desa-desa tradisional Flores. Cocok untuk wisatawan petualang yang ingin pengalaman alam, laut, dan budaya khas NTT.', 600000.00, '69491354ae6ae.jpg', 'active', '2025-11-20 12:51:52');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `booking_code` varchar(20) NOT NULL,
  `reservation_date` date NOT NULL,
  `guests` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `special_requests` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `hotel_id` int(11) DEFAULT NULL,
  `days` int(11) DEFAULT NULL,
  `nights` int(11) DEFAULT NULL,
  `service_total` decimal(10,2) DEFAULT NULL,
  `hotel_total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `service_id`, `booking_code`, `reservation_date`, `guests`, `total_price`, `status`, `special_requests`, `created_at`, `hotel_id`, `days`, `nights`, `service_total`, `hotel_total`) VALUES
(1, 2, 1, 'HK20241201ABC123', '2024-12-15', 2, 700000.00, 'confirmed', 'Minta kamar dengan view gunung', '2025-11-03 18:51:33', NULL, NULL, NULL, NULL, NULL),
(2, 3, 5, 'HK20241201DEF456', '2024-12-20', 4, 1200000.00, 'pending', 'Ada anggota keluarga lansia, butuh akses mudah', '2025-11-03 18:51:33', NULL, NULL, NULL, NULL, NULL),
(3, 4, 3, 'HK20251103ADBA99', '2025-11-29', 2, 500000.00, 'completed', '', '2025-11-03 19:06:18', NULL, NULL, NULL, NULL, NULL),
(4, 4, 1, 'HK20251104102B1F', '2025-11-29', 5, 1750000.00, 'completed', '', '2025-11-04 07:03:13', NULL, NULL, NULL, NULL, NULL),
(5, 6, 1, 'HK20251120417FA1', '2025-11-22', 5, 1750000.00, 'cancelled', '', '2025-11-20 02:43:16', NULL, NULL, NULL, NULL, NULL),
(6, 6, 14, 'HK2025112064EC5A', '2025-11-29', 4, 1900000.00, 'completed', '', '2025-11-20 06:36:38', NULL, NULL, NULL, NULL, NULL),
(7, 6, 17, 'HK2025112059493D', '2025-11-30', 10, 1250000.00, 'completed', '', '2025-11-20 06:38:45', NULL, NULL, NULL, NULL, NULL),
(8, 6, 2, 'HK202511200BC363', '2025-11-22', 9, 1800000.00, 'completed', '', '2025-11-20 09:39:28', NULL, NULL, NULL, NULL, NULL),
(9, 6, 1, 'HK20251120858EBF', '2025-11-21', 8, 2800000.00, 'completed', '', '2025-11-20 11:41:28', NULL, NULL, NULL, NULL, NULL),
(10, 6, 1, 'HK20251120AE4168', '2025-11-21', 8, 5800000.00, 'completed', '', '2025-11-20 12:53:46', 1, 6, 6, 2800000.00, 3000000.00),
(11, 6, 10, 'HK2025112023C9D0', '2025-11-22', 4, 3450000.00, 'completed', '', '2025-11-20 13:15:46', 31, 3, 3, 2400000.00, 1050000.00),
(12, 6, 18, 'HK2025112029391C', '2025-11-29', 2, 1350000.00, 'completed', '', '2025-11-20 13:42:10', 53, 2, 2, 750000.00, 600000.00),
(13, 6, 17, 'HK20251121B498A0', '2025-11-22', 5, 1675000.00, 'confirmed', '', '2025-11-21 12:55:07', 50, 3, 3, 625000.00, 1050000.00),
(14, 7, 20, 'HK2025120557B4FA', '2025-12-20', 3, 2850000.00, 'completed', '', '2025-12-05 01:39:01', 61, 3, 3, 1050000.00, 1800000.00),
(15, 7, 4, 'HK20251205F7D262', '2025-12-24', 2, 2500000.00, 'cancelled', '', '2025-12-05 01:53:19', 14, 2, 2, 1700000.00, 800000.00),
(16, 7, 3, 'HK2025120520DA26', '2025-12-10', 3, 1650000.00, 'cancelled', '', '2025-12-05 01:57:06', 10, 3, 3, 750000.00, 900000.00),
(17, 7, 7, 'HK2025120556BF51', '2025-12-13', 2, 3400000.00, 'completed', '', '2025-12-05 01:59:33', 24, 2, 2, 1000000.00, 2400000.00),
(18, 7, 1, 'HK2025120567A311', '2025-12-06', 1, 700000.00, 'completed', '', '2025-12-05 02:01:26', 4, 1, 1, 350000.00, 350000.00),
(19, 7, 1, 'HK202512050062B0', '2025-12-11', 2, 1450000.00, 'completed', '', '2025-12-05 02:41:04', 6, 1, 1, 700000.00, 750000.00),
(20, 7, 14, 'HK20251205D852D2', '2025-12-26', 5, 2975000.00, 'completed', '', '2025-12-05 02:47:57', 41, 2, 2, 2375000.00, 600000.00),
(21, 7, 9, 'HK202512051AA0CE', '2025-12-06', 5, 3350000.00, 'completed', '', '2025-12-05 03:14:25', 30, 1, 1, 2250000.00, 1100000.00),
(22, 7, 13, 'HK2025120517CE24', '2025-12-19', 2, 900000.00, 'completed', '', '2025-12-05 03:19:13', 40, 1, 1, 550000.00, 350000.00),
(23, 7, 1, 'HK2025120522AD25', '2026-02-19', 5, 3250000.00, 'completed', '', '2025-12-05 03:21:22', 3, 2, 2, 1750000.00, 1500000.00),
(24, 7, 1, 'HK20251222728E98', '2025-12-23', 4, 2900000.00, 'completed', '', '2025-12-22 08:53:11', 1, 3, 3, 1400000.00, 1500000.00),
(25, 7, 1, 'HK20251222F034C7', '2025-12-24', 4, 2900000.00, 'completed', '', '2025-12-22 09:17:35', 5, 3, 3, 1400000.00, 1500000.00),
(26, 9, 1, 'HK2025122634C3ED', '2025-12-31', 2, 1200000.00, 'completed', '', '2025-12-26 02:40:03', 1, 1, 1, 700000.00, 500000.00),
(27, 9, 1, 'HK20251226992E03', '2026-01-01', 2, 3150000.00, 'completed', '', '2025-12-26 02:46:01', 2, 7, 7, 700000.00, 2450000.00),
(28, 9, 4, 'HK202512262DDFAB', '2025-12-26', 2, 5550000.00, 'completed', '', '2025-12-26 02:56:34', 15, 7, 7, 1700000.00, 3850000.00),
(29, 9, 9, 'HK20251226ACE366', '2025-12-31', 2, 2000000.00, 'cancelled', '', '2025-12-26 03:20:10', 28, 2, 2, 900000.00, 1100000.00);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` enum('alam','buatan') NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `type`, `location`, `description`, `price`, `capacity`, `image`, `status`, `created_at`) VALUES
(1, 'Gunung Bromo', 'alam', 'Probolinggo, Jawa Timur', 'Gunung Bromo adalah gunung berapi aktif yang terkenal dengan pemandangan sunrise-nya yang menakjubkan. Berlokasi di Taman Nasional Bromo Tengger Semeru, pengunjung dapat menyaksikan matahari terbit dari Penanjakan dan menjelajahi lautan pasir serta kawah yang masih aktif. Pengalaman menunggang kuda di lautan pasir menjadi daya tarik tersendiri.', 350000.00, 40, '691b2636bc96a.jpeg', 'active', '2025-11-03 18:51:33'),
(2, 'Pantai Kuta Bali', 'alam', 'Badung, Bali', 'Pantai Kuta adalah destinasi pantai paling ikonik di Bali dengan pasir putih, ombak yang cocok untuk berselancar, dan sunset yang spektakuler. Terletak di jantung pariwisata Bali, pantai ini menawarkan berbagai aktivitas watersport, restoran seafood, dan kehidupan malam yang vibrant.', 200000.00, 80, '691e6ae280937.jpg', 'active', '2025-11-03 18:51:33'),
(3, 'Candi Borobudur', 'buatan', 'Magelang, Jawa Tengah', 'Candi Borobudur adalah candi Buddha terbesar di dunia yang dibangun pada abad ke-9. Sebagai warisan dunia UNESCO, candi ini memiliki arsitektur megah dengan 504 patung Buddha dan 2.672 panel relief. Sunrise dari puncak candi memberikan pengalaman spiritual yang tak terlupakan.', 250000.00, 100, '691e6b26e55c1.jpeg', 'active', '2025-11-03 18:51:33'),
(4, 'Raja Ampat', 'alam', 'Raja Ampat, Papua Barat', 'Kepulauan Raja Ampat adalah surga biodiversitas laut dengan lebih dari 1.500 spesies ikan dan 550 spesies koral. Destinasi diving terbaik dunia ini menawarkan perairan jernih, gugusan pulau karst, dan kehidupan bawah laut yang memukau. Perfect untuk snorkeling dan diving.', 850000.00, 25, '691e6b6020ffa.jpg', 'active', '2025-11-03 18:51:33'),
(5, 'Danau Toba', 'alam', 'Samosir, Sumatera Utara', 'Danau Toba adalah danau vulkanik terbesar di dunia dengan Pulau Samosir di tengahnya. Danau ini menawarkan pemandangan alam yang menakjubkan, budaya Batak yang kaya, dan air yang jernih. Pengunjung dapat menikmati kuliner khas Batak dan menjelajahi situs budaya.', 300000.00, 60, '691e6b9dbf847.jpg', 'active', '2025-11-03 18:51:33'),
(6, 'Ubud', 'alam', 'Gianyar, Bali', 'Ubud adalah pusat budaya Bali yang dikelilingi oleh sawah terasering hijau dan hutan tropis. Terkenal dengan Monkey Forest, galeri seni, pertunjukan tradisional, dan pusat wellness. Destinasi sempurna untuk mereka yang mencari ketenangan dan inspirasi budaya.', 275000.00, 50, '691e6be99dfe1.jpg', 'active', '2025-11-03 18:51:33'),
(7, 'Komodo Island', 'alam', 'Labuan Bajo, NTT', 'Pulau Komodo adalah rumah bagi komodo, kadal terbesar di dunia. Taman Nasional Komodo menawarkan trekking untuk melihat komodo di habitat alami, snorkeling di Pink Beach, dan pemandangan panorama dari bukit-bukit. Pengalaman wildlife yang unik.', 500000.00, 30, '691e6c4a3d1df.jpg', 'active', '2025-11-03 18:51:33'),
(8, 'Tana Toraja', 'alam', 'Toraja, Sulawesi Selatan', 'Tana Toraja terkenal dengan budaya pemakaman yang unik, rumah adat Tongkonan, dan pemandangan alam pegunungan yang hijau. Ritual Rambu Solo dan kuburan tebing menjadi daya tarik budaya yang mendalam. Landscape sawah dan pegunungan sangat fotogenik.', 400000.00, 35, '691e7146f40c5.jpg', 'active', '2025-11-03 18:51:33'),
(9, 'Gunung Rinjani', 'alam', 'Lombok, NTB', 'Gunung Rinjani adalah gunung berapi tertinggi kedua di Indonesia dengan danau kawah Segara Anak yang indah. Pendakian ke puncak menawarkan pemandangan spektakuler dan pengalaman camping yang menantang. Hot spring di Segara Anak menjadi reward setelah mendaki.', 450000.00, 20, '691e717780a6e.jpg', 'active', '2025-11-03 18:51:33'),
(10, 'Bunaken', 'alam', 'Manado, Sulawesi Utara', 'Taman Nasional Bunaken adalah surga diving dengan wall diving terbaik di dunia. Terumbu karang vertikal, biodiversitas laut yang tinggi, dan perairan jernih menjadikannya destinasi impian para penyelam. Spot diving untuk semua level keahlian.', 600000.00, 25, '691e7212337ad.jpg', 'active', '2025-11-03 18:51:33'),
(11, 'Prambanan', 'buatan', 'Sleman, Yogyakarta', 'Candi Prambanan adalah kompleks candi Hindu terbesar di Indonesia dengan arsitektur yang tinggi dan ramping. Relief Ramayana yang dipentaskan dalam sendratari malam hari menambah keunikan. Candi ini simbol kejayaan Hindu di Jawa pada masa lalu.', 200000.00, 80, '691e72358be04.jpg', 'active', '2025-11-03 18:51:33'),
(12, 'Derawan Islands', 'alam', 'Berau, Kalimantan Timur', 'Kepulauan Derawan terdiri dari pulau-pulau eksotis dengan pasir putih dan perairan biru jernih. Surga bagi penyu, khususnya di Sangalaki. Snorkeling dengan ubur-ubur tidak menyengat di Kakaban menjadi pengalaman langka yang tak terlupakan.', 550000.00, 20, '691e725f486e1.jpg', 'active', '2025-11-03 18:51:33'),
(13, 'Kelimutu', 'alam', 'Ende, NTT', 'Gunung Kelimutu terkenal dengan tiga danau kawah yang bisa berubah warna. Fenomena alam unik ini dikaitkan dengan kepercayaan lokal. Sunrise dari puncak gunung menampilkan pemandangan danau tiga warna yang spektakuler.', 275000.00, 40, '691e729e87693.jpg', 'active', '2025-11-03 18:51:33'),
(14, 'Tanjung Putting', 'alam', 'Kotawaringin Barat, Kalimantan Tengah', 'Taman Nasional Tanjung Puting adalah habitat orangutan terbesar di dunia. Pengunjung dapat berpetualang dengan klotok (perahu tradisional) menyusuri sungai untuk melihat orangutan di feeding platform. Pengalaman wildlife yang mendalam.', 475000.00, 15, '691e72c78e802.jpg', 'active', '2025-11-03 18:51:33'),
(15, 'Wae Rebo', 'alam', 'Manggarai, NTT', 'Desa adat Wae Rebo adalah desa tradisional dengan rumah Mbaru Niang berbentuk kerucut. Terletak di ketinggian 1.200 mdpl, desa ini menawarkan pengalaman budaya autentik dan pemandangan alam pegunungan yang memukau. Homestay dengan masyarakat lokal.', 325000.00, 25, '691e72e82cc3a.jpg', 'active', '2025-11-03 18:51:33'),
(16, 'Kawah Putih', 'alam', 'Bandung, Jawa Barat', 'Kawah Putih adalah danau kawah dengan air berwarna putih kebiruan yang dikelilingi dinding kawah yang tandus. Suasana mistis dan pemandangan unik menjadikannya destinasi fotografi yang populer. Lokasinya di kawasan pegunungan dengan udara sejuk.', 150000.00, 60, '691e73272435f.jpg', 'active', '2025-11-03 18:51:33'),
(17, 'Taman Mini Indonesia Indah', 'buatan', 'Jakarta Timur', 'TMII adalah taman rekreasi edukatif yang menampilkan miniatur Indonesia dengan anjungan tradisional dari 34 provinsi. Terdapat museum, teater, dan wahana budaya yang cocok untuk keluarga. Destinasi sempurna untuk mengenal keragaman Indonesia dalam satu lokasi.', 125000.00, 150, '691e73a36709c.jpg', 'active', '2025-11-03 18:51:33'),
(18, 'Gili Trawangan', 'alam', 'Lombok Utara, NTB', 'Gili T adalah pulau kecil tanpa kendaraan bermotor dengan pantai pasir putih dan terumbu karang yang indah. Famous untuk sunset, diving dengan turtles, dan kehidupan malam yang santai. Transportasi hanya dengan sepeda atau cidomo (kereta kuda).', 375000.00, 45, '691e7449a92a2.jpg', 'active', '2025-11-03 18:51:33'),
(19, 'Karimunjawa', 'alam', 'Jepara, Jawa Tengah', 'Kepulauan Karimunjawa adalah surga tropis dengan perairan biru kehijauan dan pantai pasir putih. Snorkeling dan diving menampilkan terumbu karang yang sehat dan kehidupan laut yang beragam. Atmosfer yang masih alami dan belum terlalu ramai.', 425000.00, 30, '691e7480549b0.jpeg', 'active', '2025-11-03 18:51:33'),
(20, 'Labuan Bajo', 'alam', 'Manggarai Barat, NTT', 'Labuan Bajo adalah gerbang menuju Taman Nasional Komodo dengan pemandangan teluk yang indah. Sunset dari bukit Amelia atau bukit Sylvia menjadi highlight. Kota kecil ini menawarkan restoran seafood dan akomodasi dengan view teluk yang memukau.', 350000.00, 50, '691e74ab0e5c8.jpg', 'active', '2025-11-03 18:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `address`, `created_at`) VALUES
(1, 'Administrator', 'admin@healingkuy.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NULL, '2025-11-03 18:51:33'),
(2, 'Budi Santoso', 'budi@healingkuy.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '', '', '2025-11-03 18:51:33'),
(3, 'Sari Indah', 'sari@healingkuy.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NULL, NULL, '2025-11-03 18:51:33'),
(4, 'Alifian SB', 'alifiansb@student.uns.ac.id', '$2y$10$jfLuNLM8JAWZvh6YlKeZdOdCI3JYXZwXjrGGSVvBUVR74wDOBTPL2', 'user', NULL, NULL, '2025-11-03 19:05:54'),
(5, 'azizgosong', 'azizgosong@gmail.com', '$2y$10$ZL6anNlumbjKUjCIAapUteXu9qHU4QlUe90BG9ojtm0/ScdCriuXK', 'user', NULL, NULL, '2025-11-04 07:11:26'),
(6, 'dillo', 'dilo@gmail.com', '$2y$10$TitsjaIUGOXwEByVhzxMJeqczjRObGPmSagUYYTN0xE.6HePOtCHS', 'user', '1234567890', 'klaten', '2025-11-18 07:05:32'),
(7, 'albertindraw', 'albertindrawiguna@gmail.com', '$2y$10$AaK3f3k4ikZXsEGlA2/9YermjdsB75EC8tPC17lJJMdk8JCAhUrpe', 'user', '9281094820', '', '2025-12-05 01:37:02'),
(8, 'azizgosong', 'sampitak@healingkuy.id', '$2y$10$tKOxNc.334/AzQQdw3hSoOSuHNGDKfN5o2fJVOCjY0mgQxsvp9htO', 'user', NULL, NULL, '2025-12-24 04:45:55'),
(9, 'Dji', 'djiamputh@healingkuy.id', '$2y$10$jjhjTabJOL3AXumwSpPpzO6s1jXe.8HQu0rRRX5nfzW91rWcjdxmW', 'user', '0874563573653', '', '2025-12-24 10:29:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=354;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cancellations`
--
ALTER TABLE `cancellations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD CONSTRAINT `cancellations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`);

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `reservations_ibfk_3` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
