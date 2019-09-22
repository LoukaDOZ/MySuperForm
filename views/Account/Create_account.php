<!-- Affichage de la page de création de compte -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('Form'); ?>">
	<script src="<?php echo js('FormValidationErrors'); ?>"></script>
</head>
<body>

	<h1 class="page_title">Créez votre propre SuperCompte</h1>

	<?php echo form_open('CreateAccount',array('method'=>'post'));?>

	<div class="submit_connection">
		<div class="error"><?php echo validation_errors(); ?></div>
		<input id="login" type="text" class="input_text" name="login" placeholder="NOM UTILISATEUR"><br>
		<input id="email" type="text" class="input_text" name="email" placeholder="EMAIL">
		<input id="password" type="password" class="input_text" name="password" placeholder="MOT DE PASSE"><br>
		<input id="confirm_password" type="password" class="input_text" name="confirm_password" placeholder="CONFIRMER MOT DE PASSE"><br>
		<input class="submit" type="submit" value="Confirmer">
	</div>

</body>
</html>
