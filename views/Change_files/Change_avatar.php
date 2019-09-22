<!-- Affichage de la modifcation d'avatar -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('Form'); ?>">
</head>
<body>

	<div id="change_avatar">
		<h3 class="little_title">Changer d'avatar</h3>

		<?php
			echo form_open_multipart('MyAccount/change_avatar');
		?>

		<input id="file" type="file" name="userfile"/><br>
		<input class="submit" type="submit" value="Confirmer"/>
	</div>
	</form>
</body>

</html>
