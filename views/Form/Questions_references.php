<!-- Affichage du menu de navigation de modification de formulaires -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo css('References'); ?>">
</head>
<body>

	<div id="aside_questions">
    <h3 class="aside_title">Rechercher dans la page</h3>
    <input id="search1" type="text" placeholder="Rechercher" onfocus="startAutocomplete('autocomplete1');" oninput="complete('search1','autocomplete1');" onblur="stopAutocomplete('autocomplete1');" autocomplete="off"></input><br>
    <ul class="autocomplete" id="autocomplete1" style="display: none;">
      <?php
      $search_list = array();

        $question_number = 1;
        foreach($form->get_questions() as $question){

          $search_list[$question_number]['reference'] = $question->get_id();

          if($question->get_question()){

            $search_list[$question_number]['title'] = $question->get_question();
            echo "<a href='#goto_".$question->get_id()."' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete1');><li>Question ".$question_number." : ".$question->get_question()."</li></a>";
          }else{

            $search_list[$question_number]['title'] = "Sans titre";
            echo "<a href='#goto_".$question->get_id()."' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete1');><li>Question ".$question_number." : Sans titre</li></a>";
          }

          $question_number++;
        }

        $search_list[$question_number]['reference'] = "form_head";
        $search_list[$question_number]['title'] = "Titre";
        $question_number++;
        $search_list[$question_number]['reference'] = "form_head";
        $search_list[$question_number]['title'] = "Description";
      ?>
      <a href="#goto_form_head" onmouseover="closeAutocomplete(false);" onmouseout='closeAutocomplete(true);'  onclick='forceStopAutocomplete("autocomplete1");'><li>Titre</li></a>
      <a href="#goto_form_head" onmouseover="closeAutocomplete(false);" onmouseout='closeAutocomplete(true);'  onclick='forceStopAutocomplete("autocomplete1");'><li>Description</li></a>
    </ul>
    <div id="go_to">
      <a href="#goto_new_type">- Ajouter une nouvelle question -</a><br><br>
      <a href="#">- Haut de page -</a>
    </div>
	  <br><button id='confirm_modification' type='submit' name="submit" value="save">Sauvegarder</button>
		<br><button id="confirm_modification_quit" id='just_save' type="submit" name="submit" value="save&quit">Sauvegarder et quitter</button>
	</form>
  </div>

</body>
<script>
  var searchList = <?php echo json_encode($search_list); ?>;
  var length = <?php echo count($search_list); ?> + 1;
</script>
</html>
