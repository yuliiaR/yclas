<?
/**
 * SQL installation import
 *
 * @package    Install
 * @category   Helper
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2013 Open Classifieds Team
 * @license    GPL v3
 */

defined('SYSPATH') or exit('Install must be loaded from within index.php!');

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
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `id_location_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) NULL,
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
  `contacted` tinyint(1) NOT NULL DEFAULT '0',
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
  `description` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`id_order`),
  KEY `".$_POST['TABLE_PREFIX']."orders_IK_id_user` (`id_user`),
  KEY `".$_POST['TABLE_PREFIX']."orders_IK_status` (`status`)
)ENGINE=MyISAM DEFAULT CHARSET=".$_POST['DB_CHARSET'].";");


mysql_query("CREATE TABLE IF NOT EXISTS `".$_POST['TABLE_PREFIX']."content` (
  `id_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `locale` varchar(8) NOT NULL DEFAULT 'en_EN',
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
 * add basic content like emails
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."content` (`order`, `title`, `seotitle`, `description`, `from_email`, `type`, `status`) 
    VALUES
(0, 'Change Password [SITE.NAME]', 'auth.remember', 'Hello [USER.NAME],\n\nFollow this link  [URL.QL]\n\nThanks!!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Welcome to [SITE.NAME]!', 'auth.register', 'Welcome [USER.NAME],\n\nWe are really happy that you have joined us! [URL.QL]\n\nRemember your user details:\nEmail: [USER.EMAIL]\nPassword: [USER.PWD]\n\nWe do not have your original password anymore.\n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Hello [USER.NAME]!', 'user.contact', 'You have been contacted regarding your advertisement. User [EMAIL.SENDER] [EMAIL.FROM], has a message for you: \n\n[EMAIL.BODY]. \n\n Regards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Hello [USER.NAME]!', 'user.new', 'Welcome to [SITE.NAME]. \n\n We are really happy that you have joined us! , \n\n you can log in with you email : [USER.EMAIL], \n\n with password: [USER.PWD]. Password is generated for you, to change it you can visit this link [URL.PWCH]. \n\n Thank you for trusting us! \n\n Regards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, '[EMAIL.SENDER] wants to contact you!', 'contact.admin', 'Hello Admin,\n\n [EMAIL.SENDER]: [EMAIL.FROM], have a message for you:\n\n [EMAIL.BODY] \n\n Regards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Your advertisement at [SITE.NAME], has been activated!', 'ads.activated', 'Hello [USER.OWNER],\n\n We want to inform you that your advertisement [URL.QL] has been activated!\n\n Now it can be seen by others. \n\n We hope we did not make you wait for long. \n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Success! Your advertisement is created on [SITE.NAME]!', 'ads.notify', 'Hello [USER.NAME],\n\nThank you for creating an advertisement at [SITE.NAME]! \n\nYou can edit your advertisement here [URL.QL].\n\n Your ad is still not published, it needs to be validated by an administrator. \n\n We are sorry for any inconvenience. We will review it as soon as possible. \n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Advertisement is created on [SITE.NAME]!', 'ads.user_check', 'Hello [USER.NAME],\n\n Advertisement is created under your account [USER.NAME]! You can visit this link to see advertisement [URL.AD]\n\n If you are not responsible for creating this advertisement, click a link to contact us [URL.CONTACT].\n\n', '".$_POST['ADMIN_EMAIL']."', 'email', 1),
(0, 'Success! Your advertisement is created on [SITE.NAME]!', 'ads.confirm', 'Welcome [USER.NAME],\n\nThank you for creating an advertisement at [SITE.NAME]! \n\nPlease click on this link [URL.QL] to confirm it.\n\nRegards!', '".$_POST['ADMIN_EMAIL']."', 'email', 1);");

/**
 * Access
 */
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."roles` (`id_role`, `name`, `description`) VALUES (1, 'user', 'Normal user'), (5, 'translator', 'User + Translations'), (10, 'admin', 'Full access');");
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."access` (`id_access`, `id_role`, `access`) VALUES 
            (1, 10, '*.*'),
            (2, 1, 'profile.*'),(3, 1, 'stats.user'),
            (4, 5, 'translations.*'),(5, 5, 'profile.*'),(6, 5, 'stats.user'),(7, 5, 'content.*');");

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
('appearance', 'theme_mobile', ''),
('appearance', 'allow_query_theme', 0),
('i18n', 'charset', 'utf-8'),
('i18n', 'timezone', '".$_POST['TIMEZONE']."'),
('i18n', 'locale', '".$_POST['LANGUAGE']."'),
('i18n', 'allow_query_language', 0),
('payment', 'paypal_currency', 'USD'),
('payment', 'sandbox', 0),
('payment', 'to_featured', 0),
('payment', 'to_top', 0),
('payment', 'featured_days', '5'),
('payment', 'pay_to_go_on_feature', '10'),
('payment', 'pay_to_go_on_top', '5'),
('payment', 'paypal_account', ''),
('general', 'number_format', ''),
('general', 'date_format', 'd-m-y'),
('general', 'base_url', '".$_POST['SITE_URL']."'),
('general', 'moderation', 0),
('general', 'maintenance', 0),
('general', 'analytics', ''),
('general', 'translate', ''),
('general', 'feed_elements', '20'),
('general', 'map_elements', '100'),
('general', 'site_name', '".$_POST['SITE_NAME']."'),
('general', 'global_currency', 'USD'),
('general', 'advertisements_per_page', '10'),
('image', 'allowed_formats', 'jpeg,jpg,png,'),
('image', 'max_image_size', '5'),
('image', 'height', ''),
('image', 'width', '1200'),
('image', 'height_thumb', '200'),
('image', 'width_thumb', '200'),
('image', 'quality', '90'),
('advertisement', 'num_images', '4'),
('advertisement', 'expire_date', '0'),
('advertisement', 'address', 1),
('advertisement', 'phone', 1),
('advertisement', 'upload_file', 0),
('advertisement', 'location', 1),
('advertisement', 'captcha', 1),
('advertisement', 'website', 1),
('advertisement', 'price', 1),
('advertisement', 'tos', ''),
('advertisement', 'disqus', ''),
('advertisement', 'map', 0),
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
VALUES (1, 'Home category', 0 , 0, 0, 'all', 'root category');");


//base location
mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."locations` 
  (`id_location` ,`name` ,`id_location_parent` ,`parent_deep` ,`seoname` ,`description`)
VALUES (1 , 'Home location', 0, 0, 'all', 'root location');");

 

//sample values 
if ( cP('SAMPLE_DB') !== NULL)
{
    //sample catpegories
    mysql_query("INSERT INTO `".$_POST['TABLE_PREFIX']."categories` (`id_category`, `name`, `order`, `created`, `id_category_parent`, `parent_deep`, `seoname`, `description`, `price`) VALUES
    (2, 'Jobs', 1, '2013-05-01 16:41:04', 1, 0, 'jobs', 'The best place to find work is with our job offers. Also you can ask for work in the ''Need'' section.', 0),
    (3, 'Languages', 2, '2013-05-01 16:41:04', 1, 0, 'languages', 'You want to learn a new language? Or can you teach a language? This is your section!', 0),
    (4, 'Others', 4, '2013-05-01 16:41:04', 1, 0, 'others', 'Whatever you can imagine is in this section.', 0),
    (5, 'Housing', 0, '2013-05-01 16:41:53', 1, 0, 'housing', 'Do you need a place to sleep, or you have something to offer; rooms, shared apartments, houses... etc.\n\nFind your perfect roommate here!', 0),
    (6, 'Market', 3, '2013-05-01 16:41:04', 1, 0, 'market', 'Buy or sell things that you don''t need anymore in the City, you will find someone interested, or maybe you are going to find exactly what you need.', 0),
    (7, 'Full Time', 1, '2009-04-22 17:31:43', 2, 1, 'full-time', 'Are you looking for a fulltime job? Or do you have a fulltime job to offer? Post your Ad here!', 0),
    (8, 'Part Time', 2, '2009-04-22 17:32:15', 2, 1, 'part-time', 'Are you looking for a parttime job? Or do you have a partime job to offer? Post your Ad here!', 0),
    (9, 'Internship', 3, '2009-04-22 17:33:05', 2, 1, 'internship', 'Are you looking for a internship in the City? Or do you have an internship to offer? Post it here!', 0),
    (10, 'Au pair', 4, '2009-06-19 09:26:22', 2, 1, 'au-pair', 'Find or require for a Au Pair service. Here is the best place', 0),
    (11, 'English', 1, '2009-04-22 17:33:52', 3, 1, 'english', 'Do you speak English? Or can you teach it? Do you want to learn? This is your category.', 0),
    (12, 'Spanish', 2, '2009-04-22 17:34:29', 3, 1, 'spanish', 'You want to learn Spanish? Or can you teach Spanish? This is your section!', 0),
    (13, 'Other Languages', 3, '2009-04-22 17:35:34', 3, 1, 'other-languages', 'Are you interested in learning or teaching any other language that is not listed? Post it here!', 0),
    (14, 'Events', 0, '2013-05-01 16:41:11', 4, 1, 'events', 'Upcoming Parties, Cinema, Museums, Parades, Birthdays, Dinners.... Everything!', 0),
    (15, 'Hobbies', 1, '2013-05-01 16:41:11', 4, 1, 'hobbies', 'Share your hobby with someone! Football, running, cinema, music, cinema, party ... Post it here!', 0),
    (16, 'Services', 3, '2009-04-22 17:38:33', 4, 1, 'services', 'Do you need a service? Relocation? Insurance? Doctor? Cleaning? Here you can ask for it or offer services!', 0),
    (17, 'Friendship', 2, '2013-05-01 16:41:17', 1, 1, 'friendship', 'Are you alone in the City? Here you can find new friends! or a new boyfriend/girlfriend ;)', 0),
    (18, 'Apartment', 1, '2009-04-22 17:39:32', 5, 1, 'apartment', 'Apartments, flats, monthly rentals, long terms, for days... this is the section to have your apartment in the City!', 0),
    (19, 'Shared Apartments - Rooms', 2, '2009-05-03 21:53:57', 5, 1, 'shared-apartments-rooms', 'You want to share an apartment? Then you need a room! Ask for rooms or add yours in this section.', 0),
    (20, 'House', 3, '2009-04-22 17:40:50', 5, 1, 'house', 'Rent a house, or offer your house for rent! Here you can find your beach house close to the City!', 0),
    (21, 'TV', 1, '2009-04-22 17:41:39', 6, 1, 'tv', 'TV, Video Games, TFT, Plasma, your old TV, or your new one can find a new owner!', 0),
    (22, 'Audio', 2, '2009-04-22 17:42:13', 6, 1, 'audio', 'HI-FI systems, iPod, MP3 players, MP4, if you don''t use it anymore sell it! If you try to find a second hand one, this is your place!', 0),
    (23, 'Furniture', 3, '2009-04-22 17:43:16', 6, 1, 'furniture', 'Do you need to furnish your home? Or would you like to sell your furniture? Post it here!', 0),
    (24, 'IT', 4, '2009-04-22 17:43:48', 6, 1, 'it', 'You need a computer? Laptop? Or do you have some old components? This is the IT market of the City!', 0),
    (25, 'Other Market', 5, '2009-04-22 17:44:12', 6, 1, 'other-market', 'In this market you can sell everything you want! Or search for it!', 0);
    ");
}


mysql_close();