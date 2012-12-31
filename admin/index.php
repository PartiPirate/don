<?php
include_once sprintf('%s/libmain.php', dirname(__FILE__).'/../PPlib');
include_once sprintf('%s/adhesion.php', PPLIB_PATH);
?>
<!DOCTYPE html>
  <html>
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <title>Admin - contributions au Parti Pirate</title>
          <meta charset="utf-8">
          <link rel="stylesheet" type="text/css" href="../styles/total.css">
          <link rel="stylesheet" type="text/css" href="../styles/adhesion.css">
		  <script src="../styles/jquery-1.8.3.min.js"></script>
      </head>
      <body class="pagination">
          <div id="centrage">
              <img src="../styles/header4.png" alt="Parti Pirate" /><br/>
			<div id="contenu" class="full_width">
				<div class="page">
					<div id="content_box">
						<div id="content">
							<div class="post_box">
<div class="headline_area">
	<h2>Ajouter un fichier Apayer.fr</h2>
</div>
<div class="contenu_texte">
	<form id="addApayer" method="POST" accept-charset="utf-8" action="/admin/insert.php" enctype="multipart/form-data">
		<label class="required" for="input_apayer">Fichier :</label> <input type="file" name="input_apayer" id="input_apayer" /><br/>
		<label></label><input type="submit" value="Envoyer" class="bt_send" /><br/>
	</form>
</div>
<div class="headline_area">
	<h2>Récupérer une extraction</h2>
</div>
<div class="contenu_texte">
	<h3>Extraction pour le secrétaire</h3>
	<p></p>
	<p><a href="/admin/extract.php?type=sn">Cliquez ici pour télécharger.</a></p>
	<h3>Extraction pour l'AFPP</h3>
	<p></p>
	<p><a href="/admin/extract.php?type=afpp">Cliquez ici pour télécharger.</a></p>
</div>
							</div>
						</div>
					</div>
					<div id="sidebar_2" class="sidebar">
						<ul class="sidebar_list">
							<li class="widget">
								<p style="font-weight:bold;font-size:22px;text-align:center"><a style="text-decoration:none" href="/">Retour au site</a></p>
								<hr/>
								<hr/>
								<!--p style="font-size:18px;text-align:center"><a style="text-decoration:none" href="/adhesion.php">Adhérer</a></p-->
							</li>
						</ul>
					</div>
				</div>
			</div>
        </div> 
    </body>
</html>
