<!-- Affichage des formulaire s d'un utilisateur -->
<!DOCTYPE html>
<html lang="fr">
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo css('MyForms'); ?>">
	<script src="<?php echo js('Autocomplete'); ?>"></script>
</head>
<body>

	<h1 class='page_title'>Mes SuperFormulaires</h1>

	<h3 class="search_title">Rechercher dans la page</h3>
	<input id="search2" type="text" placeholder="Rechercher un formulaire" onfocus="startAutocomplete('autocomplete2');" oninput="completeFormList('search2','autocomplete2');" onblur="stopAutocomplete('autocomplete2');" autocomplete="off"></input><br>
	<ul class="autocomplete" id="autocomplete2" style="display: none;">
		<?php
			$search_list = array();
			$case = 1;
			foreach($forms as $form){

				echo "<a href='#goto_".$form->get_key()."' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete2');><li>".$form->get_title()." (Clée: ".$form->get_key().")</li></a>";

				$search_list[$case]['title'] = $form->get_title();
				$search_list[$case]['key'] = $form->get_key();
				$case++;
			}
		?>
	</ul>

	<a id="add_form" href="<?php echo base_url('Modify/new_form/') ?>"><img src="<?php echo image('add') ?>" alt='Ajouter un formulaire'  onmouseover="showInformation('Nouveau//formulaire');" onmouseout="hideInformation();"></a>
	<!-- Affichage des formulaires -->
	<?php
		foreach($forms as $form){

			echo "<div class='anchor' id='goto_".$form->get_key()."'></div>";
			echo "<div class='form_list'>";

			if($form->get_state() == 0){

				echo "<a href=".base_url('MyForms/modify/'.$form->get_key()).">";
				echo "<img src=".image('modify')." title='Modifier le formulaire'  alt='Modifier le formulaire' onmouseover=showInformation('Modifier//ce//formulaire');  onmouseout=hideInformation();></a>";
				echo "<a href=".base_url('MyForms/activate/'.$form->get_key())."><img src=".image('activate')." alt='Activer le formulaire' onmouseover=showInformation('Activer//ce//formulaire');  onmouseout=hideInformation();></a>";
				echo "<a href=".base_url('MyForms/delete/'.$form->get_key())."><img src=".image('delete')." alt='Supprimer le formulaire' onmouseover=showInformation('Supprimer//ce//formulaire');  onmouseout=hideInformation();></a>";
			}

			if($form->get_state() == 1){

				echo "<a href=".base_url('MyForms/expire/'.$form->get_key())."><img src=".image('desactivate')." alt='Périmer le formulaire' onmouseover=showInformation('Périmer//ce//formulaire');  onmouseout=hideInformation();></a>";

				if(!$form->get_public()){

					echo "<a href=".base_url('MyForms/publish/'.$form->get_key())."><img src=".image('public')." alt='Rendre publique le formulaire' onmouseover=showInformation('Publier//ce//formulaire//(Actuellement//privé).//Il//sera//possible//de//répondre//au//formulaire//sans//même//connaître//sa//clée//(voir//la//page//\"Trouver//un//SuperFormulaire\")');  onmouseout=hideInformation();></a>";
				}else{

					echo "<a href=".base_url('MyForms/privatize/'.$form->get_key())."><img src=".image('private')." alt='Rendre privé le formulaire' onmouseover=showInformation('Privatiser//ce//formulaire//(Actuellement//publique).//Pour//répondre//au//formulaire,//il//faudra//nécessairement//sa//clée');  onmouseout=hideInformation();></a>";
				}
			}

			if($form->get_state() == 2){

				echo "<a href=".base_url('MyForms/activate/'.$form->get_key())."><img src=".image('activate')." alt='Activer le formulaire' onmouseover=showInformation('Activer//ce//formulaire');  onmouseout=hideInformation();></a>";
				echo "<a href=".base_url('MyForms/see_results/'.$form->get_key())."><img src=".image('see_results2')." alt='Voir les résulats' onmouseover=showInformation('Voir//les//résulats//de//ce//formulaire');  onmouseout=hideInformation();></a>";
				echo "<a href=".base_url('MyForms/delete/'.$form->get_key())."><img src=".image('delete')." alt='Supprimer le formulaire' onmouseover=showInformation('Supprimer//ce//formulaire');  onmouseout=hideInformation();></a>";
			}

			echo "<p id='key'>Clé : ".$form->get_key()."</p>";
			echo "<h3>".$form->get_title()."</h3>";
			echo "<p id='description'>".$form->get_description()."</p>";
			if($form->get_number_of_answer() > 1){

	      echo "<p class='number_answer'>".$form->get_number_of_answer()." personnes ont répondu a votre SuperFormulaire</p>";
	    }
	    if($form->get_number_of_answer() == 1){

	      echo "<p class='number_answer'>".$form->get_number_of_answer()." personne a répondu a votre SuperFormulaire</p>";
	    }
	    if($form->get_number_of_answer() < 1){

	      echo "<p class='number_answer'>Personne n'a encore répondu a votre SuperFormulaire</p>";
	    }
			echo "</div>";
		}
	?>
</body>
<script>
  var searchList = <?php echo json_encode($search_list); ?>;
  var length = <?php echo count($search_list); ?> + 1;
</script>
</html>
