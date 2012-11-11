--old
CREATE TABLE  `oc`.`oc_accounts` (
  `idAccount` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `email` varchar(145) NOT NULL,
  `password` varchar(145) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `idLocation` int(10) unsigned DEFAULT NULL,
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `lastModifiedDate` datetime DEFAULT NULL,
  `lastSigninDate` datetime DEFAULT NULL,
  `activationToken` varchar(225) NOT NULL,
  PRIMARY KEY (`idAccount`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--new
--renamed to users, we will also store here the admins
--active now is status, we can have 0=not activated 1=activated 2=marked as spam
--new field role, 1= member, 10=administrator
--new unique key email
--new logins to store how many times did login
--user_agent
--token fields is to use in the remember me for the login.
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



--old
CREATE TABLE  `oc`.`oc_categories` (
  `idCategory` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `idCategoryParent` int(10) unsigned NOT NULL DEFAULT '0',
  `friendlyName` varchar(64) NOT NULL,
  `description` text,
  `price` float NOT NULL,
  PRIMARY KEY (`idCategory`) USING BTREE,
  KEY `Index_fname` (`friendlyName`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

--new
--friendly name = seo name
--description varchar 355
--parent deep to indicate how many levels of categories has, this allows to have cat1->subcatfrom1->subcatfromsubcat1->etc..
--price decimal
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


--old
CREATE TABLE  `oc`.`oc_locations` (
  `idLocation` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `idLocationParent` int(10) unsigned NOT NULL DEFAULT '0',
  `friendlyName` varchar(64) NOT NULL,
  PRIMARY KEY (`idLocation`)
) ENGINE=InnoDB AUTO_INCREMENT=741 DEFAULT CHARSET=utf8;

--new
--friendly name = seo name
--description varchar 255
--parent deep to indicate how many levels of categories has, this allows to have cat1->subcatfrom1->subcatfromsubcat1->etc..
--latitude, longitudes
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

--old
CREATE TABLE  `oc`.`oc_posts` (
  `idPost` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `isAvailable` int(1) NOT NULL DEFAULT '1',
  `isConfirmed` int(1) NOT NULL DEFAULT '0',
  `idCategory` int(10) unsigned NOT NULL DEFAULT '0',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `description` text NOT NULL,
  `email` varchar(145) NOT NULL,
  `idLocation` int(10) unsigned NOT NULL DEFAULT '0',
  `place` varchar(145) DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `price` float NOT NULL DEFAULT '0',
  `ip` varchar(18) NOT NULL DEFAULT '',
  `insertDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(8) NOT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `hasImages` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idPost`) USING BTREE,
  KEY `FK_posts_categories` (`idCategory`),
  KEY `Index_title` (`title`),
  CONSTRAINT `FK_posts_categories` FOREIGN KEY (`idCategory`) REFERENCES `oc_categories` (`idCategory`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--new
--id_user added
--status instead of = isAvailable, isConfirmed (statuses = 0 = not confirmed 1=published 2=in moderation 3=spam 4=repeated 5=expired)
--type int 1 (let's see how we do the types)
--seo title will allow us to search by url
--email,name field deleted
--adress = place
--created = insertDate 
--added published date field
--price decimal
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

--old
CREATE TABLE  `oc`.`oc_postshits` (
  `idHit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idPost` int(10) unsigned NOT NULL,
  `hitTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(18) NOT NULL,
  PRIMARY KEY (`idHit`),
  KEY `FK_PostsHits_idPost` (`idPost`),
  KEY `Index_hitTime` (`hitTime`),
  CONSTRAINT `FK_PostsHits_idPost` FOREIGN KEY (`idPost`) REFERENCES `oc_posts` (`idPost`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8;


--new
--uses mysam, faster and less expensive
--not any more FK
--ip now is a long, faster to search usage of ip_to_long php
--new field user, in case someone loged in
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

--new configuration table
CREATE TABLE IF NOT EXISTS `oc_config` ( 
  `group_name` VARCHAR(128)  NOT NULL, 
  `config_key` VARCHAR(128)  NOT NULL, 
  `config_value` TEXT, 
  PRIMARY KEY (`group_name`, `config_key`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;


--new pages table
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
('appearance', 'theme', 'twitter'),
('i18n', 'charset', 'utf-8'),
('init', 'base_url', '/'),
('i18n', 'timezone', 'Europe/Madrid'),
('i18n', 'locale', 'en_US'),
('cookie', 'salt', '13413mdksdf-948jd');
