-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Serveur: cale
-- Généré le : Lun 24 Décembre 2012 à 03:39
-- Version du serveur: 5.5.28
-- Version de PHP: 5.3.17-1~dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `don`
--

-- --------------------------------------------------------

--
-- Structure de la table `PP_ADHERENT`
--

CREATE TABLE IF NOT EXISTS `PP_ADHERENT` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `isMajeur` tinyint(4) DEFAULT NULL,
  `inscritForum` tinyint(1) NOT NULL,
  `pseudonymeForum` varchar(150) COLLATE utf8_general_ci NOT NULL,
  `identifiantClePGP` varchar(150) COLLATE utf8_general_ci NOT NULL COMMENT 'Sur serveur pirate',
  `urlClePGP` varchar(150) COLLATE utf8_general_ci NOT NULL COMMENT 'sur serveur externe',
  `SECTION_LOCALE_oid` int(11) NOT NULL,
  `abonnementML` text COLLATE utf8_general_ci NOT NULL,
  `infoCreationSectionLocale` varchar(150) COLLATE utf8_general_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateMAJ` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `PERSONNE_oid` int(11) NOT NULL,
  PRIMARY KEY (`oid`),
  KEY `adherent_is_personne` (`PERSONNE_oid`),
  KEY `adherent_has_sectionLocale` (`SECTION_LOCALE_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Un adhérent' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_ADHESION`
--

CREATE TABLE IF NOT EXISTS `PP_ADHESION` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(25) COLLATE utf8_general_ci NOT NULL COMMENT 'Référence de la transaction',
  `montantCotisation` float(6,2) NOT NULL,
  `isRenouvellement` tinyint(4) NOT NULL DEFAULT '0',
  `accepteRiStatut` tinyint(1) NOT NULL,
  `declarationHonneur` tinyint(1) NOT NULL,
  `optinStat` tinyint(1) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `ADHERENT_oid` int(11) NOT NULL,
  PRIMARY KEY (`oid`),
  UNIQUE KEY `reference` (`reference`),
  KEY `adhesion_has_adherent` (`ADHERENT_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Une adhesion' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_CMCIC_TRANSACTION`
--

CREATE TABLE IF NOT EXISTS `PP_CMCIC_TRANSACTION` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` char(12) COLLATE utf8_general_ci NOT NULL,
  `fields` text COLLATE utf8_general_ci NOT NULL,
  `hmac` varchar(45) COLLATE utf8_general_ci NOT NULL,
  `paymentDone` tinyint(1) NOT NULL,
  `issue` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `DON_oid` int(11) NOT NULL,
  `ADHESION_oid` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateMAJ` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`oid`),
  KEY `payment_has_don` (`DON_oid`),
  KEY `payment_has_adhesion` (`ADHESION_oid`),
  KEY `payment_has_reference` (`reference`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Un paiement CM-CIC' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_DON`
--

CREATE TABLE IF NOT EXISTS `PP_DON` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(25) COLLATE utf8_general_ci NOT NULL COMMENT 'Référence de la transaction',
  `montantDon` float(6,2) NOT NULL,
  `accepteRiStatut` tinyint(1) NOT NULL,
  `declarationHonneur` tinyint(1) NOT NULL,
  `optinStat` tinyint(1) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `PERSONNE_oid` int(11) NOT NULL,
  PRIMARY KEY (`oid`),
  UNIQUE KEY `reference` (`reference`),
  KEY `don_has_personne` (`PERSONNE_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Un don' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_DON_DETAIL`
--

CREATE TABLE IF NOT EXISTS `PP_DON_DETAIL` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `montant` float(6,2) NOT NULL,
  `DON_oid` int(11) NOT NULL,
  `DON_POSTE_oid` int(11) NOT NULL,
  PRIMARY KEY (`oid`),
  KEY `detail_has_don` (`DON_oid`),
  KEY `detail_has_poste` (`DON_POSTE_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Le détail d''un don' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_DON_POSTE`
--

CREATE TABLE IF NOT EXISTS `PP_DON_POSTE` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `brief` varchar(750) COLLATE utf8_general_ci NOT NULL,
  `idInt` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Un poste pour un don' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_MAILING_LISTS`
--

CREATE TABLE IF NOT EXISTS `PP_MAILING_LISTS` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `libelle` varchar(150) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Une mailing list' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_PAYS`
--

CREATE TABLE IF NOT EXISTS `PP_PAYS` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `isoCode` char(3) COLLATE utf8_general_ci NOT NULL,
  `libelle` varchar(150) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Un pays' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_PERSONNE`
--

CREATE TABLE IF NOT EXISTS `PP_PERSONNE` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(25) COLLATE utf8_general_ci NOT NULL COMMENT 'Référence de la personne',
  `nom` varchar(150) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `prenoms` varchar(250) COLLATE utf8_general_ci NOT NULL,
  `adresseFiscale_ligne1` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `adresseFiscale_ligne2` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `adresseFiscale_codePostal` varchar(10) COLLATE utf8_general_ci NOT NULL,
  `adresseFiscale_ville` varchar(150) COLLATE utf8_general_ci NOT NULL,
  `adresseFiscale_PAYS_oid` int(11) NOT NULL,
  `adressePostale_ligne1` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `adressePostale_ligne2` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `adressePostale_codePostal` varchar(10) COLLATE utf8_general_ci NOT NULL,
  `adressePostale_ville` varchar(150) COLLATE utf8_general_ci NOT NULL,
  `adressePostale_PAYS_oid` int(11) NOT NULL,
  `adressesDifferentes` tinyint(1) NOT NULL COMMENT 'Adresse fiscale différente de l''adresse postale',
  `email` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8_general_ci NOT NULL,
  `pseudonyme` varchar(150) COLLATE utf8_general_ci NOT NULL,
  `autresInformations` text COLLATE utf8_general_ci NOT NULL,
  `commentaires` text COLLATE utf8_general_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `DateMAJ` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`oid`),
  KEY `adressePostale_has_pays` (`adressePostale_PAYS_oid`),
  KEY `adresseFiscale_has_pays` (`adresseFiscale_PAYS_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Une Personne' ;

-- --------------------------------------------------------

--
-- Structure de la table `PP_SECTION_LOCALE`
--

CREATE TABLE IF NOT EXISTS `PP_SECTION_LOCALE` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(150) COLLATE utf8_general_ci NOT NULL,
  `idInt` varchar(30) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Un section locale' ;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `V_PP_ADHESION`
--
CREATE TABLE IF NOT EXISTS `V_PP_ADHESION` (
`reference` varchar(25)
,`nom` varchar(150)
,`prenoms` varchar(250)
,`inscritForum` varchar(3)
,`pseudonymeForum` varchar(150)
,`identifiantClePGP` varchar(150)
,`urlClePGP` varchar(150)
,`abonnementML` text
,`adhesion_reference` varchar(25)
,`montantCotisation` float(6,2)
,`accepteRiStatut` varchar(3)
,`declarationHonneur` varchar(3)
,`optinStat` varchar(3)
,`dateCreation` datetime
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `V_PP_DON`
--
CREATE TABLE IF NOT EXISTS `V_PP_DON` (
`reference` varchar(25)
,`nom` varchar(150)
,`prenoms` varchar(250)
,`don_reference` varchar(25)
,`montantDon` float(6,2)
,`accepteRiStatut` varchar(3)
,`declarationHonneur` varchar(3)
,`optinStat` varchar(3)
,`dateCreation` datetime
);
-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `V_PP_PERSONNE`
--
CREATE TABLE IF NOT EXISTS `V_PP_PERSONNE` (
`Personne_oid` int(11)
,`reference` varchar(25)
,`nom` varchar(150)
,`prenoms` varchar(250)
,`adresseFiscale_ligne1` varchar(255)
,`adresseFiscale_ligne2` varchar(255)
,`adresseFiscale_codePostal` varchar(10)
,`adresseFiscale_ville` varchar(150)
,`adresseFiscale_Pays` varchar(150)
,`adressePostale_ligne1` varchar(255)
,`adressePostale_ligne2` varchar(255)
,`adressePostale_codePostal` varchar(10)
,`adressePostale_ville` varchar(150)
,`adressePostale_Pays` varchar(150)
,`adressesDifferentes` varchar(3)
,`email` varchar(255)
,`telephone` varchar(20)
,`pseudonyme` varchar(150)
,`autresInformations` text
,`commentaires` text
,`dateCreation` datetime
,`DateMAJ` timestamp
);
-- --------------------------------------------------------

--
-- Structure de la vue `V_PP_ADHESION`
--
DROP TABLE IF EXISTS `V_PP_ADHESION`;

CREATE VIEW `V_PP_ADHESION` AS select `VPS`.`reference` AS `reference`,`VPS`.`nom` AS `nom`,`VPS`.`prenoms` AS `prenoms`,if((`AT`.`inscritForum` = 1),'OUI','NON') AS `inscritForum`,`AT`.`pseudonymeForum` AS `pseudonymeForum`,`AT`.`identifiantClePGP` AS `identifiantClePGP`,`AT`.`urlClePGP` AS `urlClePGP`,`AT`.`abonnementML` AS `abonnementML`,`AN`.`reference` AS `adhesion_reference`,`AN`.`montantCotisation` AS `montantCotisation`,if((`AN`.`accepteRiStatut` = 1),'OUI','NON') AS `accepteRiStatut`,if((`AN`.`declarationHonneur` = 1),'OUI','NON') AS `declarationHonneur`,if((`AN`.`optinStat` = 1),'OUI','NON') AS `optinStat`,`AN`.`dateCreation` AS `dateCreation` from ((`V_PP_PERSONNE` `VPS` join `PP_ADHERENT` `AT` on((`AT`.`PERSONNE_oid` = `VPS`.`Personne_oid`))) join `PP_ADHESION` `AN` on((`AT`.`oid` = `AN`.`ADHERENT_oid`)));

-- --------------------------------------------------------

--
-- Structure de la vue `V_PP_DON`
--
DROP TABLE IF EXISTS `V_PP_DON`;

CREATE VIEW `V_PP_DON` AS select `VPS`.`reference` AS `reference`,`VPS`.`nom` AS `nom`,`VPS`.`prenoms` AS `prenoms`,`D`.`reference` AS `don_reference`,`D`.`montantDon` AS `montantDon`,if((`D`.`accepteRiStatut` = 1),'OUI','NON') AS `accepteRiStatut`,if((`D`.`declarationHonneur` = 1),'OUI','NON') AS `declarationHonneur`,if((`D`.`optinStat` = 1),'OUI','NON') AS `optinStat`,`D`.`dateCreation` AS `dateCreation` from (`V_PP_PERSONNE` `VPS` join `PP_DON` `D` on((`D`.`PERSONNE_oid` = `VPS`.`Personne_oid`)));

-- --------------------------------------------------------

--
-- Structure de la vue `V_PP_PERSONNE`
--
DROP TABLE IF EXISTS `V_PP_PERSONNE`;

CREATE VIEW `V_PP_PERSONNE` AS select `P`.`oid` AS `Personne_oid`,`P`.`reference` AS `reference`,`P`.`nom` AS `nom`,`P`.`prenoms` AS `prenoms`,`P`.`adresseFiscale_ligne1` AS `adresseFiscale_ligne1`,`P`.`adresseFiscale_ligne2` AS `adresseFiscale_ligne2`,`P`.`adresseFiscale_codePostal` AS `adresseFiscale_codePostal`,`P`.`adresseFiscale_ville` AS `adresseFiscale_ville`,(select `PP_PAYS`.`libelle` from `PP_PAYS` where (`PP_PAYS`.`oid` = `P`.`adresseFiscale_PAYS_oid`)) AS `adresseFiscale_Pays`,`P`.`adressePostale_ligne1` AS `adressePostale_ligne1`,`P`.`adressePostale_ligne2` AS `adressePostale_ligne2`,`P`.`adressePostale_codePostal` AS `adressePostale_codePostal`,`P`.`adressePostale_ville` AS `adressePostale_ville`,(select `PP_PAYS`.`libelle` from `PP_PAYS` where (`PP_PAYS`.`oid` = `P`.`adressePostale_PAYS_oid`)) AS `adressePostale_Pays`,if((`P`.`adressesDifferentes` = 1),'OUI','NON') AS `adressesDifferentes`,`P`.`email` AS `email`,`P`.`telephone` AS `telephone`,`P`.`pseudonyme` AS `pseudonyme`,`P`.`autresInformations` AS `autresInformations`,`P`.`commentaires` AS `commentaires`,`P`.`dateCreation` AS `dateCreation`,`P`.`DateMAJ` AS `DateMAJ` from `PP_PERSONNE` `P`;
