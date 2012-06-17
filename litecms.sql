-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 17 Juin 2012 à 10:01
-- Version du serveur: 5.1.62
-- Version de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `litecmsdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `contentmenu`
--

DROP TABLE IF EXISTS `contentmenu`;
CREATE TABLE IF NOT EXISTS `contentmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `contentobject_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `path_string` longtext NOT NULL,
  `lang` varchar(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `contentmenu`
--

INSERT INTO `contentmenu` (`id`, `node_id`, `contentobject_id`, `name`, `path_string`, `lang`) VALUES
(1, 1, 1, 'Accueil', '/accueil', 'fre-FR'),
(2, 2, 2, 'Contact', '/accueil/contact', 'fre-FR');

-- --------------------------------------------------------

--
-- Structure de la table `contentobjects`
--

DROP TABLE IF EXISTS `contentobjects`;
CREATE TABLE IF NOT EXISTS `contentobjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_name` varchar(32) NOT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  `class_identifier` varchar(32) NOT NULL,
  `node_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_identifier` (`class_identifier`,`node_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `contentobjects`
--

INSERT INTO `contentobjects` (`id`, `object_name`, `created`, `updated`, `class_identifier`, `node_id`) VALUES
(1, 'Accueil', 1307482362, 0, 'article', 0),
(2, 'Contact', 1317283035, 0, 'contact', 0);

-- --------------------------------------------------------

--
-- Structure de la table `contentobject_attributes`
--

DROP TABLE IF EXISTS `contentobject_attributes`;
CREATE TABLE IF NOT EXISTS `contentobject_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contentobject_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `int_value` int(11) NOT NULL,
  `txt_value` varchar(255) NOT NULL,
  `ltxt_value` text NOT NULL,
  `datatype` varchar(32) NOT NULL,
  `identifier` varchar(32) NOT NULL,
  `lang` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contentobject_id` (`contentobject_id`,`datatype`,`identifier`,`lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `contentobject_attributes`
--

INSERT INTO `contentobject_attributes` (`id`, `contentobject_id`, `name`, `int_value`, `txt_value`, `ltxt_value`, `datatype`, `identifier`, `lang`) VALUES
(1, 1, 'Name', 0, 'Accueil', '', 'string', 'name', 'fre-FR'),
(2, 1, 'Description', 0, '', '<p>Bienvenue dans Moskito Lite CMS.</p>\r\n<p>Ceci est la premi&egrave;re mouture d''un nouveau CMS qui se veut l&eacute;ger tout en respectant des standards de d&eacute;veloppement comme le mod&egrave;le MVC2 et en fournissant par d&eacute;faut tous les outils de base n&eacute;cessaires &agrave; la cr&eacute;ation d''un site web.</p>', 'xmlbloc', 'description', 'fre-FR'),
(3, 1, 'Image', 0, '', '<?xml version=''1.0''?><file>\n<name>bannière petite2.jpg</name>\n<type>image/jpeg</type>\n<filename>banniere-petite2.jpg</filename>\n<basename>banniere-petite2</basename>\n<extension>jpg</extension>\n<size>275035</size>\n<created>05-09-2011</created>\n</file>\n', 'image', 'image', 'fre-FR'),
(4, 2, 'Name', 0, 'Contact', '', 'string', 'name', 'fre-FR'),
(5, 2, 'Nom', 0, '', '', 'string', 'nom', 'fre-FR'),
(6, 2, 'Prénom', 0, '', '', 'string', 'prenom', 'fre-FR'),
(7, 2, 'Courriel', 0, '', '', 'string', 'email', 'fre-FR'),
(8, 2, 'Destinataire', 0, 'contact@atelier-siloe.fr', '', 'string', 'recipient', 'fre-FR'),
(9, 2, 'Sujet', 0, '', '', 'string', 'subject', 'fre-FR'),
(10, 2, 'Message', 0, '', '', 'textbloc', 'message', 'fre-FR'),
(11, 1, 'Titre Sous menu', 0, '', '', 'string', 'subname', 'fre-FR'),
(12, 1, 'Pièce jointe', 0, '', '', 'file', 'fichier', 'fre-FR'),
(13, 1, 'Pas dans menu', 0, '', '', 'option', 'not_in_menu', 'fre-FR'),
(14, 1, 'Menu externe', 0, '', '', 'option', 'external_menu', 'fre-FR');

-- --------------------------------------------------------

--
-- Structure de la table `info`
--

DROP TABLE IF EXISTS `info`;
CREATE TABLE IF NOT EXISTS `info` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `info`
--

INSERT INTO `info` (`name`, `value`) VALUES
('release', '0.5'),
('release', '0.5'),
('release', '0.5');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `node_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_node_id` int(11) NOT NULL,
  `sort_val` int(11) NOT NULL,
  `path_ids` varchar(255) NOT NULL,
  `section_id` int(11) NOT NULL,
  `depth` int(11) NOT NULL,
  PRIMARY KEY (`node_id`),
  KEY `parent_node_id` (`parent_node_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `menu`
--

INSERT INTO `menu` (`node_id`, `parent_node_id`, `sort_val`, `path_ids`, `section_id`, `depth`) VALUES
(1, 0, 0, '/1/', 1, 0),
(2, 1, 0, '/1/2/', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'anonymous'),
(4, 'élève');

-- --------------------------------------------------------

--
-- Structure de la table `rules`
--

DROP TABLE IF EXISTS `rules`;
CREATE TABLE IF NOT EXISTS `rules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `function` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `role` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `rules`
--

INSERT INTO `rules` (`id`, `role_id`, `module`, `function`, `params`) VALUES
(1, 1, 'all', 'all', ''),
(14, 2, 'content', 'read', 'a:1:{s:7:"section";i:1;}'),
(15, 4, 'content', 'read', 'a:1:{s:7:"section";i:2;}'),
(16, 4, 'content', 'read', 'a:1:{s:7:"section";i:1;}');

-- --------------------------------------------------------

--
-- Structure de la table `sections`
--

DROP TABLE IF EXISTS `sections`;
CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `sections`
--

INSERT INTO `sections` (`id`, `name`) VALUES
(2, 'prive'),
(1, 'standard');

-- --------------------------------------------------------

--
-- Structure de la table `test`
--

DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `val` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `test`
--

INSERT INTO `test` (`id`, `val`) VALUES
(1, 'test'),
(3, 'test2'),
(4, 'test3'),
(5, 'test3_4'),
(6, 'test3'),
(7, 'test3_6');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `login` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`login`, `password`, `id`, `role`) VALUES
('admin', '725647b89d6ab6e41cb5cdfaf3b72112', 3, 'admin'),
('nathalie', '74a21b4b03f76eb695eac8fa0c44becd', 4, 'admin'),
('eleve', '3fb99d1a3846db41157d376c235d729a', 5, 'élève');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
