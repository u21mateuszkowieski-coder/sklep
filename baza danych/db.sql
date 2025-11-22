-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql1.small.pl
-- Generation Time: Lis 22, 2025 at 07:34 PM
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

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `category`
--

CREATE TABLE `category` (
  `id_category` bigint UNSIGNED NOT NULL,
  `nazwa` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

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

--
-- Wyzwalacze `order_items`
--
DELIMITER $$
CREATE TRIGGER `po_zlozeniu_zamowienia_wstaw_historie` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    INSERT INTO historia_zakupow (
        id_user,
        id_order,
        id_product,
        nazwa_produktu,
        zdjecie,
        cena_jednostkowa,
        ilosc,
        wartosc_calkowita,
        data_zakupu,
        status_zamowienia
    )
    SELECT 
        o.id_user,
        NEW.id_order,
        NEW.id_product,
        p.name,
        p.zdjecie,
        NEW.price_per_unit,
        NEW.quantity,
        NEW.price_per_unit * NEW.quantity,
        o.created_at,
        o.status
    FROM orders o
    JOIN product p ON p.id_product = NEW.id_product
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

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_historia_uzytkownika`
-- (See below for the actual view)
--
CREATE TABLE `widok_historia_uzytkownika` (
`id_user` bigint unsigned
,`liczba_zakupow` bigint
,`laczna_wartosc` decimal(32,2)
,`srednia_cena_produktu` decimal(14,6)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_sprzedaz_kwartalna`
-- (See below for the actual view)
--
CREATE TABLE `widok_sprzedaz_kwartalna` (
`rok` int
,`kwartal` int
,`ilosc_sprzedanych_produktow` decimal(32,0)
,`calkowita_kwota` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_sprzedaz_miesieczna`
-- (See below for the actual view)
--
CREATE TABLE `widok_sprzedaz_miesieczna` (
`rok` int
,`miesiac` int
,`ilosc_sprzedanych_produktow` decimal(32,0)
,`calkowita_kwota` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Zastąpiona struktura widoku `widok_sprzedaz_roczna`
-- (See below for the actual view)
--
CREATE TABLE `widok_sprzedaz_roczna` (
`rok` int
,`ilosc_sprzedanych_produktow` decimal(32,0)
,`calkowita_kwota` decimal(32,2)
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
-- Indeksy dla zrzutów tabel
--

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
-- Indeksy dla tabeli `rabaty`
--
ALTER TABLE `rabaty`
  ADD PRIMARY KEY (`id_rabat`),
  ADD UNIQUE KEY `kod` (`kod`);

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

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_category` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `historia_zakupow`
--
ALTER TABLE `historia_zakupow`
  MODIFY `id_historia` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `koszyk`
--
ALTER TABLE `koszyk`
  MODIFY `id_koszyk` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id_order_item` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id_payment` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rabaty`
--
ALTER TABLE `rabaty`
  MODIFY `id_rabat` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_data`
--
ALTER TABLE `user_data`
  MODIFY `id_user_data` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `historia_zakupow`
--
ALTER TABLE `historia_zakupow`
  ADD CONSTRAINT `fk_historia_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historia_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historia_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `koszyk`
--
ALTER TABLE `koszyk`
  ADD CONSTRAINT `fk_koszyk_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_koszyk_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_order` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_produkty_kategoria` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_data`
--
ALTER TABLE `user_data`
  ADD CONSTRAINT `fk_user_data_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
