<!-- Affichage de la page de réponse à un formulaire -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <link rel="stylesheet" type="text/css" href="<?php echo css('Answer'); ?>">
  <script src="<?php echo js('ShowBlock'); ?>"></script>
  <script src="<?php echo js('Autocomplete'); ?>"></script>
</head>
<body>

  <h1 class="page_title">Répondre à un SuperFormulaire</h1>

  <!-- Affichage du créateur du formulaire -->
  <div id='head'>
    <?php
      echo "<h1 id='title'>".$form->get_title()."</h1>";
      echo "<h2>Description</h2>";

      if($form->get_description()){

        echo "<p id='description'>".$form->get_description()."</p>";
      }else{

        echo "<p id='description'>Aucune description</p>";
      }
    ?>
  </div>

  <!-- Affichage en-tête du formulaire (titre description) -->
	<?php
    echo form_open('Answer/form/'.$form->get_key(),array('method'=>'post'));

    $types = array('name'=>array('Champ de texte'=>'text',
                                 'Zone de texte'=>'textarea',
                                 'Liste déroulante'=>'list',
                                 'Bouton à choix unique'=>'radio',
                                 'Bouton à choix multiple'=>'checkbox',
                                 'Date'=>'date'),
                   'description'=>array('Champ de texte'=>"Cela signifie que vous ne pouvez répondre qu'un mot ou une chaîne de caractère sans espace entre 1 et 500 caractères",
                                        'Zone de texte'=>"Cela signifie que vous pouvez répondre ce que vous voulez entre 1 et 500 caractères",
                                        'Liste déroulante'=>"Cela signifie que vous devez sélectionner un élement de la liste",
                                        'Bouton à choix unique'=>"Cela signifie que ne vous pouvez choisir qu'une réponse parmi toutes celles proposées",
                                        'Bouton à choix multiple'=>"Cela signifie que vous pouvez répondre une réponse ou plus parmis toutes celles proposées",
                                        'Date'=>"Cela signifie que vous devez choisir une date"));

    // Affichage des questions
    $question_count = 1;
    foreach($form->get_questions() as $question){

      $type_name = array_search($question->get_type(),$types['name']);
      $type_description = $types['description'][$type_name];

      // Affichage de l'aide
      echo "<div class='anchor' id='goto_".$question_count."'></div>";
      echo "<div class='question' id='question_id".$question->get_id()."'>";
      echo "<img class='img_button' src=". image('help')." style='cursor: pointer;' onClick=show('help_text".$question->get_id()."'); onmouseover=showInformation('Comment//répondre//à//cette//question?');  onmouseout=hideInformation(); alt='aide'>";
      echo "<p class='question_number'>Question ".$question_count."</p>";
      echo "<div class='help_text' id='help_text".$question->get_id()."' style='display: none;'>";
      echo "<p>Vous êtes bloqué ? Vous n'arrivez pas à répondre à cette question ? Pas de souci !";
      echo "<h4>Cette question est de type : ".$type_name."</h4>";
      echo "<p>".$type_description."</p>";
      echo "<h4>Lorsque vous êtes satisfait de vos réponses, cliquez sur \"Terminé\" (il s'agit d'une action irréversible !)</h4>";
      echo "<p class='close_help'>Cliquez de nouveau sur l'îcone point d'interrogation pour fermer l'aide.</p>";
      echo "</div>";

      // Affichage des réponses de la question
      echo "<div class='the_question'>";
      echo "<div class='question_title_box'>";
      echo $question->get_question();
      echo "</div>";
      echo "<div class='error'>".form_error($question->get_id())."</div>";

      if($question->get_type() === 'text' || $question->get_type() === 'date'){

        echo "<input id=".$question->get_id()." class='".$question->get_type()."' type=".$question->get_type()." name=".$question->get_id()." value='".$question->get_selected_answer()."'  maxlength='500'>";
      }

      if($question->get_type() === 'textarea'){

        echo "<textarea id=".$question->get_id()." name=".$question->get_id()."  maxlength='500'>".$question->get_selected_answer()."</textarea>";
      }

      if($question->get_type() === 'radio'){

        echo "<div class='choose'>";

        foreach ($question->get_answers() as $answer=>$value){

          if($question->get_selected_answer() == $answer){

            echo "<input id=".$question->get_id().".".$answer." type=".$question->get_type()." name=".$question->get_id()." value=".$answer." checked='checked'>";
          }else{

            echo "<input id=".$question->get_id().".".$answer." type=".$question->get_type()." name=".$question->get_id()." value=".$answer.">";
          }
          echo "<label for=".$question->get_id().".".$answer.">".$value."</label>";
        }
        echo "</div>";
      }

      if($question->get_type() === 'checkbox'){

        echo "<div class='choose'>";

        foreach ($question->get_answers() as $answer=>$value){

          if(array_search($answer,$question->get_selected_answer())){

            echo "<input id=".$question->get_id().".".$answer." type=".$question->get_type()." name='".$question->get_id()."radio".$answer."' checked='checked'>";
          }else{

            echo "<input id=".$question->get_id().".".$answer." type=".$question->get_type()." name='".$question->get_id()."radio".$answer."'>";
          }
          echo "<label for=".$question->get_id().".".$answer.">".$value."</label>";
        }
        echo "</div>";
      }

      if($question->get_type() === 'list'){

        echo "<select id=".$question->get_id()." name=".$question->get_id().">";

        if(!$question->get_selected_answer()){

          echo "<option value='' selected='selected'>Choisissez</option>";
        }else{

          echo "<option value=''>Choisissez</option>";
        }

        foreach ($question->get_answers() as $answer=>$value){echo $answer."/".$value;

          if($question->get_selected_answer() == $answer){

            echo "<option selected='selected' value=".$answer.">".$value."</option>";
          }else{

            echo "<option value=".$answer.">".$value."</option>";
          }
        }
      echo "</select>";
    }
    echo "</div></div>";
    $question_count++;
  }
  ?>
</body>
</html>
