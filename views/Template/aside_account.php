<!-- Affichage du petit block qui affiche quelques informations du compte (avatar, nom) -->
<!DOCTYPE html>
<html lang="fr">
<head>
		<link rel="stylesheet" type="text/css" href="<?php echo css('Template/aside_account'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo css('Template/Base'); ?>">
</head>
<body>

		<a href="<?php echo base_url('MyAccount'); ?>" title="Acceder au compte">
		<?php
			echo "<div id='aside_account'>";
			echo "<img id = 'avatar' src = ".avatar($user->get_login())." alt='Avatar'>";
			echo "<p>Bonjour<br>".$user->get_login()."</p>";
			echo "</div>";
		?>
	</a>
</body>
</html>
