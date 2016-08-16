CREATE TABLE IF NOT EXISTS `mail_domains`
(
    domain_id        mediumint(8)  unsigned  NOT NULL  auto_increment,
    customer_id      int(10)                           DEFAULT NULL,
	domain           varchar(255)            NOT NULL  default '',
	maildir          varchar(4096)           NOT NULL  default '',
	uid              smallint(5)   unsigned  NOT NULL  default 'MUID',
	gid              smallint(5)   unsigned  NOT NULL  default 'MGID',
	max_accounts     int(10)       unsigned  NOT NULL  default '0',
	quotas           int(10)       unsigned  NOT NULL  default '0',
	type             varchar(5)                        default NULL,
	avscan           bool                    NOT NULL  default '0',
	blocklists       bool                    NOT NULL  default '0',
	complexpass      bool                    NOT NULL  default '0',
	enabled          bool                    NOT NULL  default '1',
	mailinglists     bool                    NOT NULL  default '0',
	maxmsgsize       mediumint(8)  unsigned  NOT NULL  default '0',
	pipe             bool                    NOT NULL  default '0',
	spamassassin     bool                    NOT NULL  default '0',
	sa_tag           smallint(5)   unsigned  NOT NULL  default '0',
	sa_refuse        smallint(5)   unsigned  NOT NULL  default '0',
	relay_address    varchar(64)             NOT NULL  default '',
	outgoing_ip      varchar(15)             NOT NULL  default '',
	PRIMARY KEY (domain_id),
	UNIQUE KEY domain (domain)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail_users`
(
	user_id          int(10)       unsigned  NOT NULL  auto_increment,
    customer_id      int(10)                           DEFAULT NULL,
	domain_id        mediumint(8)  unsigned  NOT NULL,
	localpart        varchar(64)             NOT NULL  default '',
	username         varchar(255)            NOT NULL  default '',
	clear            varchar(255)                      default NULL,
	crypt            varchar(255)                      default NULL,
	uid              smallint(5)   unsigned  NOT NULL  default 'MUID',
	gid              smallint(5)   unsigned  NOT NULL  default 'MGID',
	smtp             varchar(4096)                      default NULL,
	pop              varchar(4096)                      default NULL,
	type             enum('local', 'alias',
                          'catch', 'fail',
                          'piped', 'admin',
                          'site')                NOT NULL  default 'local',
	admin            bool                    NOT NULL  default '0',
	on_avscan        bool                    NOT NULL  default '0',
	on_blocklist     bool                    NOT NULL  default '0',
	on_complexpass   bool                    NOT NULL  default '0',
	on_forward       bool                    NOT NULL  default '0',
	on_piped         bool                    NOT NULL  default '0',
	on_spamassassin  bool                    NOT NULL  default '0',
	on_vacation      bool                    NOT NULL  default '0',
	enabled          bool                    NOT NULL  default '1',
	flags            varchar(16)                       default NULL,
	forward          varchar(255)                      default NULL,
        unseen           bool                              default '0',
	maxmsgsize       mediumint(8)  unsigned  NOT NULL  default '0',
	quota            int(10)       unsigned  NOT NULL  default '0',
	realname         varchar(255)                      default NULL,
	sa_tag           smallint(5)   unsigned  NOT NULL  default '0',
	sa_refuse        smallint(5)   unsigned  NOT NULL  default '0',
	tagline          varchar(255)                      default NULL,
	vacation         varchar(4096)                     default NULL,
	on_spambox       tinyint(1)              NOT NULL  default '0',
	on_spamboxreport tinyint(1)	         NOT NULL  default '0',
	PRIMARY KEY (user_id),
	UNIQUE KEY username (localpart, domain_id),
	KEY local (localpart)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail_blocklists`
(
	block_id         int(10)       unsigned  NOT NULL  auto_increment,
    customer_id      int(10)                           DEFAULT NULL,
	domain_id        mediumint(8)  unsigned  NOT NULL,
	user_id          int(10)       unsigned            default NULL,
	blockhdr         varchar(192)            NOT NULL  default '',
	blockval         varchar(255)            NOT NULL  default '',
	color            varchar(8)              NOT NULL  default '',
	PRIMARY KEY (block_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail_domainalias`
(
	domain_id        mediumint(8)  unsigned  NOT NULL,
    customer_id      int(10)                 NOT NULL,
	alias varchar(255)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail_groups`
(
	id               int(10)                           auto_increment,
	domain_id        mediumint(8)  unsigned  NOT NULL,
    customer_id      int(10)                           DEFAULT NULL,
	name             varchar(64)             NOT NULL,
	is_public        char(1)                 NOT NULL  default 'Y',
	enabled          bool                    NOT NULL  default '1',
	PRIMARY KEY (id),
	UNIQUE KEY group_name(domain_id, name)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail_group_contents`
(
	group_id         int(10)                 NOT NULL,
    customer_id      int(10)                           DEFAULT NULL,
	member_id        int(10)                 NOT NULL,
	PRIMARY KEY (group_id, member_id)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `mail_ml`
(
	domain_id           mediumint(8) unsigned   NOT NULL,
    customer_id      int(10)                           DEFAULT NULL,
	name                varchar(64)             NOT NULL,
	email               varchar(128)            NOT NULL,
	enabled             bool                    NOT NULL default '1',
	type                char(1)                 NOT NULL default 'm',
	memberCount         int                     NULL,
	replyTo             char(1)                 NOT NULL default 's',
	fullName            varchar(256)            NULL,
	PRIMARY KEY (domain_id, type, name, email)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
