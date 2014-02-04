SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `PP_ADHERENT` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `isMajeur` tinyint(4) DEFAULT NULL,
  `inscritForum` tinyint(1) NOT NULL,
  `pseudonymeForum` varchar(150) NOT NULL,
  `identifiantClePGP` varchar(150) NOT NULL COMMENT 'Sur serveur pirate',
  `urlClePGP` varchar(150) NOT NULL COMMENT 'sur serveur externe',
  `SECTION_LOCALE_oid` int(11) NOT NULL,
  `abonnementML` text NOT NULL,
  `infoCreationSectionLocale` varchar(150) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateMAJ` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `PERSONNE_oid` int(11) NOT NULL,
  PRIMARY KEY (`oid`),
  KEY `adherent_is_personne` (`PERSONNE_oid`),
  KEY `adherent_has_sectionLocale` (`SECTION_LOCALE_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Un adh�rent' AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `PP_ADHESION` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(25) NOT NULL COMMENT 'R�f�rence de la transaction',
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Une adhesion' AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `PP_APAYER_TRANSACTION` (
  `oid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PERSONNE_oid` int(11) DEFAULT NULL,
  `DON_oid` int(11) DEFAULT NULL,
  `ADHESION_oid` int(11) DEFAULT NULL,
  `isMatchRef` tinyint(4) DEFAULT NULL,
  `isMatchAmount` tinyint(4) DEFAULT NULL,
  `datePaiement` date NOT NULL,
  `objetPaiement` tinyint(4) DEFAULT NULL,
  `autreObjet` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `montant` float NOT NULL DEFAULT '0',
  `reference` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `commentaire` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `referenceBancaire` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `numeroAutorisation` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `nom` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `prenom` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `adresse` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `codePostal` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `ville` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `courriel` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `etat` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `etatManuel` tinyint(4) DEFAULT NULL,
  `motifRefus` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `cvx` tinyint(4) DEFAULT NULL,
  `vld` varchar(4) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `brand` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `status3DS` tinyint(4) DEFAULT NULL,
  `by` varchar(255) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

CREATE TABLE IF NOT EXISTS `PP_CMCIC_TRANSACTION` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` char(12) NOT NULL,
  `fields` text NOT NULL,
  `hmac` varchar(45) NOT NULL,
  `paymentDone` tinyint(1) NOT NULL,
  `issue` varchar(50) NOT NULL,
  `DON_oid` int(11) NOT NULL,
  `ADHESION_oid` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateMAJ` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`oid`),
  KEY `payment_has_don` (`DON_oid`),
  KEY `payment_has_adhesion` (`ADHESION_oid`),
  KEY `payment_has_reference` (`reference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Un paiement CM-CIC' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `PP_PAYBOX_TRANSACTION` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` char(12) NOT NULL,
  `fields` text NOT NULL,
  `hmac` varchar(45) NOT NULL,
  `paymentDone` tinyint(1) NOT NULL,
  `issue` varchar(50) NOT NULL,
  `DON_oid` int(11) NOT NULL,
  `ADHESION_oid` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `dateMAJ` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`oid`),
  KEY `payment_has_don` (`DON_oid`),
  KEY `payment_has_adhesion` (`ADHESION_oid`),
  KEY `payment_has_reference` (`reference`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Un paiement PAYBOX' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `PP_DON` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(25) NOT NULL COMMENT 'R�f�rence de la transaction',
  `montantDon` float(6,2) NOT NULL,
  `accepteRiStatut` tinyint(1) NOT NULL,
  `declarationHonneur` tinyint(1) NOT NULL,
  `optinStat` tinyint(1) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `PERSONNE_oid` int(11) NOT NULL,
  PRIMARY KEY (`oid`),
  UNIQUE KEY `reference` (`reference`),
  KEY `don_has_personne` (`PERSONNE_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Un don' AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `PP_DON_DETAIL` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `montant` float(6,2) NOT NULL,
  `DON_oid` int(11) NOT NULL,
  `DON_POSTE_oid` int(11) NOT NULL,
  PRIMARY KEY (`oid`),
  KEY `detail_has_don` (`DON_oid`),
  KEY `detail_has_poste` (`DON_POSTE_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Le d�tail d''un don' AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `PP_DON_POSTE` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(250) NOT NULL,
  `brief` varchar(750) NOT NULL,
  `idInt` varchar(50) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Un poste pour un don' AUTO_INCREMENT=31 ;

CREATE TABLE IF NOT EXISTS `PP_MAILING_LISTS` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `libelle` varchar(150) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Une mailing list' AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `PP_PAYS` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `isoCode` char(3) NOT NULL,
  `libelle` varchar(150) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Un pays' AUTO_INCREMENT=238 ;

CREATE TABLE IF NOT EXISTS `PP_PERSONNE` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(25) NOT NULL COMMENT 'R�f�rence de la personne',
  `nom` varchar(150) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL,
  `prenoms` varchar(250) NOT NULL,
  `adresseFiscale_ligne1` varchar(255) NOT NULL,
  `adresseFiscale_ligne2` varchar(255) NOT NULL,
  `adresseFiscale_codePostal` varchar(10) NOT NULL,
  `adresseFiscale_ville` varchar(150) NOT NULL,
  `adresseFiscale_PAYS_oid` int(11) NOT NULL,
  `adressePostale_ligne1` varchar(255) NOT NULL,
  `adressePostale_ligne2` varchar(255) NOT NULL,
  `adressePostale_codePostal` varchar(10) NOT NULL,
  `adressePostale_ville` varchar(150) NOT NULL,
  `adressePostale_PAYS_oid` int(11) NOT NULL,
  `adressesDifferentes` tinyint(1) NOT NULL COMMENT 'Adresse fiscale diff�rente de l''adresse postale',
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `pseudonyme` varchar(150) NOT NULL,
  `autresInformations` text NOT NULL,
  `commentaires` text NOT NULL,
  `dateCreation` datetime NOT NULL,
  `DateMAJ` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`oid`),
  KEY `adressePostale_has_pays` (`adressePostale_PAYS_oid`),
  KEY `adresseFiscale_has_pays` (`adresseFiscale_PAYS_oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Une Personne' AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `PP_SECTION_LOCALE` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(150) NOT NULL,
  `idInt` varchar(30) NOT NULL,
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Un section locale' AUTO_INCREMENT=16 ;




DROP VIEW IF EXISTS `V_PP_ADHESION`;
CREATE VIEW `V_PP_ADHESION` AS 
SELECT 
	`VPS`.`reference` AS `reference`,
	`VPS`.`nom` AS `nom`,
	`VPS`.`prenoms` AS `prenoms`,
	`AN`.`reference` AS `adhesion_reference`,
	`AN`.`montantCotisation` AS `montantCotisation`,
	`APAYER`.`etatManuel` AS `APAYER_etatManuel`,
	`APAYER`.`etat` AS `APAYER_etat`,
	`APAYER`.`isMatchAmount` AS `APAYER_isMatchAmount`,
	`APAYER`.`isMatchRef` AS `APAYER_isMatchRef`,
	`APAYER`.`datePaiement` AS `APAYER_datePaiement`,
	`APAYER`.`montant` AS `APAYER_montantTotal`,
	`APAYER`.`referenceBancaire` AS `APAYER_referenceBancaire`,
	`APAYER`.`status3DS` AS `APAYER_status3DS`,
	`APAYER`.`vld` AS `APAYER_vld`,
	if((`AN`.`isRenouvellement` = 1),'OUI','NON') AS `isRenouvellement`,
	if((`AT`.`isMajeur` = 1),'OUI','NON') AS `isMajeur`,
	if((`AT`.`inscritForum` = 1),'OUI','NON') AS `inscritForum`,
	`AT`.`pseudonymeForum` AS `pseudonymeForum`,
	`AT`.`identifiantClePGP` AS `identifiantClePGP`,
	`AT`.`urlClePGP` AS `urlClePGP`,
	`AT`.`abonnementML` AS `abonnementML`,
	if((`AN`.`accepteRiStatut` = 1),'OUI','NON') AS `accepteRiStatut`,
	if((`AN`.`declarationHonneur` = 1),'OUI','NON') AS `declarationHonneur`,
	if((`AN`.`optinStat` = 1),'OUI','NON') AS `optinStat`,
	`AN`.`dateCreation` AS `dateCreation` 
FROM 

		(
			(
				`V_PP_PERSONNE` `VPS` join `PP_ADHERENT` `AT` ON `AT`.`PERSONNE_oid` = `VPS`.`Personne_oid`
			) 
			
			JOIN `PP_ADHESION` `AN` ON `AT`.`oid` = `AN`.`ADHERENT_oid`
		) 
		LEFT JOIN `PP_APAYER_TRANSACTION` `APAYER` ON `AN`.`oid` = `APAYER`.`ADHESION_oid`
;

DROP VIEW IF EXISTS `V_PP_AFPP`;
CREATE VIEW `V_PP_AFPP` AS 
SELECT 
	`APAYER`.`isMatchRef` AS `isMatchRef`,
	`APAYER`.`isMatchAmount` AS `APAYER_isMatchAmount`,
	`APAYER`.`datePaiement` AS `APAYER_datePaiement`,
	`APAYER`.`montant` AS `APAYER_montantTotal`,
	`VPER`.`reference` AS `reference`,
	`VPER`.`nom` AS `nom`,
	`VPER`.`prenoms` AS `prenoms`,
	`VPER`.`adresseFiscale_ligne1` AS `adresseFiscale_ligne1`,
	`VPER`.`adresseFiscale_ligne2` AS `adresseFiscale_ligne2`,
	`VPER`.`adresseFiscale_codePostal` AS `adresseFiscale_codePostal`,
	`VPER`.`adresseFiscale_ville` AS `adresseFiscale_ville`,
	`VPER`.`adresseFiscale_Pays` AS `adresseFiscale_Pays`,
	`VPER`.`adressePostale_ligne1` AS `adressePostale_ligne1`,
	`VPER`.`adressePostale_ligne2` AS `adressePostale_ligne2`,
	`VPER`.`adressePostale_codePostal` AS `adressePostale_codePostal`,
	`VPER`.`adressePostale_Pays` AS `adressePostale_Pays`,
	`VPER`.`adressesDifferentes` AS `adressesDifferentes`,
	`VPER`.`email` AS `email`,`VPER`.`telephone` AS `telephone`,
	`VPER`.`pseudonyme` AS `pseudonyme` 
FROM 
	`PP_APAYER_TRANSACTION` `APAYER` JOIN `V_PP_PERSONNE` `VPER` ON `VPER`.`Personne_oid` = `APAYER`.`PERSONNE_oid`
;

DROP VIEW IF EXISTS `V_PP_DON`;
CREATE VIEW `V_PP_DON` AS 
SELECT 
	`VPS`.`reference` AS `reference`,
	`VPS`.`nom` AS `nom`,
	`VPS`.`prenoms` AS `prenoms`,
	`D`.`reference` AS `don_reference`,
	`D`.`montantDon` AS `montantDon`,
	`APAYER`.`etatManuel` AS `APAYER_etatManuel`,
	`APAYER`.`etat` AS `APAYER_etat`,
	`APAYER`.`isMatchAmount` AS `APAYER_isMatchAmount`,
	`APAYER`.`isMatchRef` AS `APAYER_isMatchRef`,
	`APAYER`.`datePaiement` AS `APAYER_datePaiement`,
	`APAYER`.`montant` AS `APAYER_montantTotal`,
	`APAYER`.`referenceBancaire` AS `APAYER_referenceBancaire`,
	`APAYER`.`status3DS` AS `APAYER_status3DS`,
	`APAYER`.`vld` AS `APAYER_vld`,if((`D`.`accepteRiStatut` = 1),'OUI','NON') AS `accepteRiStatut`,
	if((`D`.`declarationHonneur` = 1),'OUI','NON') AS `declarationHonneur`,
	if((`D`.`optinStat` = 1),'OUI','NON') AS `optinStat`,
	`D`.`dateCreation` AS `dateCreation` 
FROM 
	
	(
		`V_PP_PERSONNE` `VPS` JOIN `PP_DON` `D` ON `D`.`PERSONNE_oid` = `VPS`.`Personne_oid`
	)
	LEFT JOIN `PP_APAYER_TRANSACTION` `APAYER` ON `D`.`oid` = `APAYER`.`DON_oid`
;

DROP VIEW IF EXISTS `V_PP_DON_DETAIL`;
CREATE VIEW `V_PP_DON_DETAIL` AS 
SELECT 
	`VPS`.`reference` AS `reference`,
	`VPS`.`nom` AS `nom`,
	`VPS`.`prenoms` AS `prenoms`,
	`D`.`reference` AS `don_reference`,
	`DP`.`idInt` AS `poste`,
	`DD`.`montant` AS `montantPoste`,
	`APAYER`.`etatManuel` AS `APAYER_etatManuel`,
	`APAYER`.`etat` AS `APAYER_etat`,
	`APAYER`.`isMatchAmount` AS `APAYER_isMatchAmount`,
	`APAYER`.`isMatchRef` AS `APAYER_isMatchRef`,
	`APAYER`.`datePaiement` AS `APAYER_datePaiement`,
	`APAYER`.`montant` AS `APAYER_montantTotal`,
	`APAYER`.`referenceBancaire` AS `APAYER_referenceBancaire`,
	`APAYER`.`status3DS` AS `APAYER_status3DS`,
	`APAYER`.`vld` AS `APAYER_vld`,
	if((`D`.`accepteRiStatut` = 1),'OUI','NON') AS `accepteRiStatut`,
	if((`D`.`declarationHonneur` = 1),'OUI','NON') AS `declarationHonneur`,
	if((`D`.`optinStat` = 1),'OUI','NON') AS `optinStat`,
	`D`.`dateCreation` AS `dateCreation` 
FROM
	(
		(
			(
				`V_PP_PERSONNE` `VPS` join `PP_DON` `D` ON `D`.`PERSONNE_oid` = `VPS`.`Personne_oid`
			) 
			JOIN `PP_DON_DETAIL` `DD` ON `DD`.`DON_oid` = `D`.`oid`
		) 
		JOIN `PP_DON_POSTE` `DP` ON `DP`.`oid` = `DD`.`DON_POSTE_oid`
	) 
	LEFT JOIN `PP_APAYER_TRANSACTION` `APAYER` ON `D`.`oid` = `APAYER`.`DON_oid`
;

DROP VIEW IF EXISTS `V_PP_PERSONNE`;
CREATE VIEW `V_PP_PERSONNE` AS 
SELECT 
	`P`.`oid` AS `Personne_oid`,
	`P`.`reference` AS `reference`,
	`P`.`nom` AS `nom`,
	`P`.`prenoms` AS `prenoms`,
	`P`.`adresseFiscale_ligne1` AS `adresseFiscale_ligne1`,
	`P`.`adresseFiscale_ligne2` AS `adresseFiscale_ligne2`,
	`P`.`adresseFiscale_codePostal` AS `adresseFiscale_codePostal`,
	`P`.`adresseFiscale_ville` AS `adresseFiscale_ville`,
	(SELECT `PP_PAYS`.`libelle` FROM `PP_PAYS` WHERE `PP_PAYS`.`oid` = `P`.`adresseFiscale_PAYS_oid`) AS `adresseFiscale_Pays`,
	`P`.`adressePostale_ligne1` AS `adressePostale_ligne1`,
	`P`.`adressePostale_ligne2` AS `adressePostale_ligne2`,
	`P`.`adressePostale_codePostal` AS `adressePostale_codePostal`,
	`P`.`adressePostale_ville` AS `adressePostale_ville`,
	(SELECT `PP_PAYS`.`libelle` FROM `PP_PAYS` WHERE `PP_PAYS`.`oid` = `P`.`adressePostale_PAYS_oid`) AS `adressePostale_Pays`,
	if((`P`.`adressesDifferentes` = 1),'OUI','NON') AS `adressesDifferentes`,
	`P`.`email` AS `email`,
	`P`.`telephone` AS `telephone`,
	`P`.`pseudonyme` AS `pseudonyme`,
	`P`.`autresInformations` AS `autresInformations`,
	`P`.`commentaires` AS `commentaires`,
	`P`.`dateCreation` AS `dateCreation`,
	`P`.`DateMAJ` AS `DateMAJ` 
FROM
	`PP_PERSONNE` `P`
;
