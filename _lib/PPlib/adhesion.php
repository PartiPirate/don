<?php
/**
 * Librairie adhesion
 * @TODO Ajouter Une fonction de test sur la disponibilité des serveurs Voir ManuelIntegrationPayboxSystem_V5.08_FR.pdf p17
 */
namespace PPlib\adhesion;
use PPlib as lib;

include_once sprintf('%s/sanitize.php', PPLIB_PATH);

/**
 * Contrôle les données saisies dans un formulaire d'adhesion
 * @param array $aValues les valeurs à contrôler
 * Le format est le suivant
 *
 * $aValues = array(
 * 	'personne.oid' => NULL, // l'oid de la base de données
 * 	'personne.reference' => NULL, // la réference est générée lors de l'enregistrement
 * 	'personne.nom' => 'le nom',
 * 	'personne.prenoms' => 'le ou les prénoms',
 * 	'personne.adresseFiscale_ligne1' => 'adresseFiscale ligne1',
 * 	'personne.adresseFiscale_ligne2' => 'adresseFiscale ligne2',
 * 	'personne.adresseFiscale_codePostal' => 'adresseFiscale ville',
 * 	'personne.adresseFiscale_ville' => 'adresseFiscale ville',
 * 	'personne.adresseFiscale_pays_oid' => '40',	// OID du pays
 * 	'personne.adressePostale_ligne1' => 'adressePostale ligne1',
 * 	'personne.adressePostale_ligne2' => 'adressePostale ligne2',
 * 	'personne.adressePostale_codePostal' => 'adressePostale ligne2',
 * 	'personne.adressePostale_ville' => 'adressePostale ville',
 * 	'personne.adressePostale_pays_oid' => '40',	// OID du pays
 * 	'personne.adressesDifferentes' => '1',
 * 	'personne.email' => 'address@mail.org',
 * 	'personne.telephone' => '01 23 45 67 89',
 * 	'personne.pseudonyme' => 'pseudo',
 * 	'personne.autresInformations' => 'autresInformations : lorem ipsum dolor sit amet',
 * 	'personne.commentaires' => 'commentaires : lorem ipsum dolor sit amet',
 * 	
 *	'personne.hasDon' => TRUE,
 *	'personne.hasAdhesion' => TRUE,
 *
 * 	'adherent.inscritForum' => '1',
 * 	'adherent.pseudonymeForum' => 'pseudoForum',
 * 	'adherent.identifiantPGP' => '0x123456789',
 * 	'adherent.urlPGP' => 'http://www.url.com/0x123456789',
 * 	'adherent.abonnementML' => 'general;cr-conseil;annonces-consultations', // Les codes des ML séparés par des ;
 * 	'adherent.infoCreationSectionLocale' => 'Pays basque', 
 * 	'adherent.sectionLocale' => 1, // OID de la section locale
 * 	
 * 	'adhesion.reference' => NULL, // la réference est générée lors de l'enregistrement
 * 	'adhesion.montantCotisation' => '20',
 * 	'adhesion.accepteRIStatut' => '1',
 * 	'adhesion.declarationHonneur' => '1',
 * 	'adhesion.optinStat' => '1',
 * 	
 * 	'don.reference' => NULL, // la réference est générée lors de l'enregistrement
 * 	'don.montantDon' => 20,
 * 	'don.accepteRIStatut' => '1',
 * 	'don.declarationHonneur' => '1',
 * 	'don.optinStat' => '1',
 *
 * 	// En indice, l'OID du poste et en valeur le montant
 * 	'don.details' => array(
 * 		'1' => '10',
 * 		'2' => '20',
 * 		'3' => '20'
 * 	)
 * );
 * 
 * @return array Un tableau contenant les messages d'erreurs.
 */
function checkAdhesionFormValues(&$aValues){

	$aReturn = array('issue' => ACTION_SUCCESS, 'message' => '');
	$bHasFault = FALSE;
	
	// -----------------------------------------------------------------------------------
	// Sanitize
	$aValues['personne.oid'] 						= NULL;
	$aValues['personne.reference'] 					= NULL;
	$aValues['personne.nom'] 						= lib\toSafeHTMLString($aValues['personne.nom']);
	$aValues['personne.prenoms'] 					= lib\toSafeHTMLString($aValues['personne.prenoms']);
	$aValues['personne.adresseFiscale_ligne1'] 		= lib\toSafeHTMLString($aValues['personne.adresseFiscale_ligne1']);
	$aValues['personne.adresseFiscale_ligne2'] 		= lib\toSafeHTMLString($aValues['personne.adresseFiscale_ligne2']);
	$aValues['personne.adresseFiscale_ligne2'] 		= lib\toSafeHTMLString($aValues['personne.adresseFiscale_ligne2']);
	$aValues['personne.adresseFiscale_codePostal'] 	= lib\toSafeHTMLString($aValues['personne.adresseFiscale_codePostal']);
	$aValues['personne.adresseFiscale_ville'] 		= lib\toSafeHTMLString($aValues['personne.adresseFiscale_ville']);
	$aValues['personne.adresseFiscale_pays_oid'] 	= lib\toInt($aValues['personne.adresseFiscale_pays_oid']);
	
	$aValues['personne.adressePostale_ligne1'] 		= lib\toSafeHTMLString($aValues['personne.adressePostale_ligne1']);
	$aValues['personne.adressePostale_ligne2'] 		= lib\toSafeHTMLString($aValues['personne.adressePostale_ligne2']);
	$aValues['personne.adressePostale_codePostal'] 	= lib\toSafeHTMLString($aValues['personne.adressePostale_codePostal']);
	$aValues['personne.adressePostale_ville'] 		= lib\toSafeHTMLString($aValues['personne.adressePostale_ville']);
	$aValues['personne.adressePostale_pays_oid'] 	= lib\toInt($aValues['personne.adressePostale_pays_oid']);
	
	$aValues['personne.adressesDifferentes'] 		= lib\toBool($aValues['personne.adressesDifferentes']);
	
	$aValues['personne.email'] 						= lib\toSafeHTMLString($aValues['personne.email']);
	$aValues['personne.telephone'] 					= lib\toSafeHTMLString($aValues['personne.telephone']);
	$aValues['personne.pseudonyme'] 				= lib\toSafeHTMLString($aValues['personne.pseudonyme']);
	$aValues['personne.autresInformations'] 		= lib\toSafeHTMLString($aValues['personne.autresInformations']);
	$aValues['personne.commentaires'] 				= lib\toSafeHTMLString($aValues['personne.commentaires']);
	
	$aValues['personne.hasDon'] 			= lib\toBool($aValues['personne.hasDon']);
	$aValues['personne.hasAdhesion'] 		= lib\toBool($aValues['personne.hasAdhesion']);
	
	$aValues['adherent.isMajeur'] 					= lib\toBool($aValues['adherent.isMajeur']);
	$aValues['adherent.inscritForum'] 		= lib\toFloat($aValues['adherent.inscritForum']);
	$aValues['adherent.pseudonymeForum'] 	= lib\toSafeHTMLString($aValues['adherent.pseudonymeForum']);
	$aValues['adherent.identifiantPGP'] 	= lib\toSafeHTMLString($aValues['adherent.identifiantPGP']);
	$aValues['adherent.urlPGP'] 			= lib\toSafeHTMLString($aValues['adherent.urlPGP']);
	$aValues['adherent.abonnementML'] 		= lib\toSafeHTMLString($aValues['adherent.abonnementML']);
	$aValues['adherent.infoCreationSectionLocale'] = lib\toSafeHTMLString($aValues['adherent.infoCreationSectionLocale']);
	$aValues['adherent.sectionLocale'] 		= lib\toInt($aValues['adherent.sectionLocale']);
	
	$aValues['adhesion.oid'] 				= NULL;
	$aValues['adhesion.reference'] 			= NULL;
	$aValues['adhesion.montantCotisation'] = lib\toFloat($aValues['adhesion.montantCotisation']);
	$aValues['adhesion.isRenouvellement'] = lib\toBool($aValues['adhesion.isRenouvellement']);
	$aValues['adhesion.accepteRIStatut'] 	= lib\toBool($aValues['adhesion.accepteRIStatut']);
	$aValues['adhesion.declarationHonneur'] = lib\toBool($aValues['adhesion.declarationHonneur']);
	$aValues['adhesion.optinStat'] 			= lib\toBool($aValues['adhesion.optinStat']);
	
	$aValues['don.oid'] 				= NULL;
	$aValues['don.reference'] 			= NULL;
	$aValues['don.montantDon'] 			= lib\toFloat($aValues['don.montantDon']);
	$aValues['don.accepteRIStatut'] 	= lib\toBool($aValues['don.accepteRIStatut']);
	$aValues['don.declarationHonneur'] 	= lib\toBool($aValues['don.declarationHonneur']);
	$aValues['don.optinStat'] 			= lib\toBool($aValues['don.optinStat']);
	
	$aTemp = array();
	
	if(is_array($aValues['don.details'])){
		foreach($aValues['don.details'] as $sIndex => $sValue){
			$aTemp[lib\toInt($sIndex)] = lib\toFloat($sValue);
		}//end foreach
	}//end if
	
	$aValues['don.details'] = $aTemp;
	
	// -----------------------------------------------------------------------------------
	// Check values
	checkStrLength($aValues, $aReturn, 'personne.nom', 'Vous devez saisir un nom');
	checkStrLength($aValues, $aReturn, 'personne.prenoms', 'Vous devez saisir un prénom');
	checkStrLength($aValues, $aReturn, 'personne.adresseFiscale_ligne1', "Vous devez au moins indiquer une ligne de l'adresse fiscale");
	checkStrLength($aValues, $aReturn, 'personne.adresseFiscale_ville', "Vous devez saisir la ville de l'adresse fiscale");
	checkValueSupZero($aValues, $aReturn, 'personne.adresseFiscale_pays_oid', "Le code pays n'est pas correct");
	
	checkValueIsEmail($aValues, $aReturn, 'personne.email', "Le format de l'adresse email n'est pas correct");
		
	if($aValues['personne.adressesDifferentes']){
	
		checkStrLength($aValues, $aReturn, 'personne.adressePostale_ligne1', "Vous devez au moins indiquer une ligne de l'adresse postale");
		checkStrLength($aValues, $aReturn, 'personne.adressePostale_ville', "Vous devez saisir la ville de l'adresse postale");
		checkValueSupZero($aValues, $aReturn, 'personne.adressePostale_pays_oid', "Le code pays n'est pas correct");
		
	}//end if
	else{
		$aValues['personne.adressePostale_ligne1'] = '';
		$aValues['personne.adressePostale_ville'] = '';
		$aValues['personne.adressePostale_pays_oid'] = 0;
	}//end else
	
	if(!$aValues['personne.hasAdhesion'] && !$aValues['personne.hasDon']){
		$aReturn['issue'] = ACTION_FAILURE;
		$aReturn['personne.hasAdhesion'] = "Vous devez choisir d'adhérer et/ou de faire un don";
	}//end if
	else{
	
		if($aValues['personne.hasAdhesion']){
		
			if($aValues['adherent.inscritForum']){
				
				// On recherche le pseudonyme pour le forum. On renvoie une erreur sur les deux valeurs sinon on synchronise les valeurs
				if(strlen($aValues['personne.pseudonyme']) == 0 && strlen($aValues['adherent.pseudonymeForum'] == 0)){
					checkStrLength($aValues, $aReturn, 'personne.pseudonyme', "Vous devez indiquer le pseudonyme sur le forum");
					checkStrLength($aValues, $aReturn, 'adherent.pseudonymeForum', "Vous devez indiquer le pseudonyme sur le forum");
				}//end if
				elseif(strlen($aValues['personne.pseudonyme']) == 0){
					$aValues['personne.pseudonyme'] = $aValues['adherent.pseudonymeForum'];
				}//end if
				elseif(strlen($aValues['adherent.pseudonymeForum']) == 0){
					$aValues['adherent.pseudonymeForum'] = $aValues['personne.pseudonyme'];
				}//end if
				
			}//end if
			else{
				$aValues['adherent.pseudonymeForum'] = '';
			}//end else
			
			checkValueLimiteAdhesion($aValues, $aReturn, 'adhesion.montantCotisation', "Le montant de la cotisation doit être supérieur à 6 euros et inférieur à 7500 euros");
			
		}//end if
		else{
			$aValues['adhesion.montantCotisation'] = 0;
			$aValues['adherent.pseudonymeForum'] = '';
			$aValues['adherent.inscritForum'] = FALSE;
		}//end else
		
		if($aValues['personne.hasDon']){
			
			$fMontantDon = 0;

			foreach($aValues['don.details'] as $iOidPoste => $fValue){
				$fMontantDon += $fValue;
			}//end foreach
			
			$aValues['don.montantDon'] = $fMontantDon;
			checkValueLimiteAdhesion($aValues, $aReturn, 'don.montantDon', "Le montant d'un don doit être inférieur à 7500 euros et supérieur à 10€ (en raison des coûts de traitement)");
			
		}//end if
		else{
			$aValues['don.montantDon'] = 0;
		}//end else
	}//end else
	
	/// ----------
	
	if($aReturn['issue'] == ACTION_SUCCESS){
		
		$aValues['personne.reference'] = createCodeReference('PER');
		
		if($aValues['personne.hasDon']){
			$aValues['don.reference'] = createCodeReference('DON');
		}//end if
		
		if($aValues['personne.hasAdhesion']){
			$aValues['adhesion.reference'] = createCodeReference('ADH');
		}//end if
		
	}//end if
	
	// -----------------------------------------------------------------------------------
	// End
	return $aReturn;
	
}//end function

/**
 * Enregistre les données saisies dans un formulaire d'adhesion.
 * Cette fonction appelle au prélalable 'checkAdhesionFormValues'
 *
 * @param array $aValues les valeurs à enregistrer, voir 'checkAdhesionFormValues' pour le détail
 * Les valeurs de don.reference et adhesion.reference seront fournies au retour de la fonction
 * @return array Un tableau contenant les messages d'erreurs.
 */
function saveAdhesionFormValues(&$aValues){
	
	$aReturn = checkAdhesionFormValues($aValues);
	
	try {
		$oDBH = lib\SqlGetHandle();
	}//end try
	catch (PDOException $e) {
	
		$aReturn['issue'] = ACTION_ERROR;
		$aReturn['message'] = $e->getMessage();
		
		return $aReturn;
		
	}//end cacth
	
	$sInsertPersonne = "INSERT INTO `PP_PERSONNE` (
	`reference`, `nom`, `prenoms`, 
	`adresseFiscale_ligne1`, `adresseFiscale_ligne2`, `adresseFiscale_codePostal`, `adresseFiscale_ville`, `adresseFiscale_PAYS_oid`, 
	`adressePostale_ligne1`, `adressePostale_ligne2`, `adressePostale_codePostal`, `adressePostale_ville`, `adressePostale_PAYS_oid`, 
	`adressesDifferentes`, 
	`email`, `telephone`, 
	`pseudonyme`, 
	`autresInformations`, `commentaires`, 
	`dateCreation`
	) 
	VALUES (
		%s, %s, %s, 
		%s, %s, %s, %s, %d, 
		%s, %s, %s, %s, %d, 
		%d, 
		%s, %s, 
		%s, 
		%s, %s, 
		%s 
	)";
	
	$sInsertAdherent = "INSERT INTO `PP_ADHERENT` (
	`isMajeur`, `inscritForum`, 
	`pseudonymeForum`, `identifiantClePGP`, `urlClePGP`, 
	`SECTION_LOCALE_oid`, 
	`abonnementML`, `infoCreationSectionLocale`,
	`dateCreation`,
	`PERSONNE_oid`
	) 
	VALUES (
		%d, %d, 
		%s, %s, %s, 
		%d, 
		%s, %s,
		%s, 
		%d
	)";
	
	$sInsertAdhesion = "INSERT INTO `PP_ADHESION` (
	`reference`, `montantCotisation`, `isRenouvellement`, 
	`accepteRiStatut`, `declarationHonneur`, `optinStat`, 
	`dateCreation`,
	`ADHERENT_oid`
	) 
	VALUES (
		%s, %.2f, %d, 
		%d, %d,  %d, 
		%s,
		%d
	)";
	
	$sInsertDon = "INSERT INTO `PP_DON` (
	`reference`, `montantDon`, 
	`accepteRiStatut`, `declarationHonneur`, `optinStat`, 
	`dateCreation`,
	`PERSONNE_oid`
	) 
	VALUES (
		%s, %.2f, 
		%d, %d,  %d, 
		%s,
		%d
	)";
	
	$sInsertDonDetail = "INSERT INTO `PP_DON_DETAIL` (
	`montant`, `DON_oid`, `DON_POSTE_oid`
	) 
	VALUES (
		%.2f, %d, %d 
	)";
	
	if($aReturn['issue'] == ACTION_SUCCESS){
		
		// Backup temporaire
		$sTmpFile = sprintf('%s/%s.bak', PPLIB_PATH_TMP, $aValues['personne.reference']);
		file_put_contents($sTmpFile, var_export($aValues, TRUE));
		
		// Long try
		try {

			$sDateCreation = $oDBH->quote(date('Y-m-d H:m:s'));

			$sInsertPersonneSQL = sprintf($sInsertPersonne,
				$oDBH->quote($aValues['personne.reference']),
				$oDBH->quote($aValues['personne.nom']),
				$oDBH->quote($aValues['personne.prenoms']),
				$oDBH->quote($aValues['personne.adresseFiscale_ligne1']),
				$oDBH->quote($aValues['personne.adresseFiscale_ligne2']),
				$oDBH->quote($aValues['personne.adresseFiscale_codePostal']),
				$oDBH->quote($aValues['personne.adresseFiscale_ville']),
				$aValues['personne.adresseFiscale_pays_oid'],
				$oDBH->quote($aValues['personne.adressePostale_ligne1']),
				$oDBH->quote($aValues['personne.adressePostale_ligne2']),
				$oDBH->quote($aValues['personne.adressePostale_codePostal']),
				$oDBH->quote($aValues['personne.adressePostale_ville']),
				$aValues['personne.adressePostale_pays_oid'],
				($aValues['personne.adressesDifferentes']) ? 1 : 0,
				$oDBH->quote($aValues['personne.email']),
				$oDBH->quote($aValues['personne.telephone']),
				$oDBH->quote($aValues['personne.pseudonyme']),
				$oDBH->quote($aValues['personne.autresInformations']),
				$oDBH->quote($aValues['personne.commentaires']),
				$sDateCreation
			);
			
			$oDBH->query($sInsertPersonneSQL);
			$iOidPersonne = (int)$oDBH->lastInsertId();
			$aValues['personne.oid'] = $iOidPersonne;
			
			if($aValues['personne.hasAdhesion']){
	
				$sInsertAdherentSQL = sprintf($sInsertAdherent,
					$aValues['adherent.isMajeur'],
					$aValues['adherent.inscritForum'],
					$oDBH->quote($aValues['adherent.pseudonymeForum']),
					$oDBH->quote($aValues['adherent.identifiantPGP']),
					$oDBH->quote($aValues['adherent.urlPGP']),
					$aValues['adherent.sectionLocale'],
					$oDBH->quote($aValues['adherent.abonnementML']),
					$oDBH->quote($aValues['adherent.infoCreationSectionLocale']),
					$sDateCreation,
					$iOidPersonne
				);
				
				$oDBH->query($sInsertAdherentSQL);
				$iOidAdherent = (int)$oDBH->lastInsertId();
				
				$sInsertAdhesionSQL = sprintf($sInsertAdhesion,
					$oDBH->quote($aValues['adhesion.reference']),
					$aValues['adhesion.montantCotisation'],
					$aValues['adhesion.isRenouvellement'],
					$aValues['adhesion.accepteRIStatut'],
					$aValues['adhesion.declarationHonneur'],
					$aValues['adhesion.optinStat'],
					$sDateCreation,
					$iOidAdherent
				);
				
				$oDBH->query($sInsertAdhesionSQL);
				
				$aValues['adhesion.oid'] = (int)$oDBH->lastInsertId();
				
			}//end if
			
			if($aValues['personne.hasDon']){
			
				$sInsertDonSQL = sprintf($sInsertDon,
					$oDBH->quote($aValues['don.reference']),
					$aValues['don.montantDon'],
					$aValues['don.accepteRIStatut'],
					$aValues['don.declarationHonneur'],
					$aValues['don.optinStat'],
					$sDateCreation,
					$iOidPersonne
				);
				
				$oDBH->query($sInsertDonSQL);
				$iOidDon = (int)$oDBH->lastInsertId();
				$aValues['don.oid'] = $iOidDon;
				
				foreach($aValues['don.details'] as $iOidPoste => $fValue){
				
					$sInsertDonDetailSQL = sprintf($sInsertDonDetail,
						$fValue,
						$iOidDon,
						$iOidPoste 
					);
					
					$oDBH->query($sInsertDonDetailSQL);
					
				}//end foreach
			
			}//end if
			
			// On peux envoyer les notifications mails
			sendNotification($aValues);
			
		}//end try
		catch(PDOException $e) {
		
			$aReturn['issue'] = ACTION_ERROR;
			$aReturn['message'] = $e->getMessage();
			
			return $aReturn;
			
		}//end cacth
		
		// Suppression du backup temporaire
		unlink($sTmpFile);
		
	}//end if
	
	return $aReturn;
	
}//end function

/**
 * Construit un formulaire html pour préremplir les champs du site payer.fr. La plupart des champs sont hidden
 * Cela revient à sauter la première étape du site de paiement pour éviter les ressaisies
 *
 * @param array $aValues les valeurs à enregistrer, voir 'checkAdhesionFormValues' pour le détail
 * @param string $sTextBouton le texte du bouton de validation
 * @return string le formulaire html
 */
function createFormApayerfr(&$aValues, $sTextBouton = "Payer maintenant"){
	
	// Petit hack de circonstances, on recherche une url valide du formulaire
	// donc on charge la page pus on recherche l'élément action du premier formulaire
	$oDoc = new \DOMDocument();
	@$oDoc->loadHTMLFile("https://www.apayer.fr/partipirate");
	$oElement = $oDoc->getElementByID('DoValider');
	
	// Echec, tant pis l'utilisateur devra ressaisir. Dans tous les cas
	// ce système de paiement ne sera pas gardé longtemps
	if(is_null($oElement)){
		$sAction = "/partipirate";
	}//end if
	else{
		$sAction = str_replace("&", "&amp;", $oElement->getAttribute('action'));
	}//end else
	
	//On recherche un paramètre étrange du formulaire
	$oElement = $oDoc->getElementByID('xformsegnmodelinfo');
	
	// Echec, tant pis on indique une valeur qui a fonctionnée à un moment
	if(is_null($oElement)){
		$sSegnmodelinfo = "/APAYERASSO/ZZ/App_Render/AssociatiCreer.aspx;DFLT_XFORMS_MODEL";
	}//end if
	else{
		$sSegnmodelinfo = $oElement->getAttribute('value');
	}//end else

	foreach($aValues as $value) {
		$value = utf8_decode($value);
	}//end foreach
	
	$sMontant = sprintf('%.2f', $aValues['don.montantDon'] + $aValues['adhesion.montantCotisation']);
	
	$sCommentaire = sprintf("Adhésion : %s \r\nDon : %s", $aValues['adhesion.reference'], $aValues['don.reference'] );
	$sForm = <<<EOT
	
<form method="POST" action="https://www.apayer.fr{$sAction}" id="formApayerfr" accept-charset="ISO-8859-1">

	<input type="hidden" name="xformsegnmodelinfo" value="{$sSegnmodelinfo}" />
	<input type="hidden" name="xformslang" value="FR" />
	<input type="hidden" name="xformscountry" value="FR" />
	<input type="hidden" name="data/fiche/objet_paiement_combo" value="1" />
	<input type="hidden" name="data/fiche/afficheObjetPaiement" value="2" />
	<input type="hidden" name="data/fiche/devise" value="EUR" />
	<input type="hidden" name="data/fiche/autre_precision" value="" />
	<input type="hidden" name="data/fiche/reference" value="{$aValues['personne.reference']}" />
	
	<input type="hidden" name="data/fiche/nom" value="{$aValues['personne.nom']}" />
	<input type="hidden" name="data/fiche/prenom" value="{$aValues['personne.prenoms']}" />
	<input type="hidden" name="data/fiche/adresse" value="{$aValues['personne.adresseFiscale_ligne1']}" />
	<input type="hidden" name="data/fiche/adresse2" value="{$aValues['personne.adresseFiscale_ligne2']}" />
	<input type="hidden" name="data/fiche/ville" value="{$aValues['personne.adresseFiscale_ville']}" />
	<input type="hidden" name="data/fiche/cp" value="{$aValues['personne.adresseFiscale_codePostal']}" />
	<input type="hidden" name="data/fiche/courriel" value="{$aValues['personne.email']}" />
	<input type="hidden" name="data/fiche/confirmation_courriel" value="{$aValues['personne.email']}" />
	<input type="hidden" name="data/fiche/montant" value="{$sMontant}" />
	<input type="hidden" name="data/fiche/commentaire" value="{$sCommentaire}" />
	<input type="hidden" name="data/fiche/tel" value="{$aValues['personne.telephone']}" />
	
	<input type="submit" value="{$sTextBouton}" />
</form>

EOT;

	return $sForm;
	
}//end function

/**
 * Construit un formulaire html de paiement paybox via CGI. La plupart des champs sont hidden
 * Le mode utilisé est par fichier local
 *
 * @param array $aValues les valeurs à enregistrer, voir 'checkAdhesionFormValues' pour le détail
 * @param string $sTextBouton le texte du bouton de validation
 * @return string le formulaire html ou un tableau contenant les messages d'erreurs.
 *		array(
 *			'issue' => ACTION_ERROR,
 *			'message' => "Le message d'erreur"
 *		);
 */
function createFormPayboxCGI(&$aValues, $sTextBouton = "Payer maintenant"){

	try {
		$oDBH = lib\SqlGetHandle();

		$sInsertPayment = "INSERT INTO `PP_PAYBOX_TRANSACTION` (
			`reference`, `fields`, `hmac`, `paymentDone`, `issue`, `DON_oid`, `ADHESION_oid`, `dateCreation`
		)
		VALUES (
			%s, %s, %s, %d, %s, %d, %d, %s
		);";

		$sUpdatePayment = "UPDATE `PP_PAYBOX_TRANSACTION` SET `reference` = %s, `fields` = %s, `hmac` = %s WHERE `oid` = %d";


		// ----------------------------------------------------------------------------
		// Premier enregistrement pour obtenir la référence du paiement
		// ----------------------------------------------------------------------------
		$sInsertPaymentSQL = sprintf($sInsertPayment,
				$oDBH->quote(''), $oDBH->quote(''), $oDBH->quote(''), 0, $oDBH->quote('En cours'),
				$aValues['don.oid'],
				$aValues['adhesion.oid'],
				$oDBH->quote(date('Y-m-d H:m:s'))
		);

		$oDBH->query($sInsertPaymentSQL);
			
		$iPaymentOID = (int)$oDBH->lastInsertId();

	}//end try
	catch (PDOException $e) {

		return array(
				'issue' => ACTION_ERROR,
				'message' => $e->getMessage()
		);

	}//end cacth

	// ----------------------------------------------------------------------------
	// PAYBOX
	// ----------------------------------------------------------------------------
	// Format du fichier
	$sFileContent = <<<EOT

# Numéro de site (TPE) donné par la banque
PBX_SITE=%s
			
# Numéro de rang (« machine ») donné par la banque
PBX_RANG=%s
			
# Identifiant PAYBOX fourni par PAYBOX
PBX_IDENTIFIANT=%s
			
# Montant total de l’achat en centimes sans virgule ni point.
PBX_TOTAL=%d

# Code monnaie de la transaction suivant la norme ISO 4217
PBX_DEVISE=%d

# référence commande.
PBX_CMD=%s
			
# Adresse email de l’acheteur (porteur de carte).
PBX_PORTEUR=%s

# Variables renvoyées par Paybox (montant, référence commande, numéro de transaction, numéro
# d’abonnement et numéro d’autorisation)
PBX_RETOUR=montant:M;ref:R;auto:A;trans:T;erreur:E;sign:K
			
# Page de retour de Paybox après paiement accepté
PBX_EFFECTUE=%s/merci.php
			
# Page de retour de Paybox après paiement refusé
PBX_REFUSE=%s/regrets.php
			
# Page de retour de Paybox après paiement annulé
PBX_ANNULE=%s/regrets.php

# Langue de l'interface
PBX_LANGUE=%s

# URL du serveur de paiement primaire de Paybox
PBX_PAYBOX=%s

# Url de notification
PBX_REPONDRE_A=%s
			
# On force le mode de maiment par CB
PBX_TYPEPAIEMENT=CARTE
			
# url backup à ne conserver qu'en mode preprod
PBX_BACKUP1=%s
PBX_BACKUP2=%s

EOT;

	// Reference: unique, alphaNum (A-Z a-z 0-9), 12 characters max
	// Format YY0000000NNNN, pad avec des 0 suivant l'oid de la transaction
	$sReference = sprintf('%s%s', date("Y"), str_pad((string)$iPaymentOID, 8, '0', STR_PAD_LEFT));

	// Fichier de paramètre pour paybox
	$sPayboxFile = PPLIB_PATH_PAYBOX_FILES."/{$sReference}.pbx";
	
	// Amount : format  "xxxxx.yy" (no spaces)
	$sMontant = str_replace('.', '', sprintf('%.2f', $aValues['don.montantDon'] + $aValues['adhesion.montantCotisation']));

	// transaction date : format d/m/y:h:m:s
	$sDate = date("d/m/Y:H:i:s");
	
	// ----------------------------------------------------------------------------

	$sFileContent = utf8_decode(sprintf(
		$sFileContent,
		PAYBOX_SITE,
		PAYBOX_RANG,
		PAYBOX_IDENTIFIANT,
		$sMontant,
		PAYBOX_DEVISE_EURO,
		$sReference,
		$aValues['personne.email'],
		URL_ROOT,
		URL_ROOT,
		URL_ROOT,
		PAYBOX_LANG_FRA,
		PAYBOX_PAYMENT_URL,
		PAYBOX_EVENT_URL,
		PAYBOX_PAYMENT_URL_BACKUP1,
		PAYBOX_PAYMENT_URL_BACKUP2
	));

	try{
		// Enregister dans la base de données
		$sUpdatePaymentSQL = sprintf($sUpdatePayment,
				$oDBH->quote($sReference), $oDBH->quote($sFileContent), "''", $iPaymentOID
		);

		$oDBH->query($sUpdatePaymentSQL);

		file_put_contents($sPayboxFile, $sFileContent);
		
	}//end try
	catch (PDOException $e) {

		return array(
				'issue' => ACTION_ERROR,
				'message' => $e->getMessage()
		);

	}//end cacth

	$sCGIurl = PAYBOX_CGI_URL;
	
	$sForm = <<<EOT
	<form action="{$sCGIurl}" method="post" id="PaymentRequestPAYBOX">

		<input type="hidden" name="PBX_MODE" id="paybox-mode"      	   value="13" />
		<input type="hidden" name="PBX_OPT"  id="paybox-opt"           value="{$sPayboxFile}" />

		<input type="submit" name="bouton"   id="paybox-aymentButton"  value="{$sTextBouton}" />

	</form>

EOT;

	return $sForm;

}//end function

/**
 * Construit un formulaire html de paiement paybox via CGI. La plupart des champs sont hidden
 * Le mode utilisé est par fichier local
 *
 * @param array $aValues les valeurs à enregistrer, voir 'checkAdhesionFormValues' pour le détail
 * @param string $sTextBouton le texte du bouton de validation
 * @return string le formulaire html ou un tableau contenant les messages d'erreurs.
 *		array(
 *			'issue' => ACTION_ERROR,
 *			'message' => "Le message d'erreur"
 *		);
 */
function createFormPaybox(&$aValues, $sTextBouton = "Payer maintenant"){

	try {
		$oDBH = lib\SqlGetHandle();

		$sInsertPayment = "INSERT INTO `PP_PAYBOX_TRANSACTION` (
			`reference`, `fields`, `hmac`, `paymentDone`, `issue`, `DON_oid`, `ADHESION_oid`, `dateCreation`
		)
		VALUES (
			%s, %s, %s, %d, %s, %d, %d, %s
		);";

		$sUpdatePayment = "UPDATE `PP_PAYBOX_TRANSACTION` SET `reference` = %s, `fields` = %s, `hmac` = %s WHERE `oid` = %d";


		// ----------------------------------------------------------------------------
		// Premier enregistrement pour obtenir la référence du paiement
		// ----------------------------------------------------------------------------
		$sInsertPaymentSQL = sprintf($sInsertPayment,
				$oDBH->quote(''), $oDBH->quote(''), $oDBH->quote(''), 0, $oDBH->quote('En cours'),
				$aValues['don.oid'],
				$aValues['adhesion.oid'],
				$oDBH->quote(date('Y-m-d H:m:s'))
		);

		$oDBH->query($sInsertPaymentSQL);
			
		$iPaymentOID = (int)$oDBH->lastInsertId();

	}//end try
	catch (PDOException $e) {

		return array(
				'issue' => ACTION_ERROR,
				'message' => $e->getMessage()
		);

	}//end cacth

	// ----------------------------------------------------------------------------
	// PAYBOX
	// ----------------------------------------------------------------------------
	
	// Reference: unique, alphaNum (A-Z a-z 0-9), 12 characters max
	// Format YY0000000NNNN, pad avec des 0 suivant l'oid de la transaction
	$sReference = sprintf('%s%s', date("Y"), str_pad((string)$iPaymentOID, 8, '0', STR_PAD_LEFT));

	// Amount : format  "xxxxx.yy" (no spaces)
	$sMontant = str_replace('.', '', sprintf('%.2f', $aValues['don.montantDon'] + $aValues['adhesion.montantCotisation']));

	// transaction date : format ISO 8601
	$aData = array();
	
	// L'identifiant de la boutique
	$aData['PBX_SITE'] = PAYBOX_SITE;
	
	// Numéro de rang (« machine ») donné par la banque
	$aData['PBX_RANG'] = PAYBOX_RANG;
	
	// Identifiant PAYBOX fourni par PAYBOX
	$aData['PBX_IDENTIFIANT'] = PAYBOX_IDENTIFIANT;
	
	// Date de la transaction
	$aData['PBX_TIME'] = date("c");
	
	// Montant total de l’achat en centimes sans virgule ni point.
	$aData['PBX_TOTAL'] = $sMontant;
			
	// Code monnaie de la transaction suivant la norme ISO 4217
	$aData['PBX_DEVISE'] = PAYBOX_DEVISE_EURO;
			
	// référence commande.
	$aData['PBX_CMD'] = $sReference;
	
	// Adresse email de l’acheteur (porteur de carte).
	$aData['PBX_PORTEUR'] = $aValues['personne.email'];
			
	// Variables renvoyées par Paybox
	$aData['PBX_RETOUR'] = "montant:M;ref:R;auto:A;trans:T;erreur:E;sign:K";
	
	// Page de retour de Paybox après paiement accepté
	$aData['PBX_EFFECTUE'] = URL_ROOT."merci.php";
	
	// Page de retour de Paybox après paiement refusé
	$aData['PBX_REFUSE'] = URL_ROOT."regrets.php";
	
	// Page de retour de Paybox après paiement annulé
	$aData['PBX_ANNULE'] = URL_ROOT."regrets.php";
			
	// Langue de l'interface
	$aData['PBX_LANGUE'] = PAYBOX_LANG_FRA;
			
	// Url de notification
	$aData['PBX_REPONDRE_A'] = PAYBOX_EVENT_URL;
	
	// On force le mode de maiment par CB
	$aData['PBX_TYPEPAIEMENT'] = "CARTE";

	// Algorihtme utilisé pour le hash
	$aData['PBX_HASH'] = "SHA512";
	

	$sFormParams = '';
	$aSignedValues = array();
	
	foreach($aData as $sIndex => $sValue){
		$sFormParams .= sprintf('<input type="hidden" name="%s" value="%s" />'.PHP_EOL, $sIndex, $sValue);
		$aSignedValues[] = sprintf('%s=%s', $sIndex, $sValue);
	}//end foreach
	
	$sHmac = strtoupper(hash_hmac('sha512', implode('&', $aSignedValues), pack("H*", PAYBOX_KEY)));
	
	try{
		// Enregister dans la base de données
		$sUpdatePaymentSQL = sprintf($sUpdatePayment,
				$oDBH->quote($sReference), $oDBH->quote($sFields), $oDBH->quote($sHmac), $iPaymentOID
		);

		$oDBH->query($sUpdatePaymentSQL);

	}//end try
	catch (PDOException $e) {

		return array(
				'issue' => ACTION_ERROR,
				'message' => $e->getMessage()
		);

	}//end cacth

	$sCGIurl = PAYBOX_PAYMENT_URL;
	
	$sForm = <<<EOT
	<form action="{$sCGIurl}" method="post" id="PaymentRequestPAYBOX">
		
		{$sFormParams}
		<input type="hidden" name="PBX_HMAC" value="{$sHmac}">
		<input type="submit" name="bouton"   id="paybox-aymentButton"  value="{$sTextBouton}" />

	</form>

EOT;

	return $sForm;

}//end function

/**
 * @param string $sRawdata
 * @param string $sSignature
 * @return boolean
 */
function payboxCheckSignature($sRawdata, $sSignature){
	
	$mKey = openssl_pkey_get_public(file_get_contents(PAYBOX_PUBKEY));
	return openssl_verify($sRawdata, base64_decode($sSignature), $mKey) == 1;
	
}//end function

/**
 * Valide la reception du paiement via Apayer
 * @param array $results les valeurs à enregistrer
 *      [datePaiement] => 24/12/12
 *      [objetPaiement] => 1
 *      [autreObjet] => 
 *      [montant] => 50
 *      [reference] => PER_201212240500_a9249e0
 *      [commentaire] => Adhésion : ADH_201212240500_a9249e60 Don :DON_201212240500_aa9249e0
 *      [referenceBancaire] => 
 *      [numeroAutorisation] => 000056
 *      [nom] => Nom
 *      [prenom] => test
 *      [adresse] => Adresse machin
 *      [codePostal] => 00000
 *      [ville] => Rennes
 *      [courriel] => mistral@partipirate.org
 *      [etat] => Paiement accepté (payé)
 *      [motifRefus] => fzef
 *      [cvx] => oui
 *      [vld] => 0113
 *      [brand] => MC
 *      [status3DS] => 1
 *      [PERSONNE_ref] => PER_201212240500_a9249e0
 *      [ADHESION_ref] => ADH_201212240500_a9249e60
 *      [DON_ref] => DON_201212240500_aa9249e0
 * @param string $nameOrigin Nom de la référence d'importation
 * @return bool pour le statut de l'enregistrement
 */
function validApayerfr(&$results, $nameOrigin = null){
	try {
		$date = explode('/', $results['datePaiement']);
		$results['datePaiement'] = '20'.$date[2].'-'.$date[1].'-'.$date[0];
		$results['objetPaiement'] = lib\toFloat($results['objetPaiement']);
		$results['montant'] = lib\toFloat($results['montant']);
		$results['cvx'] = (preg_match('/^(oui|1|o)$/i', $results['cvx'])?1:0);
		$results['status3DS'] = lib\toInt($results['status3DS']);
		
		$montantBdd = 0;
		$oDBH = lib\SqlGetHandle();
		$results['isMatchRef'] = 0;
		$results['PERSONNE_oid'] = null;
		if(!empty($results['PERSONNE_ref'])) {
			$oResult = $oDBH->query(sprintf("SELECT `oid` FROM `PP_PERSONNE` WHERE `reference` = %s", $oDBH->quote($results['PERSONNE_ref'])));
			if($oRow = $oResult->fetch(\PDO::FETCH_OBJ)) {
				$results['PERSONNE_oid'] = $oRow->oid;
				$results['isMatchRef'] = 1;
			}
		}
		$results['ADHESION_oid'] = null;
		if(!empty($results['ADHESION_ref'])) {
			$oResult = $oDBH->query(sprintf("SELECT `oid`, `montantCotisation` FROM `PP_ADHESION` WHERE `reference` = %s", $oDBH->quote($results['ADHESION_ref'])));
			if($oRow = $oResult->fetch(\PDO::FETCH_OBJ)) {
				$results['ADHESION_oid'] = $oRow->oid;
				$montantBdd+= $oRow->montantCotisation;
			} else {
				$results['isMatchRef'] = 0;
			}
		}
		$results['DON_oid'] = null;
		if(!empty($results['DON_ref'])) {
			$oResult = $oDBH->query(sprintf("SELECT `oid`, `montantDon` FROM `PP_DON` WHERE `reference` = %s", $oDBH->quote($results['DON_ref'])));
			if($oRow = $oResult->fetch(\PDO::FETCH_OBJ)) {
				$results['DON_oid'] = $oRow->oid;
				$montantBdd+= $oRow->montantDon;
			} else {
				$results['isMatchRef'] = 0;
			}
		}
		$results['isMatchAmount'] = ($results['montant'] == $montantBdd?1:0);
		if(preg_match('/^Paiement accept/i', $results['etat'])) {
			if($results['isMatchAmount'] and $results['isMatchRef']) {
				$results['etatManuel'] = 1;
			} else {
				$results['etatManuel'] = -1;
			}
		} else {
			$results['etatManuel'] = 0;
		}
		$results['oid'] = null;
		//if(!empty($results['PERSONNE_ref'])) {
			$oResult = $oDBH->query(sprintf("SELECT `oid` FROM `PP_APAYER_TRANSACTION` WHERE 
				`datePaiement` = %s AND `referenceBancaire` = %s AND
				`PERSONNE_oid` = %d AND `DON_oid` = %d AND `ADHESION_oid` = %d
					ORDER BY `oid` DESC LIMIT 0,1", 
				$oDBH->quote($results['datePaiement']), $oDBH->quote($results['referenceBancaire']),
				$results['PERSONNE_oid'], $results['DON_oid'], $results['ADHESION_oid']));
			if($oRow = $oResult->fetch(\PDO::FETCH_OBJ)) {
				$results['oid'] = $oRow->oid;
			}
		//}
		
		
		@$results['by'] = "REMOTE_ADDR={$_SERVER['REMOTE_ADDR']} - X-Real-IP={$_SERVER['X-Real-IP']} \r\nFROM : {$nameOrigin}";
		
		$sInsertApayer = "REPLACE INTO `PP_APAYER_TRANSACTION` (
		`oid`, 
		`PERSONNE_oid`, `DON_oid`,  `ADHESION_oid`, 
		`isMatchRef`, `isMatchAmount`, 
		`datePaiement`,
		`objetPaiement`, `autreObjet`,
		`montant`,
		`reference`, `commentaire`,
		`referenceBancaire`, `numeroAutorisation`,
		`nom`, `prenom`, `adresse`, `codePostal`, `ville`, `courriel`,
		`etat`, `etatManuel`,
		`motifRefus`,
		`cvx`, `vld`, `brand`, `status3DS`,
		`by`
		) 
		VALUES (
			%d, 
			%d, %d, %d, 
			%d, %d,
			%s, 
			%d, %s,
			%.2f,
			%s, %s,
			%s, %s,
			%s, %s, %s, %s, %s, %s,
			%s, %d,
			%s, 
			%d, %s, %s, %d,
			%s
		)";
		$sInsertApayerSQL = sprintf($sInsertApayer,
			$results['oid'],
			$results['PERSONNE_oid'], $results['DON_oid'], $results['ADHESION_oid'],
			$results['isMatchRef'], $results['isMatchAmount'],
			$oDBH->quote($results['datePaiement']),
			$results['objetPaiement'], $oDBH->quote($results['autreObjet']), 
			$results['montant'],
			$oDBH->quote($results['reference']), $oDBH->quote($results['commentaire']),
			$oDBH->quote($results['referenceBancaire']), $oDBH->quote($results['numeroAutorisation']),
			$oDBH->quote($results['nom']), $oDBH->quote($results['prenom']), $oDBH->quote($results['adresse']), $oDBH->quote($results['codePostal']), $oDBH->quote($results['ville']), $oDBH->quote($results['courriel']),
			$oDBH->quote($results['etat']), $results['etatManuel'],
			$oDBH->quote($results['motifRefus']),
			$results['cvx'], $oDBH->quote($results['vld']), $oDBH->quote($results['brand']), $results['status3DS'],
			$oDBH->quote($results['by'])
		);
		//die($sInsertApayerSQL);
		$oDBH->query($sInsertApayerSQL);
		$iOidApayer = (int)$oDBH->lastInsertId();
		if($results['etatManuel'] == -1) {
			return -$iOidApayer;
		}
		return $iOidApayer;
	} catch (PDOException $e) {
		return NULL;
	}
}

/**
 * Envoi un email à l'utilisateur et au secretaire pour le prévenir de l'enregistrement
 * @param array $aValues les valeurs à enregistrer, voir 'checkAdhesionFormValues' pour le détail
 * @return string le formulaire html
 */
function sendNotification(&$aValues){

	require_once PPLIB_PATH_MAILER.'/class.phpmailer.php';
	
	// 1 Message à l'utilisateur
	$sMessage = file_get_contents(sprintf('%s/mail_adhesion_user.html', PPLIB_PATH));

	foreach($aValues as $sIndex => $mValue){
	
		if(is_array($mValue)){
			continue;
		}//end if
		
		$sMessage = str_replace('%'.$sIndex, $mValue, $sMessage);
		
	}//end foreach
	
	$sHtmlMessage = nl2br($sMessage);
	$sTextMessage = lib\toSafeHTMLString($sHtmlMessage);
	
	$oUserMail = new \PHPMailer();
	$oUserMail->AddReplyTo("secretaire@partipirate.org","Secrétaire Parti Pirate");
	$oUserMail->SetFrom('secretaire@partipirate.org', 'Secrétaire Parti Pirate');
	//$oUserMail->AddAddress($aValues['personne.email'], sprintf('%s %s', $aValues['personne.prenoms'], $aValues['personne.nom']));
	$oUserMail->AddAddress("mistral.oz@partipirate.org","Sysadmin test"); // DEBUG
	
	$oUserMail->Subject = "Votre adhésion ou don au Parti Pirate";
	
	$oUserMail->AltBody = $sTextMessage;
	$oUserMail->MsgHTML($sHtmlMessage);
	
	//$oUserMail->Send();

	// 2 - Message au secretaire
	$sMessage = file_get_contents(sprintf('%s/mail_adhesion_secpp.html', PPLIB_PATH));

	foreach($aValues as $sIndex => $mValue){
	
		if(is_array($mValue)){
			continue;
		}//end if
		
		$sMessage = str_replace('%'.$sIndex, $mValue, $sMessage);
		
	}//end foreach
	
	$sHtmlMessage = nl2br($sMessage);
	$sTextMessage = lib\toSafeHTMLString($sHtmlMessage);
	
	$oSecMail = new \PHPMailer();
	$oSecMail->AddReplyTo("noreply@partipirate.org","Secrétaire Parti Pirate");
	$oSecMail->SetFrom('noreply@partipirate.org', 'Secrétaire Parti Pirate');
	//$oSecMail->AddAddress("secretaire@partipirate.org","Secrétaire Parti Pirate");
	$oSecMail->AddAddress("mistral.oz@partipirate.org","Secrétaire Parti Pirate"); // DEBUG
	
	$oSecMail->Subject = "Nouvelle adhésion ou don au Parti Pirate";
	
	$oSecMail->AltBody = $sTextMessage;
	$oSecMail->MsgHTML($sHtmlMessage);
	
	//$oSecMail->Send();
	
}//end function

/**
 * Construit un nouveau code référence pour la transaction
 * pour un don DON_201208211430_ecf61351
 * pour une adhesion ADH_201208211430_cd5e1408
 * soit le format TYP_YYYYMMDDHHII_NNNNNNNNN = 25 caractères
 * @param string $sType Le tableau de valeur
 */
function createCodeReference($sType){

	$sCode = sprintf('%s_%s_%s',
		strtoupper(substr($sType, 0,3)),
		date('YmdHi'),
		substr(md5(microtime(TRUE)), rand(0, 32 -8), 8) // On prends 8 caractères consécutifs suivant une position de départ aléatoire
	);
	
	return $sCode;
	
}//end function

/**
 * Contrôle que la chaîne soit présente
 * @param array $aValues Le tableau de valeur
 * @param array $aReturn Le tableau de résultats
 * @param string $sName Le nom de la valeur à contrôler dans $aValues
 * @param string $sFaultMessage Le message d'erreur
 */
function checkStrLength(&$aValues, &$aReturn, $sName, $sFaultMessage){

	if(strlen($aValues[$sName]) == 0){
		$aReturn['issue'] = ACTION_FAILURE;
		$aReturn[$sName] = $sFaultMessage;
	}//end if
	
}//end function

/**
 * Contrôle que la valeur soit supéreure à 0
 * @param array $aValues Le tableau de valeur
 * @param array $aReturn Le tableau de résultats
 * @param string $sName Le nom de la valeur à contrôler dans $aValues
 * @param string $sFaultMessage Le message d'erreur
 */
function checkValueSupZero(&$aValues, &$aReturn, $sName, $sFaultMessage){

	if($aValues[$sName] <= 0){
		$aReturn['issue'] = ACTION_FAILURE;
		$aReturn[$sName] = $sFaultMessage;
	}//end if
	
}//end function

/**
 * Contrôle que la valeur soit supéreure à 10 et inférieure ou égal à 7500
 * @param array $aValues Le tableau de valeur
 * @param array $aReturn Le tableau de résultats
 * @param string $sName Le nom de la valeur à contrôler dans $aValues
 * @param string $sFaultMessage Le message d'erreur
 */
function checkValueLimiteAdhesion(&$aValues, &$aReturn, $sName, $sFaultMessage){

	if($aValues[$sName] < 6 || $aValues[$sName] > 7500){
		$aReturn['issue'] = ACTION_FAILURE;
		$aReturn[$sName] = $sFaultMessage;
	}//end if
	
}//end function

/**
 * Contrôle que la valeur soit supéreure à 0 et inférieure ou égal à 7500
 * @param array $aValues Le tableau de valeur
 * @param array $aReturn Le tableau de résultats
 * @param string $sName Le nom de la valeur à contrôler dans $aValues
 * @param string $sFaultMessage Le message d'erreur
 */
function checkValueLimiteDon(&$aValues, &$aReturn, $sName, $sFaultMessage){

	if($aValues[$sName] <=10 || $aValues[$sName] > 7500){
		$aReturn['issue'] = ACTION_FAILURE;
		$aReturn[$sName] = $sFaultMessage;
	}//end if
	
}//end function

/**
 * Contrôle que la valeur est une adresse email
 * @param array $aValues Le tableau de valeur
 * @param array $aReturn Le tableau de résultats
 * @param string $sName Le nom de la valeur à contrôler dans $aValues
 * @param string $sFaultMessage Le message d'erreur
 */
function checkValueIsEmail(&$aValues, &$aReturn, $sName, $sFaultMessage){

	$regExp = "/^.+@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/";

	if(!preg_match($regExp, $aValues[$sName])){
		$aReturn['issue'] = ACTION_FAILURE;
		$aReturn[$sName] = $sFaultMessage;
	}//end if
	
}//end function

	
/**
 * Retourne la liste des pays
 * @return array ou null en cas d'erreur
 * Format
 * array(
 *         oid => libelle
 *)
 */
function listPays(){
    
    try {
        $oDBH = lib\SqlGetHandle();
        $oResult = $oDBH->query("SELECT `oid`, `libelle` FROM `PP_PAYS`");
       
        $aReturn  = array();
       
        while($oRow = $oResult->fetch(\PDO::FETCH_OBJ)){
            $aReturn[$oRow->oid] = array("oid" => $oRow->oid, "libelle" => $oRow->libelle);
        }//end while
       
        return $aReturn;
       
    }//end try
    catch (PDOException $e) {
    
        return NULL;
    }//end cacth
    
}//end function

/**
 * Retourne la liste des sections locales
 * @return array ou null en cas d'erreur
 * array(
 *         oid => libelle
 *)
 */
function listSectionsLocales(){
    
    try {
        $oDBH = lib\SqlGetHandle();
        $oResult = $oDBH->query("SELECT `oid`, `libelle`, `idInt` FROM `PP_SECTION_LOCALE` ORDER BY `libelle` ASC");
       
        $aReturn  = array();
       
        while($oRow = $oResult->fetch(\PDO::FETCH_OBJ)){
            $aReturn[$oRow->oid] = array("libelle" => $oRow->libelle, "oid" => $oRow->oid, "idInt" => $oRow->idInt);
        }//end while
       
        return $aReturn;
       
    }//end try
    catch (PDOException $e) {
    
        return NULL;
    }//end cacth
    
}//end function

/**
 * Retourne la liste des postes de don
 * @return array ou null en cas d'erreur
 * array(
 *         oid => array(libelle, description courte)
 * )
 */
function listDonPostes(){
    
    try {
        $oDBH = lib\SqlGetHandle();
        $oResult = $oDBH->query("SELECT `oid`, `libelle`, `brief`, `idInt`, `type`, `order` FROM `PP_DON_POSTE` WHERE `type` != 0 ORDER BY `type` ASC, `order` ASC, `libelle` ASC");
       
        $aReturn  = array();
       
        while($oRow = $oResult->fetch(\PDO::FETCH_OBJ)){
            $aReturn[$oRow->oid] = array("libelle" => $oRow->libelle, "oid" => $oRow->oid, "brief" => $oRow->brief, "idInt" => $oRow->idInt, "type" => $oRow->type, "order" => $oRow->order);
        }//end while
       
        return $aReturn;
       
    }//end try
    catch (PDOException $e) {
    
        return NULL;
    }//end cacth
    
}//end function

/**
 * Retourne la liste des mailings lists
 * @return array ou null en cas d'erreur
 * array(
 *         oid => array(code, libelle)
 * )
 */
function listMailingLists(){
    
    try {
        $oDBH = lib\SqlGetHandle();
        $oResult = $oDBH->query("SELECT `oid`, `code`, `libelle` FROM `PP_MAILING_LISTS`");
       
        $aReturn  = array();
       
        while($oRow = $oResult->fetch(\PDO::FETCH_OBJ)){
            $aReturn[$oRow->oid] = array("code" => $oRow->code, "oid" => $oRow->oid, "libelle" => $oRow->libelle);
        }//end while
       
        return $aReturn;
       
    }//end try
    catch (PDOException $e) {
    
        return NULL;
    }//end cacth
    
}//end function 

?>