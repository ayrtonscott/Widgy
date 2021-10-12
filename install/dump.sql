CREATE TABLE `users` (
`user_id` int(11) NOT NULL AUTO_INCREMENT,
`email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
`password` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
`name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
`billing` text COLLATE utf8mb4_unicode_ci,
`api_key` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`token_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`twofa_secret` varchar(16) DEFAULT NULL,
`one_time_login_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`pending_email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`email_activation_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`lost_password_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`facebook_id` bigint(20) DEFAULT NULL,
`type` int(11) NOT NULL DEFAULT '0',
`active` int(11) NOT NULL DEFAULT '0',
`plan_id` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`plan_expiration_date` datetime DEFAULT NULL,
`plan_settings` text COLLATE utf8mb4_unicode_ci,
`plan_trial_done` tinyint(4) DEFAULT '0',
`plan_expiry_reminder` tinyint(4) DEFAULT '0',
`payment_subscription_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`referral_key` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`referred_by` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`referred_by_has_converted` tinyint(4) DEFAULT '0',
`current_month_notifications_impressions` int(11) DEFAULT '0',
`total_notifications_impressions` int(11) DEFAULT '0',
`language` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'english',
`timezone` varchar(32) DEFAULT 'UTC',
`date` datetime DEFAULT NULL,
`ip` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`country` varchar(32) DEFAULT NULL,
`last_activity` datetime DEFAULT NULL,
`last_user_agent` text COLLATE utf8mb4_unicode_ci,
`total_logins` int(11) DEFAULT '0',
PRIMARY KEY (`user_id`),
KEY `plan_id` (`plan_id`),
KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `users` (`user_id`, `email`, `password`, `api_key`, `referral_key`, `name`, `token_code`, `email_activation_code`, `lost_password_code`, `facebook_id`, `type`, `active`, `plan_id`, `plan_expiration_date`, `plan_settings`, `plan_trial_done`, `payment_subscription_id`, `current_month_notifications_impressions`, `total_notifications_impressions`, `date`, `ip`, `last_activity`, `last_user_agent`, `total_logins`)
VALUES (1,'admin','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', md5(rand()), md5(rand()), 'AltumCode','','','',NULL,1,1,'custom','2030-07-15 11:26:19','{"no_ads":true,"removable_branding":true,"custom_branding":true,"api_is_enabled":true,"affiliate_is_enabled":true,"campaigns_limit":-1,"notifications_limit":-1,"notifications_impressions_limit":-1,"enabled_notifications":{"INFORMATIONAL":true,"COUPON":true,"LIVE_COUNTER":true,"EMAIL_COLLECTOR":true,"LATEST_CONVERSION":true,"CONVERSIONS_COUNTER":true,"VIDEO":true,"SOCIAL_SHARE":true,"RANDOM_REVIEW":true,"EMOJI_FEEDBACK":true,"COOKIE_NOTIFICATION":true,"SCORE_FEEDBACK":true,"REQUEST_COLLECTOR":true,"COUNTDOWN_COLLECTOR":true,"INFORMATIONAL_BAR":true,"IMAGE":true,"COLLECTOR_BAR":true,"COUPON_BAR":true,"BUTTON_BAR":true,"COLLECTOR_MODAL":true,"COLLECTOR_TWO_MODAL":true,"BUTTON_MODAL":true,"TEXT_FEEDBACK":true,"ENGAGEMENT_LINKS":true}}',1,'',0,0,NOW(),'',NOW(),'',0);

-- SEPARATOR --

CREATE TABLE `campaigns` (
`campaign_id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`pixel_key` varchar(32) DEFAULT NULL,
`name` varchar(256) NOT NULL DEFAULT '',
`domain` varchar(256) NOT NULL DEFAULT '',
`include_subdomains` int(11) DEFAULT '0',
`branding` text,
`is_enabled` tinyint(4) NOT NULL DEFAULT '1',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`campaign_id`),
KEY `user_id` (`user_id`),
KEY `campaigns_domain_index` (`domain`),
KEY `campaigns_pixel_key_index` (`pixel_key`),
CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `notifications` (
`notification_id` int(11) NOT NULL AUTO_INCREMENT,
`campaign_id` int(11) NOT NULL,
`user_id` int(11) NOT NULL,
`name` varchar(256) NOT NULL DEFAULT '',
`type` varchar(64) NOT NULL DEFAULT '',
`settings` text NOT NULL,
`last_action_date` datetime DEFAULT NULL COMMENT 'action ex: conversion',
`notification_key` varchar(32) NOT NULL DEFAULT '' COMMENT 'Used for identifying webhooks',
`is_enabled` tinyint(4) NOT NULL DEFAULT '0',
`last_datetime` datetime DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`notification_id`),
KEY `campaign_id` (`campaign_id`),
KEY `user_id` (`user_id`),
CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- SEPARATOR --

CREATE TABLE `plans` (
`plan_id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(256) NOT NULL DEFAULT '',
`description` varchar(256) NOT NULL DEFAULT '',
`monthly_price` float NULL,
`annual_price` float NULL,
`lifetime_price` float NULL,
`trial_days` int unsigned NOT NULL DEFAULT '0',
`settings` text NOT NULL,
`taxes_ids` text,
`color` varchar(16) DEFAULT NULL,
`status` tinyint(4) NOT NULL,
`order` int(10) unsigned DEFAULT '0',
`date` datetime NOT NULL,
PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `pages_categories` (
`pages_category_id` int(11) NOT NULL AUTO_INCREMENT,
`url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT '',
`icon` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`order` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`pages_category_id`),
KEY `url` (`url`)
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `pages` (
`page_id` int(11) NOT NULL AUTO_INCREMENT,
`pages_category_id` int(11) DEFAULT NULL,
`url` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
`title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`content` longtext COLLATE utf8mb4_unicode_ci,
`type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT '',
`position` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`order` int(11) DEFAULT '0',
`total_views` int(11) DEFAULT '0',
`date` datetime DEFAULT NULL,
`last_date` datetime DEFAULT NULL,
PRIMARY KEY (`page_id`),
KEY `pages_pages_category_id_index` (`pages_category_id`),
KEY `pages_url_index` (`url`),
CONSTRAINT `pages_pages_categories_pages_category_id_fk` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `pages` (`pages_category_id`, `url`, `title`, `description`, `content`, `type`, `position`, `order`, `total_views`, `date`, `last_date`) VALUES
(NULL, 'https://altumcode.com/', 'Software by AltumCode', '', '', 'external', 'bottom', 1, 0, NOW(), NOW()),
(NULL, 'https://altumco.de/socialproofo-buy', 'Built with SocialProofo', '', '', 'external', 'bottom', 0, 0, NOW(), NOW());

-- SEPARATOR --

CREATE TABLE `track_conversions` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`notification_id` int(11) NOT NULL,
`type` varchar(32) NOT NULL DEFAULT '',
`data` longtext NOT NULL,
`url` varchar(2048) CHARACTER SET utf8mb4 DEFAULT NULL,
`location` varchar(512) DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `notification_id` (`notification_id`),
KEY `track_conversions_date_index` (`datetime`),
CONSTRAINT `track_conversions_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`notification_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `track_logs` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`domain` varchar(256) CHARACTER SET utf8mb4 NOT NULL,
`url` varchar(2048) CHARACTER SET utf8mb4 NOT NULL,
`ip_binary` varbinary(16) DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`),
KEY `domain` (`domain`),
KEY `track_logs_ip_binary_index` (`ip_binary`),
CONSTRAINT `track_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `track_notifications` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`campaign_id` int(11) DEFAULT NULL,
`notification_id` int(11) NOT NULL,
`type` varchar(32) NOT NULL DEFAULT '',
`url` varchar(2048) CHARACTER SET utf8mb4 NOT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `notification_id` (`notification_id`),
KEY `track_notifications_date_index` (`datetime`),
KEY `track_notifications_campaign_id_index` (`campaign_id`),
CONSTRAINT `track_notifications_campaigns_campaign_id_fk` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `track_notifications_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`notification_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `settings` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`key` varchar(64) NOT NULL DEFAULT '',
`value` longtext NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --
SET @cron_key = MD5(RAND());
-- SEPARATOR --
SET @cron_reset_date = NOW();

-- SEPARATOR --
INSERT INTO `settings` (`key`, `value`)
VALUES
('main', '{"se_indexing":true}'),
('ads', '{\"header\":\"\",\"footer\":\"\"}'),
('captcha', '{"type":"basic","recaptcha_public_key":"","recaptcha_private_key":"","login_is_enabled":0,"register_is_enabled":0,"lost_password_is_enabled":0,"resend_activation_is_enabled":0}'),
('cron', concat('{\"key\":\"', @cron_key, '\",\"reset_date\":\"', @cron_reset_date, '\"}')),
('default_language', 'english'),
('default_theme_style', 'light'),
('email_confirmation', '0'),
('register_is_enabled', '1'),
('email_notifications', '{\"emails\":\"\",\"new_user\":\"0\",\"new_payment\":\"0\"}'),
('facebook', '{\"is_enabled\":\"0\",\"app_id\":\"\",\"app_secret\":\"\"}'),
('google', '{\"is_enabled\":\"0\",\"client_id\":\"\",\"client_secret\":\"\"}'),
('twitter', '{\"is_enabled\":\"0\",\"consumer_api_key\":\"\",\"consumer_api_secret\":\"\"}'),
('favicon', ''),
('logo', ''),
('opengraph', ''),
('plan_custom', '{\"plan_id\":\"custom\",\"name\":\"Custom\",\"status\":1}'),
('plan_free', '{"plan_id":"free","name":"Free","days":null,"status":1,"settings":{"no_ads":false,"removable_branding":false,"custom_branding":false,"api_is_enabled":true,"affiliate_is_enabled":false,"campaigns_limit":5,"notifications_limit":25,"notifications_impressions_limit":100000,"enabled_notifications":{"INFORMATIONAL":true,"COUPON":true,"LIVE_COUNTER":true,"EMAIL_COLLECTOR":true,"LATEST_CONVERSION":true,"CONVERSIONS_COUNTER":true,"VIDEO":true,"SOCIAL_SHARE":true,"RANDOM_REVIEW":true,"EMOJI_FEEDBACK":true,"COOKIE_NOTIFICATION":true,"SCORE_FEEDBACK":true,"REQUEST_COLLECTOR":true,"COUNTDOWN_COLLECTOR":true,"INFORMATIONAL_BAR":true,"IMAGE":true,"COLLECTOR_BAR":true,"COUPON_BAR":true,"BUTTON_BAR":true,"COLLECTOR_MODAL":true,"COLLECTOR_TWO_MODAL":true,"BUTTON_MODAL":true,"TEXT_FEEDBACK":true,"ENGAGEMENT_LINKS":true}}}'),
('payment', '{\"is_enabled\":false,\"brand_name\":\"SocialProof\",\"currency\":\"USD\"}'),
('paypal', '{\"is_enabled\":\"0\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
('stripe', '{\"is_enabled\":\"0\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
('offline_payment', '{\"is_enabled\":\"0\",\"instructions\":\"Your offline payment instructions go here..\"}'),
('coinbase', '{\"is_enabled\":\"0\"}'),
('smtp', '{\"host\":\"\",\"from\":\"\",\"from_name\":\"\",\"encryption\":\"tls\",\"port\":\"587\",\"auth\":\"0\",\"username\":\"\",\"password\":\"\"}'),
('custom', '{\"head_js\":\"\",\"head_css\":\"\"}'),
('socials', '{\"facebook\":\"\",\"instagram\":\"\",\"twitter\":\"\",\"youtube\":\"\"}'),
('default_timezone', 'UTC'),
('title', 'SocialProof'),
('privacy_policy_url', ''),
('terms_and_conditions_url', ''),
('index_url', ''),
('announcements', '{"id":"","content":"","show_logged_in":"","show_logged_out":""}'),
('business', '{\"invoice_is_enabled\":\"0\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
('webhooks', '{"user_new": "", "user_delete": ""}'),
('socialproofo', '{"branding":"by SocialProofo", \"analytics_is_enabled\": true, \"pixel_cache\": 0}'),
('license', '{\"license\":\"\",\"type\":\"\"}'),
('product_info', '{\"version\":\"8.0.0\", \"code\":\"800\"}');

-- SEPARATOR --

CREATE TABLE `users_logs` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`user_id` int(11) DEFAULT NULL,
`type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`ip` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`device_type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`os_name` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`country_code` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`id`),
KEY `users_logs_user_id` (`user_id`),
CONSTRAINT `users_logs_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
