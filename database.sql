DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table tasks
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table `users`
LOCK TABLES `users` WRITE;
INSERT INTO `users` VALUES
(8,'Johnny','ye.johnnypro@gmail.com','$2y$10$bZx/yu6tGSLjuIhX4L.Mqu5Opnv7EcTZjvyVrntsfU0bwi9H22M02','2025-01-07 16:38:18'),
(9,'Alex','alex@gmail.fr','$2y$10$U3i0sy77iWZi4m6ZL9wbvOpdp82LL3QaUfx06LErQTK.omarVcBre','2025-01-07 18:54:19'),
(10,'igris','igris@gmail.com','$2y$10$u6L8B4yhGITOCU4ru0oKfeg7PFbJo4yzZ9uPwgquEZalNpGqF4nDm','2025-01-12 00:58:40'),
(11,'Max','Max@gmail.com','$2y$10$rKONf1mYxdGXh9NERi8uPuKeQs6x0ppvdQ7IzrpoRw.GeN1dFA.F2','2025-01-12 01:08:03');
UNLOCK TABLES;

-- Dumping data for table `tasks`
LOCK TABLES `tasks` WRITE;
UNLOCK TABLES;