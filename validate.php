<?php


    mysql_connect("127.0.0.1", "*****", "*****");
    mysql_select_db("*****");
    mysql_query("SET NAMES utf8");


$sCommentaire = '';

foreach ($_POST as $entry => $val) {
	
	// Permet de ne pas déclencher d'erreur sur l'interface de paiement
	if($entry == 'data/fiche/commentaire'){
		$sCommentaire = substr(preg_replace("#\r|\n#", ' ', str_replace('"', '', $val)), 0, 150);
	}//end if
	
    $_POST[$entry] = strip_tags(mysql_real_escape_string($val));
}


function checknumeric($list) {
    $return = Array();
    foreach ($list as $entry => $val) {
        if (!is_numeric($val)) {
            array_push($list, $val);
        }
    }
    return $return;
}

$fields = Array("data/fiche/prenom", "data/fiche/nom", "data/fiche/adresse", "data/fiche/adresse2", "data/fiche/courriel", "data/fiche/ville", "data/fiche/cp", "data/fiche/montant", "sectionlocale", "data/fiche/sl_donne", "data/fiche/commentaire", "data/fiche/membre_forum", "data/fiche/pseudo", "data/fiche/tel", "magique", "xformsegnmodelinfo", "xformslang", "xformscountry", "data/fiche/objet_paiement_combo", "data/fiche/devise", "data/fiche/autre_precision", "data/fiche/afficheObjetPaiement", "data/fiche/reference", "data/fiche/majeur", "accepte", "ml_gen", "ml_disc", "ml_consult", "ml_cr");

$numeric = Array("data/fiche/cp", "data/fiche/tel", "data/fiche/montant", "data/fiche/sl_donne", "data/fiche/tresor_donne");
$facul = Array("data/fiche/membre_forum", "data/fiche/tel", "data/fiche/adresse2", "data/fiche/pseudo", "sectionlocale", "data/fiche/sl_donne", "data/fiche/tresor_donne", "data/fiche/commentaire", "data/fiche/autre_precision", "ml_disc", "ml_gen", "ml_consult", "ml_cr");

$apayer = Array("data/fiche/prenom", "data/fiche/nom", "data/fiche/adresse", "data/fiche/adresse2", "data/fiche/courriel", "data/fiche/ville", "data/fiche/cp", "data/fiche/montant", "data/fiche/sl_donne", "data/fiche/commentaire", "data/fiche/tel", "data/fiche/reference", "data/fiche/afficheObjetPaiement", "xformsegnmodelinfo", "xformslang", "xformscountry", "data/fiche/objet_paiement_combo", "data/fiche/devise", "data/fiche/autre_precision");

$database = Array("prenom" => "data/fiche/prenom", "nom" => "data/fiche/nom", "adresse" => "data/fiche/adresse", "adresse2" => "data/fiche/adresse2", "email" => "data/fiche/courriel", "pseudo" => "data/fiche/pseudo", "membre_forum" => "data/fiche/membre_forum", "telephone" => "data/fiche/tel", "montant" => "data/fiche/montant", "sl_inscription" => "sectionlocale", "sl_donne" => "data/fiche/sl_donne", "tresor_donne" => "data/fiche/tresor_donne", "ville" => "data/fiche/ville", "cp" => "data/fiche/cp", "commentaire" => "data/fiche/commentaire", "ml_general" => "ml_gen", "ml_discussions" => "ml_disc", "ml_consultation" => "ml_consult", "ml_crconseil" => "ml_cr");

$missing = Array();
$wrong = Array();
$ignore = Array();

// If the field has no value and must have one, add to $missing
foreach($fields as $entry => $val) {
    if (!isset($_POST[$val]) || empty($_POST[$val])) {
        if (!in_array($val, $facul)) {
            array_push($missing, $val);
        }
        array_push($ignore, $val);
    }
}

foreach($facul as $entry => $val) {
    if(!in_array($val, $ignore)) {
        if (empty($_POST[$val])) {
            array_push($ignore, $val);
        }
    }
}

foreach($fields as $entry => $val) {
    if (!in_array($val, $ignore)) {
        if (!is_numeric($_POST[$val]) && in_array($val, $numeric)) {
            //echo $val;
            array_push($wrong, $val);
            array_push($ignore, $val);
        }
    }
}

if ((!in_array('data/fiche/sl_donne', $ignore)) && (in_array('sectionlocale', $ignore))) {
    array_push($ignore, 'data/fiche/sl_donne');
    array_push($wrong, 'data/fiche/sl_donne');
}

if ($_POST['data/fiche/sl_donne'] < 0) {
    if (!in_array('data/fiche/sl_donne', $ignore)) {
        array_push($ignore, 'data/fiche/sl_donne');
        array_push($wrong, 'data/fiche/sl_donne');
    }
}

if (!in_array('data/fiche/montant', $ignore)) {
    $sum = $_POST['data/fiche/montant'];
	
    if (!in_array('data/fiche/sl_donne', $ignore)) {
        $sum += $_POST['data/fiche/sl_donne'];
    }

    $sum += $_POST['data/fiche/tresor_donne'];

    if ($sum < 10 || $sum > 7500) {
        array_push($wrong, 'total');
    }
}
    // $missing = list of missing fields, coma separated
    // $wrong = list of numeric fields associated with non-numeric data, coma separated
    //$missing = "";
    //$wrong = "";
    // This one isn't one of our fields, so it's not in the « fields db »
    // but we have to check it exists (it's the unique ID created by apayer.fr)
    if (!(isset($_POST["magique"]))) {
        die("Tu veux pirater les pirates ? Essaye encore ;)");
    }
$ok = 0;
if (empty($missing) && empty($wrong)) {
    $ok = 1;
    $redirect = $_POST['magique'];
    if (isset($_POST['membre_forum'])) {
        $_POST['membre_forum'] = 1;
    } else {
        $_POST['membre_forum'] = 0;
    }
	
    if (isset($_POST['ml_gen'])) {
        $_POST['ml_gen'] = 1;
    } else {
        $_POST['ml_gen'] = 0;
    }
    if (isset($_POST['ml_disc'])) {
        $_POST['ml_disc'] = 1;
    } else {
        $_POST['ml_disc'] = 0;
    }

    if (isset($_POST['ml_consult'])) {
        $_POST['ml_consult'] = 1;
    } else {
        $_POST['ml_consult'] = 0;
    }
	
    if (isset($_POST['ml_cr'])) {
        $_POST['ml_cr'] = 1;
    } else {
        $_POST['ml_cr'] = 0;
    }
	

	
    $champs = '(id, ';
    $values = '(NULL, ';
    foreach($database as $entry => $val) {
        $champs .= $entry.', ';
        if (!isset($_POST[$val]) || empty($_POST[$val])) {

			// Le traitement du tableau pour créer la requête remplace par 0 si la valeur est vide
			if($val == 'data/fiche/adresse2'){
				$values .= '"", ';
			}//end if
			else{
				$values .= '0, ';
			}//end else
			
        } else {
            $values .= '"'.$_POST[$val].'", ';
        }
    }
    //echo $champs.'<br>';
    $champs = substr($champs, 0, -2).')';
    $values = substr($values, 0, -2).')';

    $request = 'INSERT INTO temporaire '.$champs.' VALUES '.$values;
    //echo $request;
    mysql_query($request) or die(mysql_error());

}
else {
    $redirect = 'index.php?missing=';
    foreach ($missing as $entry => $val) {
        $redirect .= $val.',';
    }
    $redirect = substr($redirect, 0, -1).'&wrong=';
    foreach ($wrong as $entry => $val) {
        $redirect .= $val.',';
    }
    $redirect = substr($redirect, 0, -1);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Redirection magique</title>
    </head>
    <body>
        <form method="POST" action="<?php echo $redirect; ?>" name="formulaire" id="formulaire" accept-charset="ISO-8859-1">
<?php


if ($ok == 1) {
    $_POST['data/fiche/montant'] += ($_POST['data/fiche/sl_donne'] + $_POST['data/fiche/tresor_donne']);
    foreach($_POST as $field => $val) {
	
		if($field == 'data/fiche/commentaire'){
			$val = $sCommentaire;
		}//end if
        
        if (in_array($field, $apayer)) {    
			echo '<input type="hidden" name="'.$field.'" value="'.$val.'">'.chr(10);
        }
    }
    echo '            <input type="hidden" name="data/fiche/confirmation_courriel" value="'.$_POST["data/fiche/courriel"].'">'.chr(10);
    //echo '            <input type="hidden" name="data/fiche/adresse2" value="">'.chr(10);
}
else {
    foreach ($_POST as $field => $val) {
        echo '<input type="hidden" name="'.$field.'" value="'.$val.'">'.chr(10);
    }
}

?>
        </form>
        <script type="text/javascript">
           document.formulaire.submit();
        </script>
    </body>
</html>
