<!-- Affichage du compte -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('MyAccount'); ?>">
	<script src="<?php echo js('FormValidationErrors'); ?>"></script>
</head>
<body>

	<h1 class="page_title">Mon SuperCompte</h1>
	<div id='account_infos'>
		<?php
			echo "<img id='avatar' src=".avatar($user->get_login())."  alt='Avatar'>";
			echo "<p id='login'>".$user->get_login()."<br>".$user->get_email()."</p>";
		?>
		<a href="<?php echo base_url('MyForms') ?>" id="my_form">Mes SuperFormulaires</a>
	</div>
	<div id='change'>
		<a href="<?php echo base_url('MyAccount/change_login#change_login') ?>">Changer de nom d'utilisateur</a>
		<a href="<?php echo base_url('MyAccount/change_email#change_email') ?>">Changer d'e-mail</a>
		<a href="<?php echo base_url('MyAccount/change_password#change_password') ?>">Changer de mot de passe</a>
		<a href="<?php echo base_url('MyAccount/change_avatar#change_avatar') ?>">Changer d'avatar</a>
	</div>
</body>

</html>
