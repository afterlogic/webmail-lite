DROP TABLE IF EXISTS `oauth_session`;
CREATE TABLE `oauth_session` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session` char(32) NOT NULL DEFAULT '',
  `state` char(32) NOT NULL DEFAULT '',
  `access_token` mediumtext NOT NULL,
  `expiry` datetime DEFAULT NULL,
  `type` char(12) NOT NULL DEFAULT '',
  `server` char(20) NOT NULL DEFAULT '',
  `creation` datetime NOT NULL DEFAULT '2000-01-01 00:00:00',
  `access_token_secret` mediumtext NOT NULL,
  `authorized` char(1) DEFAULT NULL,
  `user` int(10) unsigned DEFAULT 0 NOT NULL,
  `refresh_token` mediumtext NOT NULL,
  `access_token_response` mediumtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_oauth_session_index` (`session`,`server`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
