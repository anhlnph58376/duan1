-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2025 at 05:41 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `duan1_tuor`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment_requests`
--

CREATE TABLE `assignment_requests` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `departure_id` int UNSIGNED NOT NULL COMMENT 'FK tới departures (Đoàn được phân công)',
  `tour_guide_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_guides (HDV được yêu cầu)',
  `requested_by_user_id` int UNSIGNED NOT NULL COMMENT 'FK tới users (Admin đã tạo yêu cầu)',
  `status` enum('Pending','Accepted','Declined','Expired','Admin_Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `request_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_time` datetime NOT NULL COMMENT 'Thời gian yêu cầu hết hạn nếu không phản hồi',
  `response_date` datetime DEFAULT NULL,
  `hdv_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Lý do từ chối của HDV'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu trữ lịch sử và trạng thái yêu cầu phân công HDV';

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `customer_id` int UNSIGNED NOT NULL COMMENT 'FK tới customers (người đại diện)',
  `booking_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_date` datetime NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `deposit_amount` decimal(15,2) DEFAULT '0.00',
  `status` enum('Pending','Deposited','Completed','Canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending' COMMENT 'Chờ xác nhận, Đã cọc, Hoàn tất, Hủy',
  `tour_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Hồ sơ đặt chỗ (booking) của khách hàng';

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `booking_code`, `booking_date`, `total_amount`, `deposit_amount`, `status`, `tour_id`, `created_at`, `updated_at`) VALUES
(2, 2, 'BK202512018026', '2025-12-01 12:43:00', 19900000.00, 19000.00, 'Completed', 11, '2025-12-01 12:44:27', '2025-12-01 12:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `booking_guests`
--

CREATE TABLE `booking_guests` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `booking_id` int UNSIGNED NOT NULL COMMENT 'FK tới bookings',
  `customer_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới customers (Nếu khách đã có trong hệ thống)',
  `guest_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Các yêu cầu đặc biệt của khách hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách khách trong đoàn';

-- --------------------------------------------------------

--
-- Table structure for table `booking_members`
--

CREATE TABLE `booking_members` (
  `id` int UNSIGNED NOT NULL,
  `booking_id` int UNSIGNED NOT NULL,
  `full_name` varchar(191) DEFAULT NULL,
  `age` int DEFAULT NULL,
  `gender` varchar(16) DEFAULT NULL,
  `passport_number` varchar(64) DEFAULT NULL,
  `note` text,
  `payment_status` varchar(32) DEFAULT NULL,
  `payment_amount` decimal(12,2) DEFAULT NULL,
  `checkin_status` varchar(32) DEFAULT NULL,
  `room_assignment` varchar(64) DEFAULT NULL,
  `special_request` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking_members`
--

INSERT INTO `booking_members` (`id`, `booking_id`, `full_name`, `age`, `gender`, `passport_number`, `note`, `payment_status`, `payment_amount`, `checkin_status`, `room_assignment`, `special_request`, `created_at`) VALUES
(1, 2, 'an', 21, 'Male', '12371736127', 'akjdajkdjk', 'partial', 1900000.00, 'not_arrived', '', 'dákdakj', '2025-12-01 12:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `history_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_of_birth` int DEFAULT NULL,
  `id_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Chưa thanh toán',
  `personal_requests` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `checkin_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Chưa đến',
  `room_allocation` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Khách hàng mua tour';

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `email`, `address`, `history_notes`, `gender`, `year_of_birth`, `id_type`, `id_number`, `payment_status`, `personal_requests`, `checkin_status`, `room_allocation`) VALUES
(1, 'LO NGOC ANH', '0385618096', 'anhprince.0802@gmail.com', 'Điện Biên', 'y', 'Nam', 2002, 'Căn cước công dân', '011202000748', 'Đã thanh toán', 'y', 'Chưa đến', 'P101'),
(2, 'adad', '0823213288', 'adf@gmail.com', 'adadjkka', NULL, NULL, NULL, NULL, NULL, 'Chưa thanh toán', NULL, 'Chưa đến', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departures`
--

CREATE TABLE `departures` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_version_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_versions',
  `departure_date` datetime NOT NULL,
  `return_date` datetime NOT NULL,
  `actual_guide_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới tour_guides',
  `status` enum('Scheduled','In Progress','Completed','Canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Scheduled' COMMENT 'Khởi hành, Hoàn thành, Hủy',
  `min_pax` int UNSIGNED DEFAULT '1',
  `max_pax` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Định nghĩa một chuyến đi thực tế';

-- --------------------------------------------------------

--
-- Table structure for table `departure_assignments`
--

CREATE TABLE `departure_assignments` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `departure_id` int UNSIGNED NOT NULL COMMENT 'FK tới departures',
  `assigned_type` enum('HDV','Driver','Vehicle','Other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Enum: HDV, Driver, Vehicle',
  `assigned_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_guides hoặc bảng khác (tùy thuộc assigned_type)',
  `assignment_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân bổ nhân sự/dịch vụ cho chuyến khởi hành';

-- --------------------------------------------------------

--
-- Table structure for table `departure_bookings`
--

CREATE TABLE `departure_bookings` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `departure_id` int UNSIGNED NOT NULL COMMENT 'FK tới departures',
  `booking_id` int UNSIGNED NOT NULL COMMENT 'FK tới bookings',
  `pax_count` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Liên kết Booking với chuyến khởi hành cụ thể';

-- --------------------------------------------------------

--
-- Table structure for table `expense_items`
--

CREATE TABLE `expense_items` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `transaction_id` int UNSIGNED NOT NULL COMMENT 'FK tới transactions',
  `supplier_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới suppliers (nếu có)',
  `departure_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới departures (chi phí cho chuyến đi)',
  `cost_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại chi phí (ví dụ: Ăn uống, Vé tham quan, Vận chuyển)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết các khoản Chi';

-- --------------------------------------------------------

--
-- Table structure for table `guide_performance_logs`
--

CREATE TABLE `guide_performance_logs` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_guide_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_guides',
  `departure_id` int UNSIGNED NOT NULL COMMENT 'Đoàn được đánh giá',
  `rating` decimal(2,1) NOT NULL COMMENT 'Điểm đánh giá (ví dụ: 0.0 - 5.0)',
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Nhận xét chi tiết',
  `rated_by` enum('Admin','Customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử đánh giá hiệu suất HDV';

-- --------------------------------------------------------

--
-- Table structure for table `guide_schedules`
--

CREATE TABLE `guide_schedules` (
  `id` int UNSIGNED NOT NULL,
  `guide_id` int UNSIGNED NOT NULL,
  `schedule_date` date NOT NULL,
  `status` enum('Available','Busy','On Leave','Sick Leave','Personal Leave') DEFAULT 'Available',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenue_items`
--

CREATE TABLE `revenue_items` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `transaction_id` int UNSIGNED NOT NULL COMMENT 'FK tới transactions',
  `booking_id` int UNSIGNED NOT NULL COMMENT 'FK tới bookings',
  `payment_method` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deposit` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết các khoản Thu (Liên kết với Booking)';

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách các vai trò (Admin, HDV)';

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Admin', 'Quản trị viên hệ thống, có đầy đủ quyền.'),
(2, 'Tour Guide', 'Hướng dẫn viên du lịch, quyền hạn hạn chế.');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách đối tác, nhà cung cấp dịch vụ';

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `phone`, `email`, `supplier_type`, `address`) VALUES
(3, 'LO NGOC ANH', 'LO NGOC ANH', '0385618096', 'longanhprince@gmail.com', 'Nhà xe', 'Điện Biên'),
(4, 'hải vân', 'LO NGOC ANH', '0777888999', 'anhprince.0802@gmail.com', 'Nhà xe', 'Phạm hùng'),
(7, 'LO NGOC ANH', 'LO NGOC ANH', '0385618096', 'haivan@gmail.com', 'Nhà xe', 'Điện Biên');

-- --------------------------------------------------------

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `policy_booking` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Quy định đặt tour',
  `policy_cancellation` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Chính sách hoàn hủy',
  `policy_refund` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Chính sách hoàn tiền',
  `included_services` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả nhanh các dịch vụ bao gồm',
  `excluded_services` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả các dịch vụ không bao gồm',
  `duration` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ví dụ: 3N2Đ',
  `departure_point` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `departure_date` date DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `partner_id` int UNSIGNED DEFAULT NULL,
  `price_child` decimal(15,2) DEFAULT '0.00' COMMENT 'Giá trẻ em (ví dụ 5-11 tuổi)',
  `price_infant` decimal(15,2) DEFAULT '0.00' COMMENT 'Giá em bé (dưới 2 tuổi)',
  `is_international` tinyint(1) NOT NULL DEFAULT '0',
  `category_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông tin chung của một tour';

--
-- Dumping data for table `tours`
--

INSERT INTO `tours` (`id`, `tour_code`, `name`, `image`, `description`, `policy_booking`, `policy_cancellation`, `policy_refund`, `included_services`, `excluded_services`, `duration`, `departure_point`, `destination`, `departure_date`, `base_price`, `partner_id`, `price_child`, `price_infant`, `is_international`, `category_id`, `created_at`) VALUES
(6, 'HANOI_SAPA_4_DEM', 'Hà Nội Sapa', 'uploads/tours/911992049f2c857941640ae35f423cce.jpg', 'Không có gì', '', '', '', '', '', '3 Ngày 4 đêm', '', '', NULL, 2900000.00, NULL, 0.00, 0.00, 1, 3, '2025-11-22 19:17:22'),
(8, 'HANQUOC_2N1D', 'Hàn quốc', 'uploads/tours/tải xuống (9).jpg', 'Hiệu năng mạnh mẽ: Chạy tốt các game nặng, đồ họa 3D, lập trình, chỉnh sửa video.', '', '', '', '', '', '2n1đ', '', '', NULL, 10000000.00, NULL, 0.00, 0.00, 1, 1, '2025-11-22 19:17:22'),
(9, 'HANOI_SAPA_4_DEM_3_Ngay', 'Hà Nội Sapa', 'uploads/tours/1763838236_taodangcap.jpg', 'oke\r\n\r\n', 'oke', 'oke', 'oke', 'oke', 'ôke', '3 Ngày 4 đêm', 'Hà Nội', 'Sapa', '2025-11-16', 2900000.00, NULL, 200000.00, 100000.00, 0, 3, '2025-11-22 19:17:22'),
(10, 'oke', 'oke', 'uploads/tours/1763839395_583933708_122266369262233789_354857661529612814_n.jpg', '23432', 'sdf', 'sfd', 'sdf', 'dà', 'fd', 'oke', 'Hà Nội', 'Sapa', '2025-11-22', 10000000.00, NULL, 10000.00, 1000.00, 0, 1, '2025-11-22 19:23:15'),
(11, 'anh', 'anh', 'uploads/tours/1763840266_dechquantam.jpg', '3', '444', '4444', '44444', '44', '44', '2n3đ', 'Hà Nội', 'Sapa', '0333-03-23', 2900000.00, NULL, 9000.00, 200000.00, 0, 3, '2025-11-22 19:37:46'),
(12, 'test', 'test', 'uploads/tours/69222804c7723.jpg', 'e', 'e', 'e', 'e', 'e', 'e', '3 Ngày 2 đêm', 'Hà Nội', 'Sapa', '2025-11-15', 2900000.00, NULL, 28.00, 10000.00, 0, 1, '2025-11-22 21:15:48'),
(13, 'test 2', 'test 2', 'uploads/tours/6922316295ea6.jpg', 'test 2', 'test 2', 'test 2', 'test 2', 'test 2', 'test 2', 'test 2', 'Hà Nội', 'test 2', '2025-11-13', 2900000.00, NULL, 3.00, 1.00, 0, 4, '2025-11-22 21:55:46');

-- --------------------------------------------------------

--
-- Table structure for table `tour_assigned_services`
--

CREATE TABLE `tour_assigned_services` (
  `tour_id` int UNSIGNED NOT NULL,
  `service_id` int UNSIGNED NOT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ghi chú thêm cho dịch vụ trong tour này'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_categories`
--

CREATE TABLE `tour_categories` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'standard',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tour_categories`
--

INSERT INTO `tour_categories` (`id`, `name`, `type`, `description`, `created_at`) VALUES
(1, 'Du lịch Biển', 'standard', NULL, '2025-11-22 10:06:05'),
(2, 'Du lịch Núi', 'standard', NULL, '2025-11-22 10:06:05'),
(3, 'Tour thiết kế riêng (Custom)', 'customized', NULL, '2025-11-22 10:06:05'),
(4, 'Thăm quan di tích', 'standard', NULL, '2025-11-22 20:48:46');

-- --------------------------------------------------------

--
-- Table structure for table `tour_diaries`
--

CREATE TABLE `tour_diaries` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `departure_id` int UNSIGNED NOT NULL COMMENT 'FK tới departures',
  `tour_guide_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_guides',
  `timestamp` datetime NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `incident_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sự cố, phản hồi',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhật ký tour, sự cố, phản hồi của HDV';

-- --------------------------------------------------------

--
-- Table structure for table `tour_guides`
--

CREATE TABLE `tour_guides` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `user_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới users (nếu HDV có tài khoản login)',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive','Busy') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `birth_date` date DEFAULT NULL,
  `languages` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificates` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `experience_years` int DEFAULT NULL,
  `guide_type` enum('Nội địa','Quốc tế','Cả hai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Nội địa',
  `specialization` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `health_status` enum('Excellent','Good','Fair','Poor','Critical') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Good',
  `performance_rating` decimal(3,2) DEFAULT NULL,
  `emergency_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `join_date` date DEFAULT NULL,
  `availability_status` enum('Available','Busy','On Leave','Unavailable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Available',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Hồ sơ Hướng dẫn viên (HDV)';

--
-- Dumping data for table `tour_guides`
--

INSERT INTO `tour_guides` (`id`, `user_id`, `name`, `phone`, `email`, `license_info`, `image`, `status`, `birth_date`, `languages`, `certificates`, `experience_years`, `guide_type`, `specialization`, `health_status`, `performance_rating`, `emergency_contact`, `address`, `join_date`, `availability_status`, `notes`) VALUES
(1, 2, 'HDV Demo', NULL, NULL, NULL, NULL, 'Active', NULL, NULL, NULL, NULL, 'Nội địa', NULL, 'Good', NULL, NULL, NULL, NULL, 'Available', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tour_images`
--

CREATE TABLE `tour_images` (
  `id` int NOT NULL,
  `tour_id` int UNSIGNED NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn file ảnh',
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả ảnh (SEO)',
  `is_thumbnail` tinyint(1) DEFAULT '0' COMMENT '1=Ảnh đại diện, 0=Ảnh thường',
  `sort_order` int DEFAULT '0' COMMENT 'Thứ tự hiển thị (Số nhỏ hiện trước)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tour_images`
--

INSERT INTO `tour_images` (`id`, `tour_id`, `image_path`, `alt_text`, `is_thumbnail`, `sort_order`, `created_at`) VALUES
(1, 12, 'uploads/tours/gallery/img_69222804c9a5c.jpg', NULL, 0, 0, '2025-11-22 21:15:48'),
(3, 13, 'uploads/tours/gallery/img_6922316297688.jpg', NULL, 0, 0, '2025-11-22 21:55:46'),
(4, 13, 'uploads/tours/gallery/img_692231629847e.jpg', NULL, 0, 0, '2025-11-22 21:55:46'),
(6, 13, 'uploads/tours/1764497938_4k HD Wallpaper_ Golden Gate at Sunset_ A van Gogh-Inspired Sky.jpg', NULL, 0, 0, '2025-11-30 10:18:58'),
(7, 13, 'uploads/tours/1764497938_tải xuống (2).jpg', NULL, 0, 0, '2025-11-30 10:18:58'),
(8, 12, 'uploads/tours/1764498860_tải xuống (4).jpg', NULL, 0, 0, '2025-11-30 10:34:20'),
(9, 12, 'uploads/tours/1764498860_wallpaper.jpg', NULL, 0, 0, '2025-11-30 10:34:20'),
(10, 12, 'uploads/tours/1764498860_z5758421409324_451beb97e7e69f0811d5a66bccf4c8b8.jpg', NULL, 0, 0, '2025-11-30 10:34:20');

-- --------------------------------------------------------

--
-- Table structure for table `tour_itineraries`
--

CREATE TABLE `tour_itineraries` (
  `id` int NOT NULL,
  `tour_id` int UNSIGNED NOT NULL,
  `day_number` int NOT NULL COMMENT 'Ngày thứ mấy (1, 2, 3...)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tiêu đề ngày (VD: Hà Nội - Sapa)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả chi tiết hoạt động trong ngày',
  `meals` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bữa ăn: Sáng, Trưa, Tối',
  `accommodation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thông tin khách sạn nghỉ đêm',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tour_services`
--

CREATE TABLE `tour_services` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_version_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_versions',
  `supplier_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới suppliers',
  `service_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi phí/dịch vụ cố định cho một phiên bản tour';

-- --------------------------------------------------------

--
-- Table structure for table `tour_suppliers`
--

CREATE TABLE `tour_suppliers` (
  `id` int UNSIGNED NOT NULL,
  `tour_id` int UNSIGNED NOT NULL,
  `supplier_id` int UNSIGNED NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tour_suppliers`
--

INSERT INTO `tour_suppliers` (`id`, `tour_id`, `supplier_id`, `role`, `created_at`) VALUES
(3, 13, 7, NULL, '2025-11-30 10:55:48');

-- --------------------------------------------------------

--
-- Table structure for table `tour_versions`
--

CREATE TABLE `tour_versions` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_id` int UNSIGNED NOT NULL COMMENT 'FK tới tours',
  `version_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `base_price` decimal(15,2) NOT NULL,
  `status` enum('Draft','Active','Archived','Promotion') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Các phiên bản, biến thể của tour';

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `transaction_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('REVENUE','EXPENSE') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Enum: REVENUE, EXPENSE',
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Ghi nhận tất cả giao dịch Thu/Chi';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh đại diện',
  `role_id` int UNSIGNED NOT NULL COMMENT 'FK tới roles',
  `tour_guide_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới tour_guides (chỉ cho HDV)',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tài khoản đăng nhập hệ thống';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `image`, `role_id`, `tour_guide_id`, `is_active`, `created_at`) VALUES
(1, 'admin', '$2y$10$wH8Qw6Qw6Qw6Qw6Qw6Qw6uQw6Qw6Qw6Qw6Qw6Qw6Qw6Qw6Qw6', NULL, 1, NULL, 1, '2025-12-01 17:36:53'),
(2, 'hdv', '$2y$10$8Qn8Qn8Qn8Qn8Qn8Qn8Qn8uQn8Qn8Qn8Qn8Qn8Qn8Qn8Qn8Qn8Qn8Qn8Qn8', NULL, 2, 1, 1, '2025-12-01 17:36:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment_requests`
--
ALTER TABLE `assignment_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departure_id` (`departure_id`),
  ADD KEY `tour_guide_id` (`tour_guide_id`),
  ADD KEY `requested_by_user_id` (`requested_by_user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `booking_guests`
--
ALTER TABLE `booking_guests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_booking_guest` (`booking_id`,`guest_phone`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `booking_members`
--
ALTER TABLE `booking_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `departures`
--
ALTER TABLE `departures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_version_id` (`tour_version_id`),
  ADD KEY `actual_guide_id` (`actual_guide_id`);

--
-- Indexes for table `departure_assignments`
--
ALTER TABLE `departure_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departure_id` (`departure_id`);

--
-- Indexes for table `departure_bookings`
--
ALTER TABLE `departure_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_departure_booking` (`departure_id`,`booking_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `departure_id` (`departure_id`);

--
-- Indexes for table `guide_performance_logs`
--
ALTER TABLE `guide_performance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_guide_id` (`tour_guide_id`),
  ADD KEY `departure_id` (`departure_id`);

--
-- Indexes for table `guide_schedules`
--
ALTER TABLE `guide_schedules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_guide_date` (`guide_id`,`schedule_date`);

--
-- Indexes for table `revenue_items`
--
ALTER TABLE `revenue_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tour_code` (`tour_code`),
  ADD KEY `fk_tours_category` (`category_id`),
  ADD KEY `fk_tour_partner` (`partner_id`);

--
-- Indexes for table `tour_assigned_services`
--
ALTER TABLE `tour_assigned_services`
  ADD PRIMARY KEY (`tour_id`,`service_id`);

--
-- Indexes for table `tour_categories`
--
ALTER TABLE `tour_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departure_id` (`departure_id`),
  ADD KEY `tour_guide_id` (`tour_guide_id`);

--
-- Indexes for table `tour_guides`
--
ALTER TABLE `tour_guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tour_id` (`tour_id`);

--
-- Indexes for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_itinerary_tour` (`tour_id`);

--
-- Indexes for table `tour_services`
--
ALTER TABLE `tour_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_version_id` (`tour_version_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `tour_suppliers`
--
ALTER TABLE `tour_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_tour_supplier` (`tour_id`,`supplier_id`),
  ADD KEY `fk_ts_supplier` (`supplier_id`);

--
-- Indexes for table `tour_versions`
--
ALTER TABLE `tour_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `tour_guide_id` (`tour_guide_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment_requests`
--
ALTER TABLE `assignment_requests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking_guests`
--
ALTER TABLE `booking_guests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `booking_members`
--
ALTER TABLE `booking_members`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departures`
--
ALTER TABLE `departures`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `departure_assignments`
--
ALTER TABLE `departure_assignments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `departure_bookings`
--
ALTER TABLE `departure_bookings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `guide_performance_logs`
--
ALTER TABLE `guide_performance_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `guide_schedules`
--
ALTER TABLE `guide_schedules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revenue_items`
--
ALTER TABLE `revenue_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tour_categories`
--
ALTER TABLE `tour_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `tour_guides`
--
ALTER TABLE `tour_guides`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tour_images`
--
ALTER TABLE `tour_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tour_services`
--
ALTER TABLE `tour_services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `tour_suppliers`
--
ALTER TABLE `tour_suppliers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tour_versions`
--
ALTER TABLE `tour_versions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `booking_guests`
--
ALTER TABLE `booking_guests`
  ADD CONSTRAINT `booking_guests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_guests_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `booking_members`
--
ALTER TABLE `booking_members`
  ADD CONSTRAINT `booking_members_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departures`
--
ALTER TABLE `departures`
  ADD CONSTRAINT `departures_ibfk_1` FOREIGN KEY (`tour_version_id`) REFERENCES `tour_versions` (`id`),
  ADD CONSTRAINT `departures_ibfk_2` FOREIGN KEY (`actual_guide_id`) REFERENCES `tour_guides` (`id`);

--
-- Constraints for table `departure_assignments`
--
ALTER TABLE `departure_assignments`
  ADD CONSTRAINT `departure_assignments_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`);

--
-- Constraints for table `departure_bookings`
--
ALTER TABLE `departure_bookings`
  ADD CONSTRAINT `departure_bookings_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`),
  ADD CONSTRAINT `departure_bookings_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD CONSTRAINT `expense_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `expense_items_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `expense_items_ibfk_3` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`);

--
-- Constraints for table `guide_schedules`
--
ALTER TABLE `guide_schedules`
  ADD CONSTRAINT `guide_schedules_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `tour_guides` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `revenue_items`
--
ALTER TABLE `revenue_items`
  ADD CONSTRAINT `revenue_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `revenue_items_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `fk_tour_partner` FOREIGN KEY (`partner_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tours_category` FOREIGN KEY (`category_id`) REFERENCES `tour_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tour_assigned_services`
--
ALTER TABLE `tour_assigned_services`
  ADD CONSTRAINT `fk_assign_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_diaries`
--
ALTER TABLE `tour_diaries`
  ADD CONSTRAINT `tour_diaries_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`),
  ADD CONSTRAINT `tour_diaries_ibfk_2` FOREIGN KEY (`tour_guide_id`) REFERENCES `tour_guides` (`id`);

--
-- Constraints for table `tour_images`
--
ALTER TABLE `tour_images`
  ADD CONSTRAINT `fk_tour_id` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tour_images_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_itineraries`
--
ALTER TABLE `tour_itineraries`
  ADD CONSTRAINT `fk_itinerary_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_services`
--
ALTER TABLE `tour_services`
  ADD CONSTRAINT `tour_services_ibfk_1` FOREIGN KEY (`tour_version_id`) REFERENCES `tour_versions` (`id`),
  ADD CONSTRAINT `tour_services_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `tour_suppliers`
--
ALTER TABLE `tour_suppliers`
  ADD CONSTRAINT `fk_ts_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ts_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tour_versions`
--
ALTER TABLE `tour_versions`
  ADD CONSTRAINT `tour_versions_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
