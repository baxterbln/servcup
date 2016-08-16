SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `authorize_ip` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ip` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;


CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;


CREATE TABLE IF NOT EXISTS `dns_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  `modified_at` int(11) NOT NULL,
  `account` varchar(40) NOT NULL,
  `comment` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_domain_id_idx` (`domain_id`),
  KEY `comments_name_type_idx` (`name`,`type`),
  KEY `comments_order_idx` (`domain_id`,`modified_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `dns_cryptokeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `flags` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `domainidindex` (`domain_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `dns_domainmetadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `kind` varchar(32) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `domainmetadata_idx` (`domain_id`,`kind`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `dns_domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(5) NOT NULL,
  `domain_id` int(10) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `master` varchar(128) DEFAULT NULL,
  `last_check` int(11) DEFAULT NULL,
  `type` varchar(6) NOT NULL,
  `notified_serial` int(11) DEFAULT NULL,
  `account` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_index` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `dns_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `content` mediumtext,
  `ttl` int(11) DEFAULT NULL,
  `prio` int(11) DEFAULT NULL,
  `change_date` int(11) DEFAULT NULL,
  `disabled` tinyint(1) DEFAULT '0',
  `ordername` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `auth` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `nametype_index` (`name`,`type`),
  KEY `domain_id` (`domain_id`),
  KEY `recordorder` (`domain_id`,`ordername`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=167 ;

CREATE TABLE IF NOT EXISTS `dns_tsigkeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `algorithm` varchar(50) DEFAULT NULL,
  `secret` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `namealgoindex` (`name`,`algorithm`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

DROP TRIGGER IF EXISTS `add_domain`;
DELIMITER //
CREATE TRIGGER `add_domain` AFTER INSERT ON `domain`
 FOR EACH ROW BEGIN
DECLARE new_domain INT;
SET new_domain = NEW.id ;

INSERT INTO domain_cache ( domain_id) VALUES ( new_domain);
INSERT INTO domain_pagespeed ( domain_id) VALUES ( new_domain);
INSERT INTO domain_ssl (`isWWW`, domain_id) VALUES ( '0', new_domain);
INSERT INTO domain_ssl (`isWWW`, domain_id) VALUES ( '1', new_domain);
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `delete_domain`;
DELIMITER //
CREATE TRIGGER `delete_domain` AFTER DELETE ON `domain`
 FOR EACH ROW BEGIN

DELETE FROM domain_cache WHERE domain_id = OLD.id;
DELETE FROM domain_pagespeed WHERE domain_id = OLD.id;
DELETE FROM domain_ssl WHERE domain_id = OLD.id;

END
//
DELIMITER ;

CREATE TABLE IF NOT EXISTS `domain_alias` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(5) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `alias` varchar(70) NOT NULL,
  `SSLCertificateFile` text NOT NULL,
  `SSLCertificateKeyFile` text NOT NULL,
  `SSLCertificateChainFile` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

CREATE TABLE IF NOT EXISTS `domain_cache` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) NOT NULL,
  `expire` varchar(10) NOT NULL,
  `filetypes_enable` text NOT NULL,
  `filetypes_disable` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

CREATE TABLE IF NOT EXISTS `domain_pagespeed` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `rewrite_style_attributes_with_url` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

CREATE TABLE IF NOT EXISTS `domain_ssl` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain_id` int(10) NOT NULL,
  `type` varchar(10) NOT NULL,
  `isWWW` enum('0','1') NOT NULL DEFAULT '0',
  `SSLCertificateFile` text NOT NULL,
  `SSLCertificateKeyFile` text NOT NULL,
  `SSLCertificateChainFile` text NOT NULL,
  `expired` timestamp NULL DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

CREATE TABLE IF NOT EXISTS `domain_stats` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `ftpd` (
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
  `quotafiles` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`),
  UNIQUE KEY `User` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(5) NOT NULL,
  `name` varchar(20) NOT NULL,
  `role` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

DROP TRIGGER IF EXISTS `add_group`;
DELIMITER //
CREATE TRIGGER `add_group` AFTER INSERT ON `groups`
 FOR EACH ROW INSERT INTO permissions
   ( group_id)
   VALUES
   ( NEW.id)
//
DELIMITER ;
DROP TRIGGER IF EXISTS `delete_group`;
DELIMITER //
CREATE TRIGGER `delete_group` AFTER DELETE ON `groups`
 FOR EACH ROW DELETE FROM permissions WHERE group_id = OLD.id
//
DELIMITER ;

CREATE TABLE IF NOT EXISTS `mysql_databases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL,
  `mysql_user` int(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `server_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mysql_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(5) NOT NULL,
  `customer_id` int(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `access_orders` int(11) NOT NULL DEFAULT '0',
  `add_orders` int(11) NOT NULL DEFAULT '0',
  `activate_orders` int(11) NOT NULL DEFAULT '0',
  `delete_orders` int(11) NOT NULL DEFAULT '0',
  `access_invoice` int(11) NOT NULL DEFAULT '0',
  `add_invoice` int(11) NOT NULL DEFAULT '0',
  `access_products` int(11) NOT NULL DEFAULT '0',
  `add_products` int(11) NOT NULL DEFAULT '0',
  `edit_products` int(11) NOT NULL DEFAULT '0',
  `delete_products` int(11) NOT NULL DEFAULT '0',
  `access_payments` int(11) NOT NULL DEFAULT '0',
  `access_overview` int(11) NOT NULL DEFAULT '0',
  `manage_server` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `protect_folder` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL,
  `authname` char(50) NOT NULL,
  `path` char(20) NOT NULL,
  `server_id` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `protect_users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL,
  `folder_id` int(10) NOT NULL,
  `user_name` char(30) NOT NULL,
  `user_passwd` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `server_key` varchar(65) NOT NULL,
  `http` int(1) NOT NULL DEFAULT '1',
  `mail` int(1) NOT NULL DEFAULT '1',
  `mysql` int(1) NOT NULL DEFAULT '1',
  `dns` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `key` varchar(20) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `supermasters` (
  `ip` varchar(64) NOT NULL,
  `nameserver` varchar(255) NOT NULL,
  `account` varchar(40) NOT NULL,
  PRIMARY KEY (`ip`,`nameserver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task` varchar(20) NOT NULL,
  `object` varchar(20) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `added_by` int(5) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `seckey` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `settings` (`key`, `value`) VALUES
('customer_id', '10000'),
('primary_dns', ''),
('secundary_dns', ''),
('sytem_mailaddress', ''),
('system_mailname', ''),
('system_mailserver', ''),
('system_mailuser', ''),
('system_mailpass', ''),
('dns_module', '0'),
('piwik_module', '0'),
('mail_module', '0'),
('mysql_module', '0'),
('tools_module', '0'),
('app_module', '0'),
('billing_module', '0'),
('customer_module', '0'),
('support_module', '0'),
('support_address', ''),
('support_server', ''),
('support_mailuser', ''),
('support_mailpass', ''),
('piwik_url', ''),
('piwik_token', ''),
('mail_module', '1'),
('client_path', '');
('mail_path', '');

INSERT INTO `groups` (`id`, `customer_id`, `name`, `role`) VALUES
(1, 1, 'Admin', 'admin'),
(2, 1, 'Reseller', 'reseller'),
(3, 1, 'Hosting', 'user');

UPDATE `permissions` SET `id` = 1,`group_id` = 1,`manage_domain` = 1,`manage_alias` = 1,`manage_ssl` = 1,`manage_cache` = 1,`manage_ps` = 1,`manage_cdn` = 1,`manage_mail` = 1,`manage_mailfwd` = 1,`manage_database` = 1,`manage_ftp` = 1,`manage_dns` = 1,`manage_backup` = 1,`access_tools` = 1,`manage_customer` = 1,`add_customer` = 1,`edit_customer` = 1,`delete_customer` = 1,`manage_groups` = 1,`add_groups` = 1,`edit_groups` = 1,`delete_groups` = 1,`access_support` = 1,`access_billing` = 1,`access_orders` = 1,`add_orders` = 1,`activate_orders` = 1,`delete_orders` = 1,`access_invoice` = 1,`add_invoice` = 1,`access_products` = 1,`add_products` = 1,`edit_products` = 1,`delete_products` = 1,`access_payments` = 1,`access_overview` = 1,`manage_server` = 1 WHERE `permissions`.`id` = 1;
UPDATE `permissions` SET `id` = 2,`group_id` = 2,`manage_domain` = 1,`manage_alias` = 1,`manage_ssl` = 1,`manage_cache` = 1,`manage_ps` = 1,`manage_cdn` = 0,`manage_mail` = 1,`manage_mailfwd` = 1,`manage_database` = 1,`manage_ftp` = 1,`manage_dns` = 1,`manage_backup` = 1,`access_tools` = 1,`manage_customer` = 1,`add_customer` = 1,`edit_customer` = 1,`delete_customer` = 1,`manage_groups` = 1,`add_groups` = 1,`edit_groups` = 1,`delete_groups` = 1,`access_support` = 1,`access_billing` = 0,`access_orders` = 0,`add_orders` = 0,`activate_orders` = 0,`delete_orders` = 0,`access_invoice` = 0,`add_invoice` = 0,`access_products` = 0,`add_products` = 0,`edit_products` = 0,`delete_products` = 0,`access_payments` = 0,`access_overview` = 0,`manage_server` = 0 WHERE `permissions`.`id` = 2;
UPDATE `permissions` SET `id` = 3,`group_id` = 3,`manage_domain` = 1,`manage_alias` = 1,`manage_ssl` = 1,`manage_cache` = 0,`manage_ps` = 0,`manage_cdn` = 0,`manage_mail` = 0,`manage_mailfwd` = 0,`manage_database` = 0,`manage_ftp` = 0,`manage_dns` = 0,`manage_backup` = 0,`access_tools` = 0,`manage_customer` = 0,`add_customer` = 0,`edit_customer` = 0,`delete_customer` = 0,`manage_groups` = 0,`add_groups` = 0,`edit_groups` = 0,`delete_groups` = 0,`access_support` = 0,`access_billing` = 1,`access_orders` = 1,`add_orders` = 1,`activate_orders` = 1,`delete_orders` = 1,`access_invoice` = 1,`add_invoice` = 1,`access_products` = 1,`add_products` = 1,`edit_products` = 1,`delete_products` = 1,`access_payments` = 1,`access_overview` = 1,`manage_server` = 0 WHERE `permissions`.`id` = 3;
