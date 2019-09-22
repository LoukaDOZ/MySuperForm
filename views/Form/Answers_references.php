<!-- Affichage du menu de navigation dans la page de réponse aux formulaires -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo css('References'); ?>">
</head>
<body>

	<div id="aside_questions">
    <h3 class="aside_title">Rechercher dans la page</h3>
    <input id="search2" type="text" placeholder="Rechercher" onfocus="startAutocomplete('autocomplete2');" oninput="complete('search2','autocomplete2');" onblur="stopAutocomplete('autocomplete2');" autocomplete="off"></input><br>
    <ul class="autocomplete" id="autocomplete2" style="display: none;">
      <?php
        $question_number = 1;
        foreach($form->get_questions() as $question){

          echo "<a href='#goto_".$question->get_id()."' onmouseover='closeAutocomplete(false);' onmouseout='closeAutocomplete(true);' onclick=forceStopAutocomplete('autocomplete2');><li>Question ".$question_number." : ".$question->get_question()."</li></a>";

          $search_list[$question_number]['reference'] = $question->get_id();
          $search_list[$question_number]['title'] = $question->get_question();
          $question_number++;
        }
      ?>
    </ul>

    <div id="go_to">
      <a href="#">- Haut de page -</a>
    </div><br>
    
    <button type='submit' name="submit" value="Terminé">Terminé</button>
  </div>

  <div id="creator">
    <h3 class="aside_title">Ce formulaire à été créé par</h3>
    <?php
      echo "<h3 id='creator_login'>".$creator->get_login()."</h3>";
      echo "<img id='creator_avatar' src=".avatar($creator->get_login()).">";
    ?>
  </div>

</body>
<script>
  var searchList = <?php echo json_encode($search_list); ?>;
  var length = <?php echo count($search_list); ?> + 1;
</script>
</html>
