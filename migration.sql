CREATE TABLE `images` (
    `id` varchar(255) NOT NULL,
    `filename` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

INSERT INTO `images` (`id`, `filename`) VALUES
    ('dc885a57-8482-11ec-91f7-00155d5e9ef6', 'test-img-1.jpg'),
    ('dc8869e6-8482-11ec-91f7-00155d5e9ef6', 'test-img-2.jpg'),
    ('dc886a5f-8482-11ec-91f7-00155d5e9ef6', 'test-img-3.jpg'),
    ('dc886a82-8482-11ec-91f7-00155d5e9ef6', 'test-img-4.jpg'),
    ('dc886aa4-8482-11ec-91f7-00155d5e9ef6', 'test-img-5.jpg')
;

CREATE TABLE `categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

INSERT INTO `categories` (`id`, `name`) VALUES
    (1, 'Technology'),
    (2, 'Gaming'),
    (3, 'Auto'),
    (4, 'Entertainment'),
    (5, 'Books')
;

CREATE TABLE `posts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `category_id` int(11) NOT NULL,
    `title` varchar(255) NOT NULL,
    `body` text NOT NULL,
    `author` varchar(255) NOT NULL,
    `image_id` varchar(255),
    `ip_address` varchar(255),
    `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

INSERT INTO `posts` (`id`, `category_id`, `title`, `body`, `author`, `image_id`, ip_address) VALUES
    (1, 1, 'Technology Post One', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut interdum est nec lorem mattis interdum.','Sam Smith', 'dc885a57-8482-11ec-91f7-00155d5e9ef6', '192.168.1.1'),
    (2, 2, 'Gaming Post One', 'Adipiscing elit. Ut interdum est nec lorem mattis interdum. Cras augue est, interdum eu consectetur et, faucibus vel turpis.','Kevin Williams', 'dc8869e6-8482-11ec-91f7-00155d5e9ef6', '192.168.1.2'),
    (3, 1, 'Technology Post Two', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut interdum est nec lorem mattis interdum.','Sam Smith', 'dc886a5f-8482-11ec-91f7-00155d5e9ef6', '192.168.1.3'),
    (4, 4, 'Entertainment Post One', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut interdum est nec lorem mattis interdum.','Mary Jackson', 'dc886a82-8482-11ec-91f7-00155d5e9ef6', '192.168.1.4'),
    (5, 4, 'Entertainment Post Two', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut interdum est nec lorem mattis interdum.','Mary Jackson', 'dc886aa4-8482-11ec-91f7-00155d5e9ef6', '192.168.1.5')
;
