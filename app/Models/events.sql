CREATE DATABASE IF NOT EXISTS `events` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `events`;

-- Tabel kategori event
CREATE TABLE `event_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel events
CREATE TABLE `events` (
    `id` int NOT NULL AUTO_INCREMENT,
    `category_id` int NOT NULL,
    `title` varchar(255) NOT NULL,
    `speaker` varchar(100) NOT NULL,
    `short_description` varchar(255) NOT NULL,
    `full_description` text NOT NULL,
    `thumbnail` varchar(255) DEFAULT NULL,
    `start_date` date NOT NULL,
    `start_time` time NOT NULL,
    `end_date` date NOT NULL,
    `end_time` time NOT NULL,
    `registration_deadline` datetime DEFAULT NULL,
    `location_name` varchar(150) NOT NULL,
    `location_address` varchar(255) DEFAULT NULL,
    `location_link` varchar(255) DEFAULT NULL,
    `quota` int NOT NULL,
    `is_published` tinyint(1) DEFAULT '0',
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_events_category` (`category_id`),
    CONSTRAINT `fk_event_category_id` FOREIGN KEY (`category_id`) REFERENCES `event_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel users
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel registrations
CREATE TABLE `registrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_event` int NOT NULL,
  `id_user` int NOT NULL,
  `registration_code` varchar(20) NOT NULL,
  `registration_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','validated','canceled') DEFAULT 'pending',
  `attended` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registration_code` (`registration_code`),
  KEY `idx_registrations_event` (`id_event`),
  KEY `idx_registrations_user` (`id_user`),
  KEY `idx_registrations_code` (`registration_code`),
  KEY `idx_registrations_status` (`status`),
  CONSTRAINT `fk_registration_event` FOREIGN KEY (`id_event`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_registration_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel certificates
CREATE TABLE `certificates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `registration_id` int NOT NULL,
  `certificate_number` varchar(50) NOT NULL,
  `issued_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `template_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificate_number` (`certificate_number`),
  KEY `fk_certificate_registration` (`registration_id`),
  CONSTRAINT `fk_certificate_registration` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel tickets
CREATE TABLE `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `registration_id` int NOT NULL,
  `ticket_number` varchar(50) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_number` (`ticket_number`),
  KEY `fk_ticket_registration` (`registration_id`),
  CONSTRAINT `fk_ticket_registration` FOREIGN KEY (`registration_id`) REFERENCES `registrations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data untuk event_categories
INSERT INTO `event_categories` (`name`) VALUES
('Workshop'),
('Seminar'),
('Webinar'),
('Conference'),
('Training');

-- Data untuk users (password di-hash menggunakan password_hash di PHP, ini menggunakan 'password123')
INSERT INTO `users` (`username`, `email`, `full_name`, `password`, `role`) VALUES
('admin', 'admin@example.com', 'Administrator', '$2y$10$9TakWYN35oLWc/Gin2Bi8.GgXZ3VVuM9ymqShGwfKFWxQaHTnqKim', 'admin'),
('johndoe', 'john@example.com', 'John Doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('janesmith', 'jane@example.com', 'Jane Smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('bobwilson', 'bob@example.com', 'Bob Wilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Data untuk events with separated date and time
INSERT INTO `events` (`category_id`, `title`, `speaker`, `short_description`, `full_description`, `start_date`, `start_time`, `end_date`, `end_time`, `registration_deadline`, `location_name`, `location_address`, `location_link`, `quota`, `is_published`) VALUES
(3, 'Introduction to AI', 'Dr. Sarah Johnson', 'Learn the basics of Artificial Intelligence', 'Comprehensive introduction to AI concepts, machine learning, and practical applications.', '2025-06-15', '09:00:00', '2025-06-15', '12:00:00', '2025-06-14 23:59:59', 'Zoom Meeting', NULL, 'https://zoom.us/j/123456789', 100, 1),
(1, 'Web Development Workshop', 'Michael Chen', 'Hands-on web development training', 'Learn modern web development using HTML5, CSS3, and JavaScript.', '2025-06-20', '13:00:00', '2025-06-20', '17:00:00', '2025-06-19 23:59:59', 'Tech Hub', 'Jl. Sudirman No. 123', NULL, 30, 1),
(2, 'Digital Marketing Seminar', 'Emily Brown', 'Master digital marketing strategies', 'Comprehensive seminar about SEO, social media marketing, and content strategy.', '2025-06-25', '10:00:00', '2025-06-25', '15:00:00', '2025-06-24 23:59:59', 'Business Center', 'Jl. Thamrin No. 45', NULL, 50, 1);

-- Data untuk registrations
INSERT INTO `registrations` (`id_event`, `id_user`, `registration_code`, `status`, `attended`) VALUES
(1, 2, 'REG-20250601-001', 'validated', 1),
(1, 3, 'REG-20250601-002', 'validated', 0),
(2, 2, 'REG-20250601-003', 'pending', 0),
(2, 4, 'REG-20250601-004', 'validated', 0),
(3, 3, 'REG-20250601-005', 'canceled', 0);

-- Data untuk certificates (hanya untuk registrasi yang validated dan attended)
INSERT INTO `certificates` (`registration_id`, `certificate_number`, `template_path`) VALUES
(1, 'CERT-2025-AI-001', '/templates/ai-workshop.pdf');

-- Data untuk tickets (untuk semua registrasi yang validated)
INSERT INTO `tickets` (`registration_id`, `ticket_number`, `qr_code`, `is_used`) VALUES
(1, 'TIX-20250601-001', '/qrcodes/TIX-20250601-001.png', 1),
(2, 'TIX-20250601-002', '/qrcodes/TIX-20250601-002.png', 0),
(4, 'TIX-20250601-003', '/qrcodes/TIX-20250601-003.png', 0);
