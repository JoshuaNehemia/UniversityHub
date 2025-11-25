-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2025 at 03:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fullstack`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `username` varchar(20) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `nrp_mahasiswa` char(9) DEFAULT NULL,
  `npk_dosen` char(6) DEFAULT NULL,
  `isadmin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`username`, `password`, `nrp_mahasiswa`, `npk_dosen`, `isadmin`) VALUES
('admin', '$2y$10$z1S6hcQVWP8KlyFLcJ3qne354zUcDAUR5VO6JhBjKeTuU3qnzB8Ba', NULL, NULL, 1),
('darren123', '$2y$10$AaZp4GI365fRU5ja5cmCrunspbI1tCKBxwAFlCJjfKio.wcXnBn9y', '160423233', NULL, 0),
('evan123', '$2y$10$WvV7zwqk1n1R3AFBknz.JucUDkfwMyk1Mpf1LFFtOFDHQNGLnSONC', '160423033', NULL, 0),
('feby123', '$2y$10$/eI3LeVZqsUlFJDlvlsJsuDVASDs1Mg7E4AQJbWPYu1M7KK6qepJO', '160423058', NULL, 0),
('felix123', '$2y$10$YZLHKCnEuiHIXASCS7nV0emdJsmYwxHexE3A3Wo.s.gktQJCE910e', NULL, '217023', 0),
('hendra123', '$2y$10$v5sew2oicBq4gsZl.M3s7O2oLvmhZa9n.vOlZAQm1PyWLGA5tC5Kq', NULL, '210034', 0),
('heru123', '$2y$10$K9sMwcvtMK6WguVSkIwpzuO3CczycIG/9Bb3N.atmwGygvjJU6QZ6', NULL, '192014', 0),
('jessica123', '$2y$10$Iv4u46E3wgI0fZ3/3fGv5uW/tgUdny4HDwgCKV/QQERfQg8dFDxre', '130323075', NULL, 0),
('joko123', '$2y$10$0yihYeHxKZQcubPfzPbsEumyQDNfcmAy9y8W2vd8YfZUR3noK3iUG', NULL, '198032', 0),
('joshua123', '$2y$10$mD3BkIV8GSLGQaCEQxi15ONjUO66LySYPf6UwEkWMt9cLVJxAAVIa', '160423034', NULL, 0),
('kevin123', '$2y$10$wZ6YFxnJSFMq/6WVwGXvGuogq8agMHSZX/pnQaHIewwBo/MgQkxPu', '160423020', NULL, 0),
('marco123', '$2y$10$sNT2c98HOpMMz5SCRhznbOXrERLfVIcwnizdCXlUdIbQNOmJN.EQC', NULL, '223037', 0),
('monica123', '$2y$10$JZy5WEA/SyqZp.LEdxmBmuP.7f8oo7wyx4w6rZ2KTL1un05FoaH7C', NULL, '204027', 0),
('susana123', '$2y$10$5r7HF4qiN1yQbQcJNCPt/e/rNok1XhEdWRSbEhdVGEaKx4E/1vudC', NULL, '197030', 0),
('tyrza123', '$2y$10$HuGkjOFqGHXwYe47KaCab.ZT1dRxqNJENUiw6YL7OaoEyC3z6b2By', NULL, '210134', 0),
('vivian123', '$2y$10$5OyeqWYtd0pK/E97.gBaleMIy9XoIKbR/7FvojpcqeU.OF0tDB3oO', '160423066', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `idchat` int(11) NOT NULL,
  `idthread` int(11) NOT NULL,
  `username_pembuat` varchar(20) NOT NULL,
  `isi` text DEFAULT NULL,
  `tanggal_pembuatan` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `npk` char(6) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `foto_extension` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`npk`, `nama`, `foto_extension`) VALUES
('192014', 'Heru Arwoko, M.T.', 'jpg'),
('197030', 'Dr. Susana Limanto, M.Si.', 'jpg'),
('198032', 'Dr. Joko Siswantoro', 'jpg'),
('204027', 'Dr. Monica Widiasri', 'jpg'),
('210034', 'Dr. Hendra Dinata', 'jpg'),
('210134', 'Tyrza Adelia, M.Inf.Tech.', 'jpg'),
('217023', 'Felix Handani, M.Kom.', 'jpg'),
('223037', 'Marco Ariano Kristyanto, M.M., M.Kom.', 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `idevent` int(11) NOT NULL,
  `idgrup` int(11) NOT NULL,
  `judul` varchar(45) DEFAULT NULL,
  `judul-slug` varchar(45) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jenis` enum('Privat','Publik') DEFAULT NULL,
  `poster_extension` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grup`
--

CREATE TABLE `grup` (
  `idgrup` int(11) NOT NULL,
  `username_pembuat` varchar(20) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `deskripsi` varchar(45) DEFAULT NULL,
  `tanggal_pembentukan` datetime DEFAULT NULL,
  `jenis` enum('Privat','Publik') DEFAULT NULL,
  `kode_pendaftaran` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `nrp` char(9) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `gender` enum('Pria','Wanita') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `angkatan` year(4) DEFAULT NULL,
  `foto_extention` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`nrp`, `nama`, `gender`, `tanggal_lahir`, `angkatan`, `foto_extention`) VALUES
('130323075', 'Jessica Tanujaya Sutanto', 'Wanita', '2005-03-31', '2023', 'jpg'),
('160423020', 'Kevin Nathaniel Sutopo', 'Pria', '2025-10-22', '2023', 'jpg'),
('160423033', 'Evan Daniel Tandiawan', 'Pria', '2025-10-07', '2023', 'jpg'),
('160423034', 'Joshua Nehemia Subagyo', 'Pria', '2004-02-24', '2023', 'jpg'),
('160423058', 'Feby Soenarto', 'Wanita', '2005-03-31', '2023', 'jpg'),
('160423066', 'Vivian Sisca Maria', 'Wanita', '2004-11-01', '2023', 'jpg'),
('160423233', 'Darren Stanford Saputra', 'Pria', '2025-10-01', '2023', 'jpg');

-- --------------------------------------------------------

--
-- Table structure for table `member_grup`
--

CREATE TABLE `member_grup` (
  `idgrup` int(11) NOT NULL,
  `username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thread`
--

CREATE TABLE `thread` (
  `idthread` int(11) NOT NULL,
  `username_pembuat` varchar(20) NOT NULL,
  `idgrup` int(11) NOT NULL,
  `tanggal_pembuatan` datetime DEFAULT NULL,
  `status` enum('Open','Close') DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`username`),
  ADD KEY `fk_akun_mahasiswa_idx` (`nrp_mahasiswa`),
  ADD KEY `fk_akun_dosen1_idx` (`npk_dosen`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`idchat`),
  ADD KEY `fk_chat_thread1_idx` (`idthread`),
  ADD KEY `fk_chat_akun1_idx` (`username_pembuat`);

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`npk`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`idevent`),
  ADD KEY `fk_event_grup1_idx` (`idgrup`);

--
-- Indexes for table `grup`
--
ALTER TABLE `grup`
  ADD PRIMARY KEY (`idgrup`),
  ADD KEY `fk_grup_akun1_idx` (`username_pembuat`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`nrp`);

--
-- Indexes for table `member_grup`
--
ALTER TABLE `member_grup`
  ADD PRIMARY KEY (`idgrup`,`username`),
  ADD KEY `fk_grup_has_akun_akun1_idx` (`username`),
  ADD KEY `fk_grup_has_akun_grup1_idx` (`idgrup`);

--
-- Indexes for table `thread`
--
ALTER TABLE `thread`
  ADD PRIMARY KEY (`idthread`),
  ADD KEY `fk_thread_akun1_idx` (`username_pembuat`),
  ADD KEY `fk_thread_grup1_idx` (`idgrup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `idchat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `idevent` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grup`
--
ALTER TABLE `grup`
  MODIFY `idgrup` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thread`
--
ALTER TABLE `thread`
  MODIFY `idthread` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akun`
--
ALTER TABLE `akun`
  ADD CONSTRAINT `fk_akun_dosen1` FOREIGN KEY (`npk_dosen`) REFERENCES `dosen` (`npk`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_akun_mahasiswa` FOREIGN KEY (`nrp_mahasiswa`) REFERENCES `mahasiswa` (`nrp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `fk_chat_akun1` FOREIGN KEY (`username_pembuat`) REFERENCES `akun` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_chat_thread1` FOREIGN KEY (`idthread`) REFERENCES `thread` (`idthread`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk_event_grup1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`idgrup`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grup`
--
ALTER TABLE `grup`
  ADD CONSTRAINT `fk_grup_akun1` FOREIGN KEY (`username_pembuat`) REFERENCES `akun` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `member_grup`
--
ALTER TABLE `member_grup`
  ADD CONSTRAINT `fk_grup_has_akun_akun1` FOREIGN KEY (`username`) REFERENCES `akun` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_grup_has_akun_grup1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`idgrup`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `thread`
--
ALTER TABLE `thread`
  ADD CONSTRAINT `fk_thread_akun1` FOREIGN KEY (`username_pembuat`) REFERENCES `akun` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_thread_grup1` FOREIGN KEY (`idgrup`) REFERENCES `grup` (`idgrup`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
