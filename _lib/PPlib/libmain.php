<?php
/**
 * Librairie adhesion
 * 
 */
namespace PPlib;

define('URL_ROOT', "http://aliaslct/dev/VAD-PP/");

define('PPLIB_PATH', dirname(__FILE__));
define('PPLIB_PATH_TMP', PPLIB_PATH.'/tmp');
define('PPLIB_PATH_MAILER', PPLIB_PATH.'/phpmailer');

define('PPLIB_PATH_PAYMENT', PPLIB_PATH.'/payment');

define('PPLIB_PATH_PAYBOX_FILES', PPLIB_PATH_TMP.'/payboxCgiFiles');

define('ACTION_SUCCESS', 'OK');
define('ACTION_FAILURE', 'FAIL');
define('ACTION_ERROR', 'ERROR');

define('SQL_DSN', 'mysql:dbname=don;host=cale.partipirate.ppo');
define('SQL_USER', 'don');
define('SQL_PASSWD', '****');

// Paramétrages avec la boutique test
/*
define ("PAYBOX_SITE", "1999888");
define ("PAYBOX_RANG", "99");
define ("PAYBOX_IDENTIFIANT", "2");
*/

define ("PAYBOX_SITE", "1999888");
define ("PAYBOX_RANG", "99");
define ("PAYBOX_IDENTIFIANT", "2");

define ("PAYBOX_DEVISE_EURO", "978");
define ("PAYBOX_DEVISE_USD", "840");

define ("PAYBOX_LANG_FRA", "FRA");

define ("PAYBOX_PAYMENT_URL", "https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi");
define ("PAYBOX_PAYMENT_URL_BACKUP1", "https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi");
define ("PAYBOX_PAYMENT_URL_BACKUP2", "https://preprod-tpeweb.paybox.com/cgi/MYchoix_pagepaiement.cgi");

define ("PAYBOX_ALLOWED_IP", "195.101.99.76|194.2.122.158|195.25.7.166");
define ("PAYBOX_PUBKEY", PPLIB_PATH."/payment/paybox_pubkey.pem");

define ("PAYBOX_CGI_URL", "http://aliaslct/cgi-bin/paybox.cgi");
define ("PAYBOX_EVENT_URL", URL_ROOT."payboxEvent.php");
define ("PAYBOX_PAYMENT_CONTACT", "secretaires-nationaux@partipirate.org");

ini_set('display_errors', TRUE);
ini_set('error_reporting', TRUE);

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
