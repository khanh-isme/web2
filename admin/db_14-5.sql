SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `shoe`
--
DROP DATABASE IF EXISTS shoe;
CREATE DATABASE shoe;
USE shoe;
-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `fullname`, `status`, `role`) VALUES
(1, '0209', '$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy', 'Cao Cát Lượng', 'active', 'Người thừa'),
(2, '0033', '$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy', 'Từ Huy Bình', 'inactive', 'Ăn bám'),
(3, '0307', '$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy', 'Đinh Văn Thanh Sơn', 'active', 'Cục dàng'),
(4, '0161', '$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy', 'Dương Văn Khánh', 'active', 'Cứng đầu'),
(5, '0149', '$2y$12$C27QHh6j52Ju4QkV7yVNTuOeS.7uNCnbdKF3lr52ssb7BrROHZoCy', 'Dương Nguyễn Minh Khang', 'active', 'Nole');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `admin_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `admin_permissions`
--

INSERT INTO `admin_permissions` (`admin_id`, `perm_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 13),
(5, 14),
(5, 15),
(5, 16),
(5, 17),
(5, 18),
(5, 19),
(5, 20),
(5, 21);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `product_size_id`, `quantity`, `added_at`) VALUES
(1, 1, 1, 1, 2, '2025-03-29 01:28:56'),
(2, 2, 1, 2, 1, '2025-03-29 01:28:56'),
(3, 3, 2, 3, 3, '2025-03-29 01:28:56'),
(4, 4, 2, 4, 1, '2025-03-29 01:28:56'),
(5, 5, 3, 5, 2, '2025-03-29 01:28:56'),
(6, 6, 3, 6, 4, '2025-03-29 01:28:56'),
(7, 7, 4, 7, 2, '2025-03-29 01:28:56'),
(8, 8, 4, 8, 1, '2025-03-29 01:28:56'),
(9, 9, 5, 9, 3, '2025-03-29 01:28:56'),
(10, 10, 5, 10, 1, '2025-03-29 01:28:56'),
(11, 11, 6, 11, 2, '2025-03-29 01:28:56'),
(12, 12, 6, 12, 5, '2025-03-29 01:28:56'),
(13, 13, 6, 13, 1, '2025-03-29 01:28:56'),
(14, 14, 6, 14, 3, '2025-03-29 01:28:56'),
(15, 15, 6, 15, 2, '2025-03-29 01:28:56'),
(16, 16, 7, 16, 4, '2025-03-29 01:28:56'),
(17, 17, 7, 17, 1, '2025-03-29 01:28:56'),
(18, 18, 7, 18, 2, '2025-03-29 01:28:56'),
(19, 19, 7, 19, 3, '2025-03-29 01:28:56'),
(20, 20, 7, 20, 1, '2025-03-29 01:28:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Sneakers', 'Giày thể thao'),
(2, 'Boots', 'Giày boot thời trang'),
(3, 'Sandals', 'Dép và sandals'),
(4, 'Loafers', 'Giày lười'),
(5, 'Athletic', 'Giày thể thao chuyên dụng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `shipping_name` varchar(100) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `order_date`, `total_amount`, `status`, `shipping_name`, `shipping_phone`, `shipping_address`) VALUES
(1, 3, '2025-04-01 14:23:00', 500000.00, 'delivered', 'Nguyễn Văn A', '0987654321', '15 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội'),
(2, 5, '2025-04-12 10:15:00', 1800000.00, 'delivered', 'Trần Thị B', '0912345678', '89 Trần Quang Diệu, Quận 3, TP.HCM'),
(3, 2, '2025-04-05 18:30:00', 250000.00, 'delivered', 'Lê Văn C', '0909988777', '25 Nguyễn Đình Chiểu, Quận 1, TP.HCM'),
(4, 7, '2025-04-07 09:00:00', 300000.00, 'delivered', 'Phạm Thị D', '0988776655', '102 Trường Chinh, Thanh Xuân, Hà Nội'),
(5, 9, '2025-04-12 11:45:00', 890000.00, 'delivered', 'Vũ Minh E', '0945566777', '78 Phan Đình Phùng, Ba Đình, Hà Nội'),
(6, 4, '2025-04-09 15:20:00', 750000.00, 'delivered', 'Nguyễn Thị F', '0977333222', '12 Hoàng Văn Thụ, Phú Nhuận, TP.HCM'),
(7, 6, '2025-04-10 13:05:00', 590000.00, 'delivered', 'Đặng Văn G', '0933222111', '30 Nguyễn Văn Cừ, Long Biên, Hà Nội'),
(8, 1, '2025-04-12 16:40:00', 890000.00, 'delivered', 'Lương Thị H', '0966778899', '45 Lê Văn Sỹ, Phú Nhuận, TP.HCM'),
(9, 8, '2025-04-13 08:55:00', 630000.00, 'delivered', 'Phan Văn I', '0905112233', '23 Tô Hiến Thành, Quận 10, TP.HCM'),
(10, 10, '2025-04-14 17:30:00', 1050000.00, 'delivered', 'Đỗ Thị K', '0911223344', '68 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_size_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_size_id`, `quantity`, `price`) VALUES
(1, 1, 5, 2, 500000.00),
(2, 2, 10, 1, 1800000.00),
(3, 3, 15, 3, 250000.00),
(4, 4, 20, 5, 300000.00),
(5, 5, 25, 2, 890000.00),
(6, 6, 30, 4, 750000.00),
(7, 7, 35, 1, 590000.00),
(8, 8, 40, 3, 890000.00),
(9, 9, 45, 2, 630000.00),
(10, 10, 50, 4, 1050000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `permissions`
--

INSERT INTO `permissions` (`id`,`permission`) VALUES
('1','STATS'),
('2','PRODUCTS'),
('3','ORDERS'),
('4','CUSTOMERS'),
('5','EMPLOYEES'),
('6','SUPPLIERS'),
('7','ADD_PRODUCT'),
('8','EDIT_PRODUCT'),
('9','DELETE_PRODUCT'),
('10','MANAGE_ORDER_STATUS'),
('11','ADD_CUSTOMER'),
('12','EDIT_CUSTOMER'),
('13','DELETE_CUSTOMER'),
('14','ADD_EMPLOYEE'),
('15','EDIT_EMPLOYEE'),
('16','DELETE_EMPLOYEE'),
('17','ADD_SUPPLIER'),
('18','EDIT_SUPPLIER'),
('19','DELETE_SUPPLIER'),
('20','VIEW_RECEIPT'),
('21','ADD_RECEIPT');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `gender` enum('nam','nu','unisex') NOT NULL DEFAULT 'unisex',
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `description`, `image`, `created_at`, `gender`, `deleted`) VALUES
(1, 'Nike Air Force 1', 1, 13.48, 'Giày thể thao cổ điển', '/web2/assets/images/Gp75r2MawAIs0Ri.jpg', '2025-03-21 08:01:45', 'nam', 0),
(2, 'Adidas Ultraboost', 5, 2800000.00, 'Giày chạy bộ cao cấp', '/web2/assets/images/11.png', '2025-03-21 08:01:45', '', 0),
(3, 'Timberland Classic', 2, 3500000.00, 'Giày boot chống nước', '/web2/assets/images/11.png', '2025-03-21 08:01:45', 'nu', 0),
(4, 'Vans Old Skool', 1, 1500000.00, 'Giày trượt ván cổ điển', '/web2/assets/images/11.png', '2025-03-21 08:01:45', '', 0),
(5, 'Puma RS-X', 1, 2200000.00, 'Phong cách retro', '/web2/assets/images/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(6, 'Gucci Loafer', 4, 5500000.00, 'Giày lười thời trang cao cấp', '/web2/assets/images/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(7, 'Converse Chuck Taylor', 1, 1300000.00, 'Mẫu giày cao cổ huyền thoại', '/web2/assets/images/11.png', '2025-03-21 08:01:45', '', 0),
(8, 'Adidas Yeezy Boost', 5, 5000000.00, 'Giày sneakers phiên bản giới hạn', '/web2/assets/images/11.png', '2025-03-21 08:01:45', 'unisex', 0),
(9, 'Nike ZoomX', 5, 25.76, 'Giày chạy bộ siêu nhẹ', '/web2/assets/images/11.png', '2025-03-21 08:01:45', '', 0),
(10, 'Crocs Classic', 3, 900000.00, 'Dép thoải mái và nhẹ nhàng', '/web2/assets/images/11.png', '2025-03-21 08:01:45', 'nam', 0),
(11, 'Air Max 2025', 1, 120000.00, 'Giày thể thao cao cấp', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nam', 0),
(12, 'Winter Boot Pro', 2, 150000.00, 'Giày boot ấm áp mùa đông', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nu', 0),
(13, 'Casual Sandal', 3, 500000.00, 'Dép sandals thoáng mát', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(14, 'Leather Loafers', 4, 100000.00, 'Giày lười da sang trọng', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(15, 'Running Pro 3000', 5, 130000.00, 'Giày chạy bộ chuyên nghiệp', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(16, 'Sporty Runner', 1, 110000.00, 'Giày chạy bộ nhẹ', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(17, 'Winter Guard Boot', 2, 160000.00, 'Boot chống nước mùa đông', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nu', 0),
(18, 'Summer Sandal', 3, 450000.00, 'Dép mùa hè phong cách', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(19, 'Elegant Loafers', 4, 105000.00, 'Giày lười thanh lịch', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(20, 'Speed Track', 5, 140000.00, 'Giày thể thao tốc độ', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(21, 'Flex Sneakers', 1, 115000.00, 'Giày thể thao linh hoạt', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nam', 0),
(22, 'Mountain Boot', 2, 170000.00, 'Giày boot leo núi', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nu', 0),
(23, 'Beach Sandal', 3, 55000.00, 'Dép đi biển thời trang', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(24, 'Classic Loafers', 4, 95000.00, 'Giày lười cổ điển', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(25, 'Endurance Runner', 5, 135000.00, 'Giày chạy bộ bền bỉ', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(26, 'Elite Sneakers', 1, 125000.00, 'Giày thể thao đẳng cấp', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nam', 0),
(27, 'Arctic Boot', 2, 180000.00, 'Giày boot chống lạnh', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nu', 0),
(28, 'Urban Sandal', 3, 60000.00, 'Dép thành phố hiện đại', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(29, 'Formal Loafers', 4, 110000.00, 'Giày lười công sở', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(30, 'Marathon Pro', 5, 145000.00, 'Giày chạy marathon', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(31, 'Hyper Sneakers', 1, 130000.00, 'Giày sneaker phong cách', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nam', 0),
(32, 'Explorer Boot', 2, 175000.00, 'Boot khám phá địa hình', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nu', 0),
(33, 'Comfy Sandal', 3, 65000.00, 'Dép thoải mái mọi lúc', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(34, 'Casual Loafers', 4, 115000.00, 'Giày lười hàng ngày', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(35, 'Speed Runner X', 5, 150000.00, 'Giày chạy bộ nhanh', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(36, 'Active Sneakers', 1, 135000.00, 'Giày sneaker thể thao', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nam', 0),
(37, 'Trek Boot', 2, 185000.00, 'Boot trekking bền bỉ', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nu', 0),
(38, 'Summer Breeze Sandal', 3, 70000.00, 'Dép mùa hè thoáng mát', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(39, 'Stylish Loafers', 4, 120000.00, 'Giày lười thời trang', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(40, 'Pro Runner Elite', 5, 155000.00, 'Giày chạy bộ chuyên nghiệp', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(41, 'Dynamic Sneakers', 1, 140000.00, 'Giày thể thao năng động', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nam', 0),
(42, 'Alpine Boot', 2, 190000.00, 'Boot leo núi chuyên dụng', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'nu', 0),
(43, 'Outdoor Sandal', 3, 75000.00, 'Dép ngoài trời bền bỉ', '/web2/assets/images/14.png', '2025-03-22 01:09:11', 'unisex', 0),
(44, 'Luxury Loafers', 4, 125000.00, 'Giày lười cao cấp', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(45, 'Ultrafast Runner', 5, 160000.00, 'Giày chạy bộ tốc độ cao', '/web2/assets/images/14.png', '2025-03-22 01:09:11', '', 0),
(46, 'Giày Sneakers X1', 1, 59000.99, 'Giày sneakers phong cách trẻ trung.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nam', 0),
(47, 'Giày Sneakers X2', 1, 33.71, 'Thiết kế thể thao, năng động.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nu', 0),
(48, 'Giày Sneakers X3', 1, 70002.00, 'Màu sắc cá tính, phù hợp giới trẻ.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(49, 'Giày Boots B1', 2, 85000.99, 'Giày boot thời trang, phù hợp mùa đông.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nu', 0),
(50, 'Giày Boots B2', 2, 90000.75, 'Chất liệu da cao cấp, bền bỉ.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nam', 0),
(51, 'Giày Sandals S1', 3, 35000.99, 'Dép sandals nhẹ, thoáng khí.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', '', 0),
(52, 'Giày Sandals S2', 3, 40000.50, 'Dép đế cao, phong cách hiện đại.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', '', 0),
(53, 'Giày Loafers L1', 4, 55000.99, 'Giày lười tiện lợi, phù hợp công sở.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(54, 'Giày Loafers L2', 4, 60000.00, 'Chất liệu da sang trọng, dễ kết hợp trang phục.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nu', 0),
(55, 'Giày Thể Thao A1', 5, 78000.99, 'Giày thể thao chuyên dụng.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nam', 0),
(56, 'Giày Sneakers X4', 1, 62000.99, 'Thiết kế mới, đế cao su bền.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', '', 0),
(57, 'Giày Sneakers X5', 1, 60008.50, 'Phong cách streetwear.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nu', 0),
(58, 'Giày Boots B3', 2, 95000.99, 'Boots da thật, chống nước.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', '', 0),
(59, 'Giày Boots B4', 2, 99000.50, 'Boots cổ cao thời trang.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(60, 'Giày Sandals S3', 3, 38000.50, 'Sandal dây mềm mại.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nam', 0),
(61, 'Giày Sandals S4', 3, 42000.99, 'Dép quai ngang, thoải mái.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nu', 0),
(62, 'Giày Loafers L3', 4, 58000.75, 'Loafers phong cách Hàn Quốc.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', '', 0),
(63, 'Giày Loafers L4', 4, 63000.50, 'Giày lười nam lịch lãm.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(64, 'Giày Thể Thao A2', 5, 79000.99, 'Đế cao su chống trơn trượt.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', '', 0),
(65, 'Giày Thể Thao A3', 5, 82000.50, 'Giày nhẹ, bám sân tốt.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', '', 0),
(66, 'Giày Sneakers X6', 1, 64000.99, 'Sneakers đơn giản nhưng tinh tế.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nu', 0),
(67, 'Giày Boots B5', 2, 92000.00, 'Boots chống nước, chống trơn.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'nam', 0),
(68, 'Giày Sandals S5', 3, 37000.50, 'Sandal nữ đi biển.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', 'unisex', 0),
(69, 'Giày Loafers L5', 4, 61000.99, 'Giày lười da bò thật.', '/web2/assets/images/14.png', '2025-03-22 01:32:05', '', 0),
(70, 'Giày Thể Thao A4', 5, 12.08, 'Giày chạy bộ chuyên dụng.', '/web2/assets/images/bruh.jpg', '2025-03-22 01:32:05', 'nam', 0),
(72, 'MopNad', 2, 0.00, 'test', '/web2/assets/images/lamouch.png', '2025-05-13 15:02:29', '', 0),
(73, 'LaMouch', 2, 0.00, 'moladak', '/web2/assets/images/pzOaH.avif', '2025-05-13 15:18:09', '', 0),
(77, 'Chogstar', 2, 0.00, 'te', '/web2/assets/images/pzOaH.avif', '2025-05-13 15:51:04', 'nu', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_size`
--

CREATE TABLE `product_size` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_size`
--

INSERT INTO `product_size` (`id`, `product_id`, `size`, `stock`) VALUES
(1, 1, '40', 24),
(2, 1, '41', 137),
(3, 2, '42', 20),
(4, 2, '43', 18),
(5, 3, '40', 12),
(6, 3, '41', 14),
(7, 4, '39', 10),
(8, 4, '40', 20),
(9, 5, '41', 12),
(10, 5, '42', 16),
(11, 6, '38', 10),
(12, 6, '39', 15),
(13, 6, '40', 20),
(14, 6, '41', 25),
(15, 6, '42', 30),
(16, 7, '36', 12),
(17, 7, '37', 18),
(18, 7, '38', 22),
(19, 7, '39', 28),
(20, 7, '40', 32),
(21, 8, '39', 14),
(22, 8, '40', 20),
(23, 8, '41', 24),
(24, 8, '42', 26),
(25, 8, '43', 30),
(26, 9, '38', 10),
(27, 9, '39', 15),
(28, 9, '40', 20),
(29, 9, '41', 25),
(30, 9, '42', 42),
(31, 10, '40', 18),
(32, 10, '41', 22),
(33, 10, '42', 28),
(34, 10, '43', 32),
(35, 10, '44', 35),
(36, 11, '38', 12),
(37, 11, '39', 18),
(38, 11, '40', 22),
(39, 11, '41', 28),
(40, 11, '42', 32),
(41, 12, '36', 10),
(42, 12, '37', 15),
(43, 12, '38', 20),
(44, 12, '39', 25),
(45, 12, '40', 30),
(46, 13, '39', 14),
(47, 13, '40', 20),
(48, 13, '41', 24),
(49, 13, '42', 26),
(50, 13, '43', 30),
(51, 14, '38', 12),
(52, 14, '39', 18),
(53, 14, '40', 22),
(54, 14, '41', 28),
(55, 14, '42', 32),
(56, 15, '40', 16),
(57, 15, '41', 22),
(58, 15, '42', 28),
(59, 15, '43', 32),
(60, 15, '44', 35),
(61, 16, '38', 10),
(62, 16, '39', 15),
(63, 16, '40', 20),
(64, 16, '41', 25),
(65, 16, '42', 30),
(66, 17, '36', 12),
(67, 17, '37', 18),
(68, 17, '38', 22),
(69, 17, '39', 28),
(70, 17, '40', 32),
(71, 18, '39', 14),
(72, 18, '40', 20),
(73, 18, '41', 24),
(74, 18, '42', 26),
(75, 18, '43', 30),
(76, 19, '38', 10),
(77, 19, '39', 15),
(78, 19, '40', 20),
(79, 19, '41', 25),
(80, 19, '42', 30),
(81, 20, '40', 18),
(82, 20, '41', 22),
(83, 20, '42', 28),
(84, 20, '43', 32),
(85, 20, '44', 35),
(86, 21, '38', 12),
(87, 21, '39', 18),
(88, 21, '40', 22),
(89, 21, '41', 28),
(90, 21, '42', 32),
(91, 22, '36', 10),
(92, 22, '37', 15),
(93, 22, '38', 20),
(94, 22, '39', 25),
(95, 22, '40', 30),
(96, 23, '39', 14),
(97, 23, '40', 20),
(98, 23, '41', 24),
(99, 23, '42', 26),
(100, 23, '43', 30),
(101, 24, '38', 12),
(102, 24, '39', 18),
(103, 24, '40', 22),
(104, 24, '41', 28),
(105, 24, '42', 32),
(106, 25, '40', 16),
(107, 25, '41', 22),
(108, 25, '42', 28),
(109, 25, '43', 32),
(110, 25, '44', 35),
(111, 26, '38', 10),
(112, 26, '39', 15),
(113, 26, '40', 20),
(114, 26, '41', 25),
(115, 26, '42', 30),
(116, 27, '36', 12),
(117, 27, '37', 18),
(118, 27, '38', 22),
(119, 27, '39', 28),
(120, 27, '40', 32),
(121, 28, '39', 14),
(122, 28, '40', 20),
(123, 28, '41', 24),
(124, 28, '42', 26),
(125, 28, '43', 30),
(126, 29, '38', 10),
(127, 29, '39', 15),
(128, 29, '40', 20),
(129, 29, '41', 25),
(130, 29, '42', 30),
(131, 30, '40', 18),
(132, 30, '41', 22),
(133, 30, '42', 28),
(134, 30, '43', 32),
(135, 30, '44', 35),
(136, 31, '38', 10),
(137, 31, '39', 15),
(138, 31, '40', 20),
(139, 31, '41', 25),
(140, 31, '42', 30),
(141, 32, '36', 12),
(142, 32, '37', 18),
(143, 32, '38', 22),
(144, 32, '39', 28),
(145, 32, '40', 32),
(146, 33, '39', 14),
(147, 33, '40', 20),
(148, 33, '41', 24),
(149, 33, '42', 26),
(150, 33, '43', 30),
(151, 34, '38', 10),
(152, 34, '39', 15),
(153, 34, '40', 20),
(154, 34, '41', 25),
(155, 34, '42', 30),
(156, 35, '40', 18),
(157, 35, '41', 22),
(158, 35, '42', 28),
(159, 35, '43', 32),
(160, 35, '44', 35),
(161, 36, '38', 12),
(162, 36, '39', 18),
(163, 36, '40', 22),
(164, 36, '41', 28),
(165, 36, '42', 32),
(166, 37, '36', 10),
(167, 37, '37', 15),
(168, 37, '38', 20),
(169, 37, '39', 25),
(170, 37, '40', 30),
(171, 38, '39', 14),
(172, 38, '40', 20),
(173, 38, '41', 24),
(174, 38, '42', 26),
(175, 38, '43', 30),
(176, 39, '38', 12),
(177, 39, '39', 18),
(178, 39, '40', 22),
(179, 39, '41', 28),
(180, 39, '42', 32),
(181, 40, '40', 16),
(182, 40, '41', 22),
(183, 40, '42', 28),
(184, 40, '43', 32),
(185, 40, '44', 35),
(186, 41, '38', 10),
(187, 41, '39', 15),
(188, 41, '40', 20),
(189, 41, '41', 25),
(190, 41, '42', 30),
(191, 42, '36', 12),
(192, 42, '37', 18),
(193, 42, '38', 22),
(194, 42, '39', 28),
(195, 42, '40', 32),
(196, 43, '39', 14),
(197, 43, '40', 20),
(198, 43, '41', 24),
(199, 43, '42', 26),
(200, 43, '43', 30),
(201, 44, '38', 10),
(202, 44, '39', 15),
(203, 44, '40', 20),
(204, 44, '41', 25),
(205, 44, '42', 30),
(206, 45, '40', 18),
(207, 45, '41', 22),
(208, 45, '42', 28),
(209, 45, '43', 32),
(210, 45, '44', 35),
(211, 46, '38', 12),
(212, 46, '39', 18),
(213, 46, '40', 22),
(214, 46, '41', 28),
(215, 46, '42', 32),
(216, 47, '36', 10),
(217, 47, '37', 15),
(218, 47, '38', 20),
(219, 47, '39', 25),
(220, 47, '40', 56),
(221, 48, '39', 14),
(222, 48, '40', 20),
(223, 48, '41', 24),
(224, 48, '42', 26),
(225, 48, '43', 30),
(226, 49, '38', 12),
(227, 49, '39', 18),
(228, 49, '40', 22),
(229, 49, '41', 28),
(230, 49, '42', 32),
(231, 50, '40', 16),
(232, 50, '41', 22),
(233, 50, '42', 28),
(234, 50, '43', 32),
(235, 50, '44', 35),
(236, 51, '38', 10),
(237, 51, '39', 15),
(238, 51, '40', 20),
(239, 51, '41', 25),
(240, 51, '42', 30),
(241, 52, '36', 12),
(242, 52, '37', 18),
(243, 52, '38', 22),
(244, 52, '39', 28),
(245, 52, '40', 32),
(246, 53, '39', 14),
(247, 53, '40', 20),
(248, 53, '41', 24),
(249, 53, '42', 26),
(250, 53, '43', 30),
(251, 54, '38', 10),
(252, 54, '39', 15),
(253, 54, '40', 20),
(254, 54, '41', 25),
(255, 54, '42', 30),
(256, 55, '40', 18),
(257, 55, '41', 22),
(258, 55, '42', 28),
(259, 55, '43', 32),
(260, 55, '44', 35),
(261, 56, '38', 12),
(262, 56, '39', 18),
(263, 56, '40', 22),
(264, 56, '41', 28),
(265, 56, '42', 32),
(266, 57, '36', 10),
(267, 57, '37', 15),
(268, 57, '38', 20),
(269, 57, '39', 25),
(270, 57, '40', 30),
(271, 58, '39', 14),
(272, 58, '40', 20),
(273, 58, '41', 24),
(274, 58, '42', 26),
(275, 58, '43', 30),
(276, 59, '38', 10),
(277, 59, '39', 15),
(278, 59, '40', 20),
(279, 59, '41', 25),
(280, 59, '42', 30),
(281, 60, '40', 18),
(282, 60, '41', 22),
(283, 60, '42', 28),
(284, 60, '43', 32),
(285, 60, '44', 35),
(286, 61, '38', 12),
(287, 61, '39', 18),
(288, 61, '40', 22),
(289, 61, '41', 28),
(290, 61, '42', 32),
(291, 62, '36', 10),
(292, 62, '37', 15),
(293, 62, '38', 20),
(294, 62, '39', 25),
(295, 62, '40', 30),
(296, 63, '39', 14),
(297, 63, '40', 20),
(298, 63, '41', 24),
(299, 63, '42', 26),
(300, 63, '43', 30),
(301, 64, '38', 12),
(302, 64, '39', 18),
(303, 64, '40', 22),
(304, 64, '41', 28),
(305, 64, '42', 32),
(306, 65, '40', 16),
(307, 65, '41', 22),
(308, 65, '42', 28),
(309, 65, '43', 32),
(310, 65, '44', 35),
(311, 66, '38', 10),
(312, 66, '39', 15),
(313, 66, '40', 20),
(314, 66, '41', 25),
(315, 66, '42', 30),
(316, 67, '36', 12),
(317, 67, '37', 18),
(318, 67, '38', 22),
(319, 67, '39', 28),
(320, 67, '40', 32),
(321, 68, '39', 14),
(322, 68, '40', 20),
(323, 68, '41', 24),
(324, 68, '42', 26),
(325, 68, '43', 30),
(326, 69, '38', 10),
(327, 69, '39', 15),
(328, 69, '40', 20),
(329, 69, '41', 25),
(330, 69, '42', 30),
(331, 70, '40', 18),
(332, 70, '41', 22),
(333, 70, '42', 28),
(334, 70, '43', 32),
(335, 70, '44', 35);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `receipt_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `discount_percent` decimal(5,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `receipts`
--

INSERT INTO `receipts` (`id`, `supplier_id`, `receipt_date`, `total_amount`, `discount_percent`, `notes`) VALUES
(4, 1, '2025-04-25 23:36:28', 300.00, 12.00, 'Phiếu nhập từ form'),
(5, 8, '2025-04-25 23:41:33', 104.00, 12.00, 'Phiếu nhập từ form'),
(6, 15, '2025-05-13 17:30:21', 200.00, 23.00, 'Phiếu nhập từ form'),
(7, 15, '2025-05-13 17:36:44', 3276.00, 12.00, 'Phiếu nhập từ form'),
(8, 14, '2025-05-13 17:58:30', 960.00, 12.00, 'Phiếu nhập từ form'),
(9, 14, '2025-05-13 18:00:16', 12.00, 13.00, 'Phiếu nhập từ form'),
(10, 14, '2025-05-13 18:04:11', 559.00, 12.00, 'Phiếu nhập từ form'),
(11, 14, '2025-05-13 18:15:01', 34.00, 12.00, 'Phiếu nhập từ form'),
(12, 15, '2025-05-13 18:15:47', 34.00, 12.00, 'Phiếu nhập từ form'),
(13, 15, '2025-05-13 18:17:32', 22.00, 13.00, 'Phiếu nhập từ form'),
(14, 15, '2025-05-13 18:19:40', 86.00, 13.00, 'Phiếu nhập từ form'),
(15, 14, '2025-05-13 20:58:55', 36.09, 12.00, 'Phiếu nhập từ form'),
(16, 14, '2025-05-13 21:34:56', 108.30, 12.03, 'Phiếu nhập từ form');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `receipt_details`
--

CREATE TABLE `receipt_details` (
  `id` int(11) NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `product_size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` >= 0),
  `price` decimal(10,2) NOT NULL CHECK (`price` >= 0.00)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `receipt_details`
--

INSERT INTO `receipt_details` (`id`, `receipt_id`, `product_size_id`, `quantity`, `price`) VALUES
(5, 4, 1, 4, 30.00),
(6, 4, 2, 4, 30.00),
(7, 4, 335, 2, 30.00),
(8, 5, 29, 8, 13.00),
(9, 6, 220, 10, 20.00),
(10, 7, 2, 100, 30.00),
(11, 7, 30, 12, 23.00),
(12, 8, 1, 12, 40.00),
(13, 8, 2, 12, 40.00),
(14, 9, 1, 1, 12.00),
(15, 10, 220, 13, 43.00),
(16, 11, 1, 1, 34.00),
(17, 12, 2, 1, 34.00),
(18, 13, 220, 1, 22.00),
(19, 14, 2, 2, 43.00),
(20, 15, 2, 3, 12.03),
(21, 16, 2, 4, 12.03),
(22, 16, 220, 2, 30.09);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles_permissions`
--

CREATE TABLE `roles_permissions` (
  `role_id` int(11) NOT NULL,
  `perm_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `address`, `email`, `phone`, `created_at`, `deleted`) VALUES
(1, 'Supplier A Co.', 'khangdnm', 'HCM City', 'khangdnm@gmail.com', '0927468476', '2025-04-22 11:52:41', 1),
(6, 'Supplier F', 'Daniel Lee', '123 Street A', 'supplierF@example.com', '0901234567', '2025-04-22 01:35:47', 0),
(8, 'Supplier H', 'Kevin Nguyen', '789 Street C', 'supplierH@example.com', '0903456789', '2025-04-22 01:35:47', 0),
(9, 'Supplier I', 'Lily Tran', '321 Street D', 'supplierI@example.com', '0904567890', '2025-04-22 01:35:47', 0),
(10, 'Supplier J', 'David Kim', '654 Street E', 'supplierJ@example.com', '0905678901', '2025-04-22 01:35:47', 0),
(11, 'Supplier A', 'khangdnm', '123 Main St, Hanoi', 'duongnguyenminhkhang13@gmail.com', '0987654321', '2025-04-22 11:54:08', 0),
(13, 'Supplier M', 'Angela Zhang', '246 Street H', 'supplierM@example.com', '0908901234', '2025-04-22 01:35:47', 0),
(14, 'MopLab', 'KaiNad', 'Hong Kong', 'moplab@gmail.com', '0927468476', '2025-05-13 15:52:21', 0),
(15, 'MopLab', 'KaiNad', 'Hong Kong', 'moplab@gmail.com', '0927468476', '2025-05-13 16:26:46', 0),
(16, 'Supplier A Co.', 'khangdnm', 'dfdfd', 'khangdnm@gmail.com', '0123456789', '2025-05-13 17:09:33', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active'
) ;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `phone`, `address`, `status`) VALUES
(1, 'nguyenvana', 'Nguyễn Văn A', 'nguyenvana@example.com', '123456', '0987654321', 'Hà Nội', 'active'),
(2, 'tranthib', 'Trần Thị B', 'tranthib@example.com', 'abcdef', '0977123456', 'TP HCM', 'active'),
(3, 'levanc', 'Lê Văn C', 'levanc@example.com', 'pass123', '0912345678', 'Đà Nẵng', 'active'),
(4, 'hoangthid', 'Hoàng Thị D', 'hoangthid@example.com', 'securepass', '0933221144', 'Cần Thơ', 'active'),
(5, 'phamvane', 'Phạm Văn E', 'phamvane@example.com', 'e123456', '0966554433', 'Hải Phòng', 'active'),
(6, 'vuthif', 'Vũ Thị F', 'vuthif@example.com', 'fpassword', '0922113344', 'Huế', 'active'),
(7, 'buivang', 'Bùi Văn G', 'buivang@example.com', 'gpass', '0944556677', 'Nha Trang', 'active'),
(8, 'dangthih', 'Đặng Thị H', 'dangthih@example.com', 'hpassword', '0988223344', 'Vũng Tàu', 'active'),
(9, 'ngovani', 'Ngô Văn I', 'ngovani@example.com', 'ipass123', '0911998877', 'Quảng Ninh', 'active'),
(10, 'duongthij', 'Dương Thị J', 'duongthij@example.com', 'jpassword', '0955332244', 'Bắc Ninh', 'active'),
(11, 'trinhvank', 'Trịnh Văn K', 'trinhvank@example.com', 'ksecure', '0999223344', 'Thanh Hóa', 'active'),
(12, 'doanthil', 'Đoàn Thị L', 'doanthil@example.com', 'lpass', '0944559922', 'Nam Định', 'active'),
(13, 'maivanm', 'Mai Văn M', 'maivanm@example.com', 'mpassword', '0900557788', 'Hòa Bình', 'active'),
(14, 'chauthin', 'Châu Thị N', 'chauthin@example.com', 'npass', '0933225566', 'Bình Dương', 'active'),
(15, 'luongvano', 'Lương Văn O', 'luongvano@example.com', 'opassword', '0977448822', 'Lạng Sơn', 'active'),
(16, 'phanthip', 'Phan Thị P', 'phanthip@example.com', 'ppass123', '0966991155', 'Quảng Bình', 'active'),
(17, 'caovanq', 'Cao Văn Q', 'caovanq@example.com', 'qpassword', '0922334455', 'Cà Mau', 'active'),
(18, 'tatheir', 'Tạ Thị R', 'tatheir@example.com', 'rpass', '0999887766', 'Kon Tum', 'active'),
(19, 'truongvans', 'Trương Văn S', 'truongvans@example.com', 'spassword', '0900223344', 'Bắc Giang', 'active'),
(20, 'lythit', 'Lý Thị T', 'lythit@example.com', 'tpass123', '0944778899', 'Đắk Lắk', 'active');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Chỉ mục cho bảng `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`admin_id`,`perm_id`),
  ADD KEY `perm_id` (`perm_id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `product_size_id` (`product_size_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_product_size_id` (`product_size_id`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_size`
--
ALTER TABLE `product_size`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `receipt_details`
--
ALTER TABLE `receipt_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receipt_id` (`receipt_id`),
  ADD KEY `product_size_id` (`product_size_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD PRIMARY KEY (`role_id`,`perm_id`),
  ADD KEY `perm_id` (`perm_id`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT cho bảng `product_size`
--
ALTER TABLE `product_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=336;

--
-- AUTO_INCREMENT cho bảng `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `receipt_details`
--
ALTER TABLE `receipt_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD CONSTRAINT `admin_permissions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_permissions_ibfk_2` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_product_size_id` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`),
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_size`
--
ALTER TABLE `product_size`
  ADD CONSTRAINT `product_size_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `receipt_details`
--
ALTER TABLE `receipt_details`
  ADD CONSTRAINT `receipt_details_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `receipt_details_ibfk_2` FOREIGN KEY (`product_size_id`) REFERENCES `product_size` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `roles_permissions`
--
ALTER TABLE `roles_permissions`
  ADD CONSTRAINT `roles_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `roles_permissions_ibfk_2` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
