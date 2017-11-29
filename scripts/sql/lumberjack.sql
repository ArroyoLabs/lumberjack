/*
 Navicat MySQL Data Transfer

 Source Server         : lumberjack
 Source Server Type    : MySQL
 Source Server Version : 50710
 Source Host           : docker.local
 Source Database       : lumberjack

 Target Server Type    : MySQL
 Target Server Version : 50710
 File Encoding         : utf-8

 Date: 10/04/2017 20:51:26 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `event`
-- ----------------------------
DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL COMMENT 'user_admin ID',
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL COMMENT '{user} walk {value} miles',
  `value_unit` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL COMMENT 'km, inch,',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `event_table_name` varchar(255) DEFAULT NULL COMMENT 'km, inch,',
  PRIMARY KEY (`id`),
  KEY `fk_event_users1_idx` (`users_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `event_value_number`
-- ----------------------------
DROP TABLE IF EXISTS `event_value_number`;
CREATE TABLE `event_value_number` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `value` float NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_event_value (float)_event1_idx` (`event_id`),
  KEY `fk_event_value (float)_users1_idx` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `event_value_string`
-- ----------------------------
DROP TABLE IF EXISTS `event_value_string`;
CREATE TABLE `event_value_string` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_event_value (string)_event1_idx` (`event_id`),
  KEY `fk_event_value (string)_users1_idx` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
