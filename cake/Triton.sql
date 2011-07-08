



-- ---
-- Globals
-- ---

-- SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
-- SET FOREIGN_KEY_CHECKS=0;

-- ---
-- Table 'users'
-- 
-- ---

DROP TABLE IF EXISTS `users`;
		
CREATE TABLE `users` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `username` VARCHAR(20) NULL DEFAULT NULL,
  `full_name` VARCHAR(100) NULL DEFAULT NULL,
  `password` VARCHAR(40) NULL DEFAULT NULL,
  `email` VARCHAR(40) NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  `lastLogin` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'arguments'
-- 
-- ---

DROP TABLE IF EXISTS `arguments`;
		
CREATE TABLE `arguments` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  `debate_id` INTEGER NULL DEFAULT NULL,
  `argument` MEDIUMTEXT NULL DEFAULT NULL,
  `vote_count` INTEGER NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'debates'
-- 
-- ---

DROP TABLE IF EXISTS `debates`;
		
CREATE TABLE `debates` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  `title` VARCHAR(100) NULL DEFAULT NULL,
  `summary` VARCHAR(512) NULL DEFAULT NULL,
  `content` MEDIUMTEXT NULL DEFAULT NULL,
  `argument_count` INTEGER NULL DEFAULT NULL,
  `comment_count` INTEGER NULL DEFAULT NULL,
  `vote_count` INTEGER NULL DEFAULT NULL,
  `end_time` DATETIME NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'groups_users'
-- 
-- ---

DROP TABLE IF EXISTS `groups_users`;
		
CREATE TABLE `groups_users` (
  `group_id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`group_id`)
);

-- ---
-- Table 'groups'
-- 
-- ---

DROP TABLE IF EXISTS `groups`;
		
CREATE TABLE `groups` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `name` VARCHAR(100) NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'polls'
-- 
-- ---

DROP TABLE IF EXISTS `polls`;
		
CREATE TABLE `polls` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  `title` VARCHAR(100) NULL DEFAULT NULL,
  `summary` VARCHAR(512) NULL DEFAULT NULL,
  `content` MEDIUMTEXT NULL DEFAULT NULL,
  `status` VARCHAR(10) NULL DEFAULT NULL,
  `vote_count` INTEGER NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'poll_options'
-- 
-- ---

DROP TABLE IF EXISTS `poll_options`;
		
CREATE TABLE `poll_options` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `poll_id` INTEGER NULL DEFAULT NULL,
  `option` VARCHAR(100) NULL DEFAULT NULL,
  `vote_count` INTEGER NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'postings'
-- 
-- ---

DROP TABLE IF EXISTS `postings`;
		
CREATE TABLE `postings` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  `title` VARCHAR(100) NULL DEFAULT NULL,
  `summary` VARCHAR(512) NULL DEFAULT NULL,
  `content` MEDIUMTEXT NULL DEFAULT NULL,
  `vote_count` INTEGER NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'user_permissions'
-- 
-- ---

DROP TABLE IF EXISTS `user_permissions`;
		
CREATE TABLE `user_permissions` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  `controller` VARCHAR(100) NULL DEFAULT NULL,
  `action` VARCHAR(100) NULL DEFAULT NULL,
  `group_id` INTEGER NULL DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  `allowed` INTEGER NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'user_profiles'
-- 
-- ---

DROP TABLE IF EXISTS `user_profiles`;
		
CREATE TABLE `user_profiles` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  `political_view` VARCHAR(127) NULL DEFAULT NULL,
  `religion` VARCHAR(127) NULL DEFAULT NULL,
  `books` VARCHAR(511) NULL DEFAULT NULL,
  `music` VARCHAR(511) NULL DEFAULT NULL,
  `about_me` VARCHAR(511) NULL DEFAULT NULL,
  `zipcode` VARCHAR(5) NULL DEFAULT NULL,
  `city` VARCHAR(30) NULL DEFAULT NULL,
  `state` VARCHAR(2) NULL DEFAULT NULL,
  `debator_score` INTEGER NULL DEFAULT NULL,
  `last_activity` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'votes'
-- 
-- ---

DROP TABLE IF EXISTS `votes`;
		
CREATE TABLE `votes` (
  `id` INTEGER NULL AUTO_INCREMENT DEFAULT NULL,
  `user_id` INTEGER NULL DEFAULT NULL,
  `voteable_id` INTEGER NULL DEFAULT NULL,
  `type` INTEGER NULL DEFAULT NULL,
  `created` DATETIME NULL DEFAULT NULL,
  `modified` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `arguments` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `arguments` ADD FOREIGN KEY (debate_id) REFERENCES `debates` (`id`);
ALTER TABLE `debates` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `groups_users` ADD FOREIGN KEY (group_id) REFERENCES `groups` (`id`);
ALTER TABLE `groups_users` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `polls` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `poll_options` ADD FOREIGN KEY (poll_id) REFERENCES `polls` (`id`);
ALTER TABLE `postings` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `user_profiles` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `votes` ADD FOREIGN KEY (user_id) REFERENCES `users` (`id`);
ALTER TABLE `votes` ADD FOREIGN KEY (voteable_id) REFERENCES `debates` (`id`);
ALTER TABLE `votes` ADD FOREIGN KEY (voteable_id) REFERENCES `arguments` (`id`);
ALTER TABLE `votes` ADD FOREIGN KEY (voteable_id) REFERENCES `polls` (`id`);
ALTER TABLE `votes` ADD FOREIGN KEY (voteable_id) REFERENCES `poll_options` (`id`);
ALTER TABLE `votes` ADD FOREIGN KEY (voteable_id) REFERENCES `postings` (`id`);

-- ---
-- Table Properties
-- ---

-- ALTER TABLE `users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `arguments` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `debates` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `groups_users` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `groups` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `polls` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `poll_options` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `postings` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `user_permissions` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `user_profiles` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- ALTER TABLE `votes` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Test Data
-- ---

-- INSERT INTO `users` (`id`,`username`,`full_name`,`password`,`email`,`created`,`modified`,`lastLogin`) VALUES
-- ('','','','','','','','');
-- INSERT INTO `arguments` (`id`,`user_id`,`debate_id`,`argument`,`vote_count`,`created`,`modified`) VALUES
-- ('','','','','','','');
-- INSERT INTO `debates` (`id`,`user_id`,`title`,`summary`,`content`,`argument_count`,`comment_count`,`vote_count`,`end_time`,`created`,`modified`) VALUES
-- ('','','','','','','','','','','');
-- INSERT INTO `groups_users` (`group_id`,`user_id`) VALUES
-- ('','');
-- INSERT INTO `groups` (`id`,`name`,`created`,`modified`) VALUES
-- ('','','','');
-- INSERT INTO `polls` (`id`,`user_id`,`title`,`summary`,`content`,`status`,`vote_count`,`created`,`modified`) VALUES
-- ('','','','','','','','','');
-- INSERT INTO `poll_options` (`id`,`poll_id`,`option`,`vote_count`,`created`,`modified`) VALUES
-- ('','','','','','');
-- INSERT INTO `postings` (`id`,`user_id`,`title`,`summary`,`content`,`vote_count`,`created`,`modified`) VALUES
-- ('','','','','','','','');
-- INSERT INTO `user_permissions` (`id`,`created`,`modified`,`controller`,`action`,`group_id`,`user_id`,`allowed`) VALUES
-- ('','','','','','','','');
-- INSERT INTO `user_profiles` (`id`,`user_id`,`political_view`,`religion`,`books`,`music`,`about_me`,`zipcode`,`city`,`state`,`debator_score`,`last_activity`) VALUES
-- ('','','','','','','','','','','','');
-- INSERT INTO `votes` (`id`,`user_id`,`voteable_id`,`type`,`created`,`modified`) VALUES
-- ('','','','','','');

