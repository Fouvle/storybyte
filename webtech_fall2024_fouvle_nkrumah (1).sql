-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 20, 2024 at 11:23 AM
-- Server version: 8.0.40-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webtech_fall2024_fouvle_nkrumah`
--

-- --------------------------------------------------------

--
-- Table structure for table `drafts`
--

CREATE TABLE `drafts` (
  `id` int NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  `word_count` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drafts`
--

INSERT INTO `drafts` (`id`, `content`, `created_at`, `updated_at`, `user_id`, `word_count`) VALUES
(10, 'The day was really beautiful. and i just enjoyed how the sun was shining on my face. ', '2024-12-20 11:13:43', '2024-12-20 11:19:14', 4, 11),
(12, 'the birthday party was really fun today. i had a really good rime. ', '2024-12-20 11:18:43', '2024-12-20 11:18:43', 4, 13);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(4, 'drama'),
(1, 'fiction'),
(3, 'romance'),
(2, 'Sci-Fi');

-- --------------------------------------------------------

--
-- Table structure for table `published_stories`
--

CREATE TABLE `published_stories` (
  `id` int NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `published_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `published_stories`
--

INSERT INTO `published_stories` (`id`, `content`, `published_at`, `user_id`) VALUES
(1, 'Published story content.', '2024-12-01 12:00:00', 1),
(2, 'Another published story.', '2024-12-15 14:30:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `comments` text COLLATE utf8mb4_general_ci,
  `likes` int DEFAULT '0',
  `author_id` int DEFAULT NULL,
  `author_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `is_published` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `genre_id` int DEFAULT NULL,
  `published_date` datetime DEFAULT NULL,
  `draft_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`id`, `title`, `content`, `comments`, `likes`, `author_id`, `author_name`, `is_published`, `created_at`, `genre_id`, `published_date`, `draft_id`) VALUES
(1, 'The Great Adventure', 'Story content goes here.', 'Great story!', 100, 1, 'john_doe', 1, '2024-12-19 10:13:03', 1, '2024-12-02 10:31:12', NULL),
(2, 'Space Odyssey', 'Another exciting content.', NULL, 50, 2, 'jane_smith', 0, '2024-12-19 10:13:03', 2, '2024-11-11 10:31:18', NULL),
(3, 'Romantic Sunrise', 'Ava stood at the edge of the cliff, watching the sun break over the horizon. Behind her, James approached quietly, holding the locket she thought she had lost forever. \"I found this,\" he said softly, his voice trembling. She turned, her eyes glistening with tears. \"It was my mother\'s,\" she whispered. The moment stretched, their worlds colliding under the vibrant hues of dawn.', 'So touching!', 80, 6, 'emily_rose', 1, '2024-12-20 08:00:00', 3, NULL, NULL),
(4, 'Dramatic Turns', 'The courtroom was silent as the lawyer leaned forward, pointing at the witness. \"Isn\'t it true,\" she said, her voice echoing through the hall, \"that you were at the scene that night?\" Gasps rippled through the audience. The witness hesitated, beads of sweat forming on his brow. \"Yes,\" he finally admitted, and chaos erupted as the truth unraveled in real-time.', 'Amazing suspense!', 120, 7, 'mark_twain', 1, '2024-12-20 09:00:00', 4, NULL, NULL),
(5, 'Galaxy Explorers', 'Captain Nova tightened her grip on the controls as the spaceship hurtled through the asteroid field. Beside her, Engineer Clarke shouted, \"Shields are failing!\" Nova gritted her teeth. \"Hold on, we\'re almost through!\" The ship shuddered violently, and for a moment, all seemed lost—until a blinding light signaled their arrival at the uncharted galaxy they had risked everything to reach.', NULL, 65, 8, 'arthur_clark', 0, '2024-12-20 10:00:00', 2, NULL, NULL),
(6, 'The Fictional Truth', 'Max sat in the library, piecing together fragments of a story no one believed. \"These aren\'t coincidences,\" he muttered, flipping through yellowed pages. The deeper he delved, the clearer it became: his life mirrored the events of a long-forgotten novel. But when he reached the final chapter, he froze—the protagonist didn\'t survive.', 'Brilliant writing!', 95, 9, 'george_orwell', 1, '2024-12-20 11:00:00', 1, NULL, NULL),
(7, 'Romantic Dilemma', 'Lila stared at the letter in her hands, her heart racing. It was from Marcus, the man she once loved. But how could she tell Ethan, the man who saved her from herself? The past and present tugged at her soul, and she realized that choosing one meant losing the other forever.', 'Such a great plot!', 45, 10, 'jane_austen', 1, '2024-12-20 12:00:00', 3, NULL, NULL),
(8, 'Drama at Midnight', 'It was a quiet night until the scream shattered the silence. Detective Hart rushed to the scene, his flashlight cutting through the dark alley. There, amidst the shadows, he found a single red glove and a cryptic note: \"This isn\'t over.\" The clock struck midnight, and Hart knew the game had just begun.', 'Amazing suspense!', 110, 11, 'john_grisham', 1, '2024-12-20 13:00:00', 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `followers` int DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `draft_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `followers`, `created_at`, `draft_id`) VALUES
(1, 'john_doe', 'john@example.com', 'password123', 'user', 10, '2024-12-19 10:13:03', NULL),
(2, 'jane_smith', 'jane@example.com', 'password456', 'user', 20, '2024-12-19 10:13:03', NULL),
(3, 'johnny', 'john.k@gmail.com', '$2y$10$eam9FnFVbLM/kgDnuudoUuqBnU7PksrgqTzDDn.gX9n2B8KgHqCce', 'user', 3, '2024-12-19 11:02:57', NULL),
(4, 'yaw7', 'yaw.abdu@gmail.com', '$2y$10$s06C0y6V373Xefh.NDyppum9riCgPtGycwJT3XQ7oEY2RkTRN38jW', 'user', 6, '2024-12-19 11:11:41', 12),
(5, 'jamesey', 'jme.k@gmail.com', '$2y$10$LLghzTP6ZHc8tulsp9ERMuMLY4SOllN99RrpVgJgGbgJWJyStXJWy', 'user', 0, '2024-12-19 11:23:55', NULL),
(6, 'emily_rose', 'emily_rose@example.com', 'password123', 'uset', 300, '2024-12-20 09:00:00', NULL),
(7, 'mark_twain', 'mark_twain@example.com', 'password123', 'user', 500, '2024-12-20 09:30:00', NULL),
(8, 'arthur_clark', 'arthur_clark@example.com', 'password123', 'user', 400, '2024-12-20 10:00:00', NULL),
(9, 'george_orwell', 'george_orwell@example.com', 'password123', 'user', 600, '2024-12-20 10:30:00', NULL),
(10, 'jane_austen', 'jane_austen@example.com', 'password123', 'uset', 350, '2024-12-20 11:00:00', NULL),
(11, 'john_grisham', 'john_grisham@example.com', 'password123', 'user', 420, '2024-12-20 11:30:00', NULL),
(12, 'ato_78', 'ato@gmail.com', '$2y$10$ddcnCIo3XIfWF57hKLAv9.u8K2RjaHEuR/J8gdyxM4Ea/Ipvi8oDy', 'user', 0, '2024-12-20 10:08:27', 9);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drafts`
--
ALTER TABLE `drafts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `published_stories`
--
ALTER TABLE `published_stories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `fk_genre` (`genre_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drafts`
--
ALTER TABLE `drafts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `published_stories`
--
ALTER TABLE `published_stories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stories`
--
ALTER TABLE `stories`
  ADD CONSTRAINT `fk_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_genre` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
