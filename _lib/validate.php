<?php

include_once sprintf('%s/libmain.php', dirname(__FILE__).'/PPlib');
include_once sprintf('%s/adhesion.php', PPLIB_PATH);

if(!count($_POST)) {
	return;
}

$aValues = array(
	// 'personne.oid' => NULL,
	// 'personne.reference' => NULL,
	'personne.nom' => @$_POST['personne_nom'],
	'personne.prenoms' => @$_POST['personne_prenoms'],
	'personne.adresseFiscale_ligne1' => @$_POST['personne_adresseFiscale_ligne1'],
	'personne.adresseFiscale_ligne2' => @$_POST['personne_adresseFiscale_ligne2'],
	'personne.adresseFiscale_codePostal' => @$_POST['personne_adresseFiscale_codePostal'],
	'personne.adresseFiscale_ville' => @$_POST['personne_adresseFiscale_ville'],
	'personne.adresseFiscale_pays_oid' => @$_POST['personne_adresseFiscale_pays_oid'],	
	// 'personne.adressePostale_ligne1' => '',
	// 'personne.adressePostale_ligne2' => '',
	// 'personne.adressePostale_codePostal' => '',
	// 'personne.adressePostale_ville' => '',
	// 'personne.adressePostale_pays_oid' => '',
	'personne.adressesDifferentes' => '0',
	'personne.email' => @$_POST['personne_email'],
	'personne.telephone' => @$_POST['personne_telephone'],
	'personne.pseudonyme' => @$_POST['personne_pseudonyme'],
	'personne.autresInformations' => 'Renouvellement : '.@$_POST['renouvellement'],
	'personne.commentaires' => @$_POST['personne_commentaires'],
);

$isAdhesion = (@$_POST['type_action'] == 'adhesion');
if($isAdhesion) {
$aValues = array_merge($aValues, array(
	// 'adhesion.oid' => NULL,
	// 'adhesion.reference' => NULL,
	'personne.hasAdhesion' => 1,
	'adherent.isMajeur' => @$_POST['adherent_isMajeur'],
	'adherent.inscritForum' => @$_POST['adherent_inscritForum'],
	'adherent.pseudonymeForum' => @$_POST['personne_pseudonyme'],
	//'adherent.infoCreationSectionLocale' => 'Pays basque',
	// 'adherent.identifiantPGP' => '0x123456789',
	// 'adherent.urlPGP' => 'http://www.url.com/0x123456789',
	'adherent.sectionLocale' => @$_POST['adherent_sectionlocale'],
	'adhesion.montantCotisation' => @$_POST['adhesion_montantCotisation'],
	
	'adhesion.isRenouvellement' => @$_POST['adhesion_isRenouvellement'],
	'adhesion.accepteRIStatut' => @$_POST['accepte'],
	'adhesion.declarationHonneur' => @$_POST['accepte'],
	'adhesion.optinStat' => @$_POST['accepte'],
));
$arrayML = PPlib\adhesion\listMailingLists();
$aValues['adherent.abonnementML'] = '';
foreach($arrayML as $ML) {
	if(empty($_POST['ml_'.$ML['code']])) {
		continue;
	}
	$aValues['adherent.abonnementML'].= $ML['code'].';';
}
}

$arrayDon = PPlib\adhesion\listDonPostes();
$isDon = (@$_POST['type_action'] == 'don');
foreach($arrayDon as $don) {
	if(empty($_POST['don_'.$don['oid']])) {
		continue;
	}
	if(!isset($aValues['don.details'])) {
		$aValues['don.details'] = array();
	}
	$aValues['don.details'][$don['oid']] = $_POST['don_'.$don['oid']];
	$isDon = 1;
}
if($isDon) {
$aValues = array_merge($aValues, array(
	// 'don.oid' => NULL,
	// 'don.reference' => NULL,
	'personne.hasDon' => 1,
	'don.montantDon' => '0',
	'don.accepteRIStatut' => @$_POST['accepte'],
	'don.declarationHonneur' => @$_POST['accepte'],
	'don.optinStat' => @$_POST['accepte'],
));
}


$aResult = PPlib\adhesion\saveAdhesionFormValues($aValues);
/*
echo "<pre>\n";
echo "Les données traitées\n";
echo str_repeat('-', 60), "\n";

print_r($aValues);

echo str_repeat('-', 60), "\n\n";
*/
if($aResult['issue'] == ACTION_SUCCESS){
	// Affichage du formulaire à payer
	$sHtmlFormCode = PPlib\adhesion\createFormApayerfr($aValues, "Payer maintenant sur Apayer.fr");
	echo $sHtmlFormCode;
	?>
	<script type="text/javascript">
		$(function() {
			$('#formApayerfr').submit();
		});
	</script>
	<?php 
	/*
	$sHtmlFormCode = PPlib\adhesion\createFormCMCIC($aValues, "Payer maintenant sur un serveur CM-CIC");
	echo $sHtmlFormCode;
	*/
}elseif($aResult['issue'] == ACTION_FAILURE){
	unset($aResult['issue'], $aResult['message']);
	echo '<h4>Certaines données sont invalides !</h4>'.PHP_EOL;
	echo '<ul>'.PHP_EOL;
	foreach($aResult as $sIndex => $sValue){
		//echo '<li>'.sprintf("\t%s => %s\n", $sIndex, $sValue).'</li>'.PHP_EOL;
		echo '<li>'.htmlentities($sValue, null, 'utf-8').'</li>'.PHP_EOL;
	}
	echo '</ul>'.PHP_EOL;
}elseif($aResult['issue'] == ACTION_FAILURE){
	echo '<h4>In problème technique est survenu !</h4>'.PHP_EOL;
	echo '<ul>'.PHP_EOL;
		echo '<li>Problème technique : '.htmlentities($aResult['message'], null, 'utf-8').'</li>'.PHP_EOL;
	echo '</ul>'.PHP_EOL;
}
