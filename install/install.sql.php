<?
/**
 * SQL installation import
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2011 Open Classifieds Team
 * @license    GPL v3
 */

defined('SYSPATH') or exit('Install must be loaded from within index.php!');

//selecting the db
mysql_select_db($_POST['DB_NAME']);
mysql_query('SET NAMES '.$_POST['DB_CHARSET']);
mysql_query("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");

mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."roles` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."roles_UK_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."access` (
  `id_access` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `access` varchar(100) NOT NULL,
  PRIMARY KEY (`id_access`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS  `".$_POST['TABLE_PREFIX']."users` (
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
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."users_UK_email` (`email`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."users_UK_token` (`token`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."users_UK_seoname` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS  `".$_POST['TABLE_PREFIX']."categories` (
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
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."categories_IK_seo_name` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."locations` (
  `id_location` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `id_location_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) NULL,
  `lat` FLOAT( 10, 6 ) NULL ,
  `lng` FLOAT( 10, 6 ) NULL ,
  PRIMARY KEY (`id_location`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."categories_UK_seoname` (`seoname`)
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."ads` (
  `id_ad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL DEFAULT '0',
  `id_location` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` text NOT NULL,
  `address` varchar(145) DEFAULT '0',
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
  KEY `".$_POST['TABLE_PREFIX']."ads_IK_id_user` (`id_user`),
  KEY `".$_POST['TABLE_PREFIX']."ads_IK_id_category` (`id_category`),
  KEY `".$_POST['TABLE_PREFIX']."ads_IK_title` (`title`),
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."ads_UK_seotitle` (`seotitle`),
  KEY `".$_POST['TABLE_PREFIX']."ads_IK_status` (`status`),
  CONSTRAINT `".$_POST['TABLE_PREFIX']."ads_FK_id_user_AT_users` FOREIGN KEY (`id_user`) REFERENCES `".$_POST['TABLE_PREFIX']."users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `".$_POST['TABLE_PREFIX']."ads_FK_id_category_AT_categories` FOREIGN KEY (`id_category`) REFERENCES `".$_POST['TABLE_PREFIX']."categories` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."visits` (
  `id_visit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ad` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` float DEFAULT NULL,
  PRIMARY KEY (`id_visit`),
  KEY `".$_POST['TABLE_PREFIX']."visits_IK_id_user` (`id_user`),
  KEY `".$_POST['TABLE_PREFIX']."visits_IK_id_ad` (`id_ad`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."config` ( 
  `group_name` VARCHAR(128)  NOT NULL, 
  `config_key` VARCHAR(128)  NOT NULL, 
  `config_value` TEXT,
   KEY `".$_POST['TABLE_PREFIX']."config_IK_group_name_AND_config_key` (`group_name`,`config_key`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET']." ;");


mysql_query("CREATE TABLE IF NOT EXISTS  `".$_POST['TABLE_PREFIX']."orders` (
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
  KEY `".$_POST['TABLE_PREFIX']."orders_IK_id_user` (`id_user`),
  KEY `".$_POST['TABLE_PREFIX']."orders_IK_status` (`status`)
)ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."content` (
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
  UNIQUE KEY `".$_POST['TABLE_PREFIX']."content_UK_seotitle` (`seotitle`)
) ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");

/**
 * @todo add basic content like emails
 */
mysql_query("INSERT INTO `oc2_content` (`order`, `title`, `seotitle`, `description`, `from_email`, `type`, `status`) 
    VALUES
(0, 'Change Password [SITE.NAME]', 'auth.remember', 'Hello [USER.NAME],\n\nFollow this link  [URL.QL]\n\nThanks!!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Welcome to [SITE.NAME]!', 'auth.register', 'Welcome [USER.NAME],\n\nWe are really happy that you joined us! [URL.QL]\n\nRemember your user details:\nEmail: [USER.EMAIL]\nPassword: [USER.PWD]\n\nWe do not have your original password anymore.\n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1);");


/**
 * Access
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."roles` (`id_role`, `name`, `description`) VALUES (1, 'user', 'Normal user'), (10, 'admin', 'Full access');");
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."access` (`id_access`, `id_role`, `access`) VALUES (1, 10, '*.*'),(2, 1, 'profile.*');");

/**
 * Create user God/Admin 
 */
$password = hash_hmac('sha256', $_POST['ADMIN_PWD'], $hash_key);
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."users` (`id_user`, `name`, `seoname`, `email`, `password`, `status`, `id_role`) 
VALUES (1, 'admin', 'admin', '".$_POST['ADMIN_EMAIL']."', '$password', 1, 10)");

/**
 * Configs to make the app work
 * @todo refactor to use same coding standard
 * @todo widgets examples? at least at sidebar, rss, text advert, pages, locations...
 *
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."config` (`group_name`, `config_key`, `config_value`) VALUES
('sitemap', 'expires', '43200'),
('sitemap', 'on_post', 1),
('appearance', 'theme', 'default'),
('i18n', 'charset', 'utf-8'),
('i18n', 'timezone', '".$_POST['TIMEZONE']."'),
('i18n', 'locale', '".$_POST['LANGUAGE']."'),
('payment', 'paypal_currency', 'USD'),
('payment', 'sandbox', 0),
('payment', 'to_featured', 0),
('payment', 'to_top', 0),
('payment', 'pay_to_go_on_feature', '10'),
('payment', 'pay_to_go_on_top', '5'),
('payment', 'paypal_account', ''),
('general', 'number_format', ''),
('general', 'date_format', ''),
('general', 'base_url', '".$_POST['SITE_URL']."'),
('general', 'moderation', 0),
('general', 'analytics', ''),
('general', 'feed_elements', '20'),
('general', 'site_name', '".$_POST['SITE_NAME']."'),
('general', 'ID-pay_to_go_on_top', 'pay_to_go_on_top'),
('general', 'global-currency', 'USD'),
('general', 'ID-pay_to_go_on_feature', 'pay_to_go_on_feature'),
('general', 'featured_timer', '5'),
('general', 'advertisements_per_page', '10'),
('image', 'allowed_formats', 'jpag, jpg, png'),
('image', 'max_image_size', '5'),
('image', 'height', ''),
('image', 'width', '1200'),
('image', 'height_thumb', '200'),
('image', 'width_thumb', '200'),
('advertisement', 'num_images', '4'),
('advertisement', 'address', 1),
('advertisement', 'phone', 1),
('advertisement', 'upload_file', 0),
('advertisement', 'location', 1),
('advertisement', 'captcha-captcha', 1),
('advertisement', 'website', 1),
('advertisement', 'price', 1),
('email', 'notify_email', '".$_POST['ADMIN_EMAIL']."'),
('email', 'smtp_active', 0),
('email', 'smtp_host', ''),
('email', 'smtp_port', ''),
('email', 'smtp_auth', 0),
('email', 'smtp_ssl', 0),
('email', 'smtp_user', ''),
('email', 'smtp_pass', '');");


//base category
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."categories` 
  (`id_category` ,`name` ,`order` ,`id_category_parent` ,`parent_deep` ,`seoname` ,`description` )
VALUES (1, 'Home category', 0 , 1, 0, 'all', 'root category');");


//base location
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."locations` 
  (`id_location` ,`name` ,`id_location_parent` ,`parent_deep` ,`seoname` ,`description`)
VALUES (1 , 'Home location', 1, 0, 'all', 'root location');");

 


/*
@todo sample values categories locations...
if ($_POST["SAMPLE_DB"]==1)
{

}
*/

mysql_close();