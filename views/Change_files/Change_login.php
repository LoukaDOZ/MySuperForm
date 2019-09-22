<!-- Affichage de la modifcation de login -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('Form'); ?>">
</head>
<body>

	<div id="change_login">
		<h3 class="little_title">Changer de nom d'utilisateur</h3>

		<?php echo form_open('MyAccount/change_login',array('method'=>'post'));?>
		<div class="error"><?php echo validation_errors(); ?></div>
		<input id="enter_login" type="text" class="input_text" name="new_login" placeholder="Entrez votre nouveau nom d'utilisateur"><br>
		<input id="enter_password" type="password" class="input_text" name="password" placeholder="Entrez votre mot de passe"><br>
		<input class="submit" type="submit" value="Confirmer">
	</div>
	</form>
</body>
</html>
