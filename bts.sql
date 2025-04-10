-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2025 at 10:36 PM
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
-- Database: `bts`
--

-- --------------------------------------------------------

--
-- Table structure for table `amis`
--

CREATE TABLE `amis` (
  `user_id1` int(11) NOT NULL,
  `user_id2` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amis`
--

INSERT INTO `amis` (`user_id1`, `user_id2`, `created_at`) VALUES
(18, 14, '2025-04-10 19:26:31'),
(18, 17, '2025-04-10 19:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `comment_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `comment_text`, `comment_date`) VALUES
(41, 17, 18, '🌌', '2025-04-10 21:03:27');

-- --------------------------------------------------------

--
-- Table structure for table `formule`
--

CREATE TABLE `formule` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `datenaissance` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `adresse` text DEFAULT NULL,
  `Sexe` enum('Homme','Femme') NOT NULL,
  `branche` varchar(50) NOT NULL,
  `interet` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `photo_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formule`
--

INSERT INTO `formule` (`id`, `nom`, `prenom`, `datenaissance`, `email`, `adresse`, `Sexe`, `branche`, `interet`, `password`, `photo_name`) VALUES
(14, 'brahime', 'rochdie', '1995-08-12', 'brahime@gmail.com', '80000', 'Homme', 'se', 'etudiante', '$2y$10$ATlQt.a3umHPy4sq2NC7jOA1cCMoops3E.l3XGW1lozGw.O0th6Dm', 'brahime.png'),
(15, 'simba', '*_*', '2025-02-12', 'simba@gmail.com', '2000', 'Homme', 'cpi', 'professeur', '$2y$10$VF4shjBlpvJ88Tz6WlT0O.Z1tIN3nnhryhX3BCLO5chnSrNXfeTui', '67f8199acb691_FB_IMG_1707513215252.jpg'),
(16, 'oussama', 'idrissi', '1999-06-12', 'oussama@gmail.com', '1222', 'Homme', 'dsi', 'etudiante', '$2y$10$0hCKsKgGEZnUmKEag9ja8.In3feB0JL8zQF8z.6MOxrtD1os1ByoK', '67f81a0a5f38d_‎hbibna.jpeg'),
(17, 'Lana ', 'Del Rey', '1989-02-12', 'Lana@gmail.com', '1548', 'Femme', 'dsi', 'etudiante', '$2y$10$KObvbdG4jH/s1wL8ZxvR4u7OFttFTgGK01YiVdOaHDnSB8DifObei', 'lana.webp'),
(18, 'xavi', '°_°', '7212-05-14', 'xavi@gmail.com', '15', 'Homme', 'dsi', 'professeur', '$2y$10$4niDZNImrlgUdBCm1c2syeyGA3sJzOb94Ic2Z5hxL6QZ7pK3B2JUa', '67f81b37e9e93_FB_IMG_1626133929639.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `invitation`
--

CREATE TABLE `invitation` (
  `invitation_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invitation`
--

INSERT INTO `invitation` (`invitation_id`, `sender_id`, `receiver_id`, `status`) VALUES
(22, 18, 17, 'accepted'),
(23, 18, 14, 'accepted'),
(24, 16, 18, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `meessages`
--

CREATE TABLE `meessages` (
  `id` int(11) NOT NULL,
  `expediteur_id` int(11) NOT NULL,
  `destinataire_id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime NOT NULL,
  `lu` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meessages`
--

INSERT INTO `meessages` (`id`, `expediteur_id`, `destinataire_id`, `contenu`, `date_envoi`, `lu`) VALUES
(93, 18, 17, 'heyy', '2025-04-10 20:52:11', 0),
(94, 18, 14, 'bnjr', '2025-04-10 20:52:27', 0),
(95, 17, 18, 'cv !', '2025-04-10 20:52:45', 0),
(96, 14, 18, 'slm', '2025-04-10 20:53:32', 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `idpost` int(11) NOT NULL,
  `imgpost` varchar(255) NOT NULL,
  `idUser` int(11) NOT NULL,
  `text` text NOT NULL,
  `nblike` int(11) NOT NULL DEFAULT 0,
  `nbdeslike` int(11) NOT NULL DEFAULT 0,
  `datepost` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`idpost`, `imgpost`, `idUser`, `text`, `nblike`, `nbdeslike`, `datepost`) VALUES
(15, '67f81bec05f75_FB_IMG_1685149905595.jpg', 18, '🐐', 0, 0, '2025-04-10 20:28:44'),
(16, '67f81c2eb4655_FB_IMG_1645741845678.jpg', 14, '🛸', 0, 0, '2025-04-10 20:29:50'),
(17, '67f81c678b441_FB_IMG_1626133879243.jpg', 17, '🌞', 1, 0, '2025-04-10 20:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `reactions`
--

CREATE TABLE `reactions` (
  `reaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `reaction_type` enum('like','dislike') NOT NULL,
  `reaction_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reactions`
--

INSERT INTO `reactions` (`reaction_id`, `user_id`, `post_id`, `reaction_type`, `reaction_date`) VALUES
(13, 18, 17, 'like', '2025-04-10 21:11:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amis`
--
ALTER TABLE `amis`
  ADD PRIMARY KEY (`user_id1`,`user_id2`),
  ADD KEY `user_id2` (`user_id2`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `formule`
--
ALTER TABLE `formule`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `invitation`
--
ALTER TABLE `invitation`
  ADD PRIMARY KEY (`invitation_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `meessages`
--
ALTER TABLE `meessages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expediteur_id` (`expediteur_id`),
  ADD KEY `destinataire_id` (`destinataire_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`idpost`);

--
-- Indexes for table `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`reaction_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `formule`
--
ALTER TABLE `formule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `invitation`
--
ALTER TABLE `invitation`
  MODIFY `invitation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `meessages`
--
ALTER TABLE `meessages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `idpost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `reaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `amis`
--
ALTER TABLE `amis`
  ADD CONSTRAINT `amis_ibfk_1` FOREIGN KEY (`user_id1`) REFERENCES `formule` (`id`),
  ADD CONSTRAINT `amis_ibfk_2` FOREIGN KEY (`user_id2`) REFERENCES `formule` (`id`);

--
-- Constraints for table `invitation`
--
ALTER TABLE `invitation`
  ADD CONSTRAINT `invitation_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `formule` (`id`),
  ADD CONSTRAINT `invitation_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `formule` (`id`);

--
-- Constraints for table `meessages`
--
ALTER TABLE `meessages`
  ADD CONSTRAINT `meessages_ibfk_1` FOREIGN KEY (`expediteur_id`) REFERENCES `formule` (`id`),
  ADD CONSTRAINT `meessages_ibfk_2` FOREIGN KEY (`destinataire_id`) REFERENCES `formule` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
