SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for news_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `news_admin_user`;
CREATE TABLE `news_admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `last_login_ip` varchar(30) NOT NULL DEFAULT '',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` int(10) unsigned NOT NULL DEFAULT '8',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news_app_active
-- ----------------------------
DROP TABLE IF EXISTS `news_app_active`;
CREATE TABLE `news_app_active` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `version` int(8) unsigned NOT NULL DEFAULT '0',
  `app_type` varchar(20) NOT NULL DEFAULT '',
  `did` varchar(100) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news_comment
-- ----------------------------
DROP TABLE IF EXISTS `news_comment`;
CREATE TABLE `news_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `news_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` varchar(300) NOT NULL DEFAULT '',
  `to_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论目标用户ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `news_id` (`news_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news_crab
-- ----------------------------
DROP TABLE IF EXISTS `news_crab`;
CREATE TABLE `news_crab` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_type` varchar(10) NOT NULL DEFAULT '',
  `version_code` varchar(10) NOT NULL DEFAULT '',
  `model` varchar(10) NOT NULL DEFAULT '',
  `did` varchar(100) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1-crash 2-卡顿 3-exception 4-anr',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `line` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news_news
-- ----------------------------
DROP TABLE IF EXISTS `news_news`;
CREATE TABLE `news_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `small_title` varchar(20) NOT NULL DEFAULT '',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `description` varchar(200) NOT NULL,
  `is_position` tinyint(1) NOT NULL DEFAULT '0',
  `is_head_figure` tinyint(1) NOT NULL DEFAULT '0',
  `is_allowcomments` tinyint(1) NOT NULL DEFAULT '0',
  `listorder` int(8) NOT NULL,
  `source_type` tinyint(1) DEFAULT '0',
  `create_time` int(10) NOT NULL DEFAULT '0',
  `update_time` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `read_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `upvote_count` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news_user
-- ----------------------------
DROP TABLE IF EXISTS `news_user`;
CREATE TABLE `news_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `phone` varchar(11) NOT NULL DEFAULT '',
  `token` varchar(100) NOT NULL DEFAULT '',
  `time_out` int(10) unsigned NOT NULL DEFAULT '0',
  `image` varchar(200) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别0保密 1男 2女',
  `signature` varchar(200) NOT NULL DEFAULT '' COMMENT '个性签名',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `phone` (`phone`),
  KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news_user_news
-- ----------------------------
DROP TABLE IF EXISTS `news_user_news`;
CREATE TABLE `news_user_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `news_id` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news_version
-- ----------------------------
DROP TABLE IF EXISTS `news_version`;
CREATE TABLE `news_version` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `app_type` varchar(20) NOT NULL DEFAULT '' COMMENT 'app类型 比如 ios android',
  `version` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '内部版本号',
  `version_code` varchar(20) NOT NULL DEFAULT '' COMMENT '外部版本号比如1.2.3',
  `is_force` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否强制更新0不，1强制更新',
  `apk_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'apk最新地址',
  `upgrade_point` varchar(500) NOT NULL DEFAULT '' COMMENT '升级提示',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
