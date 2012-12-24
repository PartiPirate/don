<?php
/**
 * Librairie adhesion
 * 
 */
namespace PPlib;

const __DOT_AS_DECIMAL_POINT = "dotAsDecimalPoint";

/**
 * Convertit les données binaires $sString en représentation hexadécimale 
 * @param $sString
 * @return string
 * @access static
 */
function toBinaryString($sString){

	$sBinaryString = bin2hex($sString);
	if(strlen($sBinaryString) > 0){
		$sBinaryString = '0x'.$sBinaryString;
	}//end if
	else{
		$sBinaryString = '0x00';
	}//end else
	return $sBinaryString;
}//end function

/**
 * Supprime toutes les balises HTML de $sInput
 * @access static
 * @param string $sInput
 * @return string
 */
function toSafeHTMLString($sInput){
	return preg_replace('/<[^<>]+>/','' ,trim(stripslashes($sInput)));
}//end function
	
/**
 * Convertit tous les caractères qui posent problème pour les noms de fichiers (accents, espace, etc...)
 * @access static
 * @param string $sInput
 * @return string
 */
function cleanString($sInput){
	return preg_replace('/([^a-z|A-Z|0-9|-|\.|_])+/','',str_replace(" ","_",utf8_decode(strtr($sInput,"ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ","aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn"))));
}//end function

/**
 * Tente de convertir $sValue en int ou renvoie NULL
 * 
 * @param mixed $sValue
 * @return float
 **/
function toInt($sValue) {
	return (is_numeric($sValue)) ? (int)$sValue : NULL;
}//end function

/**
 * Tente de convertir $sValue en bool ou renvoie NULL
 * 
 * @param mixed $sValue
 * @return float
 **/
function toBool($sValue) {
	if(is_numeric($sValue)){
		return ((int)$sValue == 1)? TRUE :  FALSE;
	}//end if
	else{
		return NULL;
	}//end else
}//end function

/**
 * Tente de convertir $sValue en float ou renvoie NULL
 * 
 * @param mixed $sValue
 * @return float
 **/
function toFloat($sValue, $iOption = __DOT_AS_DECIMAL_POINT) {

	if(preg_match("/([0-9.,-]+)/", $sValue, $aMatches)){

		$sValue = $aMatches[0];
		
		if(strstr($sValue, ',')){
			$sValue = str_replace('.', '', $sValue); // Separateur des milliers
			$sValue = str_replace(',', '.', $sValue); // convertit la virgule flottante
			
			return floatval($sValue);
		}//end if
		else{
			if(preg_match("/^[0-9]+[\.]{1}[0-9-]+$/", $sValue) == TRUE && $iOption == __DOT_AS_DECIMAL_POINT){
				return floatval($sValue);
			
			}//end if
			else{
				$str = str_replace('.', '', $sValue);    // Erase thousand seps
				return floatval($sValue);
			}//end else        
		}//end else
	}//end if
	elseif(is_null($sValue)){
		return floatval(0);
	}//end elseif
	else{
		return NULL;
	}//end else
	
}//end function

?>