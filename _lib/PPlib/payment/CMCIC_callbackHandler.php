<?php
/**
 * Librairie adhesion
 * 
 */
 
// Begin Main : Retrieve Variables posted by CMCIC Payment Server 
$CMCIC_bruteVars = \getMethode();

// TPE init variables
$oTpe = new \CMCIC_Tpe();
$oHmac = new \CMCIC_Hmac($oTpe);

$bIsPaymentRequest = FALSE;
$bIsAllowedTransaction = FALSE;
$sPaymentReference = NULL;
$bPaymentStatusDone = FALSE;
$sCodeRetour = NULL;

if(is_array($CMCIC_bruteVars) && sizeof($CMCIC_bruteVars) > 1){
	
	$bIsPaymentRequest = TRUE;
	
	// Message Authentication
	$cgi2_fields = sprintf(CMCIC_CGI2_FIELDS, $oTpe->sNumero,
						  $CMCIC_bruteVars["date"],
						  $CMCIC_bruteVars['montant'],
						  $CMCIC_bruteVars['reference'],
						  $CMCIC_bruteVars['texte-libre'],
						  $oTpe->sVersion,
						  $CMCIC_bruteVars['code-retour'],
						  $CMCIC_bruteVars['cvx'],
						  $CMCIC_bruteVars['vld'],
						  $CMCIC_bruteVars['brand'],
						  $CMCIC_bruteVars['status3ds'],
						  $CMCIC_bruteVars['numauto'],
						  $CMCIC_bruteVars['motifrefus'],
						  $CMCIC_bruteVars['originecb'],
						  $CMCIC_bruteVars['bincb'],
						  $CMCIC_bruteVars['hpancb'],
						  $CMCIC_bruteVars['ipclient'],
						  $CMCIC_bruteVars['originetr'],
						  $CMCIC_bruteVars['veres'],
						  $CMCIC_bruteVars['pares']
						);

	if ($oHmac->computeHmac($cgi2_fields) == strtolower($CMCIC_bruteVars['MAC'])){
	
		$sPaymentReference = $CMCIC_bruteVars['reference'];
		$sCodeRetour = $CMCIC_bruteVars['code-retour'];
		$bIsAllowedTransaction = TRUE;
		
		switch($CMCIC_bruteVars['code-retour']) {
		
			case "Annulation" :
				$bPaymentStatusDone = FALSE;
				break;
				
			// Payment has been accepeted on the test server
			case "payetest":
				$bPaymentStatusDone = TRUE;
				break;
				
			// Payment has been accepted on the productive server
			case "paiement":
				$bPaymentStatusDone = TRUE;
				break;

			/*** ONLY FOR MULTIPART PAYMENT ***/
			case "paiement_pf2":
			case "paiement_pf3":
			case "paiement_pf4":
				// Payment has been accepted on the productive server for the part #N
				// return code is like paiement_pf[#N]
				// put your code here (email sending / Database update)
				// You have the amount of the payment part in $CMCIC_bruteVars['montantech']
				break;

			case "Annulation_pf2":
			case "Annulation_pf3":
			case "Annulation_pf4":
				// Payment has been refused on the productive server for the part #N
				// return code is like Annulation_pf[#N]
				// put your code here (email sending / Database update)
				// You have the amount of the payment part in $CMCIC_bruteVars['montantech']
				break;
		}//end switch

		$sReceipt = CMCIC_CGI2_MACOK;
		
	}//end if
	else{
		$bIsAllowedTransaction = FALSE;
		$bPaymentStatusDone = FALSE;
		$sReceipt = CMCIC_CGI2_MACNOTOK.$cgi2_fields;
	}//end else
	
	//-----------------------------------------------------------------------------
	// Send receipt to CMCIC server
	//-----------------------------------------------------------------------------
	printf (CMCIC_CGI2_RECEIPT, $sReceipt);
	
}//end if
?>