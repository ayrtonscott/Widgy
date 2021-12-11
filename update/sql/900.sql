UPDATE `settings` SET `value` = '{\"version\":\"9.0.0\", \"code\":\"900\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table plans add codes_ids text null after taxes_ids;

-- SEPARATOR --

update plans set codes_ids = '[]';

-- SEPARATOR --

alter table plans change date datetime datetime not null;

-- SEPARATOR --

alter table users change date datetime datetime null;

-- SEPARATOR --

alter table pages change date datetime datetime null;

-- SEPARATOR --

alter table pages change last_date last_datetime datetime null;

-- SEPARATOR --

alter table users add payment_processor varchar(16) null after payment_subscription_id;

-- SEPARATOR --

update users set payment_processor = SUBSTRING_INDEX(`payment_subscription_id`, '###', 1) where payment_subscription_id <> '';

-- SEPARATOR --

update users set `payment_subscription_id` = SUBSTRING_INDEX(`payment_subscription_id`, '###', -1);

-- SEPARATOR --

alter table users modify email varchar(320) collate utf8_unicode_ci not null;

-- SEPARATOR --

alter table users modify name varchar(64) not null;

-- SEPARATOR --

alter table users add payment_total_amount float null after payment_processor;

-- SEPARATOR --

alter table users add payment_currency varchar(4) null after payment_total_amount;

-- SEPARATOR --

alter table users drop column facebook_id;

-- SEPARATOR --

alter table users modify type tinyint default 0 not null;

-- SEPARATOR --

alter table users change active status tinyint default 0 not null;

-- SEPARATOR --

alter table users modify last_user_agent varchar(256) collate utf8_unicode_ci null;

-- EXTENDED SEPARATOR --

alter table taxes drop column internal_name;

-- SEPARATOR --

alter table codes add name varchar(64) null after code_id;

-- SEPARATOR --

alter table codes change date datetime datetime not null;

-- SEPARATOR --

alter table codes drop foreign key codes_ibfk_1;

-- SEPARATOR --

alter table codes drop column plan_id;

-- SEPARATOR --

alter table payments add business text null after billing;

-- SEPARATOR --

update payments set business = (select `value` FROM `settings` WHERE `key` = 'business');

-- SEPARATOR --

alter table payments change date datetime datetime null;

-- SEPARATOR --

alter table redeemed_codes change date datetime datetime not null;

-- SEPARATOR --

alter table payments drop column subscription_id;

-- SEPARATOR --

alter table payments drop column payer_id;

-- SEPARATOR --

alter table payments add plan text null after name;

