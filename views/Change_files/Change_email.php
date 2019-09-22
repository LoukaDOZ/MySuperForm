<!-- Affichage de la modifcation d'e-mail -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('Form'); ?>">
</head>
<body>

	<div id="change_email">
		<h3 class="little_title">Changer d'e-mail</h3>

		<?php echo form_open('MyAccount/change_email',array('method'=>'post'));?>
		<div class="error"><?php echo validation_errors(); ?></div>
		<input id="enter_email" type="text" class="input_text" name="new_email" placeholder="Entrez votre nouvel e-mail"><br>
		<input id="enter_password" type="password" class="input_text" name="password" placeholder="Entrez votre mot de passe"><br>
		<input class="submit" type="submit" value="Confirmer">
	</div>
	</form>
</body>
</html>
