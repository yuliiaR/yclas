CREATE TABLE  `oc_users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL,
  `email` varchar(145) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `role` int(1) NOT NULL DEFAULT '1',
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
  UNIQUE KEY `users_UK_email` (`email`),
  UNIQUE KEY `users_UK_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `oc_categories` (
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
  UNIQUE KEY `categories_IK_seo_name` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `oc_locations` (
  `id_location` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `id_location_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) NULL,
  `lat` FLOAT( 10, 6 ) NULL ,
  `lng` FLOAT( 10, 6 ) NULL ,
  PRIMARY KEY (`id_location`),
  UNIQUE KEY `categories_UK_seoname` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE  `oc_posts` (
  `id_post` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
  `ip_address` float DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` DATETIME  NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `has_images` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_post`) USING BTREE,
  KEY `posts_IK_id_user` (`id_user`),
  KEY `posts_IK_id_category` (`id_category`),
  KEY `posts_IK_title` (`title`),
  UNIQUE KEY `posts_UK_seotitle` (`seotitle`),
  KEY `posts_IK_status` (`status`),
  CONSTRAINT `posts_FK_id_user_AT_users` FOREIGN KEY (`id_user`) REFERENCES `oc_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `posts_FK_id_category_AT_categories` FOREIGN KEY (`id_category`) REFERENCES `oc_categories` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


CREATE TABLE IF NOT EXISTS `oc_visits` (
  `id_visit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_post` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` float DEFAULT NULL,
  PRIMARY KEY (`id_visit`),
  KEY `visits_IK_id_user` (`id_user`),
  KEY `visits_IK_id_post` (`id_post`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `oc_config` ( 
  `group_name` VARCHAR(128)  NOT NULL, 
  `config_key` VARCHAR(128)  NOT NULL, 
  `config_value` TEXT, 
  PRIMARY KEY (`group_name`, `config_key`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `oc_pages` (
  `id_page` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_page_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` TEXT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_page`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--default configs
INSERT INTO `oc_config` (`group_name`, `config_key`, `config_value`) VALUES
('appearance', 'theme', 'default'),
('i18n', 'charset', 'utf-8'),
('init', 'base_url', '/'),
('i18n', 'timezone', 'Europe/Madrid'),
('i18n', 'locale', 'en_US'),
('cookie', 'salt', '13413mdksdf-948jd');

--admin user
INSERT INTO `oc_users` (`name`, `email`, `password`, `status`, `role`)
VALUES ('chema', 'neo22s@gmail.com', '15ecfab1f55bea08e836dc0a393cc267969e9b35e4468aa5c90e2f22bd5d44fd', '1', '10');

--root category
INSERT INTO `oc_categories` (`id_category`, `name`, `order`, `seoname`, `description`) 
VALUES (1, 'all', '0', 'all', 'root category');
--root location
INSERT INTO `oc_locations` (`id_location`, `name`, `seoname`, `description`) 
VALUES (1, 'all','all', 'root location');
