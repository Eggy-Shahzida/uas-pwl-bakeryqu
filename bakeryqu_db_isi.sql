-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2026 at 11:25 AM
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
-- Database: `bakeryqu_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, '2026-07-07 08:16:21', '2026-07-07 08:16:21');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Roti', 'roti', '2026-07-06 09:34:26'),
(2, 'Donat', 'donat', '2026-07-06 09:34:26'),
(3, 'Cake', 'cake', '2026-07-06 09:34:26'),
(4, 'Pastry', 'pastry', '2026-07-06 09:34:26'),
(5, 'Cookies', 'cookies', '2026-07-06 09:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `province_id` varchar(20) NOT NULL,
  `province_name` varchar(100) NOT NULL,
  `city_id` varchar(20) NOT NULL,
  `city_name` varchar(100) NOT NULL,
  `district_id` varchar(20) NOT NULL,
  `district_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `note` text DEFAULT NULL,
  `courier` varchar(50) NOT NULL,
  `service` varchar(50) NOT NULL,
  `shipping_cost` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `status` enum('pending','processing','shipping','completed','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `receiver_name`, `phone`, `province_id`, `province_name`, `city_id`, `city_name`, `district_id`, `district_name`, `address`, `note`, `courier`, `service`, `shipping_cost`, `subtotal`, `total`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ORD-20260708-7806B6', 2, 'eggy shahzida', '123456789999', '12', 'JAWA TENGAH', '544', 'SUKOHARJO', '5348', 'BULU', 'bulu lor', NULL, 'jne', 'REG', 18000.00, 48000.00, 66000.00, 'pending', '2026-07-08 08:07:19', '2026-07-08 08:07:19'),
(2, 'ORD-20260708-C0C48B', 2, 'eggy shahzida', '123456789999', '12', 'JAWA TENGAH', '544', 'SUKOHARJO', '5348', 'BULU', 'bulu lor', NULL, 'jne', 'REG', 18000.00, 48000.00, 66000.00, 'rejected', '2026-07-08 08:08:44', '2026-07-09 08:47:26'),
(3, 'ORD-20260708-447E8D', 2, 'eggy shahzida', '0812345678', '14', 'PAPUA', '167', 'MERAUKE', '1713', 'KAPTEL', 'papua', 'tolong', 'jne', 'REG', 202000.00, 116000.00, 318000.00, 'rejected', '2026-07-08 08:10:44', '2026-07-09 08:37:57'),
(4, 'ORD-20260709-714FC2', 2, 'eggy shahzida', '0812345678', '12', 'JAWA TENGAH', '576', 'BANJARNEGARA', '5847', 'RAKIT', 'simbang lor', NULL, 'anteraja', 'REG', 16500.00, 48000.00, 64500.00, 'completed', '2026-07-09 07:27:19', '2026-07-09 08:28:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(150) NOT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `product_weight` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `product_weight`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 'Donat Ingoy', NULL, 20, 12000.00, 4, 48000.00),
(2, 2, 1, 'Donat Ingoy', NULL, 20, 12000.00, 4, 48000.00),
(3, 3, 1, 'Donat Ingoy', NULL, 20, 12000.00, 3, 36000.00),
(4, 3, 2, 'Roti TawarQu', NULL, 50, 16000.00, 5, 80000.00),
(5, 4, 1, 'Donat Ingoy', NULL, 20, 12000.00, 4, 48000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(170) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `weight` int(11) NOT NULL COMMENT 'gram',
  `stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `deleted_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `weight`, `stock`, `image`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Donat Ingoy', 'donat-ingoy', 'Donat mewah dengan olesan selai Lotus Biscoff halus dan topping satu biskuit spekoek utuh.', 12000.00, 20, 24, NULL, 'active', NULL, '2026-07-06 11:58:42', '2026-07-09 08:47:26'),
(2, 1, 'Roti TawarQu', 'roti-tawarqu', 'Roti tawar putih super lembut bertekstur padat yang dipotong tebal, sangat pas untuk roti bakar atau panggangan.', 16000.00, 50, 20, NULL, 'active', NULL, '2026-07-07 00:54:50', '2026-07-08 08:10:44'),
(3, 1, 'Roti Bolu Lembut', 'roti-bolu-lembut', 'roti', 30000.00, 40, 20, 'product-6a4f5b9a4e36b.png', 'active', NULL, '2026-07-09 08:28:10', '2026-07-09 08:28:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@bakeryqu.test', '$2y$10$abcdefghijklmnopqrstuvABCDEFGHIJKLMNOPQRSTUV1234567890ab', 'admin', '2026-07-06 09:34:26', '2026-07-06 09:34:26'),
(2, 'eggy shahzida', 'eshahzida@gmail.com', '$2y$10$zbz6nNnx5f4CMjQFJP.LIufh6Xpf/1Iz1FaOpPU7Z/UKPG7xRVUJq', 'customer', '2026-07-06 16:29:39', '2026-07-06 16:29:39'),
(3, 'aaa', 'e@e.com', '$2y$10$7cuMtAxnmxd.hLa4jOH4B.SikgS2wlQ6IenevMOGjQ/AjBtSRSIQq', 'customer', '2026-07-06 16:41:00', '2026-07-06 16:41:00'),
(4, 'eggy shahzida', 'eshahzidaa@gmail.com', '$2y$10$G.hCX/6sKYGZ8NllGRQncuM401Tp6AdtS.fLwfs25pWJ6HHTZzbOG', 'customer', '2026-07-07 03:35:57', '2026-07-07 03:35:57'),
(5, 'admin1', 'admin123@gmail.com', '$2a$12$VwQsgikf2U/JTYjaw8SG2esRfb.FUv/ghCzJVpyYp6CCM3S1JldqC', 'admin', '2026-07-08 08:18:58', '2026-07-09 07:55:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ci_cart` (`cart_id`),
  ADD KEY `fk_ci_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `fk_order_user` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_oi_order` (`order_id`),
  ADD KEY `fk_oi_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_product_category` (`category_id`);

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
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_ci_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ci_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_oi_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
