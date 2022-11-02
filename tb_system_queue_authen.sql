/*
 Navicat Premium Data Transfer

 Source Server         : 244
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : 172.199.9.244:3306
 Source Schema         : db_queue_phar

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 02/11/2022 15:24:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_system_queue_authen
-- ----------------------------
DROP TABLE IF EXISTS `tb_system_queue_authen`;
CREATE TABLE `tb_system_queue_authen`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `authenval` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `cid` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(1) NULL DEFAULT 0,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `correlationId` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 146 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
