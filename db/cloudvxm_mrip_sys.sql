-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2020 at 07:55 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cloudvxm_mrip_sys`
--

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_year`
--

CREATE TABLE `fiscal_year` (
  `serial` int(11) NOT NULL,
  `cFiscalYear` varchar(30) DEFAULT NULL,
  `dStartDate` date DEFAULT NULL,
  `dEndDate` date DEFAULT NULL,
  `cYearDatabasePath` varchar(50) DEFAULT NULL,
  `cDatabaseUserName` varchar(200) DEFAULT NULL,
  `cDatabasePassword` varchar(200) NOT NULL,
  `dAccountClosed` tinyint(1) NOT NULL DEFAULT '0',
  `dAccountClosedDate` date DEFAULT NULL,
  `cCurrentSession` varchar(30) DEFAULT NULL,
  `cSessionCode` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fiscal_year`
--

INSERT INTO `fiscal_year` (`serial`, `cFiscalYear`, `dStartDate`, `dEndDate`, `cYearDatabasePath`, `cDatabaseUserName`, `cDatabasePassword`, `dAccountClosed`, `dAccountClosedDate`, `cCurrentSession`, `cSessionCode`) VALUES
(1, 'April 2019-march 2020', '2019-04-01', '2020-03-31', 'WTJ4dmRXUjJlRzFmYlhKcGNGOHlNREU1', 'Y205dmRBPT0=', '', 0, NULL, '2019-2020', '19-20'),
(2, 'April 2020-march 2021', '2020-04-01', '2021-03-31', 'WTJ4dmRXUjJlRzFmYlhKcGNGOHlNREl3', 'Y205dmRBPT0=', '', 0, NULL, '2020-2021', '20-21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fiscal_year`
--
ALTER TABLE `fiscal_year`
  ADD PRIMARY KEY (`serial`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fiscal_year`
--
ALTER TABLE `fiscal_year`
  MODIFY `serial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
