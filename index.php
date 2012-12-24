<?php
include '../includes/database.php';
mysql_query("SET NAMES UTF8");
include '../includes/style2.php'; // définiton de $header et $footer
// // include '../includes/style/header.php';
include '../require/lib/sidebars.php'; // chargement de fonctions
include '../includes/truestyle/properinclude.php'; // encore des fonctions
include ("simple_html_dom.php"); // des 'define' et des fonctions

// paramétrages 
$ref_adhesion = 'adhesion2012';
$ref_don = 'don2012';
$renouv_on = false; // renouvellements ouverts de janvier à mars uniquement

// gestion parallèle don/adhésion
if (!isset($type_action) || $type_action !== 'don') $type_action='adhesion';

if ($type_action === 'adhesion') $ref_action = $ref_adhesion;
else $ref_action = $ref_don;

$html = file_get_html("https://www.apayer.fr/partipirate");
foreach($html->find('form') as $elem) {
    $action =  "https://www.apayer.fr".$elem->action;
}
$action = str_replace("&", "&amp;", $action);

if ($type_action === 'adhesion') {
	$title = "Adhésion en ligne";
}//end if
else{
	$title = "Faire un don en ligne";
}//end else

$css = "adhesion.css";

include '../require/templates/header2.php';

// Les erreurs à afficher par nom du champ
$nom_propre = Array("data/fiche/prenom" => "Prénom", "data/fiche/nom" => "Nom", "data/fiche/ville" => "Ville", "data/fiche/adresse" => "Adresse", "data/fiche/cp" => "Code Postal", "data/fiche/courriel" => "courriel","data/fiche/montant" => "Montant", "data/fiche/majeur" => "Majeur", "accepte" => "Accepter les conditions");
$strings_wrong = Array("data/fiche/sl_donne" => "La somme donnée à la section locale est incorrecte ! La section locale existe-t-elle ?",
                       "data/fiche/tel" => "Ce numéro de téléphone est incorrect… il ne doit contenir que des numéros",
                       "data/fiche/cp" => "Ce Code Postal est incorrect !",
                       "data/fiche/montant" => "Le montant de l'adhésion est incorrect ! Est-il bien d'au moins 10€ ?",
                       "total" => "Vous ne pouvez pas adhérer à moins de 10€ ou au total donner plus de 7500€ au Parti Pirate et à une section locale !"
                   );


?>
			<div id="contenu" class="full_width"><!-- décalage d'indentation, il y a déjà un div dans un des fichiers inclus -->
				<div class="page">
					<div id="content_box">
						<div id="content">
							<div class="post_box">
								<div class="headline_area">
									<!-- Un grand merci à gkr et Alexandre bonhomme pour leur contribution -->
<?php
if ($type_action === 'adhesion') {
?>
                                    <h2>J'adhère en ligne au Parti Pirate</h2>
									<br>
									<br>
<!-- <span style="color:#93117E; font-size:22px;">Attention &ndash; En raison de l'assembl&eacute;e g&eacute;n&eacute;rale du 21&nbsp;octobre&nbsp;2012, votre adh&eacute;sion ne pourra pas &ecirc;tre prise en compte imm&eacute;diatement.</span> -->
<br/>
<br/>
<span style="font-size:15px">&Eacute;tant d&eacute;j&agrave; proche de la fin de l'ann&eacute;, votre adh&eacute;sion <!-- sera prise en compte apr&egrave;s l'assembl&eacute;e g&eacute;n&eacute;rale, et --> comptera jusqu'&agrave; fin 2013.</span>
<?php
}
else{ 
?>
									<h2>Je fais un don en ligne au Parti Pirate</h2>
<?php
}
?>	 


						</div>
								<div class="contenu_texte">
<?php
if (isset($_GET["missing"])) {
	if(!empty($_GET["missing"])) {
		echo '                                    <h4>Il manque des informations !</h4>'.chr(10);
		echo '                                    <ul>'.chr(10);
		$splitted = explode(",", $_GET["missing"]);
		foreach($splitted as $field => $val) {
			echo '                                        <li>Le champ '.$nom_propre[$val].' est manquant !</li>'.chr(10);
		}
		echo '                                    </ul>'.chr(10);
		echo '                                    <br>'.chr(10);
	}
}
if (isset($_GET["wrong"])) {
	if(!empty($_GET["wrong"])) {
		echo '                                    <h4>Certains champs ne sont pas corrects !</h4>'.chr(10);
		echo '                                    <ul>'.chr(10);
		$splitted = explode(",", $_GET["wrong"]);
		foreach($splitted as $field => $val) {
			//echo '                                        <li>'.$val.'</li>'.chr(10);
			echo '                                        <li>'.$strings_wrong[$val].'</li>'.chr(10);
		}
		echo '                                    </ul>'.chr(10);
		echo '                                    <br>'.chr(10);
	}
}

?>
									<p>
									<form id="DoValider" method="POST" action="validate.php" accept-charset="UTF-8">
										<input type="hidden" name="magique" value="<?php echo $action; ?>">
										<input type="hidden" name="type_action" value="<?php echo $type_action; ?>">
										<input type="hidden" name="xformsegnmodelinfo" value="/APAYERASSO/ZZ/App_Render/AssociatiCreer.aspx;DFLT_XFORMS_MODEL" />
										<input type="hidden" name="xformslang" value="FR">
										<input type="hidden" name="xformscountry" value="FR">
										<input type="hidden" name="data/fiche/objet_paiement_combo" value="<?php echo ($type_action == 'don') ? 3 : 1; ?>">
										<input type="hidden" name="data/fiche/devise" value="EUR">
										<input type="hidden" name="data/fiche/autre_precision" value="">
										<input type="hidden" name="data/fiche/afficheObjetPaiement" value="2">
										<input type="hidden" name="data/fiche/reference" value="<?php echo $ref_action;?>">
										<span class="red">* champs obligatoires</span>
										<h3>Vos coordonnées</h3>
										<label class="required" for="frm_nom">Nom </label>
										<label class="required" for="frm_pnm">Prénom </label>
										<br><input type="text" id="frm_nom" name="data/fiche/nom" value="<?php if (isset($_POST['data/fiche/nom'])) { echo utf8_encode($_POST['data/fiche/nom']); }?>" tabindex=1>
										<input type="text" id="frm_pnm" name="data/fiche/prenom" value="<?php if(isset($_POST['data/fiche/prenom'])) { echo utf8_encode($_POST['data/fiche/prenom']); } ?>" tabindex=2>

										<br>
										<div class="required">
											<input type="checkbox" id="frm_ddn" name="data/fiche/majeur" <?php if(isset($_POST['data/fiche/majeur'])) { echo 'checked'; }?> tabindex=3>
											<label for="frm_ddn">Je suis majeur</label>
										</div>

										<label class="required" for="frm_adr">Adresse fiscale</label>
										<br><input class="long" type="text" id="frm_adr" name="data/fiche/adresse" value="<?php if(isset($_POST['data/fiche/adresse'])) { echo utf8_encode($_POST['data/fiche/adresse']); } ?>" tabindex=4>
										
										<label for="frm_adr">Adresse fiscale</label>
										<br><input class="long" type="text" id="frm_adr" name="data/fiche/adresse2" value="<?php if(isset($_POST['data/fiche/adresse2'])) { echo utf8_encode($_POST['data/fiche/adresse2']); } ?>" tabindex=4>
										
										
										<br><label class="required" for="frm_cp">Code Postal </label>
										<label class="required" for="frm_vil">Ville </label>
										<br><input type="text" name="data/fiche/cp" id="frm_cp" value="<?php if(isset($_POST['data/fiche/cp'])) { echo utf8_encode($_POST['data/fiche/cp']); }?>" tabindex=5>
										<input type="text" name="data/fiche/ville" id="frm_vil" value="<?php if(isset($_POST['data/fiche/ville'])) { echo utf8_encode($_POST['data/fiche/ville']); }?>" tabindex=6>
										<br><label class="required">Pays</label>
										<br><input type="text" value="<?php if (isset($_POST['data/fiche/pays'])) { echo utf8_encode($_POST['data/fiche/pays']); }?>" name="data/fiche/pays" tabindex=7>

										<br><label class="required" for="frm_eml">E-Mail</label>
										<br><input type="text" id="frm_eml" name="data/fiche/courriel" value="<?php if(isset($_POST['data/fiche/courriel'])) { echo $_POST['data/fiche/courriel']; } ?>" tabindex=8>
										<br><label class="form_label" for="frm_tel">Téléphone </label>
										<br><input type="text" name="data/fiche/tel" id="frm_tel" value="<?php if(isset($_POST['data/fiche/tel'])) { echo $_POST['data/fiche/tel']; }?>" tabindex=9>
<?php // apriori pas besoin de stocker le pseudo pour un don 
if ($type_action === 'adhesion' ) {
?>
										<br><label for="frm_pse">Pseudo </label>
										<br><input type="text" name="data/fiche/pseudo" id="frm_pse" value="<?php if(isset($_POST['data/fiche/pseudo'])) { echo $_POST['data/fiche/pseudo']; }?>" tabindex=10>
										<input type="checkbox" name="data/fiche/membre_forum" value="1" id="frm_cb" <?php if(isset($_POST['data/fiche/membre_forum'])) { echo 'checked="checked"'; }?> tabindex=11>
										<label for="frm_cb" class="forum">Je suis membre du forum</label>
										<h3>Votre adhésion</h3>
										
	<?php // La partie concernant les renouvellements ne doit être active que de janvier à mars de chaque année
		if ($renouv_on === true ) {
	?>	

										<div class="required">
											<input id="rd_a" type="radio" name="renouvellement" value="1" <?php if(		isset($_POST['renouvellement'])) { if($_POST['renouvellement'] == "1") {echo 'checked="checked"'; } }?> tabindex=12>
											<label for="rd_a" class="radio"> Adhésion</label>
											<input id="rd_r" type="radio" name="renouvellement" value="2" <?php if(		isset($_POST['renouvellement'])) { if($_POST['renouvellement'] == "1") { echo 'checked="checked"'; } }?> tabindex=13>
											<label for="rd_r" class="radio"> Renouvellement</label>
										</div> 
	<?php }//end renouvellement ?>
										<div class="required">
											Montant (libre à partir de 10€)
											<br>
											<input id="frm_montant" type="text" name="data/fiche/montant" class="montant" value="<?php if(isset($_POST['data/fiche/montant'])) { echo $_POST['data/fiche/montant']; }?>" tabindex=14>€
										</div>
<?php } else { //don ?>

										<h3>Votre don (budget g&eacute;n&eacute;ral)</h3>
										<div class="required">
											Montant (libre à partir de 10€)
											<br>
											<input id="frm_montant" type="text" name="data/fiche/montant" class="montant" value="<?php if(isset($_POST['data/fiche/montant'])) { echo $_POST['data/fiche/montant']; }?>" tabindex=14>€
										</div>
										

										
<?php } ?>

<?php 
$bActiveTresor = true;
if($bActiveTresor){
?>
										<h3>Contribuer au financement des campagnes</h3>
										Je contribue &eacute;galement au "trésor" pirate qui servira à financer les prochaines campagnes.<br>
										<input id="tresor_donne" type="text" name="data/fiche/tresor_donne" class="montant" value="<?php if(isset($_POST['data/fiche/tresor_donne'])) { echo $_POST['data/fiche/tresor_donne']; }?>" tabindex=14>€
										
<?php
}//end if
else{
?>
										<input id="tresor_donne" type="hidden" name="data/fiche/tresor_donne" class="montant" value="0">
										
<?php
}//end else
?>
<?php 
if ($type_action === 'adhesion' ) {
?>
										<h3>S'investir au niveau local</h3>
										<label>Je rejoins la section locale :</label>
<?php } else { ?>
										<h3>Et je donne aussi à cette section locale</h3>
										<label>Choisir la section :</label>
<?php } ?>
										<!--<input type="checkbox" id="sl_inscription" name="data/fiche/sl_suscribe" value="1"> -->
<?php
$selected_value = array();
$list_section = array(
	'Alsace','Aquitaine','Auvergne','Bourgogne','Bretagne',
	'Centre','ChampagneArdenne','Corse','FrancheComte',
	'IleDeFrance','LanguedocRoussillon','Limousin','Lorraine',
	'MidiPyrenees','NordPasDeCalais','BasseNormandie','HauteNormandie',
	'PaysDeLaLoire','Picardie','PoitouCharentes','PACA',
	'RhoneAlpes','Monaco'
);

if(isset($_POST['sectionlocale']) && in_array( $_POST['sectionlocale'], $list_section)) {
	$selected_value[$_POST['sectionlocale']] = 'selected="selected"';
}
if(empty($selected_value)) $selected_value['default'] = 'selected="selected"';
?>
										<div> 
											<select class="element select medium" id="element_19" name="sectionlocale" tabindex=15> 
												<option value="" <?php echo $selected_value['default'];?>></option> 
												<option value="Alsace" <?php echo $selected_value['Alsace'];?>>Alsace</option>
												<option value="Aquitaine" <?php echo $selected_value['Aquitaine'];?>>Aquitaine</option>
												<!-- <option value="Auvergne" <?php echo $selected_value['Auvergne'];?>>Auvergne</option> -->
												<!-- <option value="Bourgogne" <?php echo $selected_value['Bourgogne'];?>>Bourgogne</option> -->
												<option value="Bretagne" <?php echo $selected_value['Bretagne'];?>>Bretagne</option>
												<option value="Centre" <?php echo $selected_value['Centre'];?>>Centre</option>
												<option value="ChampagneArdenne" <?php echo $selected_value['ChampagneArdenne'];?>>Champagne-Ardennes</option>
												<!-- <option value="Corse" <?php echo $selected_value['Corse'];?>>Corse</option> -->
												<option value="FrancheComte" <?php echo $selected_value['FrancheComte'];?>>Franche-Comté</option>
												<option value="IleDeFrance" <?php echo $selected_value['IleDeFrance'];?>>Île-de-France</option>
												<option value="LanguedocRoussillon" <?php echo $selected_value['LanguedocRoussillon'];?>>Languedoc-Roussillon</option>
												<!-- <option value="Limousin" <?php echo $selected_value['Limousin'];?>>Limousin</option> -->
												<option value="Lorraine" <?php echo $selected_value['Lorraine'];?>>Lorraine</option>
												<option value="MidiPyrenees" <?php echo $selected_value['MidiPyrenees'];?>>Midi-Pyrénées</option>
												<option value="NordPasDeCalais" <?php echo $selected_value['NordPasDeCalais'];?>>Nord-Pas-De-Calais</option>
												<!-- <option value="BasseNormandie" <?php echo $selected_value['BasseNormandie'];?>>Basse-Normandie</option> -->
												<option value="HauteNormandie" <?php echo $selected_value['HauteNormandie'];?>>Haute-Normandie</option>
												<!-- <option value="PaysDeLaLoire" <?php echo $selected_value['PaysDeLaLoire'];?>>Pays De La Loire</option> -->
												<!-- <option value="Picardie" <?php echo $selected_value['Picardie'];?>>Picardie</option> -->
												<option value="PoitouCharentes" <?php echo $selected_value['PoitouCharentes'];?>>Poitou-Charentes</option>
												<!-- <option value="PACA" <?php echo $selected_value['PACA'];?>>PACA</option>  -->
												<option value="RhoneAlpes" <?php echo $selected_value['RhoneAlpes'];?>>Rhônes-Alpes</option> 
												<!-- <option value="Monaco" <?php echo $selected_value['Monaco'];?>>Monaco</option>  -->
												<!-- <option value="guadeloupe" <?php echo $selected_value['guadeloupe'];?>>Guadeloupe</option>  -->
												<!-- <option value="martinique" <?php echo $selected_value['martinique'];?>>Martinique</option>  -->
												<!-- <option value="guyane" <?php echo $selected_value['guyane'];?>>Guyane</option>  -->
												<!-- <option value="reunion" <?php echo $selected_value['reunion'];?>>Réunion</option>  -->
												<!-- <option value="saintpierreetmiquelon" <?php echo $selected_value['saintpierreetmiquelon'];?>>Saint Pierre et Miquelon</option>  -->
												<!-- <option value="mayotte" <?php echo $selected_value['mayotte'];?>>Mayotte</option>  -->
												<!-- <option value="saintbarthelemy" <?php echo $selected_value['saintbarthelemy'];?>>Saint Barthelemy</option>  -->
												<!-- <option value="saintmartin" <?php echo $selected_value['saintmartin'];?>>Saint Martin</option>  -->
												<!-- <option value="wallisetfutuna" <?php echo $selected_value['wallisetfutuna'];?>>Wallis et Futuna</option>  -->
												<!-- <option value="polynesiefrancaise" <?php echo $selected_value['polynesiefrancaise'];?>>Polynesie francaise</option>  -->
												<!-- <option value="nouvellecalédonie" <?php echo $selected_value['nouvellecalédonie'];?>>Nouvelle Calédonie</option>  -->
											</select> 
										</div>
<?php if ($type_action === 'adhesion' ) { ?>
										Je donne aussi à la section locale
<?php } else { ?>
										Montant du don à la section locale
<?php } ?>
										<br><input type="text" id="sl_donne" name="data/fiche/sl_donne" class="montant" value="<?php if(isset($_POST['data/fiche/sl_donne'])) { echo $_POST['data/fiche/sl_donne']; }?>" tabindex=16>€
<?php if ($type_action === 'don' ) { ?>
                                        <p>&nbsp;<p>En raison des frais engendré par chaque don, nous ne pouvons pas accepter de dons d'un montant inf&eacute;rieur &agrave; 10&nbsp;&euro;.<br> Montant total maximum 
de don (y compris les dons aux sections locales)&nbsp;: 7500&nbsp;&euro; (en cumul&eacute; par an,  adh&eacute;sion et dons), en partie déductible du revenu imposable (voir ci-dessous). Les imputations sont indicatives, voir le règlement intérieur pour la règle de dévolution.</p>
<?php } ?>
										<h3>Autres informations</h3>
<?php if ($type_action === 'adhesion' ) { ?>
										<p>S'abonner à :</p>
										<input type="checkbox" name="ml_gen" value="1"> - la Mailing List Générale<br>
										<input type="checkbox" name="ml_consult" value="1"> - les annonces de votes et consultation<br>
										<input type="checkbox" name="ml_cr" value="1"> - les comptes rendus des conseils<br>
										<!--<br><label for="ml_disc">- la Mailing List Discussions</label> <input type="checkbox" name="ml_disc" value="1">-->
										<!-- <p>
											Attention, cette liste de diffusion a un trafic important. Vous pouvez consulter 
											les archives avant de vous 
											<a href="http://lists.partipirate.org/pipermail/discussions/">y inscrire</a>. 
											Nous vous recommandons d'utiliser une adresse e-mail dédiée 
											(vous pouvez vous y inscrire directement sans être adhérent via 
											<a href="http://lists.partipirate.org/mailman/listinfo/discussions">cette adresse</a>.
										</p> -->
										<br>
<?php } ?>
										<label for="frm_com">Correspondance</label>
										<br><textarea id="frm_com" name="data/fiche/commentaire" tabindex=17><?php if(isset($_POST['data/fiche/commentaire'])) { echo $_POST['data/fiche/commentaire']; }?></textarea>
										<br>
										<div class="required">
											<input type="checkbox" name="accepte" value="1" tabindex=18 <?php if (isset($_POST['accepte'])) { echo 'checked="checked"'; } ?>> 
											Je signe la <a href="#conditions">déclaration sur l'honneur</a> et j'ai lu les <a href="#mentions">mentions à savoir</a>.<br>
										</div>
<?php if ($type_action === 'adhesion' ) { ?>
										<input type="submit" value="Adhérer !" class="bt_send" tabindex=19><br>
<?php } else { ?>
										<input type="submit" value="Donner !" class="bt_send" tabindex=19><br>
<?php } ?>
									</form>									
<?php if ($type_action === 'adhesion' ) { ?>

                <h3 id="conditions">Déclaration sur l'honneur</h3>
                                    <p>Je déclare sur l’honneur que je suis à l’origine des fonds versés pour cette adhésion au Parti Pirate, et que ces fonds ne viennent pas d’une tierce personne ou d’une personne morale.</p>
                                    <h3 id="mentions">À savoir</h3>
<p>L'enregistrement des adhésions en ligne se fait une fois par semaine. Ne vous inquiétez pas si vous ne recevez pas votre mail de bienvenue instantanément. <a href="https://www.partipirate.org/spip.php?article119">Suivez ce lien</A> pour savoir comment suivre l'avancement de votre adhésion.</p>
					                <p>Outre nos <a href="statuts.pdf">statuts</a> et notre <a href="ri.pdf">règlement intérieur</a> que vous devrez lire puisque vous vous engagez à vous y conformer, nous vous invitons particulièrement à prendre connaissance de notre <a href="http://partipirate.org/blog/com.php?id=214">déclaration de politique générale</a> et de notre <a href="http://legislatives.partipirate.org/2012/notre-programme/">programme</a> avant de remplir votre demande d'adhésion.</p>

<p>Les adhésions en tant que personne morales ne sont pas ouvertes.</p>

<p>Ce bulletin vous permet d'adhérer jusqu'à fin de l'année civile. Pour ceux qui adhèrent en fin d'année (de novembre à décembre), l'adhésion est automatiquement prolongée jusqu'à la fin de l'année suivante.</p>

<p>Si vous êtes déjà inscrit dans, le forum, votre compte et votre pseudo de forum seront promus au groupe "adhérent", et cette information (que tel pseudo est adhérent) sera visible du public. Cette promotion est automatique. Si vous ne le souhaitez pas, ne nous communiquez pas votre pseudo du forum.</p>

<p>Conformément à l’article 34 de la loi N°78-17 du 6 janvier 1978 dite « Informatique et Libertés », vous disposez d’un droit d’accès, de modification, de rectification,
de suppression des données qui vous concernent sur simple demande à &lt;listes(at)lists.partipirate.org&gt;. Le Parti Pirate est une association à but politique régie par la loi du 1er juillet 1901. L’Association de Financement du Parti Pirate, déclarée le 21/04/11 a été agréée le 18/07/11.</p>

<p>Voir <a href="https://www.partipirate.org/spip.php?article107">cette page</A> pour 
notre politique   <i>Informatique & Liberté</I>, de confidentialité des donneés communiquées sur ce bulletin, et les autres mentions à savoir avant d'adhérer.
<?php 
}//end if
else { 
?>
									<h3 id="conditions">Déclaration sur l'honneur</h3>
									<p>Je déclare sur l’honneur que je suis à l’origine des fonds versés pour ce don au Parti Pirate, et que ces fonds ne viennent pas d’une tierce personne ou d’une personne morale.</p>
                                    <h3 id="mentions">À savoir</h3>
<p>
Le Parti Pirate est  un parti politique, votre cotisation vous donne 
droit &agrave; une r&eacute;duction de 66&nbsp;% des sommes vers&eacute;es dans
la limite de 20&nbsp;% du revenu imposable (<a href="http://vosdroits.service-public.fr/F427.xhtml">voir cette page</A>). Il vous sera &eacute;tabli un certificat fiscal nominatif que vous recevrez par la Poste. <p>

                                   <p>Le Parti Pirate est une association à but politique régie par la loi du 1er juillet 1901. L’Association de Financement du Parti Pirate, déclarée le 21/04/11 a été agréée le 18/07/11.
                                   
</p>
                                   
									<p>Les données collectées seront utilisées pour l’établissement et l'envoi des reçus fiscaux</em>, pour le contrôle des comptes auprès de la CNCCFP (Commission nationale des comptes de campagne et des financements politiques) et à des fins de comptabilité interne ; elles seront communiquées au 
									

sectr&eacute;tariat national et, de façon partielle, au chargé de trésorerie de la section locale ou interne à laquelle vous donnez. En sus de ces traitements, vos données seront utilisées de manière anonyme pour des traitements statistiques qui pourront être publiés. <!--Si vous souhaitez vous opposer à ce traitement statistique, faites-le savoir en cochant la case appropriée sur le formulaire de don. --></p><p>
									
									                                   Conformément à la loi N°78-17 du 6 janvier 1978 dite «&nbsp;Informatique et Libertés&nbsp;», vous disposez d’un droit d’accès, de modification, de rectification, de suppression des données qui vous concernent. Toute demande peut être adressée à &lt;listes(at)lists.partipirate.org&gt;. 
									                                   
<?php } ?>
<p>Vous devez indiquer votre identit&eacute; v&eacute;ritable&nbsp;; donner une fausse identit&eacute; constituerai une fraude fiscale.<p> 

<?php if ($type_action === 'don' ) { ?>
									
									
<?php } ?>
									<h3 id="mentions">Mentions légales</h3>
									<p>Consulter  <a href="https://www.partipirate.org/spip.php?article107"/>cette page</a> pour les autres mentions légales.				<p>&nbsp;
				<p>&nbsp;


								</div>
							</div>
						</div>
					</div>
					<div id="sidebars">
<?php
include '../require/templates/sidebars2.php';
?>
					</div>
				</div>
<?php
//properinclude("../includes/truestyle/footer.php", 4);
?>
			</div>
        </div> <!-- pas de correspondance dans ce fichier, l'ouverture de ce div est incluse qqpart-->
    </body>
</html>
