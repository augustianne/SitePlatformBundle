SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE TABLE IF NOT EXISTS `sp_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_feature` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NOT NULL,
  `caption` TEXT NOT NULL,
  `image_filename` VARCHAR(500) NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  `url_text` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_feature_category` (
  `feature_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `rank` SMALLINT(6) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `sp_feature_category_FI_1` (`feature_id` ASC),
  INDEX `sp_feature_category_FI_2` (`category_id` ASC),
  CONSTRAINT `sp_feature_category_FK_1`
    FOREIGN KEY (`feature_id`)
    REFERENCES `sp_feature` (`id`),
  CONSTRAINT `sp_feature_category_FK_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `sp_category` (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_link` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `url` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_link_category` (
  `link_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `rank` SMALLINT(6) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `sp_link_category_FI_1` (`link_id` ASC),
  INDEX `sp_link_category_FI_2` (`category_id` ASC),
  CONSTRAINT `sp_link_category_FK_1`
    FOREIGN KEY (`link_id`)
    REFERENCES `sp_link` (`id`),
  CONSTRAINT `sp_link_category_FK_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `sp_category` (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_page` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(500) NOT NULL,
  `content` TEXT NOT NULL,
  `slug` VARCHAR(600) NOT NULL,
  `is_dynamic` TINYINT(1) NOT NULL,
  `date_created` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_page_category` (
  `page_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `rank` SMALLINT(6) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `sp_page_category_FI_1` (`page_id` ASC),
  INDEX `sp_page_category_FI_2` (`category_id` ASC),
  CONSTRAINT `sp_page_category_FK_1`
    FOREIGN KEY (`page_id`)
    REFERENCES `sp_page` (`id`),
  CONSTRAINT `sp_page_category_FK_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `sp_category` (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_setting` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `value` TEXT NOT NULL,
  PRIMARY KEY (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_setting_category` (
  `setting_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `rank` SMALLINT(6) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `sp_setting_category_FI_1` (`setting_id` ASC),
  INDEX `sp_setting_category_FI_2` (`category_id` ASC),
  CONSTRAINT `sp_setting_category_FK_1`
    FOREIGN KEY (`setting_id`)
    REFERENCES `sp_setting` (`id`),
  CONSTRAINT `sp_setting_category_FK_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `sp_category` (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_subscriber` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `date_subscribed` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_testimonial` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `bio` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `date_added` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sp_testimonial_category` (
  `testimonial_id` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  `rank` SMALLINT(6) NOT NULL,
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  INDEX `sp_testimonial_category_FI_1` (`testimonial_id` ASC),
  INDEX `sp_testimonial_category_FI_2` (`category_id` ASC),
  CONSTRAINT `sp_testimonial_category_FK_1`
    FOREIGN KEY (`testimonial_id`)
    REFERENCES `sp_testimonial` (`id`),
  CONSTRAINT `sp_testimonial_category_FK_2`
    FOREIGN KEY (`category_id`)
    REFERENCES `sp_category` (`id`)) 
ENGINE = InnoDB 
DEFAULT CHARSET=utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
