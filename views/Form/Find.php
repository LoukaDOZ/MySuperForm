<!-- Affichage de la page de recherche de formulaires -->
<!DOCTYPE html>
<html lang="fr">
<head>
		<link rel="stylesheet" type="text/css" href="<?php echo css('Form'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo css('Find'); ?>">
		<script src="<?php echo js('Information'); ?>"></script>
</head>
<body>

	<h1 class="page_title">Trouver un SuperFormulaire</h1>

	<?php echo form_open('Find',array('method'=>'post'));?>

		<div class="submit_key">
			<div class="error"><?php echo validation_errors(); ?></div>
			<input id="key" type="text" class="input_text" name="key" value="<?php echo set_value('key'); ?>" placeholder="Entrez la clé du formulaire"><br>
			<input class="submit" type="submit" value="Confirmer">
		</div>
	</form>

	<h1 class="page_title">Découvrez pleins de SuperFormulaires</h1>

	<?php

		if(!$public_forms){

			echo "<p id='nothing'>Aucun SuperFormulaire n'est disponible pour l'instant</p>";
		}else{

			foreach($public_forms as $form){

				echo "<a href='".base_url('Answer/form/'.$form['form']->get_key())."' class='form_box' onmouseover=showInformation('Cliquez//pour//répondre//à//ce//SuperFormulaire');  onmouseout='hideInformation();'>";
				echo "<div class='public_form'>";
				echo "<p class='form_title'>".$form['form']->get_title()."</p>";
				echo "<p class='form_description'>".$form['form']->get_description()."</p>";
				echo "</div>";
				echo "</a>";
			}
		}
	?>

</body>
<script src="<?php echo js('FormValidationErrors'); ?>"></script>
</html>
