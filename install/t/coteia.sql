CREATE TABLE `wiki` (
	`id` int NOT NULL,
	FOREIGN KEY (`id`) REFERENCES resource(`id`)
);

CREATE TABLE `wiki_X_resource` (
	`wiki_id` int NOT NULL,
	`resource_id` int NOT NULL,
	FOREIGN KEY (`wiki_id`) REFERENCES wiki(`id`),
	FOREIGN KEY (`resource_id`) REFERENCES resource(`id`)
);

CREATE TABLE `wiki_X_permission` (
	`resource_id` int NOT NULL,
	`user_id` int NOT NULL,
	`can_read` boolean,
	`can_write` boolean,
	FOREIGN KEY (`resource_id`) REFERENCES resource(`id`),
	FOREIGN KEY (`user_id`) REFERENCES user(`id`)
);

CREATE TABLE `wikipage` (
	`id` int NOT NULL,
	`content` text NOT NULL,
	FOREIGN KEY (`id`) REFERENCES resource(`id`)
);

CREATE TABLE `license` (
	`id` int NOT NULL,
	`acronym` text default NULL,
	`summary` text,
	`content` text,
	`password` text,
	FOREIGN KEY (`id`) REFERENCES resource(`id`)
);

CREATE TABLE `user` (
	`id` int NOT NULL,
	`login` varchar(255) NOT NULL,
	`password` varchar(255) NOT NULL,
	FOREIGN KEY (`id`) REFERENCES resource(`id`)
);

CREATE TABLE `resource` (
	`id` int NOT NULL auto_increment,
	`name` varchar(255) NOT NULL,
	`type` varchar(64) NOT NULL,
	`license_id` int NOT NULL,
	`creation_date` datetime,
	`modification_date` datetime,
	`expiration_date` datetime,
	PRIMARY KEY (`id`),
	FOREIGN KEY (`license_id`) REFERENCES license(`id`)
);

CREATE TABLE `log` (
	`resource_id` int NOT NULL,
	`user_id` int NOT NULL,
	`date` datetime NOT NULL,
	`action_type` varchar(255) NOT NULL,
	`changelog` varchar(255),
	FOREIGN KEY (`resource_id`) REFERENCES resource(`id`),
	FOREIGN KEY (`user_id`) REFERENCES user(`id`)
);