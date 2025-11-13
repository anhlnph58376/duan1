-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th10 13, 2025 lúc 10:54 AM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `duan1_tuor`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `customer_id` int UNSIGNED NOT NULL COMMENT 'FK tới customers (người đại diện)',
  `booking_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_date` datetime NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `deposit_amount` decimal(15,2) DEFAULT '0.00',
  `status` enum('Pending','Deposited','Completed','Canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending' COMMENT 'Chờ xác nhận, Đã cọc, Hoàn tất, Hủy'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Hồ sơ đặt chỗ (booking) của khách hàng';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_guests`
--

CREATE TABLE `booking_guests` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `booking_id` int UNSIGNED NOT NULL COMMENT 'FK tới bookings',
  `customer_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới customers (Nếu khách đã có trong hệ thống)',
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guest_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Các yêu cầu đặc biệt của khách hàng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách khách trong đoàn';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `history_notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Khách hàng mua tour';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departures`
--

CREATE TABLE `departures` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_version_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_versions',
  `departure_date` datetime NOT NULL,
  `return_date` datetime NOT NULL,
  `actual_guide_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới tour_guides',
  `status` enum('Scheduled','In Progress','Completed','Canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Scheduled' COMMENT 'Khởi hành, Hoàn thành, Hủy',
  `min_pax` int UNSIGNED DEFAULT '1',
  `max_pax` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Định nghĩa một chuyến đi thực tế';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departure_assignments`
--

CREATE TABLE `departure_assignments` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `departure_id` int UNSIGNED NOT NULL COMMENT 'FK tới departures',
  `assigned_type` enum('HDV','Driver','Vehicle','Other') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Enum: HDV, Driver, Vehicle',
  `assigned_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_guides hoặc bảng khác (tùy thuộc assigned_type)',
  `assignment_notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân bổ nhân sự/dịch vụ cho chuyến khởi hành';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departure_bookings`
--

CREATE TABLE `departure_bookings` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `departure_id` int UNSIGNED NOT NULL COMMENT 'FK tới departures',
  `booking_id` int UNSIGNED NOT NULL COMMENT 'FK tới bookings',
  `pax_count` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Liên kết Booking với chuyến khởi hành cụ thể';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `expense_items`
--

CREATE TABLE `expense_items` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `transaction_id` int UNSIGNED NOT NULL COMMENT 'FK tới transactions',
  `supplier_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới suppliers (nếu có)',
  `departure_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới departures (chi phí cho chuyến đi)',
  `cost_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại chi phí (ví dụ: Ăn uống, Vé tham quan, Vận chuyển)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết các khoản Chi';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `revenue_items`
--

CREATE TABLE `revenue_items` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `transaction_id` int UNSIGNED NOT NULL COMMENT 'FK tới transactions',
  `booking_id` int UNSIGNED NOT NULL COMMENT 'FK tới bookings',
  `payment_method` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_deposit` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi tiết các khoản Thu (Liên kết với Booking)';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `role_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Vai trò';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_type` enum('Hotel','Transport','Restaurant','Other') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Enum: Hotel, Transport, ...',
  `address` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách đối tác, nhà cung cấp dịch vụ';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tours`
--

CREATE TABLE `tours` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ví dụ: 3N2Đ',
  `base_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_international` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông tin chung của một tour';

--
-- Đang đổ dữ liệu cho bảng `tours`
--

INSERT INTO `tours` (`id`, `tour_code`, `name`, `image`, `description`, `duration`, `base_price`, `is_international`) VALUES
(1, 'PHUQUOC_2N1D', 'Phú quốc', 'uploads/tours/nhuocnam.jpg', 'thông tin chi tiết', '2n3đ', 3200000.00, 0),
(2, 'HANOI_SAPA', 'Hà Nội Sapa', 'uploads/tours/asus.jpg', 'Không có gì', '3 Ngày 2 đêm', 2900000.00, 0),
(6, 'HANOI_SAPA_4_DEM', 'Hà Nội Sapa', 'uploads/tours/911992049f2c857941640ae35f423cce.jpg', 'Không có gì', '3 Ngày 4 đêm', 2900000.00, 0),
(8, 'HANQUOC_2N1D', 'Hàn quốc', 'uploads/tours/tải xuống (9).jpg', 'Hiệu năng mạnh mẽ: Chạy tốt các game nặng, đồ họa 3D, lập trình, chỉnh sửa video.', '2n1đ', 10000000.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_diaries`
--

CREATE TABLE `tour_diaries` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `departure_id` int UNSIGNED NOT NULL COMMENT 'FK tới departures',
  `tour_guide_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_guides',
  `timestamp` datetime NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `incident_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sự cố, phản hồi',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhật ký tour, sự cố, phản hồi của HDV';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_guides`
--

CREATE TABLE `tour_guides` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `user_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới users (nếu HDV có tài khoản login)',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive','Busy') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Hồ sơ Hướng dẫn viên (HDV)';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_images`
--

CREATE TABLE `tour_images` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_id` int UNSIGNED NOT NULL COMMENT 'FK tới tours',
  `name_img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Ảnh của một tour';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_services`
--

CREATE TABLE `tour_services` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_version_id` int UNSIGNED NOT NULL COMMENT 'FK tới tour_versions',
  `supplier_id` int UNSIGNED DEFAULT NULL COMMENT 'FK tới suppliers',
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_cost` decimal(15,2) NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chi phí/dịch vụ cố định cho một phiên bản tour';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_versions`
--

CREATE TABLE `tour_versions` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `tour_id` int UNSIGNED NOT NULL COMMENT 'FK tới tours',
  `version_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `base_price` decimal(15,2) NOT NULL,
  `status` enum('Draft','Active','Archived','Promotion') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Các phiên bản, biến thể của tour';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transactions`
--

CREATE TABLE `transactions` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `transaction_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('REVENUE','EXPENSE') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Enum: REVENUE, EXPENSE',
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` datetime NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Ghi nhận tất cả giao dịch Thu/Chi';

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL COMMENT 'PK',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int UNSIGNED NOT NULL COMMENT 'FK tới roles',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý người dùng hệ thống';

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `booking_guests`
--
ALTER TABLE `booking_guests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_booking_guest` (`booking_id`,`guest_phone`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `departures`
--
ALTER TABLE `departures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_version_id` (`tour_version_id`),
  ADD KEY `actual_guide_id` (`actual_guide_id`);

--
-- Chỉ mục cho bảng `departure_assignments`
--
ALTER TABLE `departure_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departure_id` (`departure_id`);

--
-- Chỉ mục cho bảng `departure_bookings`
--
ALTER TABLE `departure_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_departure_booking` (`departure_id`,`booking_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `departure_id` (`departure_id`);

--
-- Chỉ mục cho bảng `revenue_items`
--
ALTER TABLE `revenue_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tour_code` (`tour_code`);

--
-- Chỉ mục cho bảng `tour_diaries`
--
ALTER TABLE `tour_diaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departure_id` (`departure_id`),
  ADD KEY `tour_guide_id` (`tour_guide_id`);

--
-- Chỉ mục cho bảng `tour_guides`
--
ALTER TABLE `tour_guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `tour_images`
--
ALTER TABLE `tour_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_services`
--
ALTER TABLE `tour_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_version_id` (`tour_version_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `tour_versions`
--
ALTER TABLE `tour_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `booking_guests`
--
ALTER TABLE `booking_guests`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `departures`
--
ALTER TABLE `departures`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `departure_assignments`
--
ALTER TABLE `departure_assignments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `departure_bookings`
--
ALTER TABLE `departure_bookings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `revenue_items`
--
ALTER TABLE `revenue_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `tour_diaries`
--
ALTER TABLE `tour_diaries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `tour_guides`
--
ALTER TABLE `tour_guides`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `tour_images`
--
ALTER TABLE `tour_images`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `tour_services`
--
ALTER TABLE `tour_services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `tour_versions`
--
ALTER TABLE `tour_versions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK';

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Ràng buộc cho bảng `booking_guests`
--
ALTER TABLE `booking_guests`
  ADD CONSTRAINT `booking_guests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_guests_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Ràng buộc cho bảng `departures`
--
ALTER TABLE `departures`
  ADD CONSTRAINT `departures_ibfk_1` FOREIGN KEY (`tour_version_id`) REFERENCES `tour_versions` (`id`),
  ADD CONSTRAINT `departures_ibfk_2` FOREIGN KEY (`actual_guide_id`) REFERENCES `tour_guides` (`id`);

--
-- Ràng buộc cho bảng `departure_assignments`
--
ALTER TABLE `departure_assignments`
  ADD CONSTRAINT `departure_assignments_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`);

--
-- Ràng buộc cho bảng `departure_bookings`
--
ALTER TABLE `departure_bookings`
  ADD CONSTRAINT `departure_bookings_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`),
  ADD CONSTRAINT `departure_bookings_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Ràng buộc cho bảng `expense_items`
--
ALTER TABLE `expense_items`
  ADD CONSTRAINT `expense_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `expense_items_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `expense_items_ibfk_3` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`);

--
-- Ràng buộc cho bảng `revenue_items`
--
ALTER TABLE `revenue_items`
  ADD CONSTRAINT `revenue_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `revenue_items_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

--
-- Ràng buộc cho bảng `tour_diaries`
--
ALTER TABLE `tour_diaries`
  ADD CONSTRAINT `tour_diaries_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`id`),
  ADD CONSTRAINT `tour_diaries_ibfk_2` FOREIGN KEY (`tour_guide_id`) REFERENCES `tour_guides` (`id`);

--
-- Ràng buộc cho bảng `tour_guides`
--
ALTER TABLE `tour_guides`
  ADD CONSTRAINT `tour_guides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `tour_images`
--
ALTER TABLE `tour_images`
  ADD CONSTRAINT `tour_images_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Ràng buộc cho bảng `tour_services`
--
ALTER TABLE `tour_services`
  ADD CONSTRAINT `tour_services_ibfk_1` FOREIGN KEY (`tour_version_id`) REFERENCES `tour_versions` (`id`),
  ADD CONSTRAINT `tour_services_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`);

--
-- Ràng buộc cho bảng `tour_versions`
--
ALTER TABLE `tour_versions`
  ADD CONSTRAINT `tour_versions_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
