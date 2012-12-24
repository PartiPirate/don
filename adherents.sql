-- phpMyAdmin SQL Dump
-- version 3.3.7
-- http://www.phpmyadmin.net
--
-- Serveur: 127.0.0.1
-- Généré le : Jeu 19 Juillet 2012 à 00:22
-- Version du serveur: 5.0.51
-- Version de PHP: 5.3.5-0.dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `adherents`
--

-- --------------------------------------------------------

--
-- Structure de la table `fields`
--

CREATE TABLE IF NOT EXISTS `fields` (
  `name` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `empty` int(1) NOT NULL default '1',
  `numeric` int(1) NOT NULL default '0',
  `apayer` tinyint(1) NOT NULL default '0',
  `database` int(1) NOT NULL default '0',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `temporaire`
--

CREATE TABLE IF NOT EXISTS `temporaire` (
  `id` int(9) NOT NULL auto_increment,
  `nom` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `prenom` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `adresse` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `email` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `pseudo` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `membre_forum` int(1) default NULL,
  `telephone` varchar(30) character set utf8 collate utf8_unicode_ci default NULL,
  `montant` int(4) NOT NULL,
  `ml_general` int(1) default NULL,
  `ml_discussions` int(1) default NULL,
  `sl_inscription` varchar(20) character set utf8 collate utf8_unicode_ci NOT NULL,
  `sl_donne` int(4) NOT NULL,
  `ville` varchar(30) character set utf8 collate utf8_unicode_ci NOT NULL,
  `cp` int(5) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=809 ;

-- --------------------------------------------------------

--
-- Structure de la table `temporaire_dons`
--

CREATE TABLE IF NOT EXISTS `temporaire_dons` (
  `id` int(9) NOT NULL auto_increment,
  `nom` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `prenom` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `adresse` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `email` varchar(50) character set utf8 collate utf8_unicode_ci NOT NULL,
  `telephone` varchar(30) character set utf8 collate utf8_unicode_ci default NULL,
  `montant` int(4) NOT NULL,
  `ville` varchar(30) character set utf8 collate utf8_unicode_ci NOT NULL,
  `cp` int(5) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;
