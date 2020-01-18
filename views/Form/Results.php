<!-- Affichage des résultats des réponses à un formulaire -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <link rel="stylesheet" type="text/css" href="<?php echo css('Results'); ?>">
  <script src="<?php echo js('Graphics'); ?>"></script>
</head>
<body>
  <?php
    $types = array('Champ de texte'=>'text',
                   'Zone de texte'=>'textarea',
                   'Liste déroulante'=>'list',
                   'Bouton à choix unique'=>'radio',
                   'Bouton à choix multiple'=>'checkbox',
                   'Date'=>'date');

    // Affichage de l'en-tête du formulaire
    echo "<h2 class='page_title'>Résultats de votre SuperFormulaire</h2>";
    echo "<div id='head'>";
    echo "<p id='title'>".$form->get_title()."</p>";
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

    // Chargement des résultats
    $question_count = 1;
    foreach($form->get_questions() as $question){

      // Affichage des questions
      $type_name = array_search($question->get_type(),$types);
      echo "<div class ='questions'>";
      echo "<div class='question_title'>";
      echo "<div class='question_number'>Question ".$question_count."</div>";
      echo "<div class='question_type'>".$type_name."</div>";
      echo "<div class='question_text'>".$question->get_question()."</div>";
      echo "</div>";

      $percent_title_array = array();
      $percent_array = array();
      $case = 0;
      $total = 0;
      foreach($form->get_results() as $results){

        if($question->get_id() == $results->get_question_id()){

          $total += $results->get_count();
        }
      }

      foreach($form->get_results() as $results){

        if($question->get_id() == $results->get_question_id()){

          $percent = ($results->get_count() / $total) * 100;
          $percent_array[$case][0] = round($percent, 2, PHP_ROUND_HALF_UP);
          $percent_array[$case][1] = $results->get_count();
          $percent_title_array[$case] = $results->get_result();
          $case++;
        }
      }

      foreach($question->get_answers() as $answer => $value){

        $is_in = FALSE;
        foreach($percent_title_array as $pta){

          if($value == $pta){

            $is_in = TRUE;
          }
        }

        if(!$is_in){

          $percent_array[$case][0] = 0;
          $percent_array[$case][1] = 0;
          $percent_title_array[$case] = $value;
          $case++;
        }
      }

      for($i = 1; $i < count($percent_title_array); $i++){

        for($j = 0; $j < count($percent_title_array); $j++){

          if($percent_array[$i][0] > $percent_array[$j][0]){

            $save_percent_1 = $percent_array[$i][0];
            $save_percent_2 = $percent_array[$i][1];
            $save_percent_title = $percent_title_array[$i];

            $percent_array[$i][0] = $percent_array[$j][0];
            $percent_array[$i][1] = $percent_array[$j][1];
            $percent_title_array[$i] = $percent_title_array[$j];

            $percent_array[$j][0] = $save_percent_1;
            $percent_array[$j][1] = $save_percent_2;
            $percent_title_array[$j] = $save_percent_title;
          }
        }
      }

      echo "<div class='results'>";
      echo "<table><tbody>";
      for($i = 0; $i < count($percent_title_array); $i++){
        echo "<tr>";
        echo "<td><div>".$percent_title_array[$i]."</div></td>";
        echo "<td><div>".$percent_array[$i][0]."% (".$percent_array[$i][1].")</div></td>";
        echo "</tr>";
      }
      echo "</tbody></table>";
      echo "</div>";
      echo "<p class='number_answer'>".$total." réponses</p>";

      echo "</div>";
      $question_count++;
    }
  ?>
</body>
</html>
