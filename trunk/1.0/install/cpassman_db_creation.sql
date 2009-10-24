CREATE TABLE IF NOT EXISTS `functions` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `groupes_visibles` varchar(255) NOT NULL,
  `groupes_interdits` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `items` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `pw` varchar(100) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `id_tree` varchar(10) DEFAULT NULL,
  `perso` tinyint(1) NOT NULL DEFAULT '0',
  `login` varchar(200) DEFAULT NULL,
  `inactif` tinyint(1) NOT NULL DEFAULT '0',
  `restricted_to` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `log_items` (
  `id_item` int(8) NOT NULL,
  `date` varchar(50) NOT NULL,
  `id_user` tinyint(4) NOT NULL,
  `action` varchar(250) NOT NULL,
  `raison` text NOT NULL
);


CREATE TABLE IF NOT EXISTS `misc` (
  `type` varchar(50) NOT NULL,
  `intitule` varchar(100) NOT NULL,
  `valeur` varchar(100) NOT NULL
);


CREATE TABLE IF NOT EXISTS `nested_tree` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `nleft` int(11) NOT NULL,
  `nright` int(11) NOT NULL,
  `nlevel` int(11) NOT NULL,
  `bloquer_creation` tinyint(1) NOT NULL DEFAULT '0',
  `bloquer_modification` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `nested_tree_parent_id` (`parent_id`),
  KEY `nested_tree_nleft` (`nleft`),
  KEY `nested_tree_nright` (`nright`),
  KEY `nested_tree_nlevel` (`nlevel`)
);


CREATE TABLE IF NOT EXISTS `rights` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `tree_id` int(12) NOT NULL,
  `fonction_id` int(12) NOT NULL,
  `authorized` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `pw` varchar(50) NOT NULL,
  `groupes_visibles` varchar(250) NOT NULL,
  `derniers` text NOT NULL,
  `key_tempo` varchar(100) NOT NULL,
  `last_pw_change` varchar(30) NOT NULL,
  `last_pw` text NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `fonction_id` varchar(255) NOT NULL,
  `groupes_interdits` varchar(255) NOT NULL,
  `last_connexion` varchar(30) NOT NULL,
  `gestionnaire` int(11) NOT NULL DEFAULT '0',
  `email` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
);

INSERT INTO `users` (`id`, `login`, `pw`, `groupes_visibles`, `derniers`, `key_tempo`, `last_pw_change`, `last_pw`, `admin`, `fonction_id`, `groupes_interdits`, `last_connexion`, `gestionnaire`, `email`) VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '', '', '', '', 1, '', '', '', 0, '');
