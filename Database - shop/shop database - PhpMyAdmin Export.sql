-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 09, 2023 at 02:08 AM
-- Server version: 8.0.28
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int NOT NULL,
  `Ordering` int DEFAULT NULL,
  `Visibility` tinyint NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(17, 'Hand Made', 'Hand Made Items', 0, 1, 1, 1, 1),
(18, 'Computers', 'Computer Items', 0, 2, 0, 0, 0),
(19, 'Cell Phones', 'Cell Phones', 0, 3, 0, 0, 0),
(20, 'Clothing', 'Clothes and Fashion', 0, 4, 0, 0, 0),
(21, 'Tools', 'Home Tools', 0, 5, 0, 0, 0),
(23, 'Blackberry', 'Blackberry Phones', 19, 2, 0, 0, 0),
(24, 'Hammers', 'Hammers Description test', 21, 1, 0, 0, 0),
(25, 'Boxes', 'Boxes Hand made', 21, 1, 0, 0, 0),
(26, 'Wool', 'Hand Made wool', 17, 3, 0, 0, 0),
(27, 'Games', '', 0, 1, 0, 0, 0),
(28, 'Cars', 'Luxurious cars', 0, 6, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `c_id` int NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(10, 'Awesome!\r\n', 0, '2023-05-23', 16, 29),
(11, 'Cool!\r\n', 1, '2023-05-23', 23, 29),
(12, 'Comfortable!\r\n', 0, '2023-05-23', 22, 29),
(13, 'test comment\r\n', 0, '2023-06-09', 17, 29);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint DEFAULT NULL,
  `Approve` tinyint NOT NULL DEFAULT '0',
  `Cat_ID` int NOT NULL,
  `Member_ID` int NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(12, 'Network Cable', 'Cat 9 Network Cable', '$100', '2018-03-10', 'USA', '', '1', 0, 1, 18, 14, ''),
(14, 'Assassin\'s Creed', 'Open-world, action-adventure, and stealth game', '150', '2018-03-26', 'Turkey', '', '4', 0, 1, 27, 21, ''),
(16, 'Wooden Game', 'A good wooden game', '100', '2018-03-29', 'Egypt', '', '1', 0, 0, 17, 21, 'Hand, Discount, Guarantee'),
(17, 'Diablo |||', 'Good playstation 4 Game', '70', '2018-03-30', 'USA', '', '1', 0, 1, 18, 21, 'RPG, Online, Game'),
(18, 'Ys: The Oath in Felghan', 'A good PS Game', '100', '2018-03-30', 'Japan', '', '1', 0, 0, 27, 21, 'Online, RPG, Gamed'),
(21, 'Lamborghini', 'Lambo cars', '3157145', '2023-04-14', 'Italy', NULL, '1', NULL, 1, 28, 21, 'cars'),
(22, 'Men&#039;s Sneakers', 'High quality sneakers', '450', '2023-05-20', 'Egypt', NULL, '1', NULL, 0, 20, 29, ''),
(23, 'iPhone 14', 'Luxurious Apple iPhone 14', '41999', '2023-05-20', 'China', NULL, '1', NULL, 1, 18, 29, '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int NOT NULL COMMENT 'To identify user',
  `Username` varchar(255) NOT NULL COMMENT 'Username to login',
  `Password` varchar(255) NOT NULL COMMENT 'Password to login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `GroupID` int NOT NULL DEFAULT '0' COMMENT 'Identifies Group ID (Admin or Normal User or Moderator)',
  `TrustStatus` int NOT NULL DEFAULT '0' COMMENT 'Seller Rank',
  `RegStatus` int NOT NULL DEFAULT '0' COMMENT 'User approval status (Ex: pending, approved, ...)',
  `Date` date NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `avatar`) VALUES
(12, 'Hind', '601f1889667efaebb33b8c12572835da3f027f78', 'hind@gmail.com', 'Hind Ahmed', 0, 0, 1, '2018-02-25', '875944_hind.jpg'),
(14, 'Fathy', '03785d4e638cd09cea620fd0939bf06825be88df', 'fathy@fathy.com', 'Fathy Shady', 0, 0, 0, '2018-02-25', ''),
(16, 'Ramy', '59f7c8818803a2f0d7946e160dc2a63b88c0ee28', 'ramy@ramy.com', 'Ramy Rabie', 0, 0, 1, '2018-02-25', '997912_ramy.jpg'),
(17, 'Adel', 'e5594062f0a0a362abbb022a6fb0c36dcd9a1bd1', 'adel@yahoo.com', 'Adel Sameh', 0, 0, 0, '2018-02-25', '475952_adel.jpg'),
(21, 'Mazen', '601f1889667efaebb33b8c12572835da3f027f78', 'mazen@mazen.com', 'Mazen Naeem', 0, 0, 1, '2018-03-02', ''),
(22, 'Fayez', '601f1889667efaebb33b8c12572835da3f027f78', 'fayez@fayez.com', 'Fayez Fawzy', 0, 0, 0, '2018-03-13', '525975_fayez.jpg'),
(27, 'Abu Gamal', '601f1889667efaebb33b8c12572835da3f027f78', 'abugamal@hotmail.com', 'Abu Gamal Mahmoud Shanawany', 0, 0, 1, '2018-03-31', ''),
(29, 'Ahmed', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'ahmed@gmail.com', 'Ahmed Yahya', 1, 0, 1, '2023-05-20', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `My_items_comments` (`item_id`),
  ADD KEY `My_users_comment` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `hamada` (`Member_ID`),
  ADD KEY `hazem` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `c_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT COMMENT 'To identify user', AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `My_items_comments` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `My_users_comment` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `hamada` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `hazem` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
