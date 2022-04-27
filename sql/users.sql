CREATE TABLE `users` (
	`id` INT unsigned NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255),
	`email` VARCHAR(255),
	`password` VARCHAR(255),
	`role` VARCHAR(50),
	`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	UNIQUE KEY `email` (`email`) USING BTREE,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;