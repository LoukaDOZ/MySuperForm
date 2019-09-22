<!-- Affichage de la page d'erreur -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="<?php echo css('Return'); ?>">
</head>
<body>

	<h1 class="page_title">Oups!</h1>

	<div id="err">
		<h2>Il semblerait qu'une erreur inattendue soit survenue, désolé, nous allons régler ce problème au plus vite</h2>
		<p><?php echo $error ?></p>
	</div>

	<div id="return">
		<?php
			foreach($redirections as $redirection=>$value){

				echo "<a href='".base_url($value)."'>".$redirection."</a>";
			}
		?>
	</div>
</body>
</html>
