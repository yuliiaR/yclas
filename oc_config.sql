-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 27, 2013 at 12:20 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.6-1ubuntu1.1

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
('appearance', 'theme', 'default'),
('i18n', 'charset', 'utf-8'),
('init', 'base_url', '/'),
('i18n', 'timezone', 'Europe/Madrid'),
('i18n', 'locale', 'en_US'),
('cookie', 'salt', '13413mdksdf-948jd'),
('general', 'num_images', '4'),
('general', 'moderation', '2'),
('paypal', 'paypal_currency', 'USD'),
('general', 'site_name', 'openclassifieds'),
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
('general', 'pay_to_go_on_feature', '40'),
('general', 'pay_to_go_on_top', '30'),
('general', 'global-currency', 'USD'),
('paypal', 'paypal_account', 'slobod_1360747823_biz@gmail.com'),
('general', 'ID-pay_to_go_on_feature', 'pay_to_go_on_feature'),
('general', 'featured_timer', '10'),
('general', 'advertisements_per_page', '20'),
('formconfig', 'advertisement-upload_file', 'TRUE');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
