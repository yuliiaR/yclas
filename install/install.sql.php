<?
//SQL installation import

mysql_query("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");

mysql_query("CREATE TABLE IF NOT EXISTS `[TABLE_PREFIX]roles` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `[TABLE_PREFIX]roles_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=[DB_CHARSET];");


mysql_query("CREATE TABLE IF NOT EXISTS `[TABLE_PREFIX]access` (
  `id_access` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `access` varchar(100) NOT NULL,
  PRIMARY KEY (`id_access`)
) ENGINE=MyISAM DEFAULT CHARSET=[DB_CHARSET];");


mysql_query("CREATE TABLE IF NOT EXISTS  `[TABLE_PREFIX]users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `seoname` varchar(145) DEFAULT NULL,
  `email` varchar(145) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `id_role` int(10) unsigned DEFAULT '1',
  `id_location` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_modified` datetime DEFAULT NULL,
  `logins` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_ip`  float DEFAULT NULL,
  `user_agent` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `token_created` datetime DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `[TABLE_PREFIX]users_UK_email` (`email`),
  UNIQUE KEY `[TABLE_PREFIX]users_UK_token` (`token`),
  UNIQUE KEY `[TABLE_PREFIX]users_UK_seoname` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=[DB_CHARSET];");


mysql_query("CREATE TABLE IF NOT EXISTS  `[TABLE_PREFIX]categories` (
  `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_category_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) NULL,
  `price` decimal NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`) USING BTREE,
  UNIQUE KEY `[TABLE_PREFIX]categories_IK_seo_name` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=[DB_CHARSET];");


mysql_query("CREATE TABLE IF NOT EXISTS `[TABLE_PREFIX]locations` (
  `id_location` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `id_location_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) NULL,
  `lat` FLOAT( 10, 6 ) NULL ,
  `lng` FLOAT( 10, 6 ) NULL ,
  PRIMARY KEY (`id_location`),
  UNIQUE KEY `[TABLE_PREFIX]categories_UK_seoname` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=[DB_CHARSET];");


mysql_query("CREATE TABLE IF NOT EXISTS `[TABLE_PREFIX]ads` (
  `id_ad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL DEFAULT '0',
  `id_location` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` text NOT NULL,
  `adress` varchar(145) DEFAULT '0',
  `price` decimal(14,3) NOT NULL DEFAULT '0',
  `phone` varchar(30) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `ip_address` float DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` DATETIME  NULL,
  `featured` DATETIME  NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `has_images` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_ad`) USING BTREE,
  KEY `[TABLE_PREFIX]ads_IK_id_user` (`id_user`),
  KEY `[TABLE_PREFIX]ads_IK_id_category` (`id_category`),
  KEY `[TABLE_PREFIX]ads_IK_title` (`title`),
  UNIQUE KEY `[TABLE_PREFIX]ads_UK_seotitle` (`seotitle`),
  KEY `[TABLE_PREFIX]ads_IK_status` (`status`),
  CONSTRAINT `[TABLE_PREFIX]ads_FK_id_user_AT_users` FOREIGN KEY (`id_user`) REFERENCES `[TABLE_PREFIX]users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `[TABLE_PREFIX]ads_FK_id_category_AT_categories` FOREIGN KEY (`id_category`) REFERENCES `[TABLE_PREFIX]categories` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=[DB_CHARSET];");


mysql_query("CREATE TABLE IF NOT EXISTS `[TABLE_PREFIX]visits` (
  `id_visit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ad` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` float DEFAULT NULL,
  PRIMARY KEY (`id_visit`),
  KEY `[TABLE_PREFIX]visits_IK_id_user` (`id_user`),
  KEY `[TABLE_PREFIX]visits_IK_id_ad` (`id_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=[DB_CHARSET];");


mysql_query("CREATE TABLE IF NOT EXISTS `[TABLE_PREFIX]config` ( 
  `group_name` VARCHAR(128)  NOT NULL, 
  `config_key` VARCHAR(128)  NOT NULL, 
  `config_value` TEXT 
) ENGINE=MyISAM DEFAULT CHARSET=[DB_CHARSET] ;");


mysql_query("CREATE TABLE IF NOT EXISTS  `[TABLE_PREFIX]orders` (
  `id_order` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_ad` int(10) unsigned NULL,
  `id_product` varchar(20) NOT NULL, 
  `paymethod` varchar(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_date` DATETIME  NULL,
  `currency` char(3) NOT NULL,
  `amount` decimal(14,3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_order`),
  KEY `[TABLE_PREFIX]orders_IK_id_user` (`id_user`),
  KEY `[TABLE_PREFIX]orders_IK_status` (`status`)
)ENGINE=InnoDB DEFAULT CHARSET=[DB_CHARSET];");


mysql_query("CREATE TABLE IF NOT EXISTS `[TABLE_PREFIX]content` (
  `id_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` TEXT NULL,
  `from_email` varchar(145) NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('page','email','help') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  UNIQUE KEY `[TABLE_PREFIX]content_UK_seotitle` (`seotitle`)
) ENGINE=MyISAM DEFAULT CHARSET=[DB_CHARSET];");


//@todo add configs, create user, roles....
//Create a "God" user with role 10. hash_hmac('sha256', $password, $hash_key);
/*
if ($_POST["SAMPLE_DB"]==1)
{

}
*/

mysql_close();