-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql1.small.pl
-- Generation Time: Lis 25, 2025 at 07:01 PM
-- Wersja serwera: 8.0.39
-- Wersja PHP: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `m3573_db_ecommercestore`
--

DELIMITER $$
--
-- Procedury
--
CREATE DEFINER=`m3573_Mateusz`@`%.devil` PROCEDURE `aktualizuj_top4` ()   BEGIN
    -- Czyszczenie starych TOP 4
    TRUNCATE TABLE top_produkty;
    TRUNCATE TABLE top_kategorie;

    -- TOP 4 produkty (według ilości sprzedanych sztuk)
    INSERT INTO top_produkty (pozycja, id_product, nazwa, ilosc_sprzedanych, zdjecie)
    SELECT 
        ROW_NUMBER() OVER (ORDER BY SUM(oi.quantity) DESC) AS pozycja,
        p.id_product,
        p.nazwa,
        SUM(oi.quantity) AS ilosc_sprzedanych,
        p.zdjecie
    FROM order_items oi
    JOIN orders o ON oi.id_order = o.id_order AND o.status = 'completed'
    JOIN product p ON oi.id_product = p.id_product
    GROUP BY p.id_product, p.nazwa, p.zdjecie
    ORDER BY ilosc_sprzedanych DESC
    LIMIT 4;

    -- TOP 4 kategorie
    INSERT INTO top_kategorie (pozycja, id_category, nazwa, ilosc_sprzedanych)
    SELECT 
        ROW_NUMBER() OVER (ORDER BY SUM(oi.quantity) DESC) AS pozycja,
        c.id_category,
        c.nazwa,
        SUM(oi.quantity) AS ilosc_sprzedanych
    FROM order_items oi
    JOIN orders o ON oi.id_order = o.id_order AND o.status = 'completed'
    JOIN product p ON oi.id_product = p.id_product
    JOIN category c ON p.id_category = c.id_category
    GROUP BY c.id_category, c.nazwa
    ORDER BY ilosc_sprzedanych DESC
    LIMIT 4;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `category`
--

CREATE TABLE `category` (
  `id_category` bigint UNSIGNED NOT NULL,
  `nazwa` varchar(50) COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;


INSERT INTO `category` (`id_category`, `nazwa`) VALUES
(1, 'Laptopy'),
(2, 'Smartfony'),
(3, 'Tablety'),
(4, 'Akcesoria'),
(5, 'Monitory'),
(6, 'Słuchawki'),
(7, 'Klawiatury'),
(8, 'Myszki'),
(9, 'Wszystkie');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `historia_zakupow`
--

CREATE TABLE `historia_zakupow` (
  `id_historia` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `id_order` bigint UNSIGNED NOT NULL,
  `id_product` bigint UNSIGNED NOT NULL,
  `nazwa_produktu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zdjecie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cena_jednostkowa` decimal(10,2) NOT NULL,
  `ilosc` int UNSIGNED NOT NULL,
  `wartosc_calkowita` decimal(10,2) NOT NULL,
  `data_zakupu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_zamowienia` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `historia_zakupow` (`id_historia`, `id_user`, `id_order`, `id_product`, `nazwa_produktu`, `zdjecie`, `cena_jednostkowa`, `ilosc`, `wartosc_calkowita`, `data_zakupu`, `status_zamowienia`) VALUES
(1, 1, 1, 1, 'MacBook Pro 16 M3', 'mac.jpg', 14999.00, 1, 14999.00, '2025-01-15 13:22:00', 'completed'),
(2, 1, 1, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 1, 499.00, '2025-01-15 13:22:00', 'completed'),
(3, 2, 2, 4, 'iPhone 15 Pro Max', 'iphone.jpg', 7299.00, 1, 7299.00, '2025-01-20 09:11:00', 'completed'),
(4, 3, 3, 2, 'Dell XPS 15', 'dell.jpg', 9499.00, 1, 9499.00, '2025-02-10 17:44:00', 'completed'),
(5, 3, 3, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-02-10 17:44:00', 'completed'),
(6, 1, 4, 8, 'Galaxy Tab S9', 'tab.jpg', 4199.00, 1, 4199.00, '2025-02-25 10:15:00', 'completed'),
(7, 4, 5, 3, 'Lenovo ThinkPad X1', 'thinkpad.jpg', 7999.00, 1, 7999.00, '2025-03-05 08:30:00', 'completed'),
(8, 4, 5, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-03-05 08:30:00', 'completed'),
(9, 4, 5, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 2, 998.00, '2025-03-05 08:30:00', 'completed'),
(10, 1, 6, 5, 'Samsung S24 Ultra', 's24.jpg', 6199.00, 1, 6199.00, '2025-03-20 15:45:00', 'completed'),
(11, 1, 6, 11, 'AirPods Pro 2', 'airpods.jpg', 1299.00, 1, 1299.00, '2025-03-20 15:45:00', 'completed'),
(12, 2, 7, 6, 'Xiaomi 14 Pro', 'xiaomi.jpg', 3899.00, 1, 3899.00, '2025-04-02 10:10:00', 'completed'),
(13, 3, 8, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-04-18 07:20:00', 'completed'),
(14, 1, 9, 2, 'Dell XPS 15', 'dell.jpg', 9499.00, 1, 9499.00, '2025-05-05 12:55:00', 'completed'),
(15, 2, 10, 4, 'iPhone 15 Pro Max', 'iphone.jpg', 7299.00, 1, 7299.00, '2025-05-20 09:30:00', 'completed'),
(16, 4, 11, 3, 'Lenovo ThinkPad X1', 'thinkpad.jpg', 7999.00, 1, 7999.00, '2025-06-08 13:40:00', 'completed'),
(17, 1, 12, 1, 'MacBook Pro 16 M3', 'mac.jpg', 14999.00, 1, 14999.00, '2025-06-25 08:25:00', 'completed'),
(18, 1, 12, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 1, 499.00, '2025-06-25 08:25:00', 'completed'),
(19, 3, 13, 5, 'Samsung S24 Ultra', 's24.jpg', 6199.00, 1, 6199.00, '2025-07-10 11:15:00', 'completed'),
(20, 2, 14, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 10, 4990.00, '2025-07-28 16:30:00', 'completed'),
(21, 1, 15, 12, 'LG 34\" UltraWide', 'lg.jpg', 2899.00, 1, 2899.00, '2025-08-12 09:11:00', 'completed'),
(22, 4, 16, 11, 'AirPods Pro 2', 'airpods.jpg', 1299.00, 1, 1299.00, '2025-08-28 12:44:00', 'completed'),
(23, 2, 17, 2, 'Dell XPS 15', 'dell.jpg', 9499.00, 1, 9499.00, '2025-09-05 07:55:00', 'completed'),
(24, 2, 17, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-09-05 07:55:00', 'completed'),
(25, 3, 18, 5, 'Samsung S24 Ultra', 's24.jpg', 6199.00, 1, 6199.00, '2025-09-22 14:20:00', 'completed'),
(26, 1, 19, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 8, 3992.00, '2025-10-03 10:35:00', 'completed'),
(27, 2, 20, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-10-18 08:10:00', 'completed'),
(28, 4, 21, 4, 'iPhone 15 Pro Max', 'iphone.jpg', 7299.00, 1, 7299.00, '2025-10-30 16:45:00', 'completed'),
(29, 1, 22, 1, 'MacBook Pro 16 M3', 'mac.jpg', 14999.00, 1, 14999.00, '2025-11-08 13:20:00', 'completed'),
(30, 1, 22, 12, 'LG 34\" UltraWide', 'lg.jpg', 2899.00, 1, 2899.00, '2025-11-08 13:20:00', 'completed'),
(31, 3, 23, 8, 'Galaxy Tab S9', 'tab.jpg', 4199.00, 1, 4199.00, '2025-11-15 10:11:00', 'completed'),
(32, 2, 24, 1, 'MacBook Pro 16 M3', 'mac.jpg', 14999.00, 1, 14999.00, '2025-11-22 14:30:00', 'completed'),
(33, 2, 24, 5, 'Samsung S24 Ultra', 's24.jpg', 6199.00, 1, 6199.00, '2025-11-22 14:30:00', 'completed'),
(34, 2, 24, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-11-22 14:30:00', 'completed'),
(35, 1, 1, 1, 'MacBook Pro 16 M3', 'mac.jpg', 14999.00, 1, 14999.00, '2025-01-15 13:22:00', 'completed'),
(36, 1, 1, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 1, 499.00, '2025-01-15 13:22:00', 'completed'),
(37, 2, 2, 4, 'iPhone 15 Pro Max', 'iphone.jpg', 7299.00, 1, 7299.00, '2025-01-20 09:11:00', 'completed'),
(38, 3, 3, 2, 'Dell XPS 15', 'dell.jpg', 9499.00, 1, 9499.00, '2025-02-10 17:44:00', 'completed'),
(39, 3, 3, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-02-10 17:44:00', 'completed'),
(40, 1, 4, 8, 'Galaxy Tab S9', 'tab.jpg', 4199.00, 1, 4199.00, '2025-02-25 10:15:00', 'completed'),
(41, 4, 5, 3, 'Lenovo ThinkPad X1', 'thinkpad.jpg', 7999.00, 1, 7999.00, '2025-03-05 08:30:00', 'completed'),
(42, 4, 5, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-03-05 08:30:00', 'completed'),
(43, 4, 5, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 2, 998.00, '2025-03-05 08:30:00', 'completed'),
(44, 1, 6, 5, 'Samsung S24 Ultra', 's24.jpg', 6199.00, 1, 6199.00, '2025-03-20 15:45:00', 'completed'),
(45, 1, 6, 11, 'AirPods Pro 2', 'airpods.jpg', 1299.00, 1, 1299.00, '2025-03-20 15:45:00', 'completed'),
(46, 2, 7, 6, 'Xiaomi 14 Pro', 'xiaomi.jpg', 3899.00, 1, 3899.00, '2025-04-02 10:10:00', 'completed'),
(47, 3, 8, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-04-18 07:20:00', 'completed'),
(48, 1, 9, 2, 'Dell XPS 15', 'dell.jpg', 9499.00, 1, 9499.00, '2025-05-05 12:55:00', 'completed'),
(49, 2, 10, 4, 'iPhone 15 Pro Max', 'iphone.jpg', 7299.00, 1, 7299.00, '2025-05-20 09:30:00', 'completed'),
(50, 4, 11, 3, 'Lenovo ThinkPad X1', 'thinkpad.jpg', 7999.00, 1, 7999.00, '2025-06-08 13:40:00', 'completed'),
(51, 1, 12, 1, 'MacBook Pro 16 M3', 'mac.jpg', 14999.00, 1, 14999.00, '2025-06-25 08:25:00', 'completed'),
(52, 1, 12, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 1, 499.00, '2025-06-25 08:25:00', 'completed'),
(53, 3, 13, 5, 'Samsung S24 Ultra', 's24.jpg', 6199.00, 1, 6199.00, '2025-07-10 11:15:00', 'completed'),
(54, 2, 14, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 10, 4990.00, '2025-07-28 16:30:00', 'completed'),
(55, 1, 15, 12, 'LG 34\" UltraWide', 'lg.jpg', 2899.00, 1, 2899.00, '2025-08-12 09:11:00', 'completed'),
(56, 4, 16, 11, 'AirPods Pro 2', 'airpods.jpg', 1299.00, 1, 1299.00, '2025-08-28 12:44:00', 'completed'),
(57, 2, 17, 2, 'Dell XPS 15', 'dell.jpg', 9499.00, 1, 9499.00, '2025-09-05 07:55:00', 'completed'),
(58, 2, 17, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-09-05 07:55:00', 'completed'),
(59, 3, 18, 5, 'Samsung S24 Ultra', 's24.jpg', 6199.00, 1, 6199.00, '2025-09-22 14:20:00', 'completed'),
(60, 1, 19, 9, 'Logitech MX Master 3S', 'mx.jpg', 499.00, 8, 3992.00, '2025-10-03 10:35:00', 'completed'),
(61, 2, 20, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-10-18 08:10:00', 'completed'),
(62, 4, 21, 4, 'iPhone 15 Pro Max', 'iphone.jpg', 7299.00, 1, 7299.00, '2025-10-30 16:45:00', 'completed'),
(63, 1, 22, 1, 'MacBook Pro 16 M3', 'mac.jpg', 14999.00, 1, 14999.00, '2025-11-08 13:20:00', 'completed'),
(64, 1, 22, 12, 'LG 34\" UltraWide', 'lg.jpg', 2899.00, 1, 2899.00, '2025-11-08 13:20:00', 'completed'),
(65, 3, 23, 8, 'Galaxy Tab S9', 'tab.jpg', 4199.00, 1, 4199.00, '2025-11-15 10:11:00', 'completed'),
(66, 2, 24, 1, 'MacBook Pro 16 M3', 'mac.jpg', 14999.00, 1, 14999.00, '2025-11-22 14:30:00', 'completed'),
(67, 2, 24, 5, 'Samsung S24 Ultra', 's24.jpg', 6199.00, 1, 6199.00, '2025-11-22 14:30:00', 'completed'),
(68, 2, 24, 10, 'Sony WH-1000XM5', 'sony.jpg', 1799.00, 1, 1799.00, '2025-11-22 14:30:00', 'completed');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `koszyk`
--

CREATE TABLE `koszyk` (
  `id_koszyk` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `id_product` bigint UNSIGNED NOT NULL,
  `ilosc` int UNSIGNED NOT NULL DEFAULT '1',
  `data_dodania` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_aktualizacji` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `koszyk` (`id_koszyk`, `id_user`, `id_product`, `ilosc`, `data_dodania`, `data_aktualizacji`) VALUES
(1, 1, 4, 1, '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(2, 1, 10, 2, '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(3, 2, 5, 1, '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(4, 3, 12, 1, '2025-11-23 16:29:44', '2025-11-23 16:29:44');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `id_order` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `orders` (`id_order`, `id_user`, `total_price`, `status`, `created_at`, `updated_at`, `shipping_address`, `payment_method`) VALUES
(1, 1, 15498.00, 'completed', '2025-01-15 13:22:00', '2025-11-23 16:29:44', 'Warszawa', 'card'),
(2, 2, 7299.00, 'completed', '2025-01-20 09:11:00', '2025-11-23 16:29:44', 'Kraków', 'blik'),
(3, 3, 8398.00, 'completed', '2025-02-10 17:44:00', '2025-11-23 16:29:44', 'Gdańsk', 'transfer'),
(4, 1, 4199.00, 'completed', '2025-02-25 10:15:00', '2025-11-23 16:29:44', 'Warszawa', 'card'),
(5, 4, 10298.00, 'completed', '2025-03-05 08:30:00', '2025-11-23 16:29:44', 'Wrocław', 'card'),
(6, 1, 5498.00, 'completed', '2025-03-20 15:45:00', '2025-11-23 16:29:44', 'Warszawa', 'blik'),
(7, 2, 3899.00, 'completed', '2025-04-02 10:10:00', '2025-11-23 16:29:44', 'Kraków', 'card'),
(8, 3, 1799.00, 'completed', '2025-04-18 07:20:00', '2025-11-23 16:29:44', 'Gdańsk', 'blik'),
(9, 1, 9498.00, 'completed', '2025-05-05 12:55:00', '2025-11-23 16:29:44', 'Warszawa', 'transfer'),
(10, 2, 7299.00, 'completed', '2025-05-20 09:30:00', '2025-11-23 16:29:44', 'Kraków', 'blik'),
(11, 4, 7999.00, 'completed', '2025-06-08 13:40:00', '2025-11-23 16:29:44', 'Wrocław', 'card'),
(12, 1, 15497.00, 'completed', '2025-06-25 08:25:00', '2025-11-23 16:29:44', 'Warszawa', 'card'),
(13, 3, 6199.00, 'completed', '2025-07-10 11:15:00', '2025-11-23 16:29:44', 'Gdańsk', 'card'),
(14, 2, 4998.00, 'completed', '2025-07-28 16:30:00', '2025-11-23 16:29:44', 'Kraków', 'blik'),
(15, 1, 2899.00, 'completed', '2025-08-12 09:11:00', '2025-11-23 16:29:44', 'Warszawa', 'transfer'),
(16, 4, 1299.00, 'completed', '2025-08-28 12:44:00', '2025-11-23 16:29:44', 'Wrocław', 'card'),
(17, 2, 8497.00, 'completed', '2025-09-05 07:55:00', '2025-11-23 16:29:44', 'Kraków', 'card'),
(18, 3, 6199.00, 'completed', '2025-09-22 14:20:00', '2025-11-23 16:29:44', 'Gdańsk', 'blik'),
(19, 1, 3998.00, 'completed', '2025-10-03 10:35:00', '2025-11-23 16:29:44', 'Warszawa', 'card'),
(20, 2, 1799.00, 'completed', '2025-10-18 08:10:00', '2025-11-23 16:29:44', 'Kraków', 'transfer'),
(21, 4, 7299.00, 'completed', '2025-10-30 16:45:00', '2025-11-23 16:29:44', 'Wrocław', 'blik'),
(22, 1, 15499.00, 'completed', '2025-11-08 13:20:00', '2025-11-23 16:29:44', 'Warszawa', 'card'),
(23, 3, 4199.00, 'completed', '2025-11-15 10:11:00', '2025-11-23 16:29:44', 'Gdańsk', 'card'),
(24, 2, 19497.00, 'completed', '2025-11-22 14:30:00', '2025-11-23 16:29:44', 'Kraków', 'card');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `order_items`
--

CREATE TABLE `order_items` (
  `id_order_item` bigint UNSIGNED NOT NULL,
  `id_order` bigint UNSIGNED NOT NULL,
  `id_product` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_per_unit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;


INSERT INTO `order_items` (`id_order_item`, `id_order`, `id_product`, `quantity`, `price_per_unit`) VALUES
(1, 1, 1, 1, 14999.00),
(2, 1, 9, 1, 499.00),
(3, 2, 4, 1, 7299.00),
(4, 3, 2, 1, 9499.00),
(5, 3, 10, 1, 1799.00),
(6, 4, 8, 1, 4199.00),
(7, 5, 3, 1, 7999.00),
(8, 5, 10, 1, 1799.00),
(9, 5, 9, 2, 499.00),
(10, 6, 5, 1, 6199.00),
(11, 6, 11, 1, 1299.00),
(12, 7, 6, 1, 3899.00),
(13, 8, 10, 1, 1799.00),
(14, 9, 2, 1, 9499.00),
(15, 10, 4, 1, 7299.00),
(16, 11, 3, 1, 7999.00),
(17, 12, 1, 1, 14999.00),
(18, 12, 9, 1, 499.00),
(19, 13, 5, 1, 6199.00),
(20, 14, 9, 10, 499.00),
(21, 15, 12, 1, 2899.00),
(22, 16, 11, 1, 1299.00),
(23, 17, 2, 1, 9499.00),
(24, 17, 10, 1, 1799.00),
(25, 18, 5, 1, 6199.00),
(26, 19, 9, 8, 499.00),
(27, 20, 10, 1, 1799.00),
(28, 21, 4, 1, 7299.00),
(29, 22, 1, 1, 14999.00),
(30, 22, 12, 1, 2899.00),
(31, 23, 8, 1, 4199.00),
(32, 24, 1, 1, 14999.00),
(33, 24, 5, 1, 6199.00),
(34, 24, 10, 1, 1799.00);

--
-- Wyzwalacze `order_items`
--
DELIMITER $$
CREATE TRIGGER `po_zlozeniu_zamowienia_wstaw_historie` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    INSERT INTO historia_zakupow 
        (id_user,id_order,id_product,nazwa_produktu,zdjecie,cena_jednostkowa,ilosc,wartosc_calkowita,data_zakupu,status_zamowienia)
    SELECT 
        o.id_user, NEW.id_order, NEW.id_product, p.nazwa, p.zdjecie, 
        NEW.price_per_unit, NEW.quantity, NEW.price_per_unit*NEW.quantity, o.created_at, o.status
    FROM orders o JOIN product p ON p.id_product = NEW.id_product
    WHERE o.id_order = NEW.id_order;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payments`
--

CREATE TABLE `payments` (
  `id_payment` bigint UNSIGNED NOT NULL,
  `id_order` bigint UNSIGNED NOT NULL,
  `kwota` decimal(10,2) NOT NULL,
  `metoda_platnosci` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `data_platnosci` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `payments` (`id_payment`, `id_order`, `kwota`, `metoda_platnosci`, `status`, `data_platnosci`, `created_at`, `updated_at`) VALUES
(1, 1, 15498.00, 'card', 'paid', '2025-01-15 13:30:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(2, 2, 7299.00, 'blik', 'paid', '2025-01-20 09:15:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(3, 3, 8398.00, 'transfer', 'paid', '2025-02-10 18:00:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(4, 4, 4199.00, 'card', 'paid', '2025-02-25 10:20:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(5, 5, 10298.00, 'card', 'paid', '2025-03-05 08:40:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(6, 6, 5498.00, 'blik', 'paid', '2025-03-20 15:50:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(7, 7, 3899.00, 'card', 'paid', '2025-04-02 10:15:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(8, 8, 1799.00, 'blik', 'paid', '2025-04-18 07:25:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(9, 9, 9498.00, 'transfer', 'paid', '2025-05-05 13:00:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(10, 10, 7299.00, 'blik', 'paid', '2025-05-20 09:35:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(11, 11, 7999.00, 'card', 'paid', '2025-06-08 13:45:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(12, 12, 15497.00, 'card', 'paid', '2025-06-25 08:30:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(13, 13, 6199.00, 'card', 'paid', '2025-07-10 11:20:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(14, 14, 4998.00, 'blik', 'paid', '2025-07-28 16:35:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(15, 15, 2899.00, 'transfer', 'paid', '2025-08-12 09:15:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(16, 16, 1299.00, 'card', 'paid', '2025-08-28 12:50:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(17, 17, 8497.00, 'card', 'paid', '2025-09-05 08:00:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(18, 18, 6199.00, 'blik', 'paid', '2025-09-22 14:25:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(19, 19, 3998.00, 'card', 'paid', '2025-10-03 10:40:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(20, 20, 1799.00, 'transfer', 'paid', '2025-10-18 08:15:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(21, 21, 7299.00, 'blik', 'paid', '2025-10-30 16:50:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(22, 22, 15499.00, 'card', 'paid', '2025-11-08 13:25:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(23, 23, 4199.00, 'card', 'paid', '2025-11-15 10:15:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(24, 24, 19497.00, 'card', 'paid', '2025-11-22 14:35:00', '2025-11-23 16:29:44', '2025-11-23 16:29:44');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `product`
--

CREATE TABLE `product` (
  `id_product` bigint UNSIGNED NOT NULL,
  `nazwa` varchar(255) COLLATE utf8mb4_polish_ci NOT NULL,
  `opis` text COLLATE utf8mb4_polish_ci NOT NULL,
  `cena` decimal(8,2) NOT NULL,
  `ilosc` int DEFAULT NULL,
  `id_category` bigint UNSIGNED DEFAULT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_aktualizacji` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `zdjecie` varchar(255) COLLATE utf8mb4_polish_ci DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;


INSERT INTO `product` (`id_product`, `nazwa`, `opis`, `cena`, `ilosc`, `id_category`, `data_utworzenia`, `data_aktualizacji`, `zdjecie`) VALUES
(1, 'MacBook Pro 16 M3', 'Apple M3 Pro', 14999.00, 5, 1, '2025-01-08 09:15:32', '2025-01-08 09:15:32', 'mac.jpg'),
(2, 'Dell XPS 15', 'OLED 4K', 9499.00, 8, 1, '2025-01-15 13:22:11', '2025-01-15 13:22:11', 'dell.jpg'),
(3, 'Lenovo ThinkPad X1', 'Biznesowy', 7999.00, 10, 1, '2025-01-28 08:45:19', '2025-01-28 08:45:19', 'thinkpad.jpg'),
(4, 'iPhone 15 Pro Max', 'Tytan', 7299.00, 15, 2, '2025-02-05 10:30:55', '2025-02-05 10:30:55', 'iphone.jpg'),
(5, 'Samsung S24 Ultra', 'Flagowiec', 6199.00, 20, 2, '2025-02-18 15:12:03', '2025-02-18 15:12:03', 's24.jpg'),
(6, 'Xiaomi 14 Pro', 'Tani top', 3899.00, 30, 2, '2025-03-02 12:27:44', '2025-03-02 12:27:44', 'xiaomi.jpg'),
(7, 'iPad Pro 12.9', 'M2', 6499.00, 7, 3, '2025-03-14 07:55:21', '2025-03-14 07:55:21', 'ipad.jpg'),
(8, 'Galaxy Tab S9', 'Android', 4199.00, 12, 3, '2025-03-28 16:41:07', '2025-03-28 16:41:07', 'tab.jpg'),
(9, 'Logitech MX Master 3S', 'Myszka', 499.00, 50, 8, '2025-04-10 08:18:33', '2025-04-10 08:18:33', 'mx.jpg'),
(10, 'Sony WH-1000XM5', 'ANC', 1799.00, 18, 6, '2025-04-22 13:06:49', '2025-04-22 13:06:49', 'sony.jpg'),
(11, 'AirPods Pro 2', 'Apple', 1299.00, 25, 6, '2025-05-03 10:33:15', '2025-05-03 10:33:15', 'airpods.jpg'),
(12, 'LG 34\" UltraWide', 'Panoramiczny', 2899.00, 10, 5, '2025-05-19 07:11:27', '2025-05-19 07:11:27', 'lg.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkty_w_promocji`
--

CREATE TABLE `produkty_w_promocji` (
  `id` bigint UNSIGNED NOT NULL,
  `id_product` bigint UNSIGNED NOT NULL,
  `cena_promocyjna` decimal(10,2) NOT NULL,
  `cena_normalna` decimal(10,2) NOT NULL,
  `data_rozpoczecia` datetime NOT NULL,
  `data_zakonczenia` datetime DEFAULT NULL,
  `nazwa_promocji` varchar(255) COLLATE utf8mb4_polish_ci DEFAULT 'Promocja specjalna',
  `aktywna` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;


INSERT INTO `produkty_w_promocji` (`id`, `id_product`, `cena_promocyjna`, `cena_normalna`, `data_rozpoczecia`, `data_zakonczenia`, `nazwa_promocji`, `aktywna`, `created_at`, `updated_at`) VALUES
(1, 1, 12999.00, 14999.00, '2025-11-24 00:00:00', '2025-12-01 23:59:59', 'Black Friday -13%', 1, '2025-11-25 17:58:12', '2025-11-25 17:58:12'),
(2, 4, 5999.00, 7299.00, '2025-11-24 00:00:00', '2025-12-02 23:59:59', 'Cyber Week iPhone', 1, '2025-11-25 17:58:12', '2025-11-25 17:58:12'),
(3, 5, 5199.00, 6199.00, '2025-11-20 00:00:00', '2025-11-30 23:59:59', 'Samsung Weekend', 1, '2025-11-25 17:58:12', '2025-11-25 17:58:12'),
(4, 10, 1399.00, 1799.00, '2025-06-01 00:00:00', '2025-06-30 23:59:59', 'Lato ze słuchawkami', 1, '2025-11-25 17:58:12', '2025-11-25 17:58:12'),
(5, 9, 399.00, 499.00, '2025-08-20 00:00:00', '2025-09-15 23:59:59', 'Powrót do szkoły', 1, '2025-11-25 17:58:12', '2025-11-25 17:58:12'),
(6, 2, 7999.00, 9499.00, '2025-03-14 18:00:00', '2025-03-16 23:59:59', 'Weekend z Dell', 1, '2025-11-25 17:58:12', '2025-11-25 17:58:12');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `rabaty`
--

CREATE TABLE `rabaty` (
  `id_rabat` bigint UNSIGNED NOT NULL,
  `kod` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `wartosc` decimal(5,2) NOT NULL,
  `typ` enum('procent','kwota') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'procent',
  `data_waznosci` date DEFAULT NULL,
  `minimalna_kwota` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `rabaty` (`id_rabat`, `kod`, `wartosc`, `typ`, `data_waznosci`, `minimalna_kwota`, `created_at`, `updated_at`) VALUES
(1, 'BLACK2025', 20.00, 'procent', '2025-12-31', 1000.00, '2025-11-23 16:29:44', '2025-11-23 16:29:44'),
(2, '50ZŁ', 50.00, 'kwota', '2025-12-31', 300.00, '2025-11-23 16:29:44', '2025-11-23 16:29:44');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `top_kategorie`
--

CREATE TABLE `top_kategorie` (
  `pozycja` int NOT NULL,
  `id_category` bigint UNSIGNED NOT NULL,
  `nazwa` varchar(255) COLLATE utf8mb4_polish_ci NOT NULL,
  `ilosc_sprzedanych` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;


INSERT INTO `top_kategorie` (`pozycja`, `id_category`, `nazwa`, `ilosc_sprzedanych`) VALUES
(1, 8, 'Myszki', 22),
(2, 1, 'Laptopy', 9),
(3, 2, 'Smartfony', 8),
(4, 6, 'Słuchawki', 8);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `top_produkty`
--

CREATE TABLE `top_produkty` (
  `pozycja` int NOT NULL,
  `id_product` bigint UNSIGNED NOT NULL,
  `nazwa` varchar(255) COLLATE utf8mb4_polish_ci NOT NULL,
  `ilosc_sprzedanych` int UNSIGNED NOT NULL,
  `zdjecie` varchar(255) COLLATE utf8mb4_polish_ci DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;


INSERT INTO `top_produkty` (`pozycja`, `id_product`, `nazwa`, `ilosc_sprzedanych`, `zdjecie`) VALUES
(1, 9, 'Logitech MX Master 3S', 22, 'mx.jpg'),
(2, 10, 'Sony WH-1000XM5', 6, 'sony.jpg'),
(3, 1, 'MacBook Pro 16 M3', 4, 'mac.jpg'),
(4, 5, 'Samsung S24 Ultra', 4, 's24.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id_user` bigint UNSIGNED NOT NULL,
  `email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `hasło` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `t_login` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `czy_admin` tinyint(1) DEFAULT NULL,
  `data_utworzenia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_aktualizacji` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `szczegoly` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci COMMENT='tabela z aktywnymi użytkownikami';


INSERT INTO `users` (`id_user`, `email`, `hasło`, `t_login`, `czy_admin`, `data_utworzenia`, `data_aktualizacji`, `szczegoly`) VALUES
(1, 'jan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'janek123', 0, '2025-11-23 16:29:44', '2025-11-23 16:29:44', 'VIP'),
(2, 'anna@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'anna88', 0, '2025-11-23 16:29:44', '2025-11-23 16:29:44', ''),
(3, 'piotr@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'piotr', 0, '2025-11-23 16:29:44', '2025-11-23 16:29:44', ''),
(4, 'kasia@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kasia', 0, '2025-11-23 16:29:44', '2025-11-23 16:29:44', ''),
(5, 'admin@sklep.pl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, '2025-11-23 16:29:44', '2025-11-23 16:29:44', 'Administrator');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_data`
--

CREATE TABLE `user_data` (
  `id_user_data` bigint UNSIGNED NOT NULL,
  `id_user` bigint UNSIGNED NOT NULL,
  `imię` varchar(50) COLLATE utf8mb4_polish_ci NOT NULL,
  `nazwisko` varchar(100) COLLATE utf8mb4_polish_ci NOT NULL,
  `ulica` varchar(100) COLLATE utf8mb4_polish_ci NOT NULL,
  `nr_domu` varchar(10) COLLATE utf8mb4_polish_ci NOT NULL,
  `miasto` varchar(50) COLLATE utf8mb4_polish_ci NOT NULL,
  `poczta` varchar(6) COLLATE utf8mb4_polish_ci NOT NULL,
  `kraj` varchar(30) COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci COMMENT='dane użytkowników';


INSERT INTO `user_data` (`id_user_data`, `id_user`, `imię`, `nazwisko`, `ulica`, `nr_domu`, `miasto`, `poczta`, `kraj`) VALUES
(1, 1, 'Jan', 'Kowalski', 'Kwiatowa', '12', 'Warszawa', '00-123', 'Polska'),
(2, 2, 'Anna', 'Nowak', 'Słoneczna', '8', 'Kraków', '30-001', 'Polska'),
(3, 3, 'Piotr', 'Lewandowski', 'Lipowa', '45', 'Gdańsk', '80-123', 'Polska'),
(4, 4, 'Kasia', 'Zielińska', 'Polna', '23', 'Wrocław', '50-001', 'Polska');

-- --------------------------------------------------------

CREATE TABLE `widok_historia_uzytkownika` (
`id_user` bigint unsigned
,`laczna_wartosc` decimal(32,2)
,`liczba_zakupow` bigint
,`srednia_cena_produktu` decimal(14,6)
);

-- --------------------------------------------------------

CREATE TABLE `widok_sprzedaz_kwartalna` (
`calkowita_kwota` decimal(32,2)
,`ilosc_sprzedanych_produktow` decimal(32,0)
,`kwartal` int
,`rok` int
);

-- --------------------------------------------------------

CREATE TABLE `widok_sprzedaz_miesieczna` (
`calkowita_kwota` decimal(32,2)
,`ilosc_sprzedanych_produktow` decimal(32,0)
,`miesiac` int
,`rok` int
);

-- --------------------------------------------------------

CREATE TABLE `widok_sprzedaz_roczna` (
`calkowita_kwota` decimal(32,2)
,`ilosc_sprzedanych_produktow` decimal(32,0)
,`rok` int
);

-- --------------------------------------------------------

--
-- Struktura widoku `widok_historia_uzytkownika`
--
DROP TABLE IF EXISTS `widok_historia_uzytkownika`;

CREATE ALGORITHM=UNDEFINED DEFINER=`m3573_admin`@`%.devil` SQL SECURITY DEFINER VIEW `widok_historia_uzytkownika`  AS SELECT `historia_zakupow`.`id_user` AS `id_user`, count(0) AS `liczba_zakupow`, sum(`historia_zakupow`.`wartosc_calkowita`) AS `laczna_wartosc`, avg(`historia_zakupow`.`cena_jednostkowa`) AS `srednia_cena_produktu` FROM `historia_zakupow` GROUP BY `historia_zakupow`.`id_user` ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_sprzedaz_kwartalna`
--
DROP TABLE IF EXISTS `widok_sprzedaz_kwartalna`;

CREATE ALGORITHM=UNDEFINED DEFINER=`m3573_admin`@`%.devil` SQL SECURITY DEFINER VIEW `widok_sprzedaz_kwartalna`  AS SELECT year(`o`.`created_at`) AS `rok`, quarter(`o`.`created_at`) AS `kwartal`, sum(`oi`.`quantity`) AS `ilosc_sprzedanych_produktow`, sum(`o`.`total_price`) AS `calkowita_kwota` FROM (`orders` `o` join `order_items` `oi` on((`o`.`id_order` = `oi`.`id_order`))) WHERE (`o`.`status` = 'completed') GROUP BY year(`o`.`created_at`), quarter(`o`.`created_at`) ORDER BY `rok` DESC, `kwartal` DESC ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_sprzedaz_miesieczna`
--
DROP TABLE IF EXISTS `widok_sprzedaz_miesieczna`;

CREATE ALGORITHM=UNDEFINED DEFINER=`m3573_admin`@`%.devil` SQL SECURITY DEFINER VIEW `widok_sprzedaz_miesieczna`  AS SELECT year(`o`.`created_at`) AS `rok`, month(`o`.`created_at`) AS `miesiac`, sum(`oi`.`quantity`) AS `ilosc_sprzedanych_produktow`, sum(`o`.`total_price`) AS `calkowita_kwota` FROM (`orders` `o` join `order_items` `oi` on((`o`.`id_order` = `oi`.`id_order`))) WHERE (`o`.`status` = 'completed') GROUP BY year(`o`.`created_at`), month(`o`.`created_at`) ORDER BY `rok` DESC, `miesiac` DESC ;

-- --------------------------------------------------------

--
-- Struktura widoku `widok_sprzedaz_roczna`
--
DROP TABLE IF EXISTS `widok_sprzedaz_roczna`;

CREATE ALGORITHM=UNDEFINED DEFINER=`m3573_admin`@`%.devil` SQL SECURITY DEFINER VIEW `widok_sprzedaz_roczna`  AS SELECT year(`o`.`created_at`) AS `rok`, sum(`oi`.`quantity`) AS `ilosc_sprzedanych_produktow`, sum(`o`.`total_price`) AS `calkowita_kwota` FROM (`orders` `o` join `order_items` `oi` on((`o`.`id_order` = `oi`.`id_order`))) WHERE (`o`.`status` = 'completed') GROUP BY year(`o`.`created_at`) ORDER BY `rok` DESC ;


--
-- Indeksy dla tabeli `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`);

--
-- Indeksy dla tabeli `historia_zakupow`
--
ALTER TABLE `historia_zakupow`
  ADD PRIMARY KEY (`id_historia`),
  ADD KEY `idx_user` (`id_user`),
  ADD KEY `idx_data` (`data_zakupu`),
  ADD KEY `idx_order` (`id_order`),
  ADD KEY `fk_historia_product` (`id_product`);

--
-- Indeksy dla tabeli `koszyk`
--
ALTER TABLE `koszyk`
  ADD PRIMARY KEY (`id_koszyk`),
  ADD KEY `idx_user_product` (`id_user`,`id_product`),
  ADD KEY `idx_product` (`id_product`);

--
-- Indeksy dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `idx_id_user` (`id_user`),
  ADD KEY `idx_status` (`status`);

--
-- Indeksy dla tabeli `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id_order_item`),
  ADD KEY `id_order` (`id_order`),
  ADD KEY `id_product` (`id_product`);

--
-- Indeksy dla tabeli `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `idx_id_order` (`id_order`),
  ADD KEY `idx_status` (`status`);

--
-- Indeksy dla tabeli `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`),
  ADD UNIQUE KEY `nazwa` (`nazwa`),
  ADD KEY `id_category` (`id_category`);

--
-- Indeksy dla tabeli `produkty_w_promocji`
--
ALTER TABLE `produkty_w_promocji`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_product` (`id_product`);

--
-- Indeksy dla tabeli `rabaty`
--
ALTER TABLE `rabaty`
  ADD PRIMARY KEY (`id_rabat`),
  ADD UNIQUE KEY `kod` (`kod`);

--
-- Indeksy dla tabeli `top_kategorie`
--
ALTER TABLE `top_kategorie`
  ADD PRIMARY KEY (`pozycja`),
  ADD UNIQUE KEY `uniq_category` (`id_category`);

--
-- Indeksy dla tabeli `top_produkty`
--
ALTER TABLE `top_produkty`
  ADD PRIMARY KEY (`pozycja`),
  ADD UNIQUE KEY `uniq_product` (`id_product`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `czy_admin` (`czy_admin`);

--
-- Indeksy dla tabeli `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`id_user_data`),
  ADD KEY `fk_user_data_user` (`id_user`);


ALTER TABLE `category`
  MODIFY `id_category` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;


ALTER TABLE `historia_zakupow`
  MODIFY `id_historia` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

ALTER TABLE `koszyk`
  MODIFY `id_koszyk` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `orders`
  MODIFY `id_order` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

ALTER TABLE `order_items`
  MODIFY `id_order_item` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

ALTER TABLE `payments`
  MODIFY `id_payment` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

ALTER TABLE `product`
  MODIFY `id_product` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `produkty_w_promocji`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `rabaty`
  MODIFY `id_rabat` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `users`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `user_data`
  MODIFY `id_user_data` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `historia_zakupow`
  ADD CONSTRAINT `fk_historia_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historia_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historia_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `koszyk`
  ADD CONSTRAINT `fk_koszyk_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_koszyk_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE;

ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON UPDATE CASCADE;

ALTER TABLE `product`
  ADD CONSTRAINT `fk_produkty_kategoria` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `produkty_w_promocji`
  ADD CONSTRAINT `fk_promocja_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE;

ALTER TABLE `user_data`
  ADD CONSTRAINT `fk_user_data_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
