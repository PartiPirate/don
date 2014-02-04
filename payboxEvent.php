<?php
/**
 * Page de notification des paiements.
 * @TODO étudier les différents codes retours et produire une réaction en conséquence.
 */

include_once sprintf('%s/libmain.php', dirname(__FILE__).'/_lib/PPlib');
include_once sprintf('%s/adhesion.php', PPLIB_PATH);

$bIsPaymentRequest = FALSE;
$bIsAllowedTransaction = FALSE;
$sPaymentReference = NULL;
$bPaymentStatusDone = FALSE;
$sCodeRetour = NULL;

$sMontant = $_GET['montant'];
$sPaymentReference = $_GET['ref'];
$sCodeRetour = $_GET['erreur'];
$sTrans = $_GET['trans'];
$sAuto = $_GET['auto'];
$sSignature = $_GET['sign'];

$sRawdata = "montant={$sMontant}&ref={$sPaymentReference}&auto={$sAuto}&trans={$sTrans}&erreur={$sCodeRetour}";

$bIsPaymentRequest = PPlib\adhesion\payboxCheckSignature($sRawdata, $sSignature);
$bIsAllowedTransaction = array_search($_SERVER['REMOTE_ADDR'], explode('|', PAYBOX_ALLOWED_IP));
$bIsAllowedTransaction= true;

switch($sCodeRetour){
	
	case '00000':
		$bPaymentStatusDone = TRUE;
	break;
	
	default:
		$bPaymentStatusDone = FALSE;
}//end switch

// On mets à jour la base de données
if($bIsPaymentRequest && $bIsAllowedTransaction){

	$oDBH = PPlib\SqlGetHandle();
	
	try{

		// Enregister dans la base de données
		$sUpdatePaymentSQL = sprintf("UPDATE `PP_PAYBOX_TRANSACTION` SET `paymentDone` = %d, `issue` = %s WHERE `reference` = %s",
			($bPaymentStatusDone)? 1 : 0, $oDBH->quote($sCodeRetour), $oDBH->quote($sPaymentReference)
		);
		
		$oDBH->query($sUpdatePaymentSQL);
		
		// On supprime le fichier de paramètres
		unlink(PPLIB_PATH_PAYBOX_FILES."/{$sPaymentReference}.pbx");
		
	}//end try
	catch (PDOException $e) {

	}//end cacth
	
}//end if

?>