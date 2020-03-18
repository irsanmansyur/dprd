#
# TABLE STRUCTURE FOR: keys
#

DROP TABLE IF EXISTS `keys`;

CREATE TABLE `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(9) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text DEFAULT NULL,
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `keys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES (1, 'user_001', 'wpu123', 1, 0, 0, NULL, 2);


#
# TABLE STRUCTURE FOR: limits
#

DROP TABLE IF EXISTS `limits`;

CREATE TABLE `limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `count` int(10) NOT NULL,
  `hour_started` int(11) NOT NULL,
  `api_key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `limits` (`id`, `uri`, `count`, `hour_started`, `api_key`) VALUES (1, 'uri:komentar/index:get', 3, 1577028995, 'wpu123');
INSERT INTO `limits` (`id`, `uri`, `count`, `hour_started`, `api_key`) VALUES (2, 'uri:komentar/index:post', 5, 1577030106, 'wpu123');


#
# TABLE STRUCTURE FOR: tbl_icons
#

DROP TABLE IF EXISTS `tbl_icons`;

CREATE TABLE `tbl_icons` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(35) NOT NULL,
  `code` varchar(128) NOT NULL,
  `kategori` varchar(128) NOT NULL,
  `date_creaated` int(11) NOT NULL DEFAULT current_timestamp(),
  `date_updated` int(11) NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (1, 'Account Balance', '<i class=\"material-icons\"> account_balance </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (2, 'Account Box', '<i class=\"material-icons\"> account_box </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (3, 'account Circle', '<i class=\"material-icons\"> account_circle </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (4, 'troli / belanja', '<i class=\"material-icons\"> add_shopping_cart </i>', '', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (5, 'fullscreen', '<i class=\"material-icons\"> all_out </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (6, 'catatan', '<i class=\"material-icons\"> assignment </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (7, 'refresh', '<i class=\"material-icons\"> autorenew </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (8, 'backup', '<i class=\"material-icons\"> backup </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (9, 'Build', '<i class=\"material-icons\"> build </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (10, 'check circle', '<i class=\"material-icons\"> check_circle </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (11, 'done ', '<i class=\"material-icons\"> done_all </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (12, 'Dashboard', '<i class=\"material-icons\"> dashboard </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (13, 'delete', '<i class=\"material-icons\"> delete </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (14, 'lock ', '<i class=\"material-icons\"> fingerprint </i> ', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (15, 'Home ', '<i class=\"material-icons\"> home </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (17, 'locked', '<i class=\"material-icons\"> lock </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (18, 'locked open', '<i class=\"material-icons\"> lock_open </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (19, 'logout', '<i class=\"material-icons\"> power_settings_new </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (20, 'folder', '<i class=\"fas fa-fw fa-folder\"></i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (21, 'folder open', '<i class=\"material-icons\"> folder_open </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (22, 'Icon Font', '<i class=\"material-icons\"> insert_emoticon </i>', 'Material', 2147483647, 2147483647);
INSERT INTO `tbl_icons` (`id`, `name`, `code`, `kategori`, `date_creaated`, `date_updated`) VALUES (24, 'new Folder', '<i class=\"material-icons\"> create_new_folder </i>', 'Material', 2147483647, 2147483647);


#
# TABLE STRUCTURE FOR: tbl_setting
#

DROP TABLE IF EXISTS `tbl_setting`;

CREATE TABLE `tbl_setting` (
  `id_setting` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(49) NOT NULL,
  `title` varchar(180) NOT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_setting`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

INSERT INTO `tbl_setting` (`id_setting`, `name`, `title`, `status`) VALUES (4, 'data_color', 'purple', '1');
INSERT INTO `tbl_setting` (`id_setting`, `name`, `title`, `status`) VALUES (5, 'background_image', 'http://localhost/irsan/dprd/themes/admin/default/assets/img/sidebar-2.jpg', '1');
INSERT INTO `tbl_setting` (`id_setting`, `name`, `title`, `status`) VALUES (6, 'theme_public', 'default', '1');
INSERT INTO `tbl_setting` (`id_setting`, `name`, `title`, `status`) VALUES (7, 'site_title', 'Memangement menu dan user serta pengaturan web', '1');
INSERT INTO `tbl_setting` (`id_setting`, `name`, `title`, `status`) VALUES (11, 'theme_admin', 'default', '1');
INSERT INTO `tbl_setting` (`id_setting`, `name`, `title`, `status`) VALUES (12, 'site_name', 'DPRD Makassar', '1');
INSERT INTO `tbl_setting` (`id_setting`, `name`, `title`, `status`) VALUES (13, 'background_color', 'black', '1');


#
# TABLE STRUCTURE FOR: tbl_user
#

DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE `tbl_user` (
  `id_user` varchar(9) NOT NULL,
  `komisi_id` varchar(9) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `email` varchar(55) NOT NULL,
  `password` varchar(256) NOT NULL,
  `tentang_saya` varchar(1000) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` varchar(288) NOT NULL,
  `tgl_lahir` int(12) NOT NULL DEFAULT current_timestamp(),
  `role_id` int(3) NOT NULL,
  `is_active` int(1) NOT NULL,
  `menu_active` enum('yes','no') NOT NULL DEFAULT 'no',
  `date_created` int(11) NOT NULL,
  `date_updated` int(11) NOT NULL DEFAULT current_timestamp(),
  `file_id` varchar(9) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`),
  KEY `file_id` (`file_id`),
  CONSTRAINT `tbl_user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `tbl_user_role` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `tbl_user_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `tbl_user_file` (`id_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_user` (`id_user`, `komisi_id`, `name`, `email`, `password`, `tentang_saya`, `no_hp`, `alamat`, `tgl_lahir`, `role_id`, `is_active`, `menu_active`, `date_created`, `date_updated`, `file_id`) VALUES ('user_001', '', 'Admin WEB', 'admin@gmail.com', '$2y$10$atyi8D112NQRFL.qwXShm.7KZfuRjj95moeIAE0gSj/fcFaEMJmaO', 'g', '085298884534', 'Ds. Jongbiru 001/001 kec. Gampengrejo - Kediri', -25200, 1, 1, 'yes', 1567261992, 2147483647, 'file_001');
INSERT INTO `tbl_user` (`id_user`, `komisi_id`, `name`, `email`, `password`, `tentang_saya`, `no_hp`, `alamat`, `tgl_lahir`, `role_id`, `is_active`, `menu_active`, `date_created`, `date_updated`, `file_id`) VALUES ('user_002', '', 'Andi Suharji', 'komisi1@gmail.com', '$2y$10$0wKcZvzYGxDDTT2lLGTZcuEAFzsNhUXqruqOXLdgfadep5gHtG.ca', 'fgfgf', '085298884534', 'Ds. Jongbiru 001/001 kec. Gampengrejo - Kediri', -25200, 2, 1, 'yes', 0, 1577263628, 'file_008');
INSERT INTO `tbl_user` (`id_user`, `komisi_id`, `name`, `email`, `password`, `tentang_saya`, `no_hp`, `alamat`, `tgl_lahir`, `role_id`, `is_active`, `menu_active`, `date_created`, `date_updated`, `file_id`) VALUES ('user_003', '', 'Irsan Mansyur', 'masyarakat1@gmail.com', '$2y$10$uze90ZmhjG1/GOC/THznBusV4h.D2G6WR2MXpp42habClKiESlXQK', 'Masyarakat biasa\r\n', '085298884534', 'Ds. Jongbiru 001/001 kec. Gampengrejo - Kediri', -25200, 3, 1, '', 0, 1579904425, 'file_012');


#
# TABLE STRUCTURE FOR: tbl_user_about
#

DROP TABLE IF EXISTS `tbl_user_about`;

CREATE TABLE `tbl_user_about` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(46) NOT NULL,
  `file_id` varchar(9) CHARACTER SET utf8mb4 NOT NULL,
  `motivasi` varchar(400) NOT NULL,
  `pekerjaan` varchar(300) NOT NULL,
  `tentang_saya` varchar(1000) NOT NULL,
  `id_gallery` int(5) NOT NULL,
  `user_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `nip` varchar(10) NOT NULL,
  `situs` varchar(188) NOT NULL,
  `alamat` varchar(288) NOT NULL,
  `tgl_lahir` int(12) NOT NULL,
  `date_updated` int(11) NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_id` (`file_id`),
  KEY `user_about_ibfk_1` (`user_id`),
  CONSTRAINT `tbl_user_about_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

INSERT INTO `tbl_user_about` (`id`, `name`, `file_id`, `motivasi`, `pekerjaan`, `tentang_saya`, `id_gallery`, `user_id`, `no_hp`, `nip`, `situs`, `alamat`, `tgl_lahir`, `date_updated`) VALUES (1, 'Admin WEB', 'file_001', 'Tak Ada usaha yang menghianati hasil. Yakinlah semakin besar usahamu, semakin besar kamu mencapai impianmu.', 'Petani|Guru Honorer|Pegawai', '&quot;Tak Ada usaha yang menghianati hasil. Yakinlah semakin besar usahamu, semakin besar kamu mencapai impianmu&quot;', 1, 'user_001', '085298198343', '161290', 'http://www.irsandp.com', 'Kamp. Sarroanging, Desa Mappilawing, Kec. eremerasa, Kab. Bantaeng', 1795021200, 2147483647);


#
# TABLE STRUCTURE FOR: tbl_user_access_menu
#

DROP TABLE IF EXISTS `tbl_user_access_menu`;

CREATE TABLE `tbl_user_access_menu` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `role_id` int(2) NOT NULL,
  `menu_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `tbl_user_access_menu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `tbl_user_menu` (`id_menu`),
  CONSTRAINT `tbl_user_access_menu_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `tbl_user_role` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;

INSERT INTO `tbl_user_access_menu` (`id`, `role_id`, `menu_id`) VALUES (60, 1, 1);
INSERT INTO `tbl_user_access_menu` (`id`, `role_id`, `menu_id`) VALUES (61, 1, 2);
INSERT INTO `tbl_user_access_menu` (`id`, `role_id`, `menu_id`) VALUES (62, 2, 2);
INSERT INTO `tbl_user_access_menu` (`id`, `role_id`, `menu_id`) VALUES (70, 1, 9);
INSERT INTO `tbl_user_access_menu` (`id`, `role_id`, `menu_id`) VALUES (79, 3, 2);
INSERT INTO `tbl_user_access_menu` (`id`, `role_id`, `menu_id`) VALUES (82, 1, 28);
INSERT INTO `tbl_user_access_menu` (`id`, `role_id`, `menu_id`) VALUES (84, 2, 29);


#
# TABLE STRUCTURE FOR: tbl_user_file
#

DROP TABLE IF EXISTS `tbl_user_file`;

CREATE TABLE `tbl_user_file` (
  `id_file` varchar(9) NOT NULL,
  `user_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `file` varchar(128) NOT NULL,
  `type` enum('1','2','3','4') NOT NULL DEFAULT '1' COMMENT '1 .Image, 2.Document, 3.MP3, 4.Video',
  PRIMARY KEY (`id_file`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_user_file` (`id_file`, `user_id`, `file`, `type`) VALUES ('f001', 'user_001', '3_X_4_.jpg', '1');
INSERT INTO `tbl_user_file` (`id_file`, `user_id`, `file`, `type`) VALUES ('file_001', '', 'default.png', '1');
INSERT INTO `tbl_user_file` (`id_file`, `user_id`, `file`, `type`) VALUES ('file_002', 'user_003', '3_X_4_1.jpg', '1');
INSERT INTO `tbl_user_file` (`id_file`, `user_id`, `file`, `type`) VALUES ('file_003', 'user_005', '3_X_4_1.jpg', '1');
INSERT INTO `tbl_user_file` (`id_file`, `user_id`, `file`, `type`) VALUES ('file_008', 'user_002', '71nnIWIaBFL__SS1000_.jpg', '1');
INSERT INTO `tbl_user_file` (`id_file`, `user_id`, `file`, `type`) VALUES ('file_009', 'user_008', '3_x_4_5.jpg', '1');
INSERT INTO `tbl_user_file` (`id_file`, `user_id`, `file`, `type`) VALUES ('file_011', 'user_013', '3_x_4_4.jpg', '');
INSERT INTO `tbl_user_file` (`id_file`, `user_id`, `file`, `type`) VALUES ('file_012', 'user_003', '3_x_4_1.jpg', '1');


#
# TABLE STRUCTURE FOR: tbl_user_menu
#

DROP TABLE IF EXISTS `tbl_user_menu`;

CREATE TABLE `tbl_user_menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(88) NOT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

INSERT INTO `tbl_user_menu` (`id_menu`, `menu`) VALUES (1, 'admin');
INSERT INTO `tbl_user_menu` (`id_menu`, `menu`) VALUES (2, 'user');
INSERT INTO `tbl_user_menu` (`id_menu`, `menu`) VALUES (9, 'Menu Managements');
INSERT INTO `tbl_user_menu` (`id_menu`, `menu`) VALUES (28, 'Komisi');
INSERT INTO `tbl_user_menu` (`id_menu`, `menu`) VALUES (29, 'Aspirasi');
INSERT INTO `tbl_user_menu` (`id_menu`, `menu`) VALUES (30, 'Aspirasi Anda');


#
# TABLE STRUCTURE FOR: tbl_user_role
#

DROP TABLE IF EXISTS `tbl_user_role`;

CREATE TABLE `tbl_user_role` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

INSERT INTO `tbl_user_role` (`id`, `name`) VALUES (1, 'admin');
INSERT INTO `tbl_user_role` (`id`, `name`) VALUES (2, 'komisi');
INSERT INTO `tbl_user_role` (`id`, `name`) VALUES (3, 'masyarakat');


#
# TABLE STRUCTURE FOR: tbl_user_sub_menu
#

DROP TABLE IF EXISTS `tbl_user_sub_menu`;

CREATE TABLE `tbl_user_sub_menu` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `icon_id` int(6) NOT NULL DEFAULT 10,
  `is_active` int(1) NOT NULL,
  `class` varchar(120) NOT NULL,
  `method` varchar(122) NOT NULL,
  `akses` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`),
  KEY `icon_id` (`icon_id`),
  CONSTRAINT `tbl_user_sub_menu_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `tbl_user_menu` (`id_menu`),
  CONSTRAINT `tbl_user_sub_menu_ibfk_2` FOREIGN KEY (`icon_id`) REFERENCES `tbl_icons` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (45, 1, 'Role Acces', 'admin/admin/role', 3, 1, 'admin', 'role', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (50, 2, 'Profile User', 'admin/user/profile', 15, 1, 'user', 'profile', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (51, 9, 'Menu Akses', 'admin/menu', 20, 1, 'menu', 'index', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (52, 9, 'Sub Menu ', 'admin/menu/submenu', 21, 1, 'menu', 'submenu', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (54, 2, 'Change Password', 'admin/user/changepassword', 13, 1, 'user', 'changepassword', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (56, 2, 'User Blocked', 'admin/user/blocked', 11, 1, 'user', 'blocked', 1);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (58, 1, 'Setting WEB', 'admin/admin/setting', 2, 1, 'admin', 'setting', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (59, 1, 'Backup', 'admin/admin/backup', 8, 1, 'admin', 'backup', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (60, 2, 'Search', 'admin/search', 2, 1, 'search', 'index', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (61, 2, 'Dashboard', 'admin/dashboard', 1, 1, 'dashboard', 'index', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (62, 9, 'Menu Icons', 'admin/icon', 22, 1, 'icon', 'index', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (64, 28, 'Index', 'admin/komisi', 1, 1, 'komisi', 'index', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (65, 28, 'User Komisi', 'admin/komisi/user', 3, 1, 'komisi', 'user', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (66, 28, 'Daftar Aspirasi', 'admin/sa/aspirasi', 6, 1, 'aspirasi', 'index', 0);
INSERT INTO `tbl_user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon_id`, `is_active`, `class`, `method`, `akses`) VALUES (67, 29, 'Daftar Aspirasi', 'komisi/aspirasi', 1, 1, 'aspirasi', 'index', 0);


#
# TABLE STRUCTURE FOR: tbl_user_token
#

DROP TABLE IF EXISTS `tbl_user_token`;

CREATE TABLE `tbl_user_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(55) NOT NULL,
  `token` varchar(128) NOT NULL,
  `date_created` int(11) NOT NULL,
  `qty` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  CONSTRAINT `tbl_user_token_ibfk_1` FOREIGN KEY (`email`) REFERENCES `tbl_user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

#
# TABLE STRUCTURE FOR: tbl_visitor
#

DROP TABLE IF EXISTS `tbl_visitor`;

CREATE TABLE `tbl_visitor` (
  `id_visitor` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `os` varchar(30) NOT NULL,
  `browser` varchar(130) NOT NULL,
  `date_created` int(11) NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_visitor`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;

INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (24, '::1', 'Windows 7', 'Chrome', 1575354941);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (25, '::1', 'Windows 7', 'Chrome', 1575485817);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (26, '::1', 'Windows 7', 'Chrome', 1576226672);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (27, '::1', 'Windows 10', 'Chrome', 1576646890);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (28, '::1', 'Windows 10', 'Chrome', 1576769761);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (29, '::1', 'Windows 10', 'Chrome', 1577003890);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (30, '::1', 'Unknown Platform', '', 1577090975);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (31, '::1', 'Windows 10', 'Chrome', 1577185585);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (32, '::1', 'Windows 10', 'Chrome', 1577302560);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (33, '::1', 'Windows 10', 'Chrome', 1577443620);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (34, '::1', 'Unknown Platform', '', 1577542611);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (35, '::1', 'Windows 10', 'Chrome', 1577547469);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (36, '::1', 'Windows 10', 'Chrome', 1577634340);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (37, '::1', 'Unknown Platform', '', 1577640617);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (38, '::1', 'Windows 10', 'Chrome', 1577721008);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (39, '127.0.0.1', 'Windows 10', 'Chrome', 1577723662);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (40, '127.0.0.1', 'Windows 10', 'Chrome', 1577723662);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (41, '::1', 'Unknown Platform', '', 1577727754);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (42, '::1', 'Windows 10', 'Chrome', 1578061684);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (43, '127.0.0.1', 'Windows 10', 'Chrome', 1578073378);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (44, '::1', 'Windows 10', 'Chrome', 1578236409);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (45, '::1', 'Unknown Platform', '', 1578259800);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (46, '::1', 'Windows 10', 'Chrome', 1578384441);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (47, '::1', 'Windows 10', 'Chrome', 1578578874);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (48, '::1', 'Unknown Platform', '', 1578580193);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (49, '::1', 'Windows 10', 'Chrome', 1579960379);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (50, '::1', 'Windows 10', 'Chrome', 1580115925);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (51, '::1', 'Android', 'Chrome', 1580119065);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (52, '::1', 'Windows XP', 'Firefox', 1580202928);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (53, '::1', 'Windows 10', 'Chrome', 1580210092);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (54, '::1', 'Windows XP', 'Firefox', 1580295069);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (55, '::1', 'Windows 10', 'Chrome', 1580312634);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (56, '::1', 'Windows XP', 'Firefox', 1580382028);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (57, '::1', 'Windows 10', 'Chrome', 1580417705);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (58, '::1', 'Windows 10', 'Chrome', 1580813199);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (59, '::1', 'Windows XP', 'Firefox', 1580813404);
INSERT INTO `tbl_visitor` (`id_visitor`, `ip`, `os`, `browser`, `date_created`) VALUES (60, '::1', 'Unknown Platform', '', 1580823955);


#
# TABLE STRUCTURE FOR: web_aspirasi
#

DROP TABLE IF EXISTS `web_aspirasi`;

CREATE TABLE `web_aspirasi` (
  `id_aspirasi` varchar(9) CHARACTER SET utf8 NOT NULL,
  `message` text NOT NULL,
  `komisi_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `user_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `status` enum('1','2','3','4') NOT NULL DEFAULT '4' COMMENT '1 = Ditanggapi, 2 = Dibaca, 3 = belum dibaca, 4 = tidak terkirim kekomisi ',
  `date_created` int(11) NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_aspirasi`),
  KEY `user_id` (`user_id`),
  KEY `web_aspirasi_ibfk_2` (`komisi_id`),
  CONSTRAINT `web_aspirasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `web_aspirasi_ibfk_2` FOREIGN KEY (`komisi_id`) REFERENCES `web_komisi` (`id_komisi`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `web_aspirasi` (`id_aspirasi`, `message`, `komisi_id`, `user_id`, `status`, `date_created`) VALUES ('asp_002', 'hallo guys', 'kms_000', 'user_003', '3', 2147483647);


#
# TABLE STRUCTURE FOR: web_komentar
#

DROP TABLE IF EXISTS `web_komentar`;

CREATE TABLE `web_komentar` (
  `id_komentar` varchar(9) CHARACTER SET utf8 NOT NULL,
  `komentar` varchar(290) NOT NULL,
  `aspirasi_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `komisi_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `user_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `type` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '0 = tidak dibaca , 1 = dibaca, 2= dikomentari, 3 = like',
  `parent` varchar(10) NOT NULL DEFAULT '0',
  `date_created` int(11) NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_komentar`),
  KEY `aspirasi_id` (`aspirasi_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `web_komentar_ibfk_1` FOREIGN KEY (`aspirasi_id`) REFERENCES `web_aspirasi` (`id_aspirasi`) ON DELETE CASCADE,
  CONSTRAINT `web_komentar_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `web_komentar` (`id_komentar`, `komentar`, `aspirasi_id`, `komisi_id`, `user_id`, `type`, `parent`, `date_created`) VALUES ('kmt_001', 'Ini komentar dari masyarakat', 'asp_002', '', 'user_003', '0', '0', 1580800759);


#
# TABLE STRUCTURE FOR: web_komisi
#

DROP TABLE IF EXISTS `web_komisi`;

CREATE TABLE `web_komisi` (
  `id_komisi` varchar(9) CHARACTER SET utf8 NOT NULL,
  `name` varchar(129) NOT NULL,
  PRIMARY KEY (`id_komisi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `web_komisi` (`id_komisi`, `name`) VALUES ('kms_000', 'Lainya');
INSERT INTO `web_komisi` (`id_komisi`, `name`) VALUES ('kms_001', 'komisi A');
INSERT INTO `web_komisi` (`id_komisi`, `name`) VALUES ('kms_002', 'Komisi B');
INSERT INTO `web_komisi` (`id_komisi`, `name`) VALUES ('kms_003', 'Komisi C');
INSERT INTO `web_komisi` (`id_komisi`, `name`) VALUES ('kms_004', 'Komisi D');


#
# TABLE STRUCTURE FOR: web_komisi_label
#

DROP TABLE IF EXISTS `web_komisi_label`;

CREATE TABLE `web_komisi_label` (
  `id_label` varchar(9) CHARACTER SET utf8 NOT NULL,
  `label` varchar(129) NOT NULL,
  `komisi_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_label`),
  KEY `komisi_id` (`komisi_id`),
  CONSTRAINT `web_komisi_label_ibfk_1` FOREIGN KEY (`komisi_id`) REFERENCES `web_komisi` (`id_komisi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_002', 'hukum', 'kms_003');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_003', 'Hukum', 'kms_001');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_004', 'Politik', 'kms_001');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_005', 'bersih', 'kms_001');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_006', 'kotor', 'kms_001');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_007', 'rumah', 'kms_003');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_008', 'sakit', 'kms_003');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_009', 'penduduk', 'kms_002');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_010', 'masyarakat', 'kms_002');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_011', 'jalan', 'kms_004');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_012', 'luban', 'kms_004');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_013', 'rusak', 'kms_004');
INSERT INTO `web_komisi_label` (`id_label`, `label`, `komisi_id`) VALUES ('lbl_014', 'jembatan', 'kms_004');


#
# TABLE STRUCTURE FOR: web_komisi_user
#

DROP TABLE IF EXISTS `web_komisi_user`;

CREATE TABLE `web_komisi_user` (
  `id_k_u` varchar(9) CHARACTER SET utf8 NOT NULL,
  `komisi_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `user_id` varchar(9) CHARACTER SET utf8 NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id_k_u`),
  KEY `user_id` (`user_id`),
  KEY `komisi_id` (`komisi_id`),
  CONSTRAINT `web_komisi_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `web_komisi_user_ibfk_2` FOREIGN KEY (`komisi_id`) REFERENCES `web_komisi` (`id_komisi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `web_komisi_user` (`id_k_u`, `komisi_id`, `user_id`, `status`) VALUES ('k_u_a_001', 'kms_001', 'user_002', 1);


