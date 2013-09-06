<?php
/**
 * Page de notification des paiements. L'url est à paramétrer sur l'interface CMCIC du commer�ant
 * 26.08.2012 : FLCT : Non testé car aucun environement de test disponible sur un serveur CMCIC
 */

include_once sprintf('%s/libmain.php', dirname(__FILE__).'/_lib/PPlib');
include_once sprintf('%s/CMCIC_Tpe.inc.php', PPLIB_PATH_CMCIC);

// Ici l'essentiel du traitement du message retour
include_once sprintf('%s/CMCIC_callbackHandler.php', PPLIB_PATH_CMCIC);

// On mets à jour la base de donn�es
if($bIsPaymentRequest && $bIsAllowedTransaction){
	
	$oDBH = PPlib\SqlGetHandle();
	
	try{
		// Enregister dans la base de donn�es
		$sUpdatePaymentSQL = sprintf("UPDATE `PP_CMCIC_TRANSACTION` SET `paymentDone` = %d, `issue` = %s WHERE `reference` = %s",
			($bPaymentStatusDone)? 1 : 0, $oDBH->quote($sCodeRetour), $oDBH->quote($sPaymentReference)
		);
		
		$oDBH->query($sUpdatePaymentSQL);
		
	}//end try
	catch (PDOException $e) {
		
	}//end cacth
	
}//end if

?>