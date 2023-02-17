-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql:3306
-- Generation Time: Nov 18, 2022 at 12:55 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.19

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
CREATE DATABASE IF NOT EXISTS `webshop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `webshop`;

-- --------------------------------------------------------

--
-- Table structure for table `Catagory`
--

DROP TABLE IF EXISTS `Catagory`;
CREATE TABLE `Catagory` (
  `idCatagory` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Melding`
--

DROP TABLE IF EXISTS `Melding`;
CREATE TABLE `Melding` (
  `idMelding` int NOT NULL,
  `idOrder` int NOT NULL,
  `melding` varchar(254) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Order`
--

DROP TABLE IF EXISTS `Order`;
CREATE TABLE `Order` (
  `idOrder` int NOT NULL,
  `idUser` int NOT NULL,
  `idParcel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Parcel`
--

DROP TABLE IF EXISTS `Parcel`;
CREATE TABLE `Parcel` (
  `idParcel` int NOT NULL,
  `postalCode` varchar(20) NOT NULL,
  `houseNumber` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Product`
--

DROP TABLE IF EXISTS `Product`;
CREATE TABLE `Product` (
  `idProduct` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `descr` varchar(255) NOT NULL,
  `catagory` int NOT NULL,
  `price` float NOT NULL,
  `stock` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Retour`
--

DROP TABLE IF EXISTS `Retour`;
CREATE TABLE `Retour` (
  `idRetour` int NOT NULL,
  `idOrder` int NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ShoppingCart`
--

DROP TABLE IF EXISTS `ShoppingCart`;
CREATE TABLE `ShoppingCart` (
  `idShoppingCart` int NOT NULL,
  `idUser` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ShoppingCartItem`
--

DROP TABLE IF EXISTS `ShoppingCartItem`;
CREATE TABLE `ShoppingCartItem` (
  `idProduct` int NOT NULL,
  `idShoppingCart` int NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `idUser` int NOT NULL,
  `firstName` varchar(254) NOT NULL,
  `lastName` varchar(254) NOT NULL,
  `email` varchar(254) NOT NULL,
  `password` varchar(254) NOT NULL,
  `level` int NOT NULL DEFAULT '1',
  `postalCode` varchar(254) NOT NULL,
  `houseNumber` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Voucher`
--

DROP TABLE IF EXISTS `Voucher`;
CREATE TABLE `Voucher` (
  `idVoucher` int NOT NULL,
  `code` varchar(254) NOT NULL,
  `worth` float NOT NULL,
  `pastDue` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Catagory`
--
ALTER TABLE `Catagory`
  ADD PRIMARY KEY (`idCatagory`);

--
-- Indexes for table `Melding`
--
ALTER TABLE `Melding`
  ADD PRIMARY KEY (`idMelding`),
  ADD KEY `idOrder` (`idOrder`);

--
-- Indexes for table `Order`
--
ALTER TABLE `Order`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `idParcel` (`idParcel`),
  ADD KEY `idUser` (`idUser`);

--
-- Indexes for table `Parcel`
--
ALTER TABLE `Parcel`
  ADD PRIMARY KEY (`idParcel`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`idProduct`),
  ADD KEY `catagory` (`catagory`);

--
-- Indexes for table `Retour`
--
ALTER TABLE `Retour`
  ADD PRIMARY KEY (`idRetour`),
  ADD KEY `idOrder` (`idOrder`);

--
-- Indexes for table `ShoppingCart`
--
ALTER TABLE `ShoppingCart`
  ADD PRIMARY KEY (`idShoppingCart`),
  ADD KEY `idUser` (`idUser`);

--
-- Indexes for table `ShoppingCartItem`
--
ALTER TABLE `ShoppingCartItem`
  ADD KEY `idProduct` (`idProduct`),
  ADD KEY `idShoppingCart` (`idShoppingCart`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`idUser`);

--
-- Indexes for table `Voucher`
--
ALTER TABLE `Voucher`
  ADD PRIMARY KEY (`idVoucher`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Catagory`
--
ALTER TABLE `Catagory`
  MODIFY `idCatagory` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Melding`
--
ALTER TABLE `Melding`
  MODIFY `idMelding` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Order`
--
ALTER TABLE `Order`
  MODIFY `idOrder` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Parcel`
--
ALTER TABLE `Parcel`
  MODIFY `idParcel` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product`
  MODIFY `idProduct` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Retour`
--
ALTER TABLE `Retour`
  MODIFY `idRetour` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ShoppingCart`
--
ALTER TABLE `ShoppingCart`
  MODIFY `idShoppingCart` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Voucher`
--
ALTER TABLE `Voucher`
  MODIFY `idVoucher` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Melding`
--
ALTER TABLE `Melding`
  ADD CONSTRAINT `melding_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `Order` (`idOrder`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `Order`
--
ALTER TABLE `Order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`idParcel`) REFERENCES `Parcel` (`idParcel`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `order_ibfk_2` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`catagory`) REFERENCES `Catagory` (`idCatagory`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `Retour`
--
ALTER TABLE `Retour`
  ADD CONSTRAINT `retour_ibfk_1` FOREIGN KEY (`idOrder`) REFERENCES `Order` (`idOrder`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `ShoppingCart`
--
ALTER TABLE `ShoppingCart`
  ADD CONSTRAINT `shoppingcart_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `User` (`idUser`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `ShoppingCartItem`
--
ALTER TABLE `ShoppingCartItem`
  ADD CONSTRAINT `shoppingcartitem_ibfk_1` FOREIGN KEY (`idProduct`) REFERENCES `Product` (`idProduct`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `shoppingcartitem_ibfk_2` FOREIGN KEY (`idShoppingCart`) REFERENCES `ShoppingCart` (`idShoppingCart`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
