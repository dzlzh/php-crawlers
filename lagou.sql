/*
Navicat MySQL Data Transfer

Source Server         : azure
Source Server Version : 50545
Source Host           : ja-cdbr-azure-west-a.cloudapp.net:3306
Source Database       : acsm_5149b91f4b3172b

Target Server Type    : MYSQL
Target Server Version : 50545
File Encoding         : 65001

Date: 2016-06-12 14:37:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lagou
-- ----------------------------
DROP TABLE IF EXISTS `lagou`;
CREATE TABLE `lagou` (
  `uuid` char(36) NOT NULL DEFAULT '',
  `positionId` int(255) NOT NULL,
  `positionName` varchar(255) DEFAULT NULL,
  `positionType` varchar(255) DEFAULT NULL,
  `positionAdvantage` varchar(255) DEFAULT NULL,
  `companyName` varchar(255) DEFAULT NULL,
  `companyShortName` varchar(255) DEFAULT NULL,
  `companySize` varchar(255) DEFAULT NULL,
  `companyLabelList` varchar(255) DEFAULT NULL,
  `industryField` varchar(255) DEFAULT NULL,
  `financeStage` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `businessZones` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `salary` varchar(255) DEFAULT NULL,
  `workYear` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `jobNature` varchar(255) DEFAULT NULL,
  `jobDescription` varchar(255) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  `jobUrl` varchar(255) DEFAULT NULL,
  `collectionTime` datetime DEFAULT NULL,
  PRIMARY KEY (`uuid`),
  UNIQUE KEY `positionId` (`positionId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
