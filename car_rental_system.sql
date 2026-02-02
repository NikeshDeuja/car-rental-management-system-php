-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2026 at 01:27 PM
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
-- Database: `car_rental_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_nrp` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `car_id`, `start_date`, `end_date`, `total_nrp`, `status`, `created_at`) VALUES
(1, 3, 1, '2026-02-01', '2026-02-02', 9000.00, 'confirmed', '2026-02-01 09:40:19'),
(2, 3, 1, '2026-02-10', '2026-02-18', 40500.00, 'confirmed', '2026-02-01 09:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `make` varchar(60) NOT NULL,
  `model` varchar(60) NOT NULL,
  `year` int(11) NOT NULL,
  `car_type` varchar(30) NOT NULL,
  `color` varchar(30) NOT NULL,
  `seats` int(11) NOT NULL DEFAULT 4,
  `transmission` varchar(20) NOT NULL DEFAULT 'Automatic',
  `fuel` varchar(20) NOT NULL DEFAULT 'Petrol',
  `price_per_day_nrp` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `make`, `model`, `year`, `car_type`, `color`, `seats`, `transmission`, `fuel`, `price_per_day_nrp`, `image_path`, `is_active`, `created_at`) VALUES
(1, 'Toyota', 'Yaris', 2022, 'Hatchback', 'White', 5, 'Automatic', 'Petrol', 4500.00, 'assets/cars/car_1769939154_4514.jpg', 1, '2026-02-01 09:20:38'),
(2, 'Hyundai', 'Creta', 2023, 'SUV', 'Grey', 5, 'Automatic', 'Petrol', 7500.00, 'assets/cars/car_1769939426_2447.jpg', 1, '2026-02-01 09:20:38'),
(3, 'Kia', 'Seltos', 2024, 'SUV', 'Black', 5, 'Automatic', 'Petrol', 8200.00, 'assets/cars/car_1769939472_4915.jpg', 1, '2026-02-01 09:20:38'),
(4, 'Honda', 'Civic', 2021, 'Sedan', 'Blue', 5, 'Automatic', 'Petrol', 6800.00, 'assets/cars/car_1769939488_6736.jpg', 1, '2026-02-01 09:20:38'),
(5, 'Suzuki', 'Swift', 2020, 'Hatchback', 'Red', 5, 'Manual', 'Petrol', 3900.00, 'assets/cars/car_1769939506_9092.jpg', 1, '2026-02-01 09:20:38'),
(6, 'Mahindra', 'XUV700', 2024, 'SUV', 'Silver', 7, 'Automatic', 'Diesel', 9800.00, 'assets/cars/car_1769939571_4203.jpg', 1, '2026-02-01 09:20:38'),
(7, 'Toyota', 'LC 300', 2025, 'SUV', 'Black', 7, 'Automatic', 'Petrol', 4000.00, 'assets/cars/car_1769939767_4906.jpeg', 1, '2026-02-01 09:56:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','client') NOT NULL DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'nikesh', 'nikeshdeuja@gmail.com', '$2y$10$egqswFx18GJimqYUSbemQexm2lui2K/olODOejopwMAI4E7Ge3WtC', 'client', '2026-02-01 09:10:11'),
(2, 'Starcar Admin', 'admin@starcar.com', '$2y$10$KxAbq7Za0PK938/rHePheOxzvDcjOEZUiKG4mUzkN9YTndY7w/nRe', 'admin', '2026-02-01 09:20:38'),
(3, 'hem', 'hem@gmail.com', '$2y$10$m87bxNyEGZ1s2OdtDbChYO41g9ShceIjYz.zCaZrD/82qq30HqWLG', 'client', '2026-02-01 09:37:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_booking_dates` (`car_id`,`start_date`,`end_date`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_book_car` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_book_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
