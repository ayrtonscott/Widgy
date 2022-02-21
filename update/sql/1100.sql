UPDATE `settings` SET `value` = '{\"version\":\"11.0.0\", \"code\":\"1100\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('users', '{}');

-- SEPARATOR --

SET @new_users_settings = JSON_OBJECT(
	'email_confirmation', (SELECT `value` FROM `settings` WHERE `key` = 'email_confirmation'),
	'register_is_enabled', (SELECT `value` FROM `settings` WHERE `key` = 'register_is_enabled'),
	'auto_delete_inactive_users', (SELECT JSON_EXTRACT(`value`, '$.auto_delete_inactive_users') FROM `settings` WHERE `key` = 'main'),
	'user_deletion_reminder', (SELECT JSON_EXTRACT(`value`, '$.user_deletion_reminder') FROM `settings` WHERE `key` = 'main')
);

-- SEPARATOR --

UPDATE `settings` SET `value` = @new_users_settings WHERE `key` = 'users';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'email_confirmation';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'register_is_enabled';

-- SEPARATOR --

SET @new_users_settings = JSON_OBJECT(
	'title', (SELECT `value` FROM `settings` WHERE `key` = 'title'),
	'default_language', (SELECT `value` FROM `settings` WHERE `key` = 'default_language'),
	'default_timezone', (SELECT `value` FROM `settings` WHERE `key` = 'default_timezone'),
	'default_theme_style', (SELECT `value` FROM `settings` WHERE `key` = 'default_theme_style'),
	'index_url', (SELECT `value` FROM `settings` WHERE `key` = 'index_url'),
	'terms_and_conditions_url', (SELECT `value` FROM `settings` WHERE `key` = 'terms_and_conditions_url'),
	'privacy_policy_url', (SELECT `value` FROM `settings` WHERE `key` = 'privacy_policy_url'),
	'not_found_url', '',
	'se_indexing', true
);

-- SEPARATOR --

UPDATE `settings` SET `value` = @new_users_settings WHERE `key` = 'main';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'title';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'default_language';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'default_timezone';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'default_theme_style';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'index_url';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'terms_and_conditions_url';

-- SEPARATOR --

DELETE FROM `settings` WHERE `key` = 'privacy_policy_url';

-- SEPARATOR --

alter table pages add editor varchar(16) null after description;

