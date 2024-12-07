-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 12:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_aklatan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_book`
--

CREATE TABLE `tbl_book` (
  `book_id` int(11) NOT NULL,
  `book_title` varchar(255) DEFAULT NULL,
  `book_author` varchar(255) DEFAULT NULL,
  `book_publisher` varchar(255) DEFAULT NULL,
  `book_publication_year` varchar(255) DEFAULT NULL,
  `book_status` enum('Available','Borrowed','','') NOT NULL,
  `book_borrowed_date` datetime DEFAULT NULL,
  `book_returned_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_book`
--

INSERT INTO `tbl_book` (`book_id`, `book_title`, `book_author`, `book_publisher`, `book_publication_year`, `book_status`, `book_borrowed_date`, `book_returned_date`) VALUES
(26, 'The Beginning After The End', 'TurtleMe', 'Talpas', '2020', 'Available', '2024-12-07 16:48:04', '2024-12-07 16:48:06'),
(27, 'The Great Gatsby', 'F. Scott Fitzgerald', 'Charles Scribner\'s Sons', '1925', 'Available', NULL, NULL),
(28, 'To Kill a Mockingbird', 'Harper Lee', 'J.B. Lippincott & Co.', '1960', 'Available', NULL, NULL),
(29, '1984', 'George Orwell', 'Secker & Warburg', '1949', 'Available', NULL, NULL),
(30, 'Pride and Prejudice', 'Jane Austen', 'T. Egerton', '1813', 'Available', NULL, NULL),
(31, 'The Catcher in the Rye', 'J.D. Salinger', 'Little, Brown and Company', '1951', 'Available', NULL, NULL),
(32, 'The Hobbit', 'J.R.R. Tolkien', 'George Allen & Unwin', '1937', 'Available', NULL, NULL),
(33, 'Moby-Dick', 'Herman Melville', 'Richard Bentley', '1851', 'Available', NULL, NULL),
(34, 'War and Peace', 'Leo Tolstoy', 'The Russian Messenger', '1869', 'Available', NULL, NULL),
(35, 'The Alchemist', 'Paulo Coelho', 'HarperCollins', '1988', 'Available', NULL, NULL),
(36, 'The Picture of Dorian Gray', 'Oscar Wilde', 'Lippincott\'s Monthly Magazine', '1890', 'Available', NULL, NULL),
(39, 'The Beginning After The End', 'TurtleMe', 'Talpas', '2020', 'Available', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_role` enum('Librarian','Admin','','') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `user_password`, `user_email`, `user_role`) VALUES
(8, 'jasperpogi1234', 'dfdsfd', 'dfdsfdsf', 'Admin'),
(28, 'librarianlibrarian', 'librarian', 'htaningco@gmail.com', 'Librarian'),
(29, 'JustineHarvey123', 'harveyharvey123', 'htaningco@gmail.com', 'Admin'),
(30, 'christianmiguel123', 'christianmiguel123', 'christianmiguel@gmail.com', 'Admin'),
(31, 'jovanybadua123', 'jovanybadua123', 'jovanybadua@gmail.com', 'Librarian'),
(32, 'dylanpalay123', 'dylanpalay123', 'dylanpalay@gmail.com', 'Admin'),
(33, 'marvijohn123', 'marvijohn123', 'marvijohn@gmail.com', 'Librarian'),
(34, 'seanedward123', 'seanedward123', 'seanedward@gmail.com', 'Admin'),
(35, 'jerichourbano123', 'jerichourbano123', 'jerichourbano@gmail.com', 'Librarian'),
(36, 'markley123', 'markley123', 'markley@gmail.com', 'Librarian');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_book`
--
ALTER TABLE `tbl_book`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_book`
--
ALTER TABLE `tbl_book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
