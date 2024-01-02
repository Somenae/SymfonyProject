-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 02, 2024 at 04:34 PM
-- Server version: 5.7.24
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `symfonyproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `address`) VALUES
(1, '2 rue chez moi'),
(2, 'montpellier');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` json NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `firstname`, `lastname`, `password`, `role`) VALUES
(1, 't@gmx.fr', 'Thibault', 'Pouplard', '$2y$13$YBRuaeKWERYl/dv4KZmeC.FMcGD34zU2RFy1C0Dip25ZWpoZO24R6', '[\"ROLE_SUPER_ADMIN\"]');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `creation_date` date NOT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `creation_date`, `orders_id`, `user_id`) VALUES
(7, '2024-01-02', 6, 3),
(8, '2024-01-02', 7, 3),
(9, '2024-01-02', 8, 3),
(10, '2024-01-02', 9, 3),
(11, '2024-01-02', NULL, 3),
(12, '2024-01-02', 10, 1),
(13, '2024-01-02', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cart_line`
--

CREATE TABLE `cart_line` (
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_line`
--

INSERT INTO `cart_line` (`id`, `quantity`, `product_id`, `cart_id`) VALUES
(11, 6, 32, 7),
(12, 1, 32, 8),
(13, 1, 33, 9),
(14, 1, 30, 10),
(15, 1, 30, 12),
(16, 5, 31, 12);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(1, 'Pop', 'pop music 80\'s 90\'s'),
(2, 'Rock', 'Rock, metal, hardrock, vintage'),
(3, 'Electro', 'electro, dub, techno'),
(4, 'Jazz', 'jazz, blues'),
(5, 'Hip Hop', 'hip hop, rap, rnb');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20231218094427', '2023-12-18 16:24:50', 567),
('DoctrineMigrations\\Version20231218094658', '2023-12-18 16:24:50', 22),
('DoctrineMigrations\\Version20231218104918', '2023-12-18 16:24:50', 1455),
('DoctrineMigrations\\Version20231219104548', '2023-12-19 10:46:22', 204);

-- --------------------------------------------------------

--
-- Table structure for table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `client_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_price` double NOT NULL,
  `users_id` int(11) NOT NULL,
  `address_shipped_id` int(11) NOT NULL,
  `billing_address_id` int(11) NOT NULL,
  `order_state_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `shipping_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_date`, `client_name`, `total_price`, `users_id`, `address_shipped_id`, `billing_address_id`, `order_state_id`, `payment_id`, `shipping_id`) VALUES
(1, '2023-12-22', 'Thibault', 66, 1, 1, 1, 3, 1, 1),
(2, '2024-01-02', 'Thibault Pouplard', 848.4, 1, 1, 1, 1, 1, 1),
(3, '2024-01-02', 'Thibault Pouplard', 8.4, 1, 1, 1, 1, 1, 1),
(4, '2024-01-02', 'Thibault Pouplard', 8.4, 1, 1, 1, 1, 1, 1),
(5, '2024-01-02', 'fred fred', 8.4, 3, 2, 2, 1, 1, 1),
(6, '2024-01-02', 'fred fred', 42, 3, 2, 2, 1, 1, 1),
(7, '2024-01-02', 'fred fred', 7, 3, 2, 2, 1, 1, 1),
(8, '2024-01-02', 'fred fred', 14, 3, 2, 2, 1, 1, 1),
(9, '2024-01-02', 'fred fred', 8.4, 3, 2, 2, 1, 1, 1),
(10, '2024-01-02', 'Thibault Pouplard', 95.9, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_line`
--

CREATE TABLE `order_line` (
  `id` int(11) NOT NULL,
  `products_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_unit_price` double NOT NULL,
  `taxe` double NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` double NOT NULL,
  `sales` double NOT NULL,
  `orders_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_line`
--

INSERT INTO `order_line` (`id`, `products_name`, `product_unit_price`, `taxe`, `quantity`, `total_price`, `sales`, `orders_id`) VALUES
(1, 'Produit 3', 10, 20, 100, 8.4, 30, 2),
(2, 'Produit 8', 10, 20, 1, 8.4, 30, 2),
(3, 'Produit 7', 10, 20, 1, 8.4, 30, 3),
(4, 'Produit 8', 10, 20, 1, 8.4, 30, 4),
(5, 'Produit 8', 10, 20, 1, 8.4, 30, 5),
(6, 'Abba', 10, 0, 6, 7, 30, 6),
(7, 'Abba', 10, 0, 1, 7, 30, 7),
(8, 'Coldplay', 20, 0, 1, 14, 30, 8),
(9, 'Mariah Carey', 10, 20, 1, 8.4, 30, 9),
(10, 'Mariah Carey', 10, 20, 1, 8.4, 30, 10),
(11, 'Dr DRE', 25, 0, 5, 17.5, 30, 10);

-- --------------------------------------------------------

--
-- Table structure for table `order_state`
--

CREATE TABLE `order_state` (
  `id` int(11) NOT NULL,
  `label` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_state`
--

INSERT INTO `order_state` (`id`, `label`) VALUES
(1, 'Attente de payement'),
(2, 'En cours de livraison'),
(3, 'Commande effectué');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `type`) VALUES
(1, 'Cheque');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_taxes_id` int(11) DEFAULT NULL,
  `product_sales_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `price`, `description`, `image`, `product_taxes_id`, `product_sales_id`) VALUES
(30, 'Mariah Carey', 10, 'Christmas', 'MariahCarey30.jpg', 1, 6),
(31, 'Dr DRE', 25, '2001', 'DrDRE31.jpg', NULL, 6),
(32, 'Abba', 10, 'Waterloo', 'Abba32.jpg', NULL, 6),
(33, 'Coldplay', 20, 'Live in Buenos Aires', 'Coldplay33.jpg', NULL, 6),
(34, 'korn', 30, 'Follow the leader', 'korn34.jpg', NULL, 6),
(35, 'Korn', 30, 'korn', 'Korn35.jpg', NULL, 6),
(36, 'Rhianna', 35, 'Anti', 'Rhianna36.jpg', NULL, 6),
(37, 'Daft Punk', 20, 'Homework', 'DaftPunk37.jpg', NULL, NULL),
(38, 'Nas', 35, 'Illmatic', 'Nas38.jpg', NULL, NULL),
(39, 'Amy Winehouse', 25, 'Back to black', 'AmyWinehouse39.jpg', NULL, NULL),
(40, 'Led Zepplin', 200, 'nblablablabla', 'Led Zepplin21.png', NULL, NULL),
(41, 'BB', 20, 'BB', NULL, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(1, 3),
(30, 1),
(41, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `amount_percentage` double DEFAULT NULL,
  `name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `amount_percentage`, `name`) VALUES
(6, 30, 'COUCOU30'),
(7, 50, 'rtrr');

-- --------------------------------------------------------

--
-- Table structure for table `shipping`
--

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL,
  `company` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transport_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping`
--

INSERT INTO `shipping` (`id`, `company`, `transport_type`, `shipping_price`) VALUES
(1, 'Vintyle', 'Click and collect', 0);

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `name`, `amount`) VALUES
(1, 'TVA', 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` json NOT NULL,
  `firstname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `firstname`, `lastname`, `email`, `password`, `address_id`) VALUES
(1, '[]', 'Thibault', 'Pouplard', 't@gmx.fr', '$2y$13$YBRuaeKWERYl/dv4KZmeC.FMcGD34zU2RFy1C0Dip25ZWpoZO24R6', 1),
(2, '[]', 'aaaa', 'aaaa', 'a@gmail.com', '$2y$13$JibmEWBhEmSsY8Fl/k4VEuMQZSH83McVZXP83ScDO5LjSatvRidMW', NULL),
(3, '[]', 'fred', 'fred', 'fred@fred.fr', '$2y$13$L380YjId69RE.53oT9EztuDBlVN6oO7rSZM4NbO3ZnQU3MZbwCGeG', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_BA388B7CFFE9AD6` (`orders_id`),
  ADD KEY `IDX_BA388B7A76ED395` (`user_id`);

--
-- Indexes for table `cart_line`
--
ALTER TABLE `cart_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3EF1B4CF4584665A` (`product_id`),
  ADD KEY `IDX_3EF1B4CF1AD5CDBF` (`cart_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_E52FFDEE67B3B43D` (`users_id`),
  ADD KEY `IDX_E52FFDEE35AA1FFE` (`address_shipped_id`),
  ADD KEY `IDX_E52FFDEE79D0C0E4` (`billing_address_id`),
  ADD KEY `IDX_E52FFDEEE420DE70` (`order_state_id`),
  ADD KEY `IDX_E52FFDEE4C3A3BB` (`payment_id`),
  ADD KEY `IDX_E52FFDEE4887F3F8` (`shipping_id`);

--
-- Indexes for table `order_line`
--
ALTER TABLE `order_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9CE58EE1CFFE9AD6` (`orders_id`);

--
-- Indexes for table `order_state`
--
ALTER TABLE `order_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04ADFD600904` (`product_taxes_id`),
  ADD KEY `IDX_D34A04AD6FE24090` (`product_sales_id`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `IDX_CDFC73564584665A` (`product_id`),
  ADD KEY `IDX_CDFC735612469DE2` (`category_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping`
--
ALTER TABLE `shipping`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1483A5E9F5B7AF75` (`address_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `cart_line`
--
ALTER TABLE `cart_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_line`
--
ALTER TABLE `order_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_state`
--
ALTER TABLE `order_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shipping`
--
ALTER TABLE `shipping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `FK_BA388B7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_BA388B7CFFE9AD6` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `cart_line`
--
ALTER TABLE `cart_line`
  ADD CONSTRAINT `FK_3EF1B4CF1AD5CDBF` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`),
  ADD CONSTRAINT `FK_3EF1B4CF4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_E52FFDEE35AA1FFE` FOREIGN KEY (`address_shipped_id`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `FK_E52FFDEE4887F3F8` FOREIGN KEY (`shipping_id`) REFERENCES `shipping` (`id`),
  ADD CONSTRAINT `FK_E52FFDEE4C3A3BB` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`),
  ADD CONSTRAINT `FK_E52FFDEE67B3B43D` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_E52FFDEE79D0C0E4` FOREIGN KEY (`billing_address_id`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `FK_E52FFDEEE420DE70` FOREIGN KEY (`order_state_id`) REFERENCES `order_state` (`id`);

--
-- Constraints for table `order_line`
--
ALTER TABLE `order_line`
  ADD CONSTRAINT `FK_9CE58EE1CFFE9AD6` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD6FE24090` FOREIGN KEY (`product_sales_id`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `FK_D34A04ADFD600904` FOREIGN KEY (`product_taxes_id`) REFERENCES `taxes` (`id`);

--
-- Constraints for table `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `FK_CDFC735612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CDFC73564584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_1483A5E9F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
