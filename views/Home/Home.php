<!-- Affichage de la page d'accueil -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('Home'); ?>">
</head>
<body>

	<article>

		<div id="welcome_title">
			<p>Bienvenue sur</p>
			<span>My</span><span>Super</span><span>Form</span>
		</div>

		<div id="global_block">

			<a href="<?php echo base_url('Find'); ?>">
				<div class="welcome_block" id="answer">
					Répondez à des SuperFormulaires anonymement !<br>
					<img src="<?php echo image('answer_form'); ?>" alt="Répondez à des formulaires">
				</div>
			</a>

			<a href="<?php echo base_url('Modify/new_form'); ?>">
				<div class="welcome_block" id="connect">
					Connectez-vous pour créer votre propre Superformulaire !<br>
					<img src="<?php echo image('new_form'); ?>" alt="Créez vos formulaires">
				</div>
			</a>

			<a href="<?php echo base_url('MyForms'); ?>">
				<div class="welcome_block" id="modify">
					Sauvegardez vos SuperFormulaires pour les modifier plus tard !<br>
					<img src="<?php echo image('modify_form'); ?>" alt="Sauvegardez vos formulaires">
				</div>
			</a>

			<a href="<?php echo base_url('MyForms'); ?>">
				<div class="welcome_block" id="see">
					Regardez les résultats de chaque question de votre SuperFormulaire !<br>
					<img src="<?php echo image('see_results_form'); ?>" alt="Regardez les résultats">
				</div>
			</a>

		</div>

	</article>

</body>


</html>
