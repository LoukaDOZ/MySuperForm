<!-- Affichage de la page de confirmation de l'enregistrement des réponses à un formulaire -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="<?php echo css('Return'); ?>">
</head>
<body>

	<h1 class="page_title">Merci d'avoir répondu</h1>

  <div id='thanks'>
    <h2>Ce SuperFormulaire vous a été proposé par :</h2>
    <h2><?php echo $creator->get_login(); ?></h2>
    <img src="<?php echo avatar($creator->get_login()); ?>">
    <h3>Vos réponses ont bien été enregistrées.<br>Nous vous remercions de votre participation !</h3>
  </div>

	<div id="return">
    <a href="<?php echo base_url() ?>">Page d'accueil</a>
    <a href="<?php echo base_url('Find') ?>">Répondre à un autre SuperFormulaire</a>
    <a href="<?php echo base_url('MyAccount') ?>">Mon SuperCompte</a>
    <a href="<?php echo base_url('MyForms') ?>">Mes SuperFormulaires</a>
	</div>

</body>
</html>
