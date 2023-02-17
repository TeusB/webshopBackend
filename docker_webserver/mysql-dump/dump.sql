-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Gegenereerd op: 14 jun 2020 om 10:31
-- Serverversie: 8.0.20
-- PHP-versie: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webshop`
--
DROP DATABASE IF EXISTS `tomaat`;
CREATE DATABASE IF NOT EXISTS `tomaat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `tomaat`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `iduser` int NOT NULL,
  `name` varchar(254) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL,
  `role` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`iduser`, `name`, `email`, `password`, `role`) VALUES
(1, 'Test account manager', 'manager@tomaat.nl', '1234', 2),
(2, 'Test account medewerker', 'medewerker@tomaat.nl', '1234', 1),
(3, 'Test account gast', 'gast@hotmail.com', '1234', 0);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Database: `webshop`
--
DROP DATABASE IF EXISTS `webshop`;
CREATE DATABASE IF NOT EXISTS `webshop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `webshop`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `idcart` int NOT NULL,
  `temphash` varchar(254) NOT NULL,
  `idproduct` int NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `cart`
--

INSERT INTO `cart` (`idcart`, `temphash`, `idproduct`, `quantity`) VALUES
(1, '$2y$10$5RB15ZrYLIsfttZzAf6BRuqhL6rj01nd0HL8FBwJ/tubKy05c.eFa', 1, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `idproduct` int NOT NULL,
  `name` varchar(254) NOT NULL,
  `description` text NOT NULL,
  `stock` int NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Gegevens worden geëxporteerd voor tabel `products`
--

INSERT INTO `product` (`idproduct`, `name`, `description`, `stock`, `price`) VALUES
(1, 'Fiets', 'Een mooie fiets', 12, 34312),
(2, 'Laptop', 'Een mooie laptop', 111, 79900);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `iduser` int NOT NULL,
  `username` varchar(254) NOT NULL,
  `password` varchar(254) NOT NULL,
  `name` varchar(254) NOT NULL,
  `email` varchar(254) NOT NULL,
  `tel` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`idcart`);

--
-- Indexen voor tabel `products`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`idproduct`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `idcart` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT voor een tabel `products`
--
ALTER TABLE `product`
  MODIFY `idproduct` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
