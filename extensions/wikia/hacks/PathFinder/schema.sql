DROP TABLE IF EXISTS `path_segments_count`;

CREATE TABLE `path_segments_count` (
	`city_id` int(8) unsigned NOT NULL,
	`referrer_id` int(8) unsigned NOT NULL,
	`target_id` int(8) unsigned NOT NULL,
	`count` bigint(20) unsigned NOT NULL,
	`updated` date NOT NULL,
	PRIMARY KEY (`city_id`, `referrer_id`, `target_id`),
	INDEX nodes (`city_id`, `referrer_id`, `updated`, `count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `path_external_keywords_count`;

CREATE TABLE `path_doorways_count` (
	`source_id` int(8) unsigned NOT NULL,
	`keyword` VARCHAR(255) NOT NULL,
	`target_id` int(8) unsigned NOT NULL,
	`count` bigint(20) unsigned NOT NULL,
	`updated` date NOT NULL,
	PRIMARY KEY (`source`,`keyword`,`target_id`),
	INDEX keywords (`source`, `keyword`, `updated`, `count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;