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
  `config_value` TEXT 
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
)ENGINE=InnoDB DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


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
 * Access @todo review normal profile
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."roles` (`id_role`, `name`, `description`) VALUES (1, 'user', 'Normal user'), (10, 'admin', 'Full access');");
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."access` (`id_access`, `id_role`, `access`) VALUES (1, 10, '*.*'),(2, 1, 'profile.*');");

/**
 * Create user God/Admin 
 */
$password = hash_hmac('sha256', $_POST['ADMIN_PWD'], $hash_key);
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."users` (`id_user`, `name`, `seoname`, `email`, `password`, `status`, `id_role`) 
VALUES (1, 'admin', NULL, '".$_POST['ADMIN_EMAIL']."', '$password', 1, 10)");

/**
 * Configs to make the app work
 *
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."config` (`group_name`, `config_key`, `config_value`) VALUES
('widget', 'list_of_all_widget', ''),
('widget', 'sidebar_widget', ''),
('widget', 'footer_widget', ''),
('appearance', 'theme', 'default'),
('i18n', 'charset', 'utf-8'),
('i18n', 'timezone', '".$_POST['TIMEZONE']."'),
('i18n', 'locale', '".$_POST['LANGUAGE']."'),
('payment', 'paypal_currency', 'USD'),
('payment', 'sandbox', 'FALSE'),
('payment', 'to_featured', 'FALSE'),
('payment', 'to_top', 'FALSE'),
('payment', 'pay_to_go_on_feature', '10'),
('payment', 'pay_to_go_on_top', '5'),
('payment', 'paypal_account', ''),
('general', 'base_url', '".$_POST['SITE_URL']."'),
('general', 'moderation', '0'),
('general', 'site_name', '".$_POST['SITE_NAME']."'),
('general', 'ID-pay_to_go_on_top', 'pay_to_go_on_top'),
('general', 'global-currency', 'USD'),
('general', 'ID-pay_to_go_on_feature', 'pay_to_go_on_feature'),
('general', 'featured_timer', '5'),
('general', 'advertisements_per_page', '10'),
('general', 'paypal_msg_product_to_top', 'Go on Top'),
('general', 'paypal_msg_product_to_featured', 'Go to Featured'),
('general', 'paypal_msg_product_category', 'Pay to publish '),
('formconfig', 'advertisement-num_images', '4'),
('formconfig', 'advertisement-address', 'TRUE'),
('formconfig', 'advertisement-phone', 'TRUE'),
('formconfig', 'contact-upload_file', 'FALSE'),
('formconfig', 'advertisement-location', 'TRUE'),
('formconfig', 'captcha-captcha', 'TRUE'),
('formconfig', 'advertisement-website', 'TRUE'),
('formconfig', 'advertisement-price', 'TRUE'),
('email-settings', 'notify_email', '".$_POST['ADMIN_EMAIL']."'),
('email-settings', 'smtp_active', 'FALSE'),
('email-settings', 'smtp_host', ''),
('email-settings', 'smtp_port', ''),
('email-settings', 'smtp_auth', 'FALSE'),
('email-settings', 'smtp_user', ''),
('email-settings', 'smtp_pass', '');");


//base category
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."categories` 
  (`id_category` ,`name` ,`order` ,`id_category_parent` ,`parent_deep` ,`seoname` ,`description` )
VALUES (1, 'all', 0 , 1, 0, 'all', 'root category');");


//base location
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."locations` 
  (`id_location` ,`name` ,`id_location_parent` ,`parent_deep` ,`seoname` ,`description`)
VALUES (1 , 'all', 1, 0, 'all', 'root location');");

 


/*
@todo sample values categories locations...
if ($_POST["SAMPLE_DB"]==1)
{

}
*/

mysql_close();