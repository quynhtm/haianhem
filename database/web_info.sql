/*
Navicat MySQL Data Transfer

Source Server         : LOCALHOST
Source Server Version : 50625
Source Host           : localhost:3306
Source Database       : haianhem

Target Server Type    : MYSQL
Target Server Version : 50625
File Encoding         : 65001

Date: 2017-03-18 20:00:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for web_info
-- ----------------------------
DROP TABLE IF EXISTS `web_info`;
CREATE TABLE `web_info` (
  `info_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `info_title` varchar(255) DEFAULT NULL,
  `info_keyword` varchar(255) DEFAULT NULL COMMENT 'keyword',
  `info_intro` longtext,
  `info_content` longtext,
  `info_img` varchar(255) DEFAULT NULL,
  `info_created` varchar(15) DEFAULT NULL,
  `info_order_no` int(11) DEFAULT '0',
  `info_status` tinyint(4) DEFAULT '0' COMMENT 'Item enabled status (1 = enabled, 0 = disabled)',
  `meta_title` text COMMENT 'Meta title',
  `meta_keywords` text COMMENT 'Meta keywords',
  `meta_description` text COMMENT 'Meta description',
  PRIMARY KEY (`info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='Stores news content.';

-- ----------------------------
-- Records of web_info
-- ----------------------------
INSERT INTO `web_info` VALUES ('2', '1', 'Thông tin giới thiệu', 'SITE_INTRO', '', '<p>Email hợp t&aacute;c: cskh@sanphamredep.com</p>\r\n\r\n<p>Địa chỉ: Số 483 - Nguyễn Khang - Cầu Giấy - H&agrave; Nội<br />\r\nLi&ecirc;n hệ: 094.11.99.656(Mr.Anh)</p>\r\n', null, '1441430611', '2', '1', '', '', '');
INSERT INTO `web_info` VALUES ('9', '1', 'Nội dung meta SEO trang chủ', 'SITE_SEO_HOME', '', '<p>Kh&ocirc;ng cần để nội dung...</p>\r\n', '', '1437450080', '9', '1', 'Manager Chat Fanpage', 'Manager Chat Fanpage', 'Manager Chat Fanpage');
INSERT INTO `web_info` VALUES ('10', '1', 'Hotline đầu trang', 'SITE_HOTLINE', null, '094.11.99.656', null, '1446789341', '10', '1', '', '', '');
INSERT INTO `web_info` VALUES ('18', null, 'Quy định', 'SITE_RULES', '', '<p>Đang cập nhật...</p>\r\n', null, '1473693704', '18', '1', 'Quy định', 'Quy định', 'Quy định');
INSERT INTO `web_info` VALUES ('19', null, 'Tài khoản ngân hàng', 'WEB_UNIT_BANK', '', '<div class=\\\"page-content-box\\\">\r\n<div class=\\\"content-static-wp\\\">\r\n<p><strong>Số TK Vietinbank:</strong>711A.696.8118.1<br />\r\nChủ t&agrave;i khoản: Nguyễn Xu&acirc;n Duy<br />\r\nChi nh&aacute;nh: Đền H&ugrave;ng - Ph&uacute; Thọ<br />\r\n<br />\r\n<strong>Số TK Vietcombank</strong> 080.1000.232.076<br />\r\nChủ t&agrave;i khoản: Nguyễn Xu&acirc;n Duy<br />\r\nChi nh&aacute;nh: Việt Tr&igrave; - Ph&uacute; Thọ</p>\r\n\r\n<p><br />\r\n<strong>Số TK Vpbank</strong>: 6468.3438<br />\r\nChủ t&agrave;i khoản:Nguyễn Xu&acirc;n Duy<br />\r\nChi nh&aacute;nh: Chương Dương - H&agrave; Nội</p>\r\n</div>\r\n</div>\r\n', null, '1480599433', '19', '1', '', '', '');
