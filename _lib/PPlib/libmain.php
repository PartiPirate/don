<?php
/**
 * Librairie adhesion
 * 
 */
namespace PPlib;

define('URL_ROOT', "http://don-beta.partipirate.ppo/");

define('PPLIB_PATH', dirname(__FILE__));
define('PPLIB_PATH_TMP', PPLIB_PATH.'/_tmp');
define('PPLIB_PATH_MAILER', PPLIB_PATH.'/phpmailer');
define('PPLIB_PATH_CMCIC', PPLIB_PATH.'/payment');
define('ACTION_SUCCESS', 'OK');
define('ACTION_FAILURE', 'FAIL');
define('ACTION_ERROR', 'ERROR');

define('SQL_DSN', 'mysql:dbname=spip_adhesion;host=cale.partipirate.ppo');
define('SQL_USER', '**');
define('SQL_PASSWD', '**');

define ("CMCIC_CLE", "0000000000000000000000000000000000000000");
define ("CMCIC_TPE", "0000000");
define ("CMCIC_CODESOCIETE", "partipirate");
define ("CMCIC_VERSION", "3.0");
define ("CMCIC_SERVEUR", "https://ssl.paiement.cic-banques.fr/test/");
define ("CMCIC_PAYMENT_CONTACT", "secretaires-nationaux@partipirate.org");

/**
 * Retourne un object PDO pour effectuer des manipulations SQL
 * @throw PDOException
 */
function SqlGetHandle(){

	static $_handle = NULL;
	
	if(is_null($_handle)){
		$_handle = new \PDO(SQL_DSN, SQL_USER, SQL_PASSWD);
		$_handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$_handle->setAttribute(\PDO::ATTR_AUTOCOMMIT, TRUE);
	}//end if
	
	return $_handle;
	
}//end function

?>