<!-- Affichage de la modifcation du mot de passe -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('Form'); ?>">
</head>
<body>

	<div id="change_password">
		<h3 class="little_title">Changer de mot de passe</h3>

		<?php echo form_open('MyAccount/change_password',array('method'=>'post'));?>
		<div class="error"><?php echo validation_errors(); ?></div>
		<input id="enter_password" type="password" class="input_text" name="new_password" placeholder="Entrez votre nouveau mot de passe"><br>
		<input id="confirm_password" type="password" class="input_text" name="confirm_password" placeholder="Confirmez votre nouveau mot de passe"><br>
		<input id="old_password" type="password" class="input_text" name="old_password" placeholder="Entrez votre ancien mot de passe"><br>
		<input class="submit" type="submit" value="Confirmer">
	</div>
	</form>
</body>
</html>
