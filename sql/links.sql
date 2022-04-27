CREATE TABLE `links` (
	`id` INT unsigned NOT NULL AUTO_INCREMENT,
    `user_id` INT unsigned,
	`link` TEXT,
	`short` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	UNIQUE KEY `short` (`short`) USING BTREE,
    PRIMARY KEY (`id`),
	FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;