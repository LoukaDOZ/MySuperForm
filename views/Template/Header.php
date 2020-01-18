<!-- Affichage des boutons de navigation enb haut de la page -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="icon" href="<?php echo image('modify'); ?>" type="image/png">
	<link rel="stylesheet" type="text/css" href="<?php echo css('Templates'); ?>">
	<script src="<?php echo js('Information'); ?>"></script>
	<script src="<?php echo js('Slide'); ?>"></script>
	<title>MySuperForm</title>
</head>
<body>
	<nav>
			<a href="<?php echo base_url(); ?>" class="menu_img"><img src="<?php echo image('home'); ?>"></a>
			<a href="<?php echo base_url('MyForms'); ?>" class="menu">Mes SuperFormulaires</a>
			<a href="<?php echo base_url('Find'); ?>" class="menu">Trouver un SuperFormulaire</a>
			<a href="<?php echo base_url('Modify/new_form'); ?>" class="menu">Créer un SuperFormulaire</a>
			<?php
				if($user->get_connected()){

					$img = avatar($user->get_login());
				}else{

					$img = image('account');
				}
			?>
			<div id="nav_account" class="menu_img"><img src="<?php echo $img ?>">
				<ul class="submenu">
					<?php
						if($user->get_connected()){

							echo '<li><a href='.base_url("Connection/disconnect").'>Déconnexion</a></li>';
							echo '<li><a href='.base_url("MyForms").'>Mes SuperFormulaires</a></li>';
							echo '<li><a href='.base_url("MyAccount").'>Mon SuperCompte</a></li>';
						}else{

							echo '<li><a href='.base_url("Connection").'>Connexion</a></li>';
							echo '<li><a href='.base_url("CreateAccount").'>Créer un compte</a></li>';
						}
					?>
				</ul>
			</div>

	</nav>
	<div id="infos_hide"></div>
</body>
</html>
