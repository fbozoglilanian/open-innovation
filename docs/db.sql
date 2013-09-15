CREATE DATABASE `daedalus`;
USE `daedalus`;


CREATE TABLE  `user` (
 `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `username` VARCHAR( 255 ) DEFAULT NULL UNIQUE ,
 `email` VARCHAR( 255 ) DEFAULT NULL UNIQUE ,
 `display_name` VARCHAR( 50 ) DEFAULT NULL ,
 `password` VARCHAR( 128 ) NOT NULL ,
 `state` SMALLINT UNSIGNED
) ENGINE = INNODB CHARSET =  "utf8";


-- Challenge Tables
CREATE TABLE IF NOT EXISTS `challenge` (
  `challenge_id` int(11) NOT NULL AUTO_INCREMENT,
  `challenge` varchar(500) NOT NULL,
  `motivation` varchar(1000) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_challenge_id` int(11) DEFAULT NULL,
  `date_added` datetime NOT NULL,
  `date_edited` datetime DEFAULT NULL,
  PRIMARY KEY (`challenge_id`),
  KEY `challenge` (`challenge`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `challenge_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `challenge_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` varchar(1000) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `challenge_id` (`challenge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




-- User Privileges


CREATE USER 'daedalus'@'localhost' IDENTIFIED BY  'daedalus';

GRANT SELECT , 
INSERT ,

UPDATE ,
DELETE ,
FILE ,
TRIGGER,
EXECUTE ON * . * TO  'daedalus'@'localhost' IDENTIFIED BY  'daedalus' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON  `daedalus` . * TO  'daedalus'@'localhost';

FLUSH PRIVILEGES;