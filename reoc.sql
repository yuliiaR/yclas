-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 13, 2013 at 10:16 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `reoc`
--

-- --------------------------------------------------------

--
-- Table structure for table `oc_access`
--

CREATE TABLE IF NOT EXISTS `oc_access` (
  `id_access` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_role` int(10) unsigned NOT NULL,
  `access` varchar(100) NOT NULL,
  PRIMARY KEY (`id_access`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `oc_access`
--

INSERT INTO `oc_access` (`id_access`, `id_role`, `access`) VALUES
(1, 10, '*.*'),
(2, 1, 'profile.*');

-- --------------------------------------------------------

--
-- Table structure for table `oc_ads`
--

CREATE TABLE IF NOT EXISTS `oc_ads` (
  `id_ad` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_category` int(10) unsigned NOT NULL DEFAULT '0',
  `id_location` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` text NOT NULL,
  `adress` varchar(145) DEFAULT '0',
  `price` decimal(14,3) NOT NULL DEFAULT '0.000',
  `phone` varchar(30) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `ip_address` float DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `has_images` tinyint(1) NOT NULL DEFAULT '0',
  `featured` datetime DEFAULT NULL,
  PRIMARY KEY (`id_ad`) USING BTREE,
  UNIQUE KEY `ads_UK_seotitle` (`seotitle`),
  KEY `ads_IK_id_user` (`id_user`),
  KEY `ads_IK_id_category` (`id_category`),
  KEY `ads_IK_title` (`title`),
  KEY `ads_IK_status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=252 ;

--
-- Dumping data for table `oc_ads`
--

INSERT INTO `oc_ads` (`id_ad`, `id_user`, `id_category`, `id_location`, `type`, `title`, `seotitle`, `description`, `adress`, `price`, `phone`, `website`, `ip_address`, `created`, `published`, `status`, `has_images`, `featured`) VALUES
(193, 2, 1, 1, 0, '1', '1', 'asd', '', 0.000, '', NULL, NULL, '2013-01-25 09:47:15', NULL, 1, 0, NULL),
(194, 2, 1, 2, 0, '1', '1-1', 'asd', '', 0.000, '', NULL, NULL, '2013-01-25 09:47:34', NULL, 1, 0, NULL),
(195, 2, 1, 1, 0, '2', '2', 'ads', '', 0.000, '', NULL, NULL, '2013-01-25 09:49:53', NULL, 1, 0, NULL),
(196, 2, 4, 2, 0, 'iphone 4s CHEEP!!!', 'iphone-4s-cheep', 'I have it for a about 1 year. I kept it in good condition.\nBut i want new one. And im selling this one cheep. \nGo go, contact me.', 'deu i mata', 120.000, '123123', NULL, NULL, '2013-01-29 08:58:09', NULL, 1, 0, NULL),
(197, 2, 2, 3, 0, 'asd', 'asd', 'asd', '', 0.000, '', NULL, NULL, '2013-01-29 09:44:22', NULL, 1, 0, NULL),
(198, 2, 1, 3, 0, 'asd', 'asd-1', 'asd', '', 0.000, '', NULL, NULL, '2013-01-29 09:46:08', NULL, 1, 0, NULL),
(199, 2, 2, 3, 0, 'asdfgsadwqe11232', 'asdfgsadwqe11232', 'asd', '', 0.000, '', NULL, NULL, '2013-01-29 09:46:36', NULL, 1, 0, NULL),
(200, 2, 1, 2, 0, 'image deletew', 'image-deletew', 'asd', '', 0.000, '', NULL, NULL, '2013-01-29 09:47:18', NULL, 1, 0, NULL),
(201, 2, 1, 1, 0, '123', '123', 'asd', '', 0.000, '', NULL, NULL, '2013-01-29 09:51:57', NULL, 1, 0, NULL),
(202, 2, 1, 1, 0, 'published', 'published', 'asd', '', 0.000, '', NULL, NULL, '2013-01-29 09:53:30', '2013-01-29 10:53:30', 50, 0, NULL),
(203, 2, 1, 1, 0, 'published', 'published-1', 'asd\n[u]asdasd[/u][ol][li][u]asd[/u][/li][li][u]asd[/u][/li][li][u]asd[/u][/li][li][u][b]asd[/b][/u][/li][/ol]', '', 0.000, '123', NULL, NULL, '2013-01-29 09:54:44', '2013-01-29 10:54:44', 1, 0, NULL),
(204, 2, 2, 2, 0, 'notpublished', 'notpublished', 'asd', '', 0.000, '', NULL, NULL, '2013-01-29 10:03:01', NULL, 0, 0, NULL),
(205, 2, 2, 2, 0, 'lalalaa', 'lalalaa', 'asd', '', 0.000, '123', NULL, NULL, '2013-01-29 10:04:16', NULL, 0, 0, NULL),
(208, 2, 4, 2, 0, 'asd', 'asd-2', '[ul][li][b]asdasdasd[/b][/li][/ul]\n[ul][li][b]asdasd[/b][/li][/ul]', 'adsa', 123.000, '123123', NULL, NULL, '2013-01-29 11:18:17', NULL, 1, 0, NULL),
(209, 2, 3, 3, 0, 'new123', 'new123', 'asd', '', 0.000, '', NULL, NULL, '2013-01-29 11:29:30', '2013-01-29 12:29:30', 1, 0, NULL),
(212, 2, 3, 2, 0, 'asdyusad', 'asdyusad', 'asd[b]asd[/b]', '', 0.000, '', NULL, NULL, '2013-01-30 11:06:15', '2013-01-30 12:06:15', 1, 0, NULL),
(213, 2, 1, 5, 0, 'belgrade', 'belgrade', 'asdasd', '', 0.000, '', NULL, NULL, '2013-02-04 09:48:52', '2013-02-04 10:48:52', 1, 0, NULL),
(214, 2, 1, 1, 0, 'send mail', 'send-mail', 'asd', '', 0.000, '', NULL, NULL, '2013-02-04 10:29:17', '2013-02-04 11:29:17', 1, 0, NULL),
(215, 2, 2, 2, 0, 'paypal', 'paypal', 'paypaltest', '', 0.000, '', NULL, NULL, '2013-02-07 08:36:13', NULL, 0, 0, NULL),
(216, 2, 2, 3, 0, 'asd', 'asd-3', 'asd', '', 0.000, '', NULL, NULL, '2013-02-07 08:39:15', NULL, 0, 0, NULL),
(217, 2, 4, 3, 0, 'asd', 'asd-4', 'asd', '', 0.000, '', NULL, NULL, '2013-02-07 08:41:13', NULL, 0, 0, NULL),
(218, 2, 1, 1, 0, 'asd', 'asd-5', 'asd', '', 0.000, '', NULL, NULL, '2013-02-07 08:41:38', NULL, 0, 0, NULL),
(219, 2, 5, 4, 0, 'asd', 'asd-6', 'asd', '', 0.000, '', NULL, NULL, '2013-02-07 08:42:19', NULL, 0, 0, NULL),
(229, 2, 2, 2, 0, 'asdyu', 'asdyu', 'das', '', 0.000, '', NULL, NULL, '2013-02-07 08:59:23', '2013-02-07 09:59:23', 1, 0, NULL),
(230, 2, 3, 4, 0, 'asdyu', 'asdyu-1', 'das', '', 0.000, '', NULL, NULL, '2013-02-07 09:00:20', '2013-03-06 12:06:42', 1, 0, '2013-03-10 09:52:34'),
(231, 2, 2, 3, 0, 'asdyu', 'asdyu-2', 'asd', '', 123.230, '', NULL, NULL, '2013-02-07 09:01:11', '2013-02-07 10:01:11', 50, 0, NULL),
(232, 2, 2, 2, 0, 'pay', 'pay', 'asd', '', 0.000, '', NULL, NULL, '2013-02-07 09:07:12', NULL, 0, 0, NULL),
(233, 2, 1, 2, 0, 'asdfgsadwqe', 'asdfgsadwqe', 'asd', '', 0.000, '', NULL, NULL, '2013-02-07 09:35:47', NULL, 0, 0, NULL),
(234, 2, 2, 2, 0, '123', '123-1', 'asdasd', '', 0.000, '', NULL, NULL, '2013-02-13 08:39:26', NULL, 0, 0, NULL),
(235, 2, 2, 4, 0, 'aasdzxc', 'aasdzxc', 'asdasd', '', 0.000, '', NULL, NULL, '2013-02-13 10:48:15', NULL, 0, 0, NULL),
(236, 2, 4, 3, 0, 'asdfgsadwqe1123', 'asdfgsadwqe1123', 'asd', '', 0.000, '', NULL, NULL, '2013-02-13 11:11:32', NULL, 0, 0, NULL),
(237, 2, 4, 3, 0, 'asdfgsadwqe', 'asdfgsadwqe-1', 'asd', '', 0.000, '', NULL, NULL, '2013-02-13 11:19:12', NULL, 0, 0, NULL),
(238, 2, 3, 3, 0, 'asd', 'asd-7', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:03:53', NULL, 0, 0, NULL),
(239, 2, 2, 2, 0, 'asd', 'asd-8', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:04:33', NULL, 0, 0, NULL),
(240, 2, 2, 3, 0, 'asdfgsadwqe1123', 'asdfgsadwqe1123-1', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:07:08', NULL, 0, 0, NULL),
(241, 2, 2, 3, 0, 'asdfgsadwqe', 'asdfgsadwqe-2', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:10:44', NULL, 0, 0, NULL),
(242, 2, 3, 2, 0, 'asdyu', 'asdyu-3', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:20:18', NULL, 0, 0, NULL),
(243, 2, 4, 3, 0, 'asd', 'asd-9', 'sad', '', 0.000, '', NULL, NULL, '2013-02-20 09:21:21', NULL, 0, 0, NULL),
(244, 2, 3, 2, 0, 'asdfgsadwqe', 'asdfgsadwqe-3', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:21:46', NULL, 0, 0, NULL),
(245, 2, 2, 2, 0, 'asdfgsadwqe', 'asdfgsadwqe-4', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:22:17', NULL, 0, 0, NULL),
(246, 2, 2, 4, 0, 'asdasd', 'asdasd', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:27:29', NULL, 0, 0, NULL),
(247, 2, 3, 5, 0, 'asdasd999', 'asdasd999', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:28:35', NULL, 0, 0, NULL),
(248, 2, 6, 2, 0, 'asdfgsadwqe', 'asdfgsadwqe-5', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:37:26', NULL, 0, 0, NULL),
(249, 2, 6, 3, 0, 'asd', 'asd-10', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:40:57', NULL, 0, 0, NULL),
(250, 2, 6, 4, 0, 'asd', 'asd-11', 'asd', '', 0.000, '', NULL, NULL, '2013-02-20 09:41:13', NULL, 0, 0, NULL),
(251, 2, 2, 2, 0, '123asdgfasd', '123asdgfasd', '123', '', 0.000, '', NULL, NULL, '2013-02-20 10:52:36', NULL, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oc_categories`
--

CREATE TABLE IF NOT EXISTS `oc_categories` (
  `id_category` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_category_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`) USING BTREE,
  UNIQUE KEY `categories_IK_seo_name` (`seoname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `oc_categories`
--

INSERT INTO `oc_categories` (`id_category`, `name`, `order`, `created`, `id_category_parent`, `parent_deep`, `seoname`, `description`, `price`) VALUES
(1, 'all', 0, '2013-02-20 09:06:26', 0, 0, 'all', 'all', 10),
(2, 'cat_car', 1, '2013-02-20 09:06:24', 1, 1, 'catcar', NULL, 20),
(3, 'cat_house', 2, '2013-02-20 09:06:25', 2, 2, 'cathouse', NULL, 30),
(4, 'mobile', 3, '2013-02-14 11:05:15', 2, 0, 'mobile', 'mobile', 20),
(5, 'hosting', 1, '2013-02-06 10:24:01', 0, 0, 'housing', 'hostings ', 0),
(6, 'bicing', 0, '2013-02-20 09:37:12', 5, 0, 'bicing', 'sad', 0);

-- --------------------------------------------------------

--
-- Table structure for table `oc_config`
--

CREATE TABLE IF NOT EXISTS `oc_config` (
  `group_name` varchar(128) NOT NULL,
  `config_key` varchar(128) NOT NULL,
  `config_value` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `oc_config`
--

INSERT INTO `oc_config` (`group_name`, `config_key`, `config_value`) VALUES
('email-settings', 'notify_email', 'testermail2105@gmail.com'),
('widget', 'list_of_all_widget', '{ "list_of_all":[{"name":"hello", "description":"HELLO WORLD WIDGET" }]}'),
('widget', 'sidebar_widget', '{\n	"sidebar" : \n	 [\n		{\n			\n		}\n]\n	\n}'),
('email-settings', 'smtp_active', 'TRUE'),
('widget', 'footer_widget', '{\n	"footer" : \n	 [\n		{\n			"name":"hello",\n			"path":"widgets/hello/hello",\n"class":"widgets/hello/hello_class",\n			"description":"Paragraf hello world"\n		},\n\n		{\n			"name":"hello",\n			"path":"widgets/hello/hello",\n"class":"widgets/hello/hello_class",\n			"description":"Paragraf hello world"\n		}\n]\n	\n}'),
('appearance', 'theme', 'default'),
('i18n', 'charset', 'utf-8'),
('init', 'base_url', '/'),
('i18n', 'timezone', 'Europe/Madrid'),
('i18n', 'locale', 'en_US'),
('cookie', 'salt', '13413mdksdf-948jd'),
('general', 'num_images', '4'),
('general', 'moderation', '2'),
('paypal', 'paypal_currency', 'USD'),
('general', 'site_name', 'openclassified'),
('paypal', 'sandbox', 'TRUE'),
('general', 'ID-pay_to_go_on_top', 'pay_to_go_on_top'),
('general', 'site_url', 'http://reoc.zz.mu'),
('formconfig', 'general-created', 'TRUE'),
('formconfig', 'general-description', 'TRUE'),
('formconfig', 'general-parent_deep', 'FALSE'),
('formconfig', 'category-seotitle', 'FALSE'),
('formconfig', 'category-id_category_parent', 'FALSE'),
('formconfig', 'category-price', 'FALSE'),
('formconfig', 'location-seoname', 'TRUE'),
('formconfig', 'location-lat', 'FALSE'),
('formconfig', 'location-lng', 'FALSE'),
('formconfig', 'user-id_location', 'FALSE'),
('formconfig', 'user-last_modified', 'FALSE'),
('formconfig', 'user-logins', 'FALSE'),
('formconfig', 'user-last_login', 'FALSE'),
('formconfig', 'user-last_ip', 'FALSE'),
('formconfig', 'user-user_agent', 'FALSE'),
('formconfig', 'user-token', 'FALSE'),
('formconfig', 'user-token_created', 'FALSE'),
('formconfig', 'user-token_expires', 'FALSE'),
('formconfig', 'role-date_created', 'FALSE'),
('formconfig', 'content-from_email', 'FALSE'),
('formconfig', 'captcha-captcha', 'FALSE'),
('formconfig', 'extrapayments-pay_to_go_on_top', 'TRUE'),
('formconfig', 'extrapayments-pay_to_go_on_feature', 'TRUE'),
('general', 'pay_to_go_on_feature', '10'),
('general', 'pay_to_go_on_top', '5'),
('general', 'global-currency', 'USD'),
('paypal', 'paypal_account', 'slobod_1360747823_biz@gmail.com'),
('general', 'ID-pay_to_go_on_feature', 'pay_to_go_on_feature'),
('general', 'featured_timer', '5'),
('general', 'advertisements_per_page', '5'),
('formconfig', 'advertisement-upload_file', 'TRUE'),
('general', 'paypal_msg_product_to_top', 'Go on Top'),
('general', 'paypal_msg_product_to_featured', 'Go to Featured'),
('general', 'paypal_msg_product_category', 'Pay to publish '),
('email-settings', 'smtp_host', 'smtp.gmail.com'),
('email-settings', 'smtp_port', '465'),
('email-settings', 'smtp_auth', 'TRUE'),
('email-settings', 'smtp_user', 'testermail2105@gmail.com'),
('email-settings', 'smtp_pass', 'frickshow');

-- --------------------------------------------------------

--
-- Table structure for table `oc_content`
--

CREATE TABLE IF NOT EXISTS `oc_content` (
  `id_content` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(145) NOT NULL,
  `seotitle` varchar(145) NOT NULL,
  `description` text,
  `from_email` varchar(145) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('page','email','help') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  UNIQUE KEY `as_content_UK_id_language_AND_seotitle` (`seotitle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `oc_content`
--

INSERT INTO `oc_content` (`id_content`, `order`, `title`, `seotitle`, `description`, `from_email`, `created`, `type`, `status`) VALUES
(2, 1, 'New advertisement', 'newadvertisement', 'This is reserved for sending mails to users that have created new advert.\n\nNOTE: Repalce this field with appropriate format.\nAnd in field "Form Email" add your own email.\n\nWARNING: If u change title or seotitle this mail wont work , so be sure to check "how to.txt" -- email configuration', 'root@slobodantumanitas-System', '2013-02-04 11:11:15', 'email', 1),
(3, 1, 'New User', 'newuser', 'This is reserved for sending mails to new users.\n\nNOTE: Repalce this field with appropriate format.\nIn field "Form Email" add your own email.\n\nWARNING: If you change title or seotitle this mail wont work , so be sure to check "how to.txt" -- email configuration', 'root@slobodantumanitas-System', '2013-02-04 12:01:55', 'email', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oc_locations`
--

CREATE TABLE IF NOT EXISTS `oc_locations` (
  `id_location` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `id_location_parent` int(10) unsigned NOT NULL DEFAULT '0',
  `parent_deep` int(2) unsigned NOT NULL DEFAULT '0',
  `seoname` varchar(145) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  PRIMARY KEY (`id_location`),
  UNIQUE KEY `categories_UK_seoname` (`seoname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `oc_locations`
--

INSERT INTO `oc_locations` (`id_location`, `name`, `id_location_parent`, `parent_deep`, `seoname`, `description`, `lat`, `lng`) VALUES
(1, 'all', 0, 0, 'all', 'root location', NULL, NULL),
(2, 'spain', 1, 0, 'spain', NULL, NULL, NULL),
(3, 'barcelona', 1, 1, 'barcelona', NULL, NULL, NULL),
(4, 'madrid', 1, 1, 'madrid', NULL, NULL, NULL),
(5, 'Belgrade', 2, 0, 'belgrade', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oc_orders`
--

CREATE TABLE IF NOT EXISTS `oc_orders` (
  `id_order` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `id_ad` int(10) unsigned DEFAULT NULL,
  `id_product` varchar(20) NOT NULL,
  `paymethod` varchar(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pay_date` datetime DEFAULT NULL,
  `currency` char(3) NOT NULL,
  `amount` decimal(14,3) NOT NULL DEFAULT '0.000',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_order`),
  KEY `orders_IK_id_user` (`id_user`),
  KEY `orders_IK_status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

--
-- Dumping data for table `oc_orders`
--

INSERT INTO `oc_orders` (`id_order`, `id_user`, `id_ad`, `id_product`, `paymethod`, `created`, `pay_date`, `currency`, `amount`, `status`) VALUES
(38, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 10:33:51', NULL, 'USD', 5.000, 0),
(39, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 10:34:23', NULL, 'USD', 5.000, 0),
(40, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 10:35:33', NULL, 'USD', 5.000, 0),
(41, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 10:35:50', NULL, 'USD', 5.000, 0),
(42, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 10:36:36', NULL, 'USD', 5.000, 0),
(43, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 10:39:17', NULL, 'USD', 5.000, 0),
(44, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 10:39:57', NULL, 'USD', 5.000, 0),
(45, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 10:45:24', NULL, 'USD', 5.000, 0),
(46, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:03:53', NULL, 'USD', 5.000, 0),
(47, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:04:27', NULL, 'USD', 5.000, 0),
(48, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:06:41', '2013-03-06 12:06:42', 'USD', 5.000, 1),
(49, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:20:19', NULL, 'USD', 5.000, 0),
(50, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:21:50', NULL, 'USD', 5.000, 0),
(51, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:22:20', NULL, 'USD', 5.000, 0),
(52, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:23:05', NULL, 'USD', 5.000, 0),
(53, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:23:25', NULL, 'USD', 5.000, 0),
(54, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:23:52', NULL, 'USD', 5.000, 0),
(55, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:24:33', NULL, 'USD', 5.000, 0),
(56, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 11:40:34', NULL, 'USD', 5.000, 0),
(57, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 20:33:43', NULL, 'USD', 5.000, 0),
(58, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 20:37:46', NULL, 'USD', 5.000, 0),
(59, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 20:40:58', NULL, 'USD', 5.000, 0),
(60, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 20:41:18', NULL, 'USD', 5.000, 0),
(61, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 20:42:12', NULL, 'USD', 5.000, 0),
(62, 2, 230, 'pay_to_go_on_top', 'paypal', '2013-03-06 20:43:30', NULL, 'USD', 5.000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `oc_roles`
--

CREATE TABLE IF NOT EXISTS `oc_roles` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(245) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `oc_roles_UK_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `oc_roles`
--

INSERT INTO `oc_roles` (`id_role`, `name`, `description`, `date_created`) VALUES
(1, 'user', 'Normal user', '2012-12-14 10:40:02'),
(10, 'Administrator', 'Access to everything', '2012-12-14 10:40:02');

-- --------------------------------------------------------

--
-- Table structure for table `oc_users`
--

CREATE TABLE IF NOT EXISTS `oc_users` (
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
  `logins` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `last_ip` float DEFAULT NULL,
  `user_agent` varchar(40) DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `token_created` datetime DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `users_UK_email` (`email`),
  UNIQUE KEY `users_UK_token` (`token`),
  UNIQUE KEY `users_UK_seoname` (`seoname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `oc_users`
--

INSERT INTO `oc_users` (`id_user`, `name`, `seoname`, `email`, `password`, `status`, `id_role`, `id_location`, `created`, `last_modified`, `logins`, `last_login`, `last_ip`, `user_agent`, `token`, `token_created`, `token_expires`) VALUES
(2, 'slobodan', NULL, 'slobodan.josifovic@gmail.com', '15ecfab1f55bea08e836dc0a393cc267969e9b35e4468aa5c90e2f22bd5d44fd', 1, 10, NULL, '2012-12-14 10:48:26', NULL, 92, '2013-03-12 09:32:37', 2130710000, '19b62e2f697adc030c6dcc59146d5b002dadf041', '28436ee8d782958bcf8c2ab8489a7fcba78e7b87', '2013-03-12 09:32:37', '2013-06-10 10:32:37'),
(26, 'andrey', NULL, 'andrey@gmail.com', '15ecfab1f55bea08e836dc0a393cc267969e9b35e4468aa5c90e2f22bd5d44fd', 1, 1, NULL, '2013-01-17 11:57:57', NULL, 7, '2013-02-01 10:11:53', 2130710000, '64bf8ec195ca70e7458e2ea8aa7f6330be09ae1f', '31ea07c9136a79d691ed0aa2165e0c172bb54ffd', '2013-02-01 10:11:58', '2013-05-02 11:11:58'),
(27, 'bla', 'bla', 'bla', '15ecfab1f55bea08e836dc0a393cc267969e9b35e4468aa5c90e2f22bd5d44fd', 1, 1, 2, '2013-02-06 12:47:31', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `oc_visits`
--

CREATE TABLE IF NOT EXISTS `oc_visits` (
  `id_visit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_ad` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` float DEFAULT NULL,
  PRIMARY KEY (`id_visit`),
  KEY `visits_IK_id_user` (`id_user`),
  KEY `visits_IK_id_ad` (`id_ad`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=258 ;

--
-- Dumping data for table `oc_visits`
--

INSERT INTO `oc_visits` (`id_visit`, `id_ad`, `id_user`, `created`, `ip_address`) VALUES
(102, 32, 2, '2013-01-07 11:28:15', NULL),
(101, 33, 2, '2013-01-07 11:28:15', NULL),
(100, 34, 2, '2013-01-07 11:28:15', NULL),
(99, 34, 2, '2013-01-07 11:24:58', NULL),
(98, 34, 2, '2013-01-07 11:24:56', NULL),
(97, 34, 2, '2013-01-07 11:24:55', NULL),
(96, 34, 2, '2013-01-07 11:24:54', NULL),
(95, 34, 2, '2013-01-07 11:24:52', NULL),
(94, 34, 2, '2013-01-07 11:24:13', NULL),
(93, 34, 2, '2013-01-07 11:24:13', NULL),
(92, 34, 2, '2013-01-07 11:24:10', NULL),
(91, 34, 2, '2013-01-07 11:24:10', NULL),
(90, 34, 2, '2013-01-07 11:22:31', NULL),
(89, 34, 2, '2013-01-07 11:22:31', NULL),
(88, 34, 2, '2013-01-07 11:22:27', NULL),
(87, 34, 2, '2013-01-07 11:22:27', NULL),
(86, 34, 2, '2013-01-07 11:22:06', NULL),
(85, 34, 2, '2013-01-07 11:22:06', NULL),
(84, 34, 2, '2013-01-07 11:20:22', NULL),
(83, 30, 2, '2013-01-07 11:19:38', NULL),
(82, 31, 2, '2013-01-07 11:19:38', NULL),
(81, 32, 2, '2013-01-07 11:19:38', NULL),
(80, 33, 2, '2013-01-07 11:19:38', NULL),
(79, 34, 2, '2013-01-07 11:19:38', NULL),
(78, 30, 2, '2013-01-07 11:01:14', NULL),
(77, 31, 2, '2013-01-07 11:01:14', NULL),
(76, 32, 2, '2013-01-07 11:01:14', NULL),
(75, 33, 2, '2013-01-07 11:01:14', NULL),
(74, 34, 2, '2013-01-07 11:01:14', NULL),
(73, 30, 2, '2013-01-07 10:59:49', NULL),
(72, 31, 2, '2013-01-07 10:59:49', NULL),
(71, 32, 2, '2013-01-07 10:59:49', NULL),
(70, 33, 2, '2013-01-07 10:59:49', NULL),
(69, 34, 2, '2013-01-07 10:59:49', NULL),
(68, 30, 2, '2013-01-07 10:54:54', NULL),
(67, 31, 2, '2013-01-07 10:54:54', NULL),
(66, 32, 2, '2013-01-07 10:54:54', NULL),
(65, 33, 2, '2013-01-07 10:54:54', NULL),
(64, 34, 2, '2013-01-07 10:54:54', NULL),
(63, 31, 2, '2013-01-07 10:53:06', NULL),
(62, 31, 2, '2013-01-07 10:49:29', NULL),
(61, 31, 2, '2013-01-07 10:49:06', NULL),
(60, 31, 2, '2013-01-07 10:48:41', NULL),
(59, 31, 2, '2013-01-07 10:48:33', NULL),
(58, 31, 2, '2013-01-07 10:45:12', NULL),
(57, 34, 2, '2013-01-07 10:45:09', NULL),
(56, 31, 2, '2013-01-07 10:45:07', NULL),
(55, 34, 2, '2013-01-07 10:45:04', NULL),
(54, 34, 2, '2013-01-07 10:45:02', NULL),
(53, 32, 2, '2013-01-07 10:44:53', NULL),
(104, 30, 2, '2013-01-07 11:28:15', NULL),
(103, 31, 2, '2013-01-07 11:28:15', NULL),
(105, 34, 2, '2013-01-07 11:29:01', NULL),
(106, 33, 2, '2013-01-07 11:29:01', NULL),
(107, 32, 2, '2013-01-07 11:29:01', NULL),
(108, 31, 2, '2013-01-07 11:29:01', NULL),
(109, 30, 2, '2013-01-07 11:29:01', NULL),
(110, 34, 2, '2013-01-07 11:29:24', NULL),
(111, 33, 2, '2013-01-07 11:29:24', NULL),
(112, 32, 2, '2013-01-07 11:29:24', NULL),
(113, 31, 2, '2013-01-07 11:29:24', NULL),
(114, 30, 2, '2013-01-07 11:29:24', NULL),
(115, 34, 2, '2013-01-07 11:29:26', NULL),
(116, 33, 2, '2013-01-07 11:29:32', NULL),
(117, 33, 2, '2013-01-07 11:29:39', NULL),
(118, 32, 2, '2013-01-07 11:29:42', NULL),
(119, 32, 2, '2013-01-07 11:29:46', NULL),
(120, 34, 2, '2013-01-07 11:34:53', NULL),
(121, 33, 2, '2013-01-07 11:34:53', NULL),
(122, 32, 2, '2013-01-07 11:34:53', NULL),
(123, 31, 2, '2013-01-07 11:34:53', NULL),
(124, 30, 2, '2013-01-07 11:34:53', NULL),
(125, 34, 2, '2013-01-07 11:34:55', NULL),
(126, 33, 2, '2013-01-07 11:34:55', NULL),
(127, 32, 2, '2013-01-07 11:34:55', NULL),
(128, 31, 2, '2013-01-07 11:34:55', NULL),
(129, 30, 2, '2013-01-07 11:34:55', NULL),
(130, 34, 2, '2013-01-07 11:35:06', NULL),
(131, 33, 2, '2013-01-07 11:35:06', NULL),
(132, 32, 2, '2013-01-07 11:35:06', NULL),
(133, 31, 2, '2013-01-07 11:35:06', NULL),
(134, 30, 2, '2013-01-07 11:35:06', NULL),
(135, 34, 2, '2013-01-07 11:42:21', NULL),
(136, 34, 2, '2013-01-07 11:43:49', NULL),
(137, 34, 2, '2013-01-07 11:56:42', NULL),
(138, 34, 2, '2013-01-07 11:57:02', NULL),
(139, 34, 2, '2013-01-07 11:57:42', NULL),
(140, 34, 2, '2013-01-07 11:57:44', NULL),
(141, 34, 2, '2013-01-07 11:58:02', NULL),
(142, 34, 2, '2013-01-08 08:34:05', NULL),
(143, 33, 2, '2013-01-08 08:34:05', NULL),
(144, 32, 2, '2013-01-08 08:34:05', NULL),
(145, 31, 2, '2013-01-08 08:34:05', NULL),
(146, 30, 2, '2013-01-08 08:34:05', NULL),
(147, 34, 2, '2013-01-08 08:35:35', NULL),
(148, 33, 2, '2013-01-08 08:35:35', NULL),
(149, 32, 2, '2013-01-08 08:35:35', NULL),
(150, 31, 2, '2013-01-08 08:35:35', NULL),
(151, 30, 2, '2013-01-08 08:35:35', NULL),
(152, 34, 2, '2013-01-08 08:36:42', NULL),
(153, 33, 2, '2013-01-08 08:36:42', NULL),
(154, 32, 2, '2013-01-08 08:36:42', NULL),
(155, 31, 2, '2013-01-08 08:36:42', NULL),
(156, 30, 2, '2013-01-08 08:36:42', NULL),
(157, 34, 2, '2013-01-08 08:45:10', NULL),
(158, 33, 2, '2013-01-08 08:45:10', NULL),
(159, 32, 2, '2013-01-08 08:45:10', NULL),
(160, 31, 2, '2013-01-08 08:45:10', NULL),
(161, 30, 2, '2013-01-08 08:45:10', NULL),
(162, 34, 2, '2013-01-08 08:45:57', NULL),
(163, 33, 2, '2013-01-08 08:45:57', NULL),
(164, 32, 2, '2013-01-08 08:45:57', NULL),
(165, 31, 2, '2013-01-08 08:45:57', NULL),
(166, 30, 2, '2013-01-08 08:45:57', NULL),
(167, 34, 2, '2013-01-08 08:46:06', NULL),
(168, 33, 2, '2013-01-08 08:46:06', NULL),
(169, 32, 2, '2013-01-08 08:46:06', NULL),
(170, 31, 2, '2013-01-08 08:46:06', NULL),
(171, 30, 2, '2013-01-08 08:46:06', NULL),
(172, 34, 2, '2013-01-08 08:46:34', NULL),
(173, 33, 2, '2013-01-08 08:46:34', NULL),
(174, 32, 2, '2013-01-08 08:46:34', NULL),
(175, 31, 2, '2013-01-08 08:46:34', NULL),
(176, 30, 2, '2013-01-08 08:46:34', NULL),
(177, 34, 2, '2013-01-08 08:47:00', NULL),
(178, 33, 2, '2013-01-08 08:47:00', NULL),
(179, 32, 2, '2013-01-08 08:47:00', NULL),
(180, 31, 2, '2013-01-08 08:47:00', NULL),
(181, 30, 2, '2013-01-08 08:47:00', NULL),
(182, 34, 2, '2013-01-08 08:47:10', NULL),
(183, 33, 2, '2013-01-08 08:47:10', NULL),
(184, 32, 2, '2013-01-08 08:47:10', NULL),
(185, 31, 2, '2013-01-08 08:47:10', NULL),
(186, 30, 2, '2013-01-08 08:47:10', NULL),
(187, 34, 2, '2013-01-08 09:54:01', NULL),
(188, 33, 2, '2013-01-08 09:54:08', NULL),
(189, 33, 2, '2013-01-08 09:54:12', NULL),
(190, 33, 2, '2013-01-08 09:54:15', NULL),
(191, 4, 1, '2013-01-08 10:03:26', NULL),
(192, 38, 2, '2013-01-08 10:29:33', NULL),
(193, 46, 2, '2013-01-08 11:13:55', NULL),
(194, 43, 2, '2013-01-09 10:23:58', NULL),
(195, 3, 2, '2013-01-09 10:25:58', NULL),
(196, 43, 2, '2013-01-15 08:25:50', NULL),
(197, 46, 2, '2013-01-15 09:15:01', NULL),
(198, 46, 2, '2013-01-15 09:19:45', NULL),
(199, 46, 2, '2013-01-15 10:02:18', NULL),
(200, 46, 2, '2013-01-15 10:09:29', NULL),
(201, 72, 2, '2013-01-17 10:52:40', NULL),
(202, 74, 25, '2013-01-17 10:54:24', NULL),
(203, 75, 2, '2013-01-17 11:23:25', NULL),
(204, 75, 2, '2013-01-17 11:53:00', NULL),
(205, 74, 25, '2013-01-18 10:44:04', NULL),
(206, 74, 25, '2013-01-18 10:52:59', NULL),
(207, 84, 2, '2013-01-21 08:18:24', NULL),
(208, 98, 2, '2013-01-21 09:23:43', NULL),
(209, 120, 2, '2013-01-22 08:06:05', NULL),
(210, 129, 2, '2013-01-23 08:13:54', NULL),
(211, 130, 2, '2013-01-23 08:17:09', NULL),
(212, 155, 2, '2013-01-23 09:13:09', NULL),
(213, 157, 27, '2013-01-23 10:10:34', NULL),
(214, 157, 27, '2013-01-23 10:10:55', NULL),
(215, 157, 27, '2013-01-23 10:16:59', NULL),
(216, 157, 27, '2013-01-23 10:17:47', NULL),
(217, 157, 27, '2013-01-23 10:20:28', NULL),
(218, 157, 27, '2013-01-23 10:31:37', NULL),
(219, 157, 27, '2013-01-23 12:01:02', NULL),
(220, 157, 27, '2013-01-23 12:01:25', NULL),
(221, 157, 27, '2013-01-23 12:02:29', NULL),
(222, 195, 2, '2013-01-29 08:09:31', NULL),
(223, 195, 2, '2013-01-29 08:11:07', NULL),
(224, 208, 2, '2013-01-29 11:20:28', NULL),
(225, 210, 2, '2013-01-30 08:59:01', NULL),
(226, 210, 2, '2013-01-30 09:02:28', NULL),
(227, 210, 2, '2013-01-30 09:05:26', NULL),
(228, 210, 2, '2013-01-30 09:05:39', NULL),
(229, 210, 2, '2013-01-30 09:06:00', NULL),
(230, 209, 2, '2013-01-30 09:07:08', NULL),
(231, 209, 2, '2013-01-30 09:07:14', NULL),
(232, 209, 2, '2013-01-30 09:07:41', NULL),
(233, 209, 2, '2013-01-30 09:08:11', NULL),
(234, 209, 2, '2013-01-30 09:08:28', NULL),
(235, 209, 2, '2013-01-30 09:08:31', NULL),
(236, 209, 2, '2013-01-30 09:08:35', NULL),
(237, 209, 2, '2013-01-30 09:08:45', NULL),
(238, 209, 2, '2013-01-30 09:12:42', NULL),
(239, 203, 2, '2013-01-30 09:17:03', NULL),
(240, 209, 2, '2013-01-30 09:17:28', NULL),
(241, 230, 2, '2013-02-27 12:06:49', NULL),
(242, 230, 2, '2013-02-27 12:06:57', NULL),
(243, 230, 2, '2013-02-27 12:07:33', NULL),
(244, 230, 2, '2013-02-27 12:10:07', NULL),
(245, 230, 2, '2013-02-27 12:10:11', NULL),
(246, 230, 2, '2013-02-27 12:10:37', NULL),
(247, 230, 2, '2013-02-27 12:10:41', NULL),
(248, 230, 2, '2013-02-27 12:12:31', NULL),
(249, 230, 2, '2013-02-27 12:13:37', NULL),
(250, 230, 2, '2013-02-27 12:15:34', NULL),
(251, 230, 2, '2013-02-27 12:15:37', NULL),
(252, 230, 2, '2013-02-27 12:16:11', NULL),
(253, 230, 2, '2013-02-27 12:16:15', NULL),
(254, 230, 2, '2013-02-27 12:16:39', NULL),
(255, 230, 2, '2013-02-27 12:19:06', NULL),
(256, 230, 2, '2013-02-27 12:20:11', NULL),
(257, 230, 2, '2013-02-27 12:20:36', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `oc_ads`
--
ALTER TABLE `oc_ads`
  ADD CONSTRAINT `ads_FK_id_category_AT_categories` FOREIGN KEY (`id_category`) REFERENCES `oc_categories` (`id_category`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ads_FK_id_user_AT_users` FOREIGN KEY (`id_user`) REFERENCES `oc_users` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
