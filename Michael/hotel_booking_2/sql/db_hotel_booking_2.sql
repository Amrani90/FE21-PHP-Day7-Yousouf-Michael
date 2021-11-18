-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Nov 2021 um 15:21
-- Server-Version: 10.4.21-MariaDB
-- PHP-Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `db_hotel_booking_2`
--
CREATE DATABASE IF NOT EXISTS `db_hotel_booking_2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_hotel_booking_2`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bookings`
--

CREATE TABLE `bookings` (
  `book_id` int(5) NOT NULL,
  `fk_hotel_id` int(5) NOT NULL,
  `fk_u_id` int(5) NOT NULL,
  `room` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `bookings`
--

INSERT INTO `bookings` (`book_id`, `fk_hotel_id`, `fk_u_id`, `room`) VALUES
(1, 1, 9, 33),
(3, 1, 9, 66),
(4, 2, 9, 13),
(6, 2, 9, 7),
(7, 3, 10, 1),
(8, 4, 4, 1),
(9, 3, 10, 77),
(10, 2, 9, 44);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `hotels`
--

CREATE TABLE `hotels` (
  `hotel_id` int(5) NOT NULL,
  `hotel_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `price` decimal(13,2) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `roomnumb` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `hotels`
--

INSERT INTO `hotels` (`hotel_id`, `hotel_name`, `address`, `price`, `picture`, `roomnumb`) VALUES
(1, 'Rote Traube', 'street 1a, city1, zip1, country1', '29.00', 'hotel-default.jpg', 53),
(2, 'Mar del Plata', 'street 2, city2, zip2, country2', '30.00', 'hotel-default.jpg', 80),
(3, 'Auenhof GmbH', 'street 3c, city3, zip3c, country3', '34.00', '61961fa251e75.jpg', 73),
(4, 'Post Gmbh', 'street 4, city4, zip,4, country4', '31.00', 'hotel-default.jpg', 50);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `u_id` int(5) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `s_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `u_picture` varchar(255) DEFAULT NULL,
  `status` varchar(4) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`u_id`, `f_name`, `s_name`, `password`, `date_of_birth`, `email`, `u_picture`, `status`) VALUES
(4, 'Mike', 'Chef', '9c56cc51b374c3ba189210d5b6d4bf57790d351c96c47c02190ecf1e430635ab', '1982-11-27', 'mike-chef@aon.at', 'admavatar.png', 'adm'),
(9, 'andrew', 'update', 'ef797c8118f02dfb649607dd5d3f8c7623048c9c063d532cc95c5ed7a898a64f', '1990-11-05', 'andy-update@aon.at', 'default-user.png', 'user'),
(10, 'carl', 'carson', '69f9cf374180fe523e2d77a7f0ff502cb080ca2dd9351af36a7ecaa52a1c4827', '1980-11-05', 'carl-car@aon.at', 'default-user.png', 'user');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `fk_hotel_id` (`fk_hotel_id`),
  ADD KEY `fk_u_id` (`fk_u_id`);

--
-- Indizes für die Tabelle `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`hotel_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bookings`
--
ALTER TABLE `bookings`
  MODIFY `book_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `hotels`
--
ALTER TABLE `hotels`
  MODIFY `hotel_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`fk_hotel_id`) REFERENCES `hotels` (`hotel_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`fk_u_id`) REFERENCES `user` (`u_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
