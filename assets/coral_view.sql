-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 10, 2021 at 09:43 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coral_view`
--

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `time_stamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `billing_additional_fees`
--

CREATE TABLE `billing_additional_fees` (
  `Id` int(11) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `time_stamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `billing_discount`
--

CREATE TABLE `billing_discount` (
  `Id` int(11) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `billing_extras`
--

CREATE TABLE `billing_extras` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `expense_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking_rooms`
--

CREATE TABLE `booking_rooms` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking_rooms`
--

INSERT INTO `booking_rooms` (`id`, `reservation_id`, `room_id`, `quantity`) VALUES
(95, 110, 21, 1),
(96, 111, 21, 1);

-- --------------------------------------------------------

--
-- Table structure for table `check_in_rooms`
--

CREATE TABLE `check_in_rooms` (
  `Id` int(11) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `room_number` varchar(255) DEFAULT NULL,
  `is_check_out` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `Id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`Id`, `name`, `amount`) VALUES
(1, 'Senior Citizen / PWD', 0.2),
(3, 'PWD', 0.2);

-- --------------------------------------------------------

--
-- Table structure for table `downpayment`
--

CREATE TABLE `downpayment` (
  `id` int(11) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `time_stamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `extras`
--

CREATE TABLE `extras` (
  `Id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `extras`
--

INSERT INTO `extras` (`Id`, `description`, `price`) VALUES
(1, 'Matress', '800'),
(2, 'Pillow', '100'),
(8, 'Food', '500');

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `reference_no` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `food`
--

INSERT INTO `food` (`reference_no`, `subject`, `description`, `created_date`) VALUES
('CRLVW-F933664', 'asdasda', 'asdasda', '2021-10-09 14:22:45'),
('CRLVW-F933664', 'asfasfa', 'asfasfa', '2021-10-09 14:23:18'),
('CRLVW-F933664', 'asfdas', 'asfdas', '2021-10-09 14:23:37'),
('', 'LOREMM', 'LOREMM', '2021-10-09 20:23:26'),
('asdsadsad', 'asdasdsa', 'asdasdsa', '2021-10-09 20:28:25'),
('CRLVW-F933664', 'CRLVW-F933664', 'CRLVW-F933664', '2021-10-09 20:45:00'),
('CRLVW-F933664', 'asdasd', 'asdasd', '2021-10-09 20:45:33');

-- --------------------------------------------------------

--
-- Table structure for table `guest`
--

CREATE TABLE `guest` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guest`
--

INSERT INTO `guest` (`id`, `first_name`, `last_name`, `address`, `email`, `contact_number`) VALUES
(207, 'camille', 'cabrieto', 'ca', 'camillecabrieto@gmail.com', '09281234567'),
(208, 'camille', 'cabrieto', 'ca', 'camillecabrieto@gmail.com', '09281234567');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `payment` varchar(255) DEFAULT NULL,
  `check_in_date` datetime DEFAULT NULL,
  `check_out_date` datetime DEFAULT NULL,
  `adult_count` int(11) DEFAULT NULL,
  `kids_count` int(11) DEFAULT NULL,
  `is_peak_rate` int(11) NOT NULL,
  `payment_path` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `guest_id`, `reference_no`, `status`, `payment`, `check_in_date`, `check_out_date`, `adult_count`, `kids_count`, `is_peak_rate`, `payment_path`, `date_created`, `date_updated`) VALUES
(110, 207, 'KLIR-4F2AC91', 'PENDING', 'BANK DEPOSIT', '2021-10-11 00:00:00', '2021-10-13 00:00:00', 1, 1, 0, NULL, '2021-10-10 15:39:04', NULL),
(111, 208, 'KLIR-FD56F49', 'PENDING', 'BANK DEPOSIT', '2021-10-11 00:00:00', '2021-10-13 00:00:00', 1, 1, 0, NULL, '2021-10-10 15:39:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reservation_expenses`
--

CREATE TABLE `reservation_expenses` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `room_quantity` int(11) DEFAULT NULL,
  `amount` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `Id` int(11) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `peak_rate` float DEFAULT NULL,
  `off_peak_rate` float DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `inclusions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`Id`, `number`, `type`, `peak_rate`, `off_peak_rate`, `description`, `image`, `capacity`, `inclusions`) VALUES
(21, NULL, 'DELUXE ROOM', 3290, 2632, '* Good for 2 persons\\r\\n\\r\\n+ Complimentary use of swimming pools\\r\\n\\r\\n+ Complimentary breakfast for 2 persons\\r\\n\\r\\n* Additional person with swimming and breakfast - Php 800.00 / head', 'uploads/rooms/deluxe.jpg', 2, '<ul><li>Air-Conditioned Room</li><li>1 Queen Bed / 2 Single Bed</li><li>Television</li><li>Toilet & Bath</li><li>Hot and cold shower</li><li>Electric kettle</li></ul>'),
(22, NULL, 'SUPERIOR ROOM', 2890, 2312, '* Good for 2 persons\\r\\n\\r\\n+ Complimentary use of swimming pools\\r\\n\\r\\n+ Complimentary breakfast for 2 persons\\r\\n\\r\\n* Additional person with swimming and breakfast - Php 800.00 / head', 'uploads/rooms/superior twin room.jpg', 2, '<ul><li>2 Queen-sized bed</li><li>Private balcony with beach view</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 4 adults</li></ul>'),
(23, NULL, 'DELUXE TWIN ROOM', 3890, 3112, '* Good for 2 persons\\r\\n\\r\\n+ Complimentary use of swimming pools\\r\\n\\r\\n+ Complimentary breakfast for 2 persons\\r\\n\\r\\n* Additional person with swimming and breakfast - Php 800.00 / head', 'uploads/rooms/Deluxe_Twin_Bed_2.jpg', 2, '<ul><li>Air-Conditioned Room</li><li>2 Queen Bed</li><li>Television</li><li>Toilet & Bath</li><li>Hot and cold shower</li><li>Electric kettle</li></ul>'),
(24, NULL, 'BUNK BED ROOM', 3890, 3112, '* Good for 3 persons\\r\\n\\r\\n+ Complimentary use of swimming pools\\r\\n\\r\\n+ Complimentary breakfast for 3 persons\\r\\n\\r\\n* Additional person with swimming and breakfast - Php 800.00 / head', 'uploads/rooms/bunk bed room 2.jpg', 3, '<ul><li>Air-Conditioned Room</li><li>3 Bunk Bed</li><li>Television</li><li>Toilet & Bath</li><li>Hot and Cold Shower</li><li>Electric kettle</li></ul>'),
(25, NULL, 'FAMILY ROOM', 5900, 4720, '* Good for 4 persons\\r\\n\\r\\n+ Complimentary use of swimming pools\\r\\n\\r\\n+ Complimentary breakfast for 2 persons\\r\\n\\r\\n* Additional person with swimming and breakfast - Php 800.00 / head', 'uploads/rooms/fam room.jpg', 4, '<ul><li>2 Air-Conditioned Room</li><li>1 Queen Bed and 3 bunk Bed</li><li>Television</li><li>Toilet & Bath</li><li>Hot and Cold Shower</li><li>Electric kettle</li></ul>'),
(26, NULL, 'SUITE ROOM', 4920, 3936, '* Good for 2 persons\\r\\n\\r\\n+ Complimentary use of swimming pools\\r\\n\\r\\n+ Complimentary breakfast for 2 persons\\r\\n\\r\\n* Additional person with swimming and breakfast - Php 800.00 / head+ Complimentary use of swimming pools\\r\\n', 'uploads/rooms/suite.jpg', 2, '<ul><li>Air-Conditioned Room</li><li>1 King Bed</li><li>2 Television</li><li>Toilet & Bath</li><li>Hot and Cold Shower</li><li>Electric kettle</li></ul>'),
(41, '1', '1', 1, 1, '1', '../.../uploads/rooms/CONFIRMATION SLIP REPLY.jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms_status`
--

CREATE TABLE `rooms_status` (
  `room_number` varchar(255) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rooms_status`
--

INSERT INTO `rooms_status` (`room_number`, `room_id`, `status`, `id`) VALUES
('PREMIERE A', 22, 'AVAILABLE', 98),
('PREMIERE B', 22, 'AVAILABLE', 99),
('PREMIERE C', 22, 'AVAILABLE', 100),
('PREMIERE C', 22, 'AVAILABLE', 101),
('DELUXE E1', 23, 'AVAILABLE', 102),
('DELUXE E', 23, 'AVAILABLE', 103),
('DELUXE F', 23, 'AVAILABLE', 104),
('DELUXE G', 23, 'AVAILABLE', 105),
('DELUXE H', 23, 'AVAILABLE', 106),
('DELUXE I', 23, 'AVAILABLE', 107),
('DELUXE J', 23, 'AVAILABLE', 108),
('SUPER DELUXE M', 21, 'OCCUPIED', 142),
('SUPER DELUXE N', 21, 'OCCUPIED', 143),
('SUPER DELUXE O', 21, 'AVAILABLE', 144),
('SUPER DELUXE P', 21, 'AVAILABLE', 145),
('SUPER DELUXE R', 21, 'AVAILABLE', 146),
('SUPER DELUXE S', 21, 'AVAILABLE', 147),
('SUPER DELUXE U', 21, 'AVAILABLE', 148),
('SUPER DELUXE V', 21, 'AVAILABLE', 149),
('SUPER DELUXE W', 21, 'AVAILABLE', 150),
('SUPER DELUXE X', 21, 'AVAILABLE', 151),
('SUPER DELUXE Z', 21, 'AVAILABLE', 152),
('DOUBLE DELUXE Q', 24, 'AVAILABLE', 153),
('DOUBLE DELUXE T', 24, 'AVAILABLE', 154),
('DOUBLE DELUXE Y', 24, 'AVAILABLE', 155),
('SUITE L', 25, 'AVAILABLE', 156),
('SUITE K', 26, 'AVAILABLE', 157);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `UserName` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `UserType` varchar(255) DEFAULT NULL,
  `Type` varchar(255) NOT NULL,
  `PhoneNumber` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `FullName`, `UserName`, `Password`, `Email`, `UserType`, `Type`, `PhoneNumber`) VALUES
(2, 'ewq', 'zcx', 'ghj', 'uyi', NULL, 'yui', 'qewqe'),
(5, 'john', 'doe', '8cb2237d0679ca88db6464eac60da96345513964', 'asd@gmail.com', 'RECEPTIONIST', 'RECEPTIONIST', '123'),
(8, 'john', 'john123', '890123', 'john@gmail.com', NULL, 'user', '123456'),
(9, 'Yo', 'test123', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'relliebalagat@gmail.com', NULL, 'Administrator', '123456'),
(12, 'admin', 'admin', 'admin', 'admin@gmail.com', NULL, 'Administrator', '123456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing_additional_fees`
--
ALTER TABLE `billing_additional_fees`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `billing_discount`
--
ALTER TABLE `billing_discount`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `billing_extras`
--
ALTER TABLE `billing_extras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `check_in_rooms`
--
ALTER TABLE `check_in_rooms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `downpayment`
--
ALTER TABLE `downpayment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extras`
--
ALTER TABLE `extras`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `guest`
--
ALTER TABLE `guest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation_expenses`
--
ALTER TABLE `reservation_expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `rooms_status`
--
ALTER TABLE `rooms_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `billing_additional_fees`
--
ALTER TABLE `billing_additional_fees`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `billing_discount`
--
ALTER TABLE `billing_discount`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `billing_extras`
--
ALTER TABLE `billing_extras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `check_in_rooms`
--
ALTER TABLE `check_in_rooms`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `downpayment`
--
ALTER TABLE `downpayment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `extras`
--
ALTER TABLE `extras`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `reservation_expenses`
--
ALTER TABLE `reservation_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `rooms_status`
--
ALTER TABLE `rooms_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
