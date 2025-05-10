-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 02:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database* Database: `shoebase`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`, `role_id`, `created_at`) VALUES
(1, 'admin', '$2y$10$8rW/joY4tV7Qz7m7h3X9k.3e7k3m9vQzY6z3vQzY6z3vQzY6z3vQz', 'admin@example.com', 1, '2025-03-21 08:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `admin_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_permissions`
--

INSERT INTO `admin_permissions` (`admin_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_size_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 1, 2, 1),
(3, 2, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Sneakers', 'Giày thể thao thời trang', '2025-03-21 08:01:45'),
(2, 'Boots', 'Giày cao cổ phù hợp mùa đông', '2025-03-21 08:01:45'),
(3, 'Sandals', 'Dép thoáng mát cho mùa hè', '2025-03-21 08:01:45'),
(4, 'Loafers', 'Giày lười thanh lịch', '2025-03-21 08:01:45'),
(5, 'Running', 'Giày chuyên dụng cho chạy bộ', '2025-03-21 08:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Summer 2025', 'Bộ sưu tập mùa hè 2025', '2025-03-21 08:01:45'),
(2, 'Winter 2025', 'Bộ sưu tập mùa đông 2025', '2025-03-21 08:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `created_at`, `shipping_address`, `payment_method`) VALUES
(1, 1, 4500000.00, 'pending', '2025-03-21 08:01:45', '123 Đường Láng, Hà Nội', 'COD'),
(2, 2, 2200000.00, 'processing', '2025-03-21 08:01:45', '456 Nguyễn Trãi, TP.HCM', 'Bank Transfer');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_size_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 1500000.00),
(2, 1, 2, 2, 1500000.00),
(3, 2, 3, 1, 2200000.00);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`) VALUES
(1, 'manage_products', 'Quản lý sản phẩm'),
(2, 'manage_orders', 'Quản lý đơn hàng'),
(3, 'manage_users', 'Quản lý người dùng'),
(4, 'manage_categories', 'Quản lý danh mục');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` enum('nam','nu','unisex') NOT NULL DEFAULT 'unisex',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `description`, `image`, `created_at`, `gender`, `deleted`) VALUES
(1, 'Nike Air Force', 2, 33.00, 'Giày thể thao cổ điển', 'uploads/products/11.png', '2025-03-21 08:01:45', 'nam', 0),
(2, 'Adidas Ultraboost', 5, 25.00, 'Giày chạy bộ cao cấp', 'uploads/products/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(3, 'Timberland Classic', 2, 3500000.00, 'Giày boot chống nước', 'uploads/products/11.png', '2025-03-21 08:01:45', 'nam', 0),
(4, 'Vans Old Skool', 1, 1500000.00, 'Giày trượt ván cổ điển', 'uploads/products/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(5, 'Puma RS-X', 1, 2200000.00, 'Phong cách retro', 'uploads/products/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(6, 'Gucci Loafer', 4, 5500000.00, 'Giày lười thời trang cao cấp', 'uploads/products/11.png', '2025-03-21 08:01:45', 'nu', 0),
(7, 'Converse Chuck Taylor', 1, 1300000.00, 'Mẫu giày cao cổ huyền thoại', 'uploads/products/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(8, 'Adidas Yeezy Boost', 5, 5000000.00, 'Giày sneakers phiên bản giới hạn', 'uploads/products/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(9, 'Nike ZoomX', 5, 14.56, 'Giày chạy bộ siêu nhẹ', 'uploads/products/11.png', '2025-03-21 08:01:45', 'nam', 0),
(10, 'Crocs Classic', 3, 900000.00, 'Dép thoải mái và nhẹ nhàng', 'uploads/products/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(11, 'Air Max 2024', 1, 120.00, 'Giày thể thao cao cấp', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(12, 'Winter Boot Pro', 2, 150.00, 'Giày boot ấm áp mùa đông', 'uploads/products/14.png', '2025-03-22 01:09:11', 'nu', 0),
(13, 'Casual Sandal', 3, 50.00, 'Dép sandals thoáng mát', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(14, 'Leather Loafers', 4, 100.00, 'Giày lười da sang trọng', 'uploads/products/14.png', '2025-03-22 01:09:11', 'nam', 0),
(15, 'Running Pro 3000', 5, 130.00, 'Giày chạy bộ chuyên nghiệp', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(16, 'Sporty Runner', 1, 110.00, 'Giày chạy bộ nhẹ', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(17, 'Winter Guard Boot', 2, 160.00, 'Boot chống nước mùa đông', 'uploads/products/14.png', '2025-03-22 01:09:11', 'nu', 0),
(18, 'Summer Sandal', 3, 45.00, 'Dép mùa hè phong cách', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(19, 'Elegant Loafers', 4, 105.00, 'Giày lười thanh lịch', 'uploads/products/14.png', '2025-03-22 01:09:11', 'nam', 0),
(20, 'Speed Track', 5, 140.00, 'Giày thể thao tốc độ', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(21, 'Flex Sneakers', 1, 115.00, 'Giày thể thao linh hoạt', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(22, 'Mountain Boot', 2, 170.00, 'Giày boot leo núi', 'uploads/products/14.png', '2025-03-22 01:09:11', 'nam', 0),
(23, 'Beach Sandal', 3, 55.00, 'Dép đi biển thời trang', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(24, 'Classic Loafers', 4, 95.00, 'Giày lười cổ điển', 'uploads/products/14.png', '2025-03-22 01:09:11', 'nam', 0),
(25, 'Endurance Runner', 5, 135.00, 'Giày chạy bộ bền bỉ', 'uploads/products/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(26, 'Elite Sneakers', 1, 125.00, 'Giày thể thao đẳng cấp', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(27, 'Arctic Boot', 2, 180.00, 'Giày boot chống lạnh', 'uploads/products/14.png', '2025-03-22 01:11:00', 'nu', 0),
(28, 'Urban Sandal', 3, 60.00, 'Dép thành phố hiện đại', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(29, 'Formal Loafers', 4, 110.00, 'Giày lười công sở', 'uploads/products/14.png', '2025-03-22 01:11:00', 'nam', 0),
(30, 'Marathon Pro', 5, 145.00, 'Giày chạy marathon', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(31, 'Hyper Sneakers', 1, 130.00, 'Giày sneaker phong cách', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(32, 'Explorer Boot', 2, 175.00, 'Boot khám phá địa hình', 'uploads/products/14.png', '2025-03-22 01:11:00', 'nam', 0),
(33, 'Comfy Sandal', 3, 65.00, 'Dép thoải mái mọi lúc', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(34, 'Casual Loafers', 4, 115.00, 'Giày lười hàng ngày', 'uploads/products/14.png', '2025-03-22 01:11:00', 'nam', 0),
(35, 'Speed Runner X', 5, 150.00, 'Giày chạy bộ nhanh', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(36, 'Active Sneakers', 1, 135.00, 'Giày sneaker thể thao', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(37, 'Trek Boot', 2, 185.00, 'Boot trekking bền bỉ', 'uploads/products/14.png', '2025-03-22 01:11:00', 'nam', 0),
(38, 'Summer Breeze Sandal', 3, 70.00, 'Dép mùa hè thoáng mát', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(39, 'Stylish Loafers', 4, 120.00, 'Giày lười thời trang', 'uploads/products/14.png', '2025-03-22 01:11:00', 'nam', 0),
(40, 'Pro Runner Elite', 5, 155.00, 'Giày chạy bộ chuyên nghiệp', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(41, 'Dynamic Sneakers', 1, 140.00, 'Giày thể thao năng động', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(42, 'Alpine Boot', 2, 190.00, 'Boot leo núi chuyên dụng', 'uploads/products/14.png', '2025-03-22 01:11:00', 'nam', 0),
(43, 'Outdoor Sandal', 3, 75.00, 'Dép ngoài trời bền bỉ', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(44, 'Luxury Loafers', 4, 125.00, 'Giày lười cao cấp', 'uploads/products/14.png', '2025-03-22 01:11:00', 'nam', 0),
(45, 'Ultrafast Runner', 5, 160.00, 'Giày chạy bộ tốc độ cao', 'uploads/products/14.png', '2025-03-22 01:11:00', 'unisex', 0),
(46, 'Giày Sneakers X1', 1, 59.99, 'Giày sneakers phong cách trẻ trung.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(47, 'Giày Sneakers X2', 1, 65.50, 'Thiết kế thể thao, năng động.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(48, 'Giày Sneakers X3', 1, 72.00, 'Màu sắc cá tính, phù hợp giới trẻ.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(49, 'Giày Boots B1', 2, 85.99, 'Giày boot thời trang, phù hợp mùa đông.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'nam', 0),
(50, 'Giày Boots B2', 2, 90.75, 'Chất liệu da cao cấp, bền bỉ.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'nu', 0),
(51, 'Giày Sandals S1', 3, 35.99, 'Dép sandals nhẹ, thoáng khí.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(52, 'Giày Sandals S2', 3, 40.50, 'Dép đế cao, phong cách hiện đại.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'nu', 0),
(53, 'Giày Loafers L1', 4, 55.99, 'Giày lười tiện lợi, phù hợp công sở.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'nam', 0),
(54, 'Giày Loafers L2', 4, 60.00, 'Chất liệu da sang trọng, dễ kết hợp trang phục.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'nu', 0),
(55, 'Giày Thể Thao A1', 5, 78.99, 'Giày thể thao chuyên dụng.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(56, 'Giày Sneakers X4', 1, 62.99, 'Thiết kế mới, đế cao su bền.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(57, 'Giày Sneakers X5', 1, 68.50, 'Phong cách streetwear.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(58, 'Giày Boots B3', 2, 95.99, 'Boots da thật, chống nước.', 'uploads/products/14.png', '2025-03-22 01:32:05', 'nam', 0),
-- Table structure for table `product_size`
--

CREATE TABLE `product_size` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_size`
--

INSERT INTO `product_size` (`id`, `product_id`, `size`, `stock`) VALUES
(1, 1, '38', 50),
(2, 1, '39', 30),
(3, 2, '40', 20),
(4, 3, '41', 15),
(5, 4, '42', 25),
(337, 1, '38', 42);

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL,
  `receipt_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `supplier_id`, `receipt_date`, `total_amount`, `discount_percent`, `notes`) VALUES
(4, 1, '2025-04-25 23:36:28', 300.00, 12.00, 'Phiếu nhập từ form'),
(5, 8, '2025-04-25 23:41:33', 104.00, 12.00, 'Phiếu nhập từ form');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_details`
--

CREATE TABLE `receipt_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receipt_id` int(11) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` >= 0),
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0.00),
  PRIMARY KEY (`id`),
  KEY `receipt_id` (`receipt_id`),
  KEY `product_size_id` (`product_size_id`),
  CONSTRAINT `receipt_details_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `receipt_details_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt_details`
--

INSERT INTO `receipt_details` (`id`, `receipt_id`, `product_size_id`, `quantity`, `price`) VALUES
(5, 4, 1, 4, 30.00),
(6, 4, 2, 4, 30.00),
(7, 4, 337, 2, 30.00),
(8, 5, 29, 8, 13.00);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`) VALUES
(1, 'Super Admin', 'Quyền quản trị tối cao'),
(2, 'Editor', 'Quyền chỉnh sửa nội dung'),
(3, 'Viewer', 'Quyền xem thông tin');

-- --------------------------------------------------------

--
-- Table structure for table `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles_permissions`
--

INSERT INTO `roles_permissions` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 4),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `address`, `email`, `phone`, `created_at`) VALUES
(1, 'Supplier A Co.', 'khangdnm', 'HCM City', 'duongnguyenminhkhang13@gmail.com', '0987654321', '2025-04-22 11:52:41'),
(6, 'Supplier F', 'Daniel Lee', '123 Street A', 'supplierF@example.com', '0901234567', '2025-04-22 01:35:47'),
(8, 'Supplier H', 'Kevin Nguyen', '789 Street C', 'supplierH@example.com', '0903456789', '2025-04-22 01:35:47'),
(9, 'Supplier I', 'Lily Tran', '321 Street D', 'supplierI@example.com', '0904567890', '2025-04-22 01:35:47'),
(10, 'Supplier J', 'David Kim', '654 Street E', 'supplierJ@example.com', '0905678901', '2025-04-22 01:35:47'),
(11, 'Supplier A', 'khangdnm', '123 Main St, Hanoi', 'duongnguyenminhkhang13@gmail.com', '098 Conserv7654321', '2025-04-22 11:54:08'),
(13, 'Supplier M', 'Angela Zhang', '246 Street H', 'supplierM@example.com', '0908901234', '2025-04-22 01:35:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `address`, `created_at`) VALUES
(1, 'user1', '$2y$10$7QzY6z3vQzY6z3vQzY6z3vQzY6z3vQzY6z3vQzY6z3vQzY6z3vQz', 'user1@example.com', '0123456789', '123 Đường Láng, Hà Nội', '2025-03-21 08:01:45'),
(2, 'user2', '$2y$10$7QzY6z3vQzY6z3vQzY6z3vQzY6z3vQzY6z3vQzY6z3vQzY6z3vQz', 'user2@example.com', '0987654321', '456 Nguyễn Trãi, TP.HCM', '2025-03-21 08:01:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`admin_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_size_id` (`product_size_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_size_id` (`product_size_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_size`
--
ALTER TABLE `product_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `receipt_details`
--
ALTER TABLE `receipt_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_id` (`receipt_id`),
  ADD KEY `product_size_id` (`product_size_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `product_size`
--
ALTER TABLE `product_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `receipt_details`
--
ALTER TABLE `receipt_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD CONSTRAINT `admin_permissions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_size`
--
ALTER TABLE `product_size`
  ADD CONSTRAINT `product_size_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `receipt_details`
--
ALTER TABLE `receipt_details`
  ADD CONSTRAINT `receipt_details_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_details_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD CONSTRAINT `roles_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;