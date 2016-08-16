SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `authorize_ip`;
CREATE TABLE `authorize_ip` (
  `id` int(5) NOT NULL,
  `ip` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `customer_id` varchar(20) NOT NULL,
  `server_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `contactName` varchar(50) NOT NULL,
  `contactLastname` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `zipcode` varchar(15) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `fax` varchar(15) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `comment` text NOT NULL,
  `website` varchar(100) NOT NULL,
  `currency` int(3) NOT NULL,
  `taxrate` int(3) NOT NULL,
  `taxid` varchar(20) NOT NULL,
  `accountholder` varchar(100) NOT NULL,
  `iban` varchar(30) NOT NULL,
  `bic` varchar(20) NOT NULL,
  `bankname` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `domain`;
CREATE TABLE `domain` (
  `id` int(11) NOT NULL,
  `parent_id` int(5) NOT NULL DEFAULT '0',
  `server_id` int(5) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `domain` varchar(255) NOT NULL,
  `type` enum('domain','forward','subdomain') NOT NULL DEFAULT 'domain',
  `forward` int(1) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `seo` varchar(10) NOT NULL,
  `redirect` varchar(10) NOT NULL,
  `redirect_destination` varchar(100) NOT NULL,
  `cache` int(11) NOT NULL DEFAULT '0',
  `pagespeed` int(11) NOT NULL DEFAULT '0',
  `php_version` varchar(10) NOT NULL,
  `cgi` int(1) NOT NULL DEFAULT '0',
  `ssi` int(1) NOT NULL DEFAULT '0',
  `ruby` int(1) NOT NULL DEFAULT '0',
  `python` int(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TRIGGER IF EXISTS `add_domain`;
DELIMITER $$
CREATE TRIGGER `add_domain` AFTER INSERT ON `domain` FOR EACH ROW BEGIN
DECLARE new_domain INT;
SET new_domain = NEW.id ;

INSERT INTO domain_cache ( domain_id) VALUES ( new_domain);
INSERT INTO domain_pagespeed ( domain_id) VALUES ( new_domain);
INSERT INTO domain_ssl (`isWWW`, domain_id) VALUES ( '0', new_domain);
INSERT INTO domain_ssl (`isWWW`, domain_id) VALUES ( '1', new_domain);
END
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `delete_domain`;
DELIMITER $$
CREATE TRIGGER `delete_domain` AFTER DELETE ON `domain` FOR EACH ROW BEGIN

DELETE FROM domain_cache WHERE domain_id = OLD.id;
DELETE FROM domain_pagespeed WHERE domain_id = OLD.id;
DELETE FROM domain_ssl WHERE domain_id = OLD.id;

END
$$
DELIMITER ;

DROP TABLE IF EXISTS `domain_alias`;
CREATE TABLE `domain_alias` (
  `id` int(10) NOT NULL,
  `domain_id` int(5) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `alias` varchar(70) NOT NULL,
  `SSLCertificateFile` text NOT NULL,
  `SSLCertificateKeyFile` text NOT NULL,
  `SSLCertificateChainFile` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `domain_cache`;
CREATE TABLE `domain_cache` (
  `id` int(10) NOT NULL,
  `domain_id` int(10) NOT NULL,
  `expire` varchar(10) NOT NULL,
  `filetypes_enable` text NOT NULL,
  `filetypes_disable` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `domain_pagespeed`;
CREATE TABLE `domain_pagespeed` (
  `id` int(10) NOT NULL,
  `domain_id` int(10) NOT NULL,
  `RunExperiment` enum('on','off') NOT NULL DEFAULT 'off',
  `UseAnalyticsJs` enum('on','off') NOT NULL DEFAULT 'off',
  `AnalyticsID` varchar(50) NOT NULL,
  `DisableRewriteOnNoTransform` enum('on','off') NOT NULL DEFAULT 'off',
  `LowercaseHtmlNames` enum('on','off') NOT NULL DEFAULT 'off',
  `ModifyCachingHeaders` enum('on','off') NOT NULL DEFAULT 'off',
  `XHeaderValue` varchar(50) NOT NULL,
  `PreserveUrlRelativity` enum('on','off') NOT NULL DEFAULT 'off',
  `insert_ga` int(1) NOT NULL DEFAULT '0',
  `add_head` int(1) NOT NULL DEFAULT '0',
  `combine_css` int(1) NOT NULL DEFAULT '0',
  `combine_javascript` int(1) NOT NULL DEFAULT '0',
  `convert_meta_tags` int(1) NOT NULL DEFAULT '0',
  `extend_cache` int(1) NOT NULL DEFAULT '0',
  `fallback_rewrite_css_urls` int(1) DEFAULT '0',
  `flatten_css_imports` int(1) NOT NULL DEFAULT '0',
  `inline_css` int(1) NOT NULL DEFAULT '0',
  `inline_import_to_link` int(1) NOT NULL DEFAULT '0',
  `inline_javascript` int(1) NOT NULL DEFAULT '0',
  `rewrite_css` int(1) NOT NULL DEFAULT '0',
  `rewrite_images` int(1) NOT NULL DEFAULT '0',
  `rewrite_javascript` int(1) NOT NULL DEFAULT '0',
  `rewrite_style_attributes_with_url` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `domain_ssl`;
CREATE TABLE `domain_ssl` (
  `id` int(10) NOT NULL,
  `domain_id` int(10) NOT NULL,
  `type` varchar(10) NOT NULL,
  `isWWW` enum('0','1') NOT NULL DEFAULT '0',
  `SSLCertificateFile` text NOT NULL,
  `SSLCertificateKeyFile` text NOT NULL,
  `SSLCertificateChainFile` text NOT NULL,
  `expired` timestamp NULL DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `domain_stats`;
CREATE TABLE `domain_stats` (
  `id` int(10) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ftpd`;
CREATE TABLE `ftpd` (
  `user` varchar(16) NOT NULL DEFAULT '',
  `server_id` int(5) NOT NULL,
  `domain_id` int(10) NOT NULL DEFAULT '0',
  `customer_id` int(10) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `password` varchar(64) NOT NULL DEFAULT '',
  `uid` varchar(11) NOT NULL DEFAULT '-1',
  `gid` varchar(11) NOT NULL DEFAULT '-1',
  `dir` varchar(128) NOT NULL DEFAULT '',
  `upload` smallint(5) NOT NULL DEFAULT '0',
  `download` smallint(5) NOT NULL DEFAULT '0',
  `comment` tinytext NOT NULL,
  `ipaccess` varchar(15) NOT NULL DEFAULT '*',
  `quotasize` smallint(5) NOT NULL DEFAULT '0',
  `quotafiles` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`id`, `customer_id`, `name`, `role`) VALUES
(1, 1, 'Admin', 'admin'),
(2, 1, 'Reseller', 'reseller'),
(3, 1, 'Hosting', 'user');
DROP TRIGGER IF EXISTS `add_group`;
DELIMITER $$
CREATE TRIGGER `add_group` AFTER INSERT ON `groups` FOR EACH ROW INSERT INTO permissions
   ( group_id)
   VALUES
   ( NEW.id)
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `delete_group`;
DELIMITER $$
CREATE TRIGGER `delete_group` AFTER DELETE ON `groups` FOR EACH ROW DELETE FROM permissions WHERE group_id = OLD.id
$$
DELIMITER ;

DROP TABLE IF EXISTS `mail_blocklists`;
CREATE TABLE `mail_blocklists` (
  `block_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `domain_id` mediumint(8) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `blockhdr` varchar(192) NOT NULL DEFAULT '',
  `blockval` varchar(255) NOT NULL DEFAULT '',
  `color` varchar(8) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mail_domainalias`;
CREATE TABLE `mail_domainalias` (
  `domain_id` mediumint(8) UNSIGNED NOT NULL,
  `customer_id` int(10) NOT NULL,
  `alias` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mail_domains`;
CREATE TABLE `mail_domains` (
  `domain_id` mediumint(8) UNSIGNED NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `domain` varchar(255) NOT NULL DEFAULT '',
  `maildir` varchar(4096) NOT NULL DEFAULT '',
  `uid` smallint(5) UNSIGNED NOT NULL DEFAULT '112',
  `gid` smallint(5) UNSIGNED NOT NULL DEFAULT '116',
  `max_accounts` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `quotas` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `type` varchar(5) DEFAULT NULL,
  `avscan` tinyint(1) NOT NULL DEFAULT '0',
  `blocklists` tinyint(1) NOT NULL DEFAULT '0',
  `complexpass` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `mailinglists` tinyint(1) NOT NULL DEFAULT '0',
  `maxmsgsize` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `pipe` tinyint(1) NOT NULL DEFAULT '0',
  `spamassassin` tinyint(1) NOT NULL DEFAULT '0',
  `sa_tag` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `sa_refuse` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `relay_address` varchar(64) NOT NULL DEFAULT '',
  `outgoing_ip` varchar(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mail_groups`;
CREATE TABLE `mail_groups` (
  `id` int(10) NOT NULL,
  `domain_id` mediumint(8) UNSIGNED NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `is_public` char(1) NOT NULL DEFAULT 'Y',
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mail_group_contents`;
CREATE TABLE `mail_group_contents` (
  `group_id` int(10) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `member_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mail_ml`;
CREATE TABLE `mail_ml` (
  `domain_id` mediumint(8) UNSIGNED NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `type` char(1) NOT NULL DEFAULT 'm',
  `memberCount` int(11) DEFAULT NULL,
  `replyTo` char(1) NOT NULL DEFAULT 's',
  `fullName` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mail_users`;
CREATE TABLE `mail_users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `domain_id` mediumint(8) UNSIGNED NOT NULL,
  `localpart` varchar(64) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `clear` varchar(255) DEFAULT NULL,
  `crypt` varchar(255) DEFAULT NULL,
  `uid` smallint(5) UNSIGNED NOT NULL DEFAULT '112',
  `gid` smallint(5) UNSIGNED NOT NULL DEFAULT '116',
  `smtp` varchar(4096) DEFAULT NULL,
  `pop` varchar(4096) DEFAULT NULL,
  `type` enum('local','alias','catch','fail','piped','admin','site') NOT NULL DEFAULT 'local',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `on_avscan` tinyint(1) NOT NULL DEFAULT '0',
  `on_blocklist` tinyint(1) NOT NULL DEFAULT '0',
  `on_complexpass` tinyint(1) NOT NULL DEFAULT '0',
  `on_forward` tinyint(1) NOT NULL DEFAULT '0',
  `on_piped` tinyint(1) NOT NULL DEFAULT '0',
  `on_spamassassin` tinyint(1) NOT NULL DEFAULT '0',
  `on_vacation` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `flags` varchar(16) DEFAULT NULL,
  `forward` varchar(255) DEFAULT NULL,
  `unseen` tinyint(1) DEFAULT '0',
  `maxmsgsize` mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
  `quota` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `realname` varchar(255) DEFAULT NULL,
  `sa_tag` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `sa_refuse` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `tagline` varchar(255) DEFAULT NULL,
  `vacation` varchar(4096) DEFAULT NULL,
  `on_spambox` tinyint(1) NOT NULL DEFAULT '0',
  `on_spamboxreport` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mysql_databases`;
CREATE TABLE `mysql_databases` (
  `id` int(11) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `mysql_user` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `server_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `mysql_user`;
CREATE TABLE `mysql_user` (
  `id` int(11) NOT NULL,
  `server_id` int(5) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `manage_domain` int(1) NOT NULL DEFAULT '0',
  `manage_alias` int(1) NOT NULL DEFAULT '0',
  `manage_ssl` int(1) NOT NULL DEFAULT '0',
  `manage_cache` int(1) NOT NULL DEFAULT '0',
  `manage_ps` int(1) NOT NULL DEFAULT '0',
  `manage_cdn` int(1) NOT NULL DEFAULT '0',
  `manage_mail` int(1) NOT NULL DEFAULT '0',
  `manage_mailfwd` int(1) NOT NULL DEFAULT '0',
  `manage_database` int(1) NOT NULL DEFAULT '0',
  `manage_ftp` int(1) NOT NULL DEFAULT '0',
  `manage_dns` int(1) NOT NULL DEFAULT '0',
  `manage_backup` int(1) NOT NULL DEFAULT '0',
  `access_tools` int(1) NOT NULL DEFAULT '0',
  `manage_customer` int(1) NOT NULL DEFAULT '0',
  `add_customer` int(1) NOT NULL DEFAULT '0',
  `edit_customer` int(1) NOT NULL DEFAULT '0',
  `delete_customer` int(1) NOT NULL DEFAULT '0',
  `manage_groups` int(1) NOT NULL DEFAULT '0',
  `add_groups` int(1) DEFAULT '0',
  `edit_groups` int(1) NOT NULL DEFAULT '0',
  `delete_groups` int(1) NOT NULL DEFAULT '0',
  `access_support` int(1) NOT NULL DEFAULT '0',
  `access_billing` int(1) NOT NULL DEFAULT '0',
  `access_orders` int(1) NOT NULL DEFAULT '0',
  `add_orders` int(1) NOT NULL DEFAULT '0',
  `activate_orders` int(1) NOT NULL DEFAULT '0',
  `delete_orders` int(1) NOT NULL DEFAULT '0',
  `access_invoice` int(1) NOT NULL DEFAULT '0',
  `add_invoice` int(1) NOT NULL DEFAULT '0',
  `access_products` int(1) NOT NULL DEFAULT '0',
  `add_products` int(1) NOT NULL DEFAULT '0',
  `edit_products` int(1) NOT NULL DEFAULT '0',
  `delete_products` int(1) NOT NULL DEFAULT '0',
  `access_payments` int(1) NOT NULL DEFAULT '0',
  `access_overview` int(1) NOT NULL DEFAULT '0',
  `manage_server` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `permissions` (`id`, `group_id`, `manage_domain`, `manage_alias`, `manage_ssl`, `manage_cache`, `manage_ps`, `manage_cdn`, `manage_mail`, `manage_mailfwd`, `manage_database`, `manage_ftp`, `manage_dns`, `manage_backup`, `access_tools`, `manage_customer`, `add_customer`, `edit_customer`, `delete_customer`, `manage_groups`, `add_groups`, `edit_groups`, `delete_groups`, `access_support`, `access_billing`, `access_orders`, `add_orders`, `activate_orders`, `delete_orders`, `access_invoice`, `add_invoice`, `access_products`, `add_products`, `edit_products`, `delete_products`, `access_payments`, `access_overview`, `manage_server`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

DROP TABLE IF EXISTS `protect_folder`;
CREATE TABLE `protect_folder` (
  `id` int(5) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `authname` char(50) NOT NULL,
  `path` char(20) NOT NULL,
  `server_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `protect_users`;
CREATE TABLE `protect_users` (
  `id` int(5) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `folder_id` int(10) NOT NULL,
  `user_name` char(30) NOT NULL,
  `user_passwd` char(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `server`;
CREATE TABLE `server` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `server_key` varchar(65) NOT NULL,
  `http` int(1) NOT NULL DEFAULT '1',
  `mail` int(1) NOT NULL DEFAULT '1',
  `mysql` int(1) NOT NULL DEFAULT '1',
  `dns` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(5) NOT NULL,
  `key` varchar(20) NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(10, 'customer_id', '10000'),
(11, 'primary_dns', ''),
(12, 'secundary_dns', ''),
(13, 'sytem_mailaddress', ''),
(14, 'system_mailname', ''),
(15, 'system_mailserver', ''),
(16, 'system_mailuser', ''),
(17, 'system_mailpass', ''),
(18, 'dns_module', '1'),
(19, 'piwik_module', '1'),
(20, 'mail_module', '1'),
(21, 'mysql_module', '1'),
(22, 'tools_module', '1'),
(23, 'app_module', '0'),
(24, 'billing_module', '0'),
(25, 'customer_module', '1'),
(26, 'support_module', '0'),
(27, 'support_address', ''),
(28, 'support_server', ''),
(29, 'support_mailuser', ''),
(30, 'support_mailpass', ''),
(31, 'piwik_url', ''),
(32, 'piwik_token', ''),
(33, 'mail_module', '1'),
(34, 'client_path', '/var/clients'),
(35, 'mail_path', '/var/clients/mail');

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task` varchar(20) NOT NULL,
  `object` varchar(20) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `added_by` int(5) NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `seckey` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `authorize_ip`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`);

ALTER TABLE `domain`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `domain_alias`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `domain_cache`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `domain_pagespeed`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `domain_ssl`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `domain_stats`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ftpd`
  ADD PRIMARY KEY (`user`),
  ADD UNIQUE KEY `User` (`user`);

ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mail_blocklists`
  ADD PRIMARY KEY (`block_id`);

ALTER TABLE `mail_domains`
  ADD PRIMARY KEY (`domain_id`),
  ADD UNIQUE KEY `domain` (`domain`);

ALTER TABLE `mail_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_name` (`domain_id`,`name`);

ALTER TABLE `mail_group_contents`
  ADD PRIMARY KEY (`group_id`,`member_id`);

ALTER TABLE `mail_ml`
  ADD PRIMARY KEY (`domain_id`,`type`,`name`,`email`);

ALTER TABLE `mail_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`localpart`,`domain_id`),
  ADD KEY `local` (`localpart`);

ALTER TABLE `mysql_databases`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mysql_user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `protect_folder`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `protect_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `server`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `authorize_ip`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `customer`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
ALTER TABLE `domain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `domain_alias`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `domain_cache`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `domain_pagespeed`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `domain_ssl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `domain_stats`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `mail_blocklists`
  MODIFY `block_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `mail_domains`
  MODIFY `domain_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `mail_groups`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mail_users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `mysql_databases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mysql_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `permissions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `protect_folder`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
ALTER TABLE `protect_users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
ALTER TABLE `server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
