<?php
include_once sprintf('%s/libmain.php', dirname(__FILE__).'/../_lib/PPlib');
include_once sprintf('%s/adhesion.php', PPLIB_PATH);
//var_dump($_FILES['input_apayer']['type']);
if(!isset($_FILES['input_apayer']) or !in_array($_FILES['input_apayer']['type'], array('text/csv','text/comma-separated-values', 'application/vnd.ms-excel'))) {
	die('Erreur fichier');
}
$fileName = $_FILES['input_apayer']['name'];
$filePath = $_FILES['input_apayer']['tmp_name'];
//é
$fileTitle = array();
$fileTable = array();
if (($handle = fopen($filePath, 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, null, ';', '"')) !== FALSE) {
		if(!count($fileTitle)) {
			for ($i=0; $i < count($data); $i++) {
				$fileTitle[$i] = $data[$i];
			}
		} else {
			$row = array();
			for ($i=0; $i < count($data); $i++) {
				if(empty($fileTitle[$i])) {
					continue;
				}
				$row[$fileTitle[$i]] = utf8_decode($data[$i]);
			}
			$fileTable[] = $row;
		}
    }
    fclose($handle);
}
$error = array();
foreach($fileTable as $key => &$line) {
//if($key!=0) continue;
	$line['CodePostal'] = str_replace(array('="', '"'), '', $line['CodePostal']);
	$line['result'] = array(
		'datePaiement' => $line['DatePaiement'],
		'objetPaiement' => $line['ObjetPaiement'],
		'autreObjet' => $line['AutreObjet'],
		'montant' => $line['Montant'],
		'reference' => $line['Reference'],
		'commentaire' => $line['Commentaire'],
		'referenceBancaire' => $line['ReferencBancaire'],
		'numeroAutorisation' => $line['NumeroAutorisation'],
		'nom' => $line['Nom'],
		'prenom' => $line['Prenom'],
		'adresse' => $line['Adresse'],
		'codePostal' => $line['CodePostal'],
		'ville' => $line['Ville'],
		'courriel' => $line['Courriel'],
		'etat' => $line['Etat'],
		'motifRefus' => $line['MotifRefus'],
		'cvx' => $line['Cvx'],
		'vld' => $line['Vld'],
		'brand' => $line['Brand'],
		'status3DS' => $line['Status3DS'],
		'PERSONNE_ref' => null,
		'ADHESION_ref' => null,
		'DON_ref' => null,
	);
	if(preg_match('/(?<personne>PER_[0-9]{12}_[a-z0-9]{8})/i', $line['Reference'], $match)) {
		$line['result']['PERSONNE_ref'] = $match['personne'];
	}
	if(preg_match('/(?<adhesion>ADH_[0-9]{12}_[a-z0-9]{8})/i', $line['Commentaire'], $match)) {
		$line['result']['ADHESION_ref'] = $match['adhesion'];
	}
	if(preg_match('/(?<don>DON_[0-9]{12}_[a-z0-9]{8})/i', $line['Commentaire'], $match)) {
		$line['result']['DON_ref'] = $match['don'];
	}
	$id = PPlib\adhesion\validApayerfr($line['result'], $fileName);
	if($id==0) {
		$error[$key] = $line;
	}elseif($id<0) {
		$error[$key] = $id;
	}
}
echo '<pre>';
//print_r($fileTable[0]);
print_r($error);
die('Done.');


?>
