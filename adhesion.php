<?php
// paramétrages 
$renouv_on = true; // renouvellements ouverts de janvier à mars uniquement

// gestion parallèle don/adhésion
if (!isset($type_action) || $type_action !== 'don') $type_action='adhesion';

if ($type_action === 'adhesion') {
	$title = "Adhésion en ligne";
}//end if
else{
	$title = "Faire un don en ligne";
}//end else
?>
 <!DOCTYPE html>
  <html>
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <title><?php echo $title; ?> - Parti Pirate</title>
          <meta charset="utf-8">
          <link rel="stylesheet" type="text/css" href="styles/total.css">
          <link rel="stylesheet" type="text/css" href="styles/adhesion.css">
		  <script src="styles/jquery-1.8.3.min.js"></script>
      </head>
      <body class="pagination">
          <div id="centrage">
              <img src="styles/header4.png" alt="Parti Pirate" /><br />
			<div id="contenu" class="full_width">
				<div class="page">
					<div id="content_box">
						<div id="content">
							<div class="post_box">
								<div class="headline_area">
<?php
if ($type_action === 'adhesion') {
?>
                                    <h2>J'adhère en ligne au Parti Pirate</h2>
									<br><br/>
<p style="font-size:12px">Votre contribution vaudra pour l'année 2013 et vous donne droit à <a href="#mentions">66% de crédit d'impot</a> sur votre imposition 2013.</p>
<?php
}
else{ 
?>
									<h2>Je fais un don en ligne au Parti Pirate</h2>
									<br><br/>
<p style="font-size:12px">Votre contribution vous donne droit à <a href="#mentions">66% de crédit d'impot</a>.</p>
<?php
}
?>	 


								</div>
								<div class="contenu_texte">
<?php include '_lib/validate.php'; ?>

<form id="DoValider" method="POST" accept-charset="utf-8">
	<input type="hidden" name="type_action" value="<?php echo $type_action; ?>">
	<span class="red">* champs obligatoires</span>
	<h3>Vos coordonnées</h3>
	<label class="required" for="frm_nom">Nom </label>
	<label class="required" for="frm_pnm">Prénom </label>
	<label class="required" for="frm_eml" id="frm_eml_label">E-Mail</label>
	<br><input type="text" id="frm_nom" name="personne_nom" value="<?php echo htmlentities(@$_POST['personne_nom']); ?>">
	<input type="text" id="frm_pnm" name="personne_prenoms" value="<?php echo htmlentities(@$_POST['personne_prenoms']); ?>">
	<input type="text" id="frm_eml" name="personne_email" value="<?php echo htmlentities(@$_POST['personne_email']); ?>">
	<br>
	<div class="required">
		<input type="checkbox" id="frm_ddn" name="adherent_isMajeur" <?php if(@$_POST['adherent_isMajeur']) { echo 'checked'; }?> value="1">
		<label for="frm_ddn">Je suis majeur</label>
	</div>
	<br><label class="required" for="frm_adr">Adresse fiscale</label>
	<br><input class="long" type="text" id="frm_adr" name="personne_adresseFiscale_ligne1" value="<?php echo htmlentities(@$_POST['personne_adresseFiscale_ligne1']); ?>">
	<br><input class="long" type="text" id="frm_adr2" name="personne_adresseFiscale_ligne2" value="<?php echo htmlentities(@$_POST['personne_adresseFiscale_ligne2']); ?>">
	
	<br><label class="required" for="frm_cp">Code Postal </label>
	<label class="required" for="frm_vil">Ville </label>
	<label class="required" for="pays" id="pays_label">Pays</label>
	<br><input type="text" name="personne_adresseFiscale_codePostal" id="frm_cp" value="<?php echo htmlentities(@$_POST['personne_adresseFiscale_codePostal']); ?>">
	<input type="text" name="personne_adresseFiscale_ville" id="frm_vil" value="<?php echo htmlentities(@$_POST['personne_adresseFiscale_ville']); ?>">
	<select class="element select medium" id="pays" name="personne_adresseFiscale_pays_oid"><?php
		$arrayPays = PPlib\adhesion\listPays();
		foreach($arrayPays as $pays) {
			echo '<option value="'.$pays['oid'].'"';
			if(!isset($_POST['personne_adresseFiscale_pays_oid'])) {
				if($pays['oid'] == 1) {
					echo ' selected';
				}
			}elseif($_POST['personne_adresseFiscale_pays_oid'] == $pays['oid']) {
				echo ' selected';
			}
			echo '>'.htmlentities($pays['libelle']).'</option>';
		}
	?></select>
	
	
	<br/><label class="form_label" for="frm_tel">Téléphone </label> <label for="frm_pse">Pseudo </label>
	<br/><input type="text" name="personne_telephone" id="frm_tel" value="<?php echo htmlentities(@$_POST['personne_telephone']); ?>"> <input type="text" name="personne_pseudonyme" id="frm_pse" value="<?php echo htmlentities(@$_POST['personne_pseudonyme']); ?>">

<?php 
if ($type_action == 'adhesion' ) {
?>
	<br/><label></label><input type="checkbox" name="adherent_inscritForum" value="1" id="frm_cb" <?php if(@$_POST['adherent_inscritForum']) { echo 'checked="checked"'; }?>> <label for="frm_cb" class="forum">Je suis membre du forum</label>
	
	<h3>Votre adhésion 2013</h3>
	<ul>
		<li><b>Plein Tarif :</b> libre à partir de <u>24€</u> ;</li>
		<li><b>Demi-tarif :</b> <u>12€</u> - Ce tarif est accessible aux mineurs, aux étudiants et aux demandeurs d'emploi ;</li>
		<li><b>Tarif réduit :</b> <u>6€</u> - Pour les personnes en difficultés (à votre appréciation).</li>
	</ul>
	<div class="required">
		<label for="frm_montant" style="display:inline">Montant (minimum 6€)</label>
		<input id="frm_montant" type="text" name="adhesion_montantCotisation" class="montant" value="<?php if(isset($_POST['adhesion_montantCotisation'])) { echo $_POST['adhesion_montantCotisation']; } else { echo '0';} ?>">€ 
	</div>
	<br/>&nbsp;
	<?php // La partie concernant les renouvellements ne doit être active que de janvier à mars de chaque année
		if ($renouv_on === true ) {
	?>	<p><input id="rd_r" type="checkbox" name="adhesion_isRenouvellement" value="1" <?php if(	@$_POST['adhesion_isRenouvellement']) { echo 'checked="checked"'; }?>>
		<label for="rd_r" class="radio" style="display:inline;"> Si vous étiez déjà adhérent en 2012, merci de cocher cette case.</label>
		</p>
	<?php }//end renouvellement ?>
<?php } 
if ($type_action === 'adhesion' ) {
?>
	<h3>S'investir au niveau local</h3>
	<label for="adherent_sectionlocale" style="display:inline">Je rejoins la section locale :</label>
	<select class="element select medium" id="adherent_sectionlocale" name="adherent_sectionlocale"><option value="0"></option><?php
		$arraySL = PPlib\adhesion\listSectionsLocales();
		foreach($arraySL as $SL) {
			echo '<option value="'.$SL['oid'].'" class="'.$SL['idInt'].'"';
			if(@$_POST['adherent_sectionlocale'] == $SL['oid']) {
				echo ' selected';
			}
			echo '>'.htmlentities($SL['libelle']).'</option>';
		}
	?></select>
	<br/>&nbsp;
	<p><i>Si votre région n'est pas présente dans la liste, c'est qu'aucun groupe local ne s'est encore formalisé dans votre région. Vous pouvez vous <a href="http://wiki.partipirate.org/wiki/Sections_locales" target="_blank">renseigner ici</a> pour initier un projet.</i></p>
<?php }

$bActiveTresor = true;
if($bActiveTresor){
?>
	<h3>Contribuer à un budget spécifique par un don (facultatif)</h3>
	
	<?php
	$arrayDon = PPlib\adhesion\listDonPostes();
	foreach($arrayDon as $don) {
		echo '<div class="budget budget-'.$don['idInt'].($don['brief']?' budget-long':'').($don['type']==5?' budget-sl':'').($don['type']==2?' budget-fade':'').'">';
		echo '		<label class="title" for="don_'.$don['oid'].'">'.htmlentities($don['libelle']).'</label>';
		if($don['brief']) {
			echo '	<label class="brief" for="don_'.$don['oid'].'">'.htmlentities($don['brief']).'</label>';
		}
		echo '<div class="montant"><input type="text" class="montant" name="don_'.$don['oid'].'" id="don_'.$don['oid'].'" value="'.(isset($_POST['don_'.$don['oid']])?$_POST['don_'.$don['oid']]:'0').'">€</div>';
		echo '</div>';
	}
	?>
	<script>
	var don = function() {
		var active_sl = function() {
			var sl_selected = $('#adherent_sectionlocale option:selected').attr('class');
			$('div.budget-sl, div.budget-fade').each(function(first, _self) {
				var self = $(_self);
				if(self.hasClass('budget-sl_'+sl_selected)) {
					self.addClass("budget-sl-hit");
					self.removeClass("budget-sl-hidden");
				} else if(self.find('input').val() == 0) {
					if(self.hasClass('budget-sl')) {
						self.removeClass("budget-sl-hit");
						self.addClass("budget-sl-hidden");
					}
					if(self.hasClass('budget-fade')) {
						self.addClass("budget-fadeon");
					}
				} else {
					if(self.hasClass('budget-fade')) {
						self.removeClass("budget-fadeon");
					}
					if(self.hasClass('budget-sl')) {
						self.removeClass("budget-sl-hit");
						self.removeClass("budget-sl-hidden");
					}
				}
			});
		};
		active_sl();
		$('#adherent_sectionlocale').change(active_sl);
		$('div.budget-sl input, div.budget-fade input').keyup(active_sl);
		$('div.budget-sl input, div.budget-fade input').change(active_sl);
	};
	$(don);
	</script>
	<div class="clearfix"></div>
<?php
}//end if
?>
		<h3>Autres informations</h3>
<?php if ($type_action === 'adhesion' ) { ?>
		<p>S'abonner à :</p>
		<?php
		$arrayML = PPlib\adhesion\listMailingLists();
		foreach($arrayML as $ML) {
			echo '<input type="checkbox" name="ml_'.$ML['code'].'" id="ml_'.$ML['code'].'" value="1"';
			if(@$_POST['ml_'.$ML['code']]) {
				echo ' checked';
			}
			echo '><label for="ml_'.$ML['code'].'" style="display:inline"> - '.htmlentities($ML['libelle']).'</label><br/>';
		}
		?>
		<br>
<?php } ?>
		<label for="frm_com">Correspondance</label>
		<br><textarea id="frm_com" name="personne_commentaires"><?php echo htmlentities($_POST['personne_commentaires']); ?></textarea>
		<br>
		<div class="required">
			<input type="checkbox" name="accepte" id="accepte" value="1" <?php if (isset($_POST['accepte'])) { echo 'checked="checked"'; } ?>> 
			<label for="accepte" style="display:inline">Je signe la <a href="#conditions">déclaration sur l'honneur</a> et j'ai lu les <a href="#mentions">mentions à savoir</a>.</label><br>
		</div>
<?php if ($type_action === 'adhesion' ) { ?>
		<input type="submit" value="Adhérer !" class="bt_send"><br>
<?php } else { ?>
		<input type="submit" value="Donner !" class="bt_send"><br>
<?php } ?>
		</form>									
<?php if ($type_action === 'adhesion' ) { ?>
	<h3 id="conditions">Déclaration sur l'honneur</h3>
		<p>Je déclare sur l’honneur que je suis à l’origine des fonds versés pour cette adhésion au Parti Pirate, et que ces fonds ne viennent pas d’une tierce personne ou d’une personne morale.</p>
	<h3 id="mentions">À savoir</h3>
		<p>L'enregistrement des adhésions en ligne se fait une fois par semaine. Ne vous inquiétez pas si vous ne recevez pas votre mail de bienvenue instantanément. <a href="https://www.partipirate.org/spip.php?article119">Suivez ce lien</A> pour savoir comment suivre l'avancement de votre adhésion.</p>
		<p>Outre nos <a href="https://partipirate.org/statuts.pdf">statuts</a> et notre <a href="https://partipirate.org/ri.pdf">règlement intérieur</a> que vous devrez lire puisque vous vous engagez à vous y conformer, nous vous invitons particulièrement à prendre connaissance de notre <a href="http://partipirate.org/blog/com.php?id=214">déclaration de politique générale</a> et de notre <a href="http://legislatives.partipirate.org/2012/notre-programme/">programme</a> avant de remplir votre demande d'adhésion.</p>
		<p>Les adhésions en tant que personne morales ne sont pas ouvertes.</p>
		<p>Le Parti Pirate est un parti politique, votre cotisation vous donne droit &agrave; une r&eacute;duction de 66&nbsp;% des sommes vers&eacute;es dans la limite de 20&nbsp;% du revenu imposable (<a href="http://vosdroits.service-public.fr/F427.xhtml" target="_blank">voir cette page</A>). Il vous sera &eacute;tabli un certificat fiscal nominatif que vous recevrez par la Poste en mars pour l'année précédente.<p>
		<p>Ce bulletin vous permet d'adhérer jusqu'à fin de l'année civile. Pour ceux qui adhèrent en fin d'année (de novembre à décembre), l'adhésion est automatiquement prolongée jusqu'à la fin de l'année suivante.</p>
		<!--p>Si vous êtes déjà inscrit dans, le forum, votre compte et votre pseudo de forum seront promus au groupe "adhérent", et cette information (que tel pseudo est adhérent) sera visible du public. Cette promotion est automatique. Si vous ne le souhaitez pas, ne nous communiquez pas votre pseudo du forum.</p-->
		<p>Conformément à l’article 34 de la loi N°78-17 du 6 janvier 1978 dite « Informatique et Libertés », vous disposez d’un droit d’accès, de modification, de rectification, de suppression des données qui vous concernent sur simple demande à &lt;listes(at)lists.partipirate.org&gt;. Le Parti Pirate est une association à but politique régie par la loi du 1er juillet 1901. L’Association de Financement du Parti Pirate, déclarée le 21/04/11 a été agréée le 18/07/11.</p>
		
		<p>Voir <a href="https://www.partipirate.org/spip.php?article107">cette page</A> pour notre politique <i>Informatique & Liberté</I>, de confidentialité des donneés communiquées sur ce bulletin, et les autres mentions à savoir avant d'adhérer.
<?php } else { ?>
	<h3 id="conditions">Déclaration sur l'honneur</h3>
		<p>Je déclare sur l’honneur que je suis à l’origine des fonds versés pour ce don au Parti Pirate, et que ces fonds ne viennent pas d’une tierce personne ou d’une personne morale.</p>
	<h3 id="mentions">À savoir</h3>
		<p>Le Parti Pirate est un parti politique, votre cotisation vous donne droit &agrave; une r&eacute;duction de 66&nbsp;% des sommes vers&eacute;es dans la limite de 20&nbsp;% du revenu imposable (<a href="http://vosdroits.service-public.fr/F427.xhtml" target="_blank">voir cette page</A>). Il vous sera &eacute;tabli un certificat fiscal nominatif que vous recevrez par la Poste en mars pour l'année précédente.<p>
		<p>Le Parti Pirate est une association à but politique régie par la loi du 1er juillet 1901. L’Association de Financement du Parti Pirate, déclarée le 21/04/11 a été agréée le 18/07/11.</p>                    
		<p>Les données collectées seront utilisées pour l’établissement et l'envoi des <em>reçus fiscaux</em>, pour le contrôle des comptes auprès de la CNCCFP (Commission nationale des comptes de campagne et des financements politiques) et à des fins de comptabilité interne ; elles seront communiquées au sectr&eacute;tariat national et, de façon partielle, au chargé de trésorerie de la section locale ou interne à laquelle vous donnez. En sus de ces traitements, vos données seront utilisées de manière anonyme pour des traitements statistiques qui pourront être publiés. <!--Si vous souhaitez vous opposer à ce traitement statistique, faites-le savoir en cochant la case appropriée sur le formulaire de don. --></p>
		<p>Conformément à la loi N°78-17 du 6 janvier 1978 dite «&nbsp;Informatique et Libertés&nbsp;», vous disposez d’un droit d’accès, de modification, de rectification, de suppression des données qui vous concernent. Toute demande peut être adressée à &lt;listes(at)lists.partipirate.org&gt;. 
<?php } ?>
	<p>Vous devez indiquer votre identit&eacute; v&eacute;ritable&nbsp;; donner une fausse identit&eacute; constituerait une fraude fiscale.<p> 
	<h3 id="legal">Mentions légales</h3>
	<p>Consulter <a href="https://www.partipirate.org/spip.php?article107">cette page</a> pour les autres mentions légales.</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
			</div>
							</div>
						</div>
					</div>
					<div id="sidebar_2" class="sidebar">
						<ul class="sidebar_list">
							<li class="widget">
								<p style="font-weight:bold;font-size:22px;text-align:center"><a style="text-decoration:none" href="https://www.partipirate.org">Retour au site du Parti Pirate</a></p>
								<hr/>
								<hr/>
								<p style="font-size:18px;text-align:center"><a style="text-decoration:none" href="/">Accueil</a></p>
								<hr/>
								<hr/>
								<?php if ($type_action === 'adhesion' ) { ?>
								<p style="font-size:18px;text-align:center"><a style="text-decoration:none" href="/don.php">Je préfère faire un don</a></p>
								<?php } else { ?>
								<p style="font-size:18px;text-align:center"><a style="text-decoration:none" href="/adhesion.php">Je préfère adhérer</a></p>
								<?php } ?>
							</li>
						</ul>
					</div>
				</div>
			</div>
        </div> 
    </body>
</html>
