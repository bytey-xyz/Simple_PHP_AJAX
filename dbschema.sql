-- Create syntax for 'numbers'

CREATE TABLE `userlist` (
	`uid` INT(11) NOT NULL AUTO_INCREMENT,
	`reg_tel` VARCHAR(50) NOT NULL DEFAULT '0',
	`hash` VARCHAR(40) NOT NULL DEFAULT '0' COMMENT 'for SHA1 - 40',
	`salt` VARCHAR(50) NOT NULL DEFAULT '0',
	`role` VARCHAR(50) NOT NULL DEFAULT '0' COMMENT 'May be edit in future, now same as reg_tel',
	PRIMARY KEY (`uid`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=4
;
/* DDOS PROTECTION TABLE */
CREATE TABLE `testing`.`ddos_protection` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`ip` VARCHAR(45) NULL,
	/* MySQL 5.7+ for now() function here. If you have less version use defining in PHP */
	`datetime` DATETIME NULL DEFAULT NOW(),
	`method` VARCHAR(10) NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `id_UNIQUE` (`id` ASC));
