CREATE TABLE `tokens` (
	`id` INT unsigned NOT NULL AUTO_INCREMENT,
	`user_id` INT unsigned,
	`token` TEXT,
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	`expired_at` TIMESTAMP ,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;