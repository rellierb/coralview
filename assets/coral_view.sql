-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 02, 2019 at 02:05 AM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `amount_paid` decimal(10,0) DEFAULT NULL,
  `total_amount` decimal(10,0) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `time_stamp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`id`, `reference_no`, `amount_paid`, `total_amount`, `description`, `time_stamp`) VALUES
(15, 'CRLVW-A2009D2', '22500', NULL, 'Customer Client Payment', '2019-08-27 05:09:23'),
(21, 'CRLVW-3F311FB', '9180', NULL, 'Customer Client Payment', '2019-09-01 16:11:14'),
(22, 'CRLVW-1C800E0', '4180', NULL, 'Customer Client Payment', '2019-09-01 19:28:09'),
(23, 'CRLVW-1C800E0', '3240', NULL, 'Customer Client Payment', '2019-09-01 20:37:43');

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

--
-- Dumping data for table `billing_additional_fees`
--

INSERT INTO `billing_additional_fees` (`Id`, `reference_no`, `amount`, `description`, `time_stamp`) VALUES
(1, 'CRLVW-A2009D2', 123, 'TEST', '2019-08-27 06:38:53'),
(2, 'CRLVW-1C800E0', 1000, 'TEST', '2019-09-01 19:44:25'),
(3, 'CRLVW-1C800E0', 1000, 'TEST 2', '2019-09-01 19:51:12'),
(4, 'CRLVW-1C800E0', 1000, 'TEST 3', '2019-09-01 20:31:37');

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

--
-- Dumping data for table `billing_discount`
--

INSERT INTO `billing_discount` (`Id`, `reference_no`, `discount_id`, `quantity`) VALUES
(1, 'CRLVW-A2009D2', 1, 1),
(2, 'CRLVW-A2009D2', 2, 1),
(3, 'CRLVW-3F311FB', 1, 1),
(4, 'CRLVW-1C800E0', 2, 1);

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

--
-- Dumping data for table `billing_extras`
--

INSERT INTO `billing_extras` (`id`, `reference_no`, `expense_id`, `quantity`, `amount`) VALUES
(12, 'CRLVW-1C800E0', 1, 4, 100),
(13, 'CRLVW-1C800E0', 2, 4, 100),
(14, 'CRLVW-3F311FB', 1, 1, 100),
(15, 'CRLVW-3F311FB', 2, 1, 100);

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
(43, 71, 21, 2),
(44, 72, 21, 2),
(45, 73, 21, 2);

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

--
-- Dumping data for table `check_in_rooms`
--

INSERT INTO `check_in_rooms` (`Id`, `reference_no`, `room_number`, `is_check_out`) VALUES
(1, 'CRLVW-A2009D2', 'CRLVW-508', NULL),
(2, 'CRLVW-A2009D2', 'CRLVW-509', NULL),
(3, 'CRLVW-A2009D2', 'CRLVW-104', NULL),
(4, 'CRLVW-A2009D2', 'CRLVW-105', NULL),
(5, 'CRLVW-861E81F', 'CRLVW-508', NULL),
(6, 'CRLVW-861E81F', 'CRLVW-509', NULL),
(7, 'CRLVW-3F311FB', 'CRLVW-508', NULL),
(8, 'CRLVW-3F311FB', 'CRLVW-509', NULL),
(9, 'CRLVW-1C800E0', 'CRLVW-601', NULL),
(10, 'CRLVW-1C800E0', 'CRLVW-602', NULL);

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
(1, 'Senior Citizen', 0.2),
(2, 'PWD', 0.2);

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

--
-- Dumping data for table `downpayment`
--

INSERT INTO `downpayment` (`id`, `reference_no`, `amount`, `description`, `time_stamp`) VALUES
(1, 'CRLVW-1C800E0', 5000, NULL, '2019-09-01 12:40:17');

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
(1, 'Matress', '100'),
(2, 'Pillow', '100');

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
(68, 'Rellie', 'Balagat', 'TEST', 'rellierubiobalagat@gmail.com', '123456'),
(69, 'Rellie', 'Balagat', '86-A Tandang sora ave qc', 'relliebalagat@gmail.com', '123456'),
(70, 'Rellie', 'Balagat', '86-A Tandang sora ave qc', 'relliebalagat@gmail.com', '123456'),
(71, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(72, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(73, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(74, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(75, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(76, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(77, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(78, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(79, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(80, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(81, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(82, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(83, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(84, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(85, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(86, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(87, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(88, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(89, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(90, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(91, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(92, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(93, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(94, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(95, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(96, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(97, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(98, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(99, '', '', '', '', ''),
(100, 'Rellie', 'Balagat', '86-A Tandang Sora', 'relliebalagat@gmail.com', '09123456789'),
(101, 'Rellie', 'Balagat', '86-A Tandang Sora Ave', 'relliebalagat@gmail.com', '09123456789'),
(102, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789'),
(103, 'Rellie', 'Balagat', 'TEST', 'relliebalagat@gmail.com', '09123456789');

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
  `date_created` datetime DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `guest_id`, `reference_no`, `status`, `payment`, `check_in_date`, `check_out_date`, `adult_count`, `kids_count`, `date_created`, `date_updated`) VALUES
(71, 101, 'CRLVW-1C800E0', 'COMPLETE', 'BANK DEPOSIT', '2019-08-31 00:00:00', '2019-09-01 00:00:00', 1, 1, '2019-08-31 15:42:42', '2019-09-02 00:00:00'),
(73, 103, 'CRLVW-3F311FB', 'FOR CHECK OUT', 'WALK-IN / CASH', '2019-09-01 00:00:00', '2019-09-04 00:00:00', 1, 1, '2019-09-01 15:32:17', '2019-09-02 00:00:00');

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
(21, NULL, 'Super Deluxe', 5000, 5600, 'Soak up a breathtaking view of the beach from the comfort of your own private balcony. The Super Deluxe room is furnished with 2 Queen sized beds and a refrigerator for all your snacks and beverages.', '/coralview/uploads/rooms/super-deluxe.jpeg', 4, '<ul><li>2 Queen size bed</li><li>Air-Conditioned room</li><li>Private balcony with beach view</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 4 adults</li></ul>'),
(22, NULL, 'Premier', 7500, 7000, 'Take in the relaxing breeze of the bay from the comfort of your own private balcony. The premier room offers breathtaking views of the beach and can accommodate 4 adults with its 2 queen-sized beds.', '/coralview/uploads/rooms/premier.jpeg', 4, '<ul><li>2 Queen-sized bed</li><li>Private balcony with beach view</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 4 adults</li></ul>'),
(23, NULL, 'Deluxe', 5000, 4500, 'The Deluxe room directly faces the beach and is ideal for small families with its 2 queen-sized beds.', '/coralview/uploads/rooms/deluxe.jpg', 4, '<ul><li>2 Queen-sized bed</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 4 adults</li></ul>'),
(24, NULL, 'Double Deluxe', 8500, 7500, 'Experience spacious comfort with 4 queen-sized beds that can accommodate 8 adults. The Super Deluxe room also features a private balcony and views of the expansive beach area.', '/coralview/uploads/rooms/double-deluxe.jpeg', 4, '<ul><li>4 Queen-sized bed</li><li>Air-Conditioned room</li><li>Private balcony with beach view</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 8 adults</li></ul>'),
(25, NULL, 'Suite with kitchen', 8500, 8000, 'The Suite with a kitchen is a spacious option for small families who love home cooked meals. The suite is furnished with a queen-sized bed, a refrigerator and a dining area. Extra bed upon request.', '/coralview/uploads/rooms/suite-w-kitchen.jpeg', 4, '<ul><li>1 King-sized bed</li><li>2 Queen-sized pull-out beds</li><li>Air-Conditioned room</li><li>Beach view</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 4 adults</li></ul>'),
(26, NULL, 'Suite w/o kitchen', 8000, 7500, 'Relax and wake up refreshed with a spacious room that offers a spectacular beachfront view. ', '/coralview/uploads/rooms/suite-kitchen.jpeg', 4, '<ul><li>1 King-sized bed</li><li>Refrigerator</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 4 adults</li></ul>'),
(27, NULL, 'Family with kitchen', 8500, 7500, 'Relax and wake up refreshed with a spacious room furnished with its own kitchen and dining table. With 4 single beds and 1 double bed, the Family room can comfortably fit up to 6 adults.', '/coralview/uploads/rooms/family-w-kitchen.jpeg', 6, '<ul><li>4 single-sized beds</li><li>1 double-sized bed</li><li>Kitchen</li><li>Dining table</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 6 adults</li></ul>'),
(28, NULL, 'Family w/o kitchen', 7500, 6500, 'With a capacity of up to 8 adults, the family room comes furnished with 4 single beds and 2 double beds which is ideal for large families.', '/coralview/uploads/rooms/family-kitchen.jpeg', 8, '<ul><li>4 single-sized beds</li><li>2 double-sized beds</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 8 adults</li></ul>'),
(29, NULL, 'Coralview Villa', 19000, 16000, 'This much-coveted villa features 2 bedrooms, a kitchen, a dining area, a grilling station, a mini infinity pool and a private lanai where you can enjoy a tropical breeze. The Coral View villa can comfortably accommodate up to 10 adults.', '/coralview/uploads/rooms/coralviewvilla.jpeg', 10, '<ul><li>2 bedrooms</li><li>Private Lanai with beach view</li><li>Kitchen</li><li>Dining area</li><li>Grilling station</li><li>Mini infinity pool</li><li>Air-Conditioned rooms</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li></ul>'),
(30, NULL, 'Quad', 4500, 4000, 'Enjoy direct access to the salt water swimming pool when you stay at the Quad room. The room features 2 queen-sized beds and is ideal for a small family or a group of friends.', '/coralview/uploads/rooms/quad.jpeg', 4, '<ul><li>2 Queen-sized bed</li><li>Air-Conditioned room</li><li>Direct access to saltwater pool</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 4 adults.</li></ul>'),
(32, NULL, 'Single', 3700, 3200, 'A comfortable and spacious single room offering a cozy place to relax featuring a queen-sized bed.\r\n\r\n', '/coralview/uploads/rooms/single.jpeg', 2, '<ul><li>Queen-sized bed</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li><li>Complimentary breakfast for 2 adults.</li></ul>\r\n\r\n\r\n\r\n\r\n\r\n'),
(33, NULL, 'Dormitory 1', 7800, 5600, 'Ideal for a group of friends or corporate bookings, The Dormitory room features a bunk bed-style setting and a private bath.', '/coralview/uploads/rooms/dormitory.jpeg', 10, '<ul><li>3 Rooms</li><li>Bunk Bed-style setting</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li></ul>'),
(34, NULL, 'Dormitory 2', 10000, 7800, 'Ideal for a group of friends or corporate bookings, The Dormitory room features a bunk bed-style setting and a private bath.', '/coralview/uploads/rooms/dormitory.jpeg', 14, '<ul><li>3 Rooms</li><li>Bunk Bed-style setting</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li></ul>'),
(35, NULL, 'Dormitory 3', 12000, 10000, 'Ideal for a group of friends or corporate bookings, The Dormitory room features a bunk bed-style setting and a private bath.', '/coralview/uploads/rooms/dormitory.jpeg', 18, '<ul><li>4 Rooms</li><li>Bunk Bed-style setting</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li></ul>'),
(36, NULL, 'Coral View (Villa Unit)', 4500, 4000, 'With 2 queen-sized beds, this guestroom offers privacy and basic amenities which makes it an affordable option for families.', '/coralview/uploads/rooms/coralviewvilla.jpeg', 4, '<ul><li>2 Queen-sized beds</li><li>Air-Conditioned room</li><li>LCD Cable TV</li><li>Hot and Cold Shower/Private bath</li></ul>');

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
('CRLVW-101', 32, 'FOR REPAIR', 95),
('CRLVW-102', 32, 'OCCUPIED', 96),
('CRLVW-103', 32, 'AVAILABLE', 97),
('CRLVW-104', 22, 'AVAILABLE', 98),
('CRLVW-105', 22, 'AVAILABLE', 99),
('CRLVW-106', 22, 'AVAILABLE', 100),
('CRLVW-107', 22, 'AVAILABLE', 101),
('CRLVW-108', 23, 'AVAILABLE', 102),
('CRLVW-109', 23, 'AVAILABLE', 103),
('CRLVW-201', 23, 'AVAILABLE', 104),
('CRLVW-202', 23, 'AVAILABLE', 105),
('CRLVW-203', 23, 'AVAILABLE', 106),
('CRLVW-204', 23, 'AVAILABLE', 107),
('CRLVW-205', 23, 'AVAILABLE', 108),
('CRLVW-206', 30, 'AVAILABLE', 116),
('CRLVW-207', 30, 'AVAILABLE', 117),
('CRLVW-208', 30, 'AVAILABLE', 118),
('CRLVW-209', 30, 'AVAILABLE', 119),
('CRLVW-301', 30, 'AVAILABLE', 120),
('CRLVW-302', 30, 'AVAILABLE', 121),
('CRLVW-303', 30, 'AVAILABLE', 122),
('CRLVW-304', 30, 'AVAILABLE', 123),
('CRLVW-305', 30, 'AVAILABLE', 124),
('CRLVW-306', 30, 'AVAILABLE', 125),
('CRLVW-307', 30, 'AVAILABLE', 126),
('CRLVW-307', 30, 'AVAILABLE', 127),
('CRLVW-308', 30, 'AVAILABLE', 128),
('CRLVW-309', 30, 'AVAILABLE', 129),
('CRLVW-401', 30, 'AVAILABLE', 130),
('CRLVW-402', 30, 'AVAILABLE', 131),
('CRLVW-403', 30, 'AVAILABLE', 132),
('CRLVW-404', 30, 'AVAILABLE', 133),
('CRLVW-405', 30, 'AVAILABLE', 134),
('CRLVW-406', 30, 'AVAILABLE', 135),
('CRLVW-407', 30, 'AVAILABLE', 136),
('CRLVW-408', 30, 'AVAILABLE', 137),
('CRLVW-409', 30, 'AVAILABLE', 138),
('CRLVW-501', 30, 'AVAILABLE', 139),
('CRLVW-502', 30, 'AVAILABLE', 140),
('CRLVW-503', 30, 'AVAILABLE', 141),
('CRLVW-504', 21, 'AVAILABLE', 142),
('CRLVW-505', 21, 'AVAILABLE', 143),
('CRLVW-506', 21, 'AVAILABLE', 144),
('CRLVW-507', 21, 'OCCUPIED', 145),
('CRLVW-508', 21, 'OCCUPIED', 146),
('CRLVW-509', 21, 'OCCUPIED', 147),
('CRLVW-601', 21, 'AVAILABLE', 148),
('CRLVW-602', 21, 'AVAILABLE', 149),
('CRLVW-603', 21, 'AVAILABLE', 150),
('CRLVW-604', 21, 'AVAILABLE', 151),
('CRLVW-605', 21, 'AVAILABLE', 152),
('CRLVW-606', 24, 'AVAILABLE', 153),
('CRLVW-607', 24, 'AVAILABLE', 154),
('CRLVW-608', 24, 'AVAILABLE', 155),
('CRLVW-609', 25, 'AVAILABLE', 156),
('CRLVW-701', 26, 'AVAILABLE', 157),
('CRLVW-702', 27, 'AVAILABLE', 158),
('CRLVW-703', 27, 'AVAILABLE', 159),
('CRLVW-704', 27, 'AVAILABLE', 160),
('CRLVW-705', 27, 'AVAILABLE', 161),
('CRLVW-706', 27, 'AVAILABLE', 162),
('CRLVW-707', 27, 'AVAILABLE', 163),
('CRLVW-708', 27, 'AVAILABLE', 164),
('CRLVW-709', 27, 'AVAILABLE', 165),
('CRLVW-801', 28, 'AVAILABLE', 166),
('CRLVW-802', 28, 'AVAILABLE', 167),
('CRLVW-803', 28, 'AVAILABLE', 168),
('CRLVW-804', 28, 'AVAILABLE', 169),
('CRLVW-805', 28, 'AVAILABLE', 170),
('CRLVW-806', 28, 'AVAILABLE', 171),
('CRLVW-807', 28, 'AVAILABLE', 172),
('CRLVW-808', 28, 'AVAILABLE', 173),
('CRLVW-809', 28, 'AVAILABLE', 174),
('CRLVW-901', 28, 'AVAILABLE', 175),
('CRLVW-902', 28, 'AVAILABLE', 176),
('CRLVW-903', 28, 'AVAILABLE', 177),
('CRLVW-904', 28, 'AVAILABLE', 178),
('CRLVW-905', 28, 'AVAILABLE', 179),
('CRLVW-906', 28, 'AVAILABLE', 180),
('CRLVW-907', 28, 'AVAILABLE', 181),
('CRLVW-908', 29, 'AVAILABLE', 182),
('CRLVW-909', 36, 'AVAILABLE', 183),
('CRLVW-111', 36, 'AVAILABLE', 184),
('CRLVW-112', 33, 'AVAILABLE', 185),
('CRLVW-113', 33, 'AVAILABLE', 186),
('CRLVW-114', 33, 'AVAILABLE', 187),
('CRLVW-115', 33, 'AVAILABLE', 188),
('CRLVW-116', 33, 'AVAILABLE', 189),
('CRLVW-117', 33, 'AVAILABLE', 190),
('CRLVW-118', 33, 'AVAILABLE', 191),
('CRLVW-119', 33, 'AVAILABLE', 192),
('CRLVW-211', 33, 'AVAILABLE', 193),
('CRLVW-212', 33, 'AVAILABLE', 194),
('CRLVW-213', 34, 'AVAILABLE', 195),
('CRLVW-214', 34, 'AVAILABLE', 196),
('CRLVW-215', 34, 'AVAILABLE', 197),
('CRLVW-216', 34, 'AVAILABLE', 198),
('CRLVW-217', 34, 'AVAILABLE', 199),
('CRLVW-218', 34, 'AVAILABLE', 200),
('CRLVW-219', 34, 'AVAILABLE', 201),
('CRLVW-311', 34, 'AVAILABLE', 202),
('CRLVW-312', 34, 'AVAILABLE', 203),
('CRLVW-313', 34, 'AVAILABLE', 204),
('CRLVW-314', 34, 'AVAILABLE', 205),
('CRLVW-315', 34, 'AVAILABLE', 206),
('CRLVW-316', 34, 'AVAILABLE', 207),
('CRLVW-317', 34, 'AVAILABLE', 208),
('CRLVW-318', 35, 'AVAILABLE', 209),
('CRLVW-319', 35, 'AVAILABLE', 210),
('CRLVW-411', 35, 'AVAILABLE', 211),
('CRLVW-412', 35, 'AVAILABLE', 212),
('CRLVW-413', 35, 'AVAILABLE', 213),
('CRLVW-414', 35, 'AVAILABLE', 214),
('CRLVW-415', 35, 'AVAILABLE', 215),
('CRLVW-416', 35, 'AVAILABLE', 216),
('CRLVW-417', 35, 'AVAILABLE', 217),
('CRLVW-418', 35, 'AVAILABLE', 218),
('CRLVW-419', 35, 'AVAILABLE', 219),
('CRLVW-511', 35, 'AVAILABLE', 220),
('CRLVW-512', 35, 'AVAILABLE', 221),
('CRLVW-513', 35, 'AVAILABLE', 222),
('CRLVW-514', 35, 'AVAILABLE', 223),
('CRLVW-515', 35, 'AVAILABLE', 224),
('CRLVW-516', 35, 'AVAILABLE', 225),
('CRLVW-517', 35, 'AVAILABLE', 226);

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
(3, 'rellie', 'balagat', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'r123@gmail.com', 'ADMINISTRATOR', 'ADMINISTRATOR', '12312'),
(5, 'john', 'balagat', '8cb2237d0679ca88db6464eac60da96345513964', 'asd@gmail.com', 'RECEPTIONIST', 'RECEPTIONIST', '123'),
(8, 'john', 'john123', '890123', 'john@gmail.com', NULL, 'user', '123456'),
(9, 'rellie', 'rellie124', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'relliebalagat@gmail.com', NULL, 'Administrator', '123456');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `billing_additional_fees`
--
ALTER TABLE `billing_additional_fees`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `billing_discount`
--
ALTER TABLE `billing_discount`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `billing_extras`
--
ALTER TABLE `billing_extras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `booking_rooms`
--
ALTER TABLE `booking_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `check_in_rooms`
--
ALTER TABLE `check_in_rooms`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `downpayment`
--
ALTER TABLE `downpayment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `extras`
--
ALTER TABLE `extras`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `guest`
--
ALTER TABLE `guest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `reservation_expenses`
--
ALTER TABLE `reservation_expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `rooms_status`
--
ALTER TABLE `rooms_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
