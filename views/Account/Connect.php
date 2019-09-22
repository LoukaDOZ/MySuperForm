<!-- Affichage de la page de connexion -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('Form'); ?>">
	<script src="<?php echo js('FormValidationErrors'); ?>"></script>
</head>
<body>

	<h1 class="page_title">Connexion à mon SuperCompte</h1>

	<?php echo form_open('Connection',array('method'=>'post'));?>

		<div class="error"><?php echo validation_errors(); ?></div>
		<input id="login" type="text" class="input_text" name="login" value="<?php echo set_value('login'); ?>" placeholder="NOM UTILISATEUR"><br>
		<input id="password" type="password" class="input_text" name="password" placeholder="MOT DE PASSE"><br>
		<input class="submit" type="submit" value="Confirmer">
	</form>
</body>
</html>
