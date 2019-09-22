<!-- Affichage de la page de modification de formulaires -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <link rel="stylesheet" type="text/css" href="<?php echo css('Modify'); ?>">
  <script src="<?php echo js('ShowBlock'); ?>"></script>
  <script src="<?php echo js('Autocomplete'); ?>"></script>
</head>
<body>

  <!-- Affichage de l'en-tête -->
  <h1 class="page_title">Modifier votre SuperFormulaire</h1>
  <?php
    echo form_open('Modify/form/'.$form->get_key(),array('method'=>'post'));
    echo "<div class='anchor' id='goto_form_head'></div>";
    echo "<div id='form_head'>";
    echo "<h4>En-tête</h4>";
    echo "<div id='title_error' class='error'>".form_error('form_title')."</div>";
    echo "<textarea id='form_title' name='form_title' placeholder='Titre du formulaire' maxlength='100' onfocus=this.className='writting_textarea'; onblur=this.className='';>".$form->get_title()."</textarea><br>";

    echo "<textarea id='form_description' name='form_description' placeholder='Description' maxlength='1000' onfocus=this.className='writting_textarea'; onblur=this.className='';>".$form->get_description()."</textarea>";
    echo "</div>";
  ?>
  <div class="anchor" id="goto_new_type"></div>
  <div id='add_questions'>
    <img class="img_button" src="<?php echo image('help') ?>" style='cursor: pointer;' onClick="show('help_text');" alt='aide' onmouseover="showInformation('Quel//type//de//question//choisir?');"  onmouseout="hideInformation();">
    <!-- Affichage de l'aide -->
    <div class="help">
        <div id='help_text' style="display: none;">
          <p>Vous êtes sur la page de création d'un formulaire, et vous vous demandez quelles questions ajouter ?<br>Voici quelque chose pour vous aider !</p>
          <p>Le futur utilisateur pourra :</p>
          <h5>Si le type est : champ de texte</h5>
          <p>Donner une réponse composée d'un mot ou d'une chaîne de caractères sans espace</p>
          <h5>Si le type est : zone de texte</h5>
          <p>Répondre ce qu'il souhaite</p>
          <h5>Si le type est : liste déroulante</h5>
          <p>Choisir l'une des propositions de la liste déroulante</p>
          <h5>Si le type est : boutons à choix unique</h5>
          <p>Choisir l'une des propositions parmi les réponses</p>
          <h5>Si le type est : boutons à choix multiple</h5>
          <p>Choisir une ou plusieurs propositions parmi les réponses</p>
          <h5>Si le type est : date</h5>
          <p>Sélectionner une date au choix</p>
          <h5 class='close_help'>Pensez à régulièrement sauvegarder vos modifications !<br>Pour cela, cliquez sur "Sauvegarder" puis "Sauvegarder et quitter" pour terminer la modification du formulaire.</h5>
          <p>Cliquez de nouveau sur l'icône point d'interrogation pour fermer l'aide.</p>
        </div>
    </div>
  <!-- Affichage de l'ajout de questions -->
    <h4 id="title_add_questions">Ajouter une question</h4>
    <?php
      $types = array('Champ de texte'=>'text',
                     'Zone de texte'=>'textarea',
                     'Liste déroulante'=>'list',
                     'Bouton à choix unique'=>'radio',
                     'Bouton à choix multiple'=>'checkbox',
                     'Date'=>'date');
      echo "<div class='error'>".form_error('new_type')."</div>";
      echo "<select id='new_type' name='new_type'>";
      echo "<option value='' selected='selected'>Type de la question</option>";
      foreach ($types as $type=>$value){

        echo "<option value=".$value.">".$type."</option>";
      }
      echo "</select>";
      echo "<button class='submit_img' type='submit' name='submit' value='add_question'>";
      echo "<img src=".image('add')." style='cursor: pointer;' alt='Ajouter une question' onmouseover=showInformation('Nouvelle//question');  onmouseout=hideInformation();>";
      echo "</button>";
    ?>
  </div>
  <!-- Affichage des questions -->
  <?php
    $question_count = 1;
    foreach($form->get_questions() as $question){

      echo "<div class='anchor' id='goto_".$question_count."'></div>";
      echo "<div class='question' id=question_id".$question->get_id().">";
      echo "<button class='submit_img' type='submit' name='submit' value='delete_question:".$question->get_id()."'>";
      echo "<img src=".image('delete')." style='cursor: pointer;' alt='Retirer la question' onmouseover=showInformation('Supprimer//cette//question');  onmouseout=hideInformation();>";
      echo "</button>";
      echo "<h4 for=".$question->get_id().">Question ".$question_count."</h4>";
      echo "<p class='type_of_question'>Type de question: ".array_search($question->get_type(),$types)."</p>";
      echo "<div id='question_error".$question->get_id()."' class='error'>".form_error("question_title".$question->get_id())."</div>";
      echo "<textarea id=".$question->get_id()." class='question_title' name='question_title".$question->get_id()."' placeholder='Question' maxlength='200' onfocus=this.className='writting_textarea'; onblur=this.className='';>".$question->get_question()."</textarea>";

      if($question->get_type() === 'radio' || $question->get_type() === 'checkbox' || $question->get_type() === 'list'){

        echo "<h4 class='answer'>Réponse(s)</h4>";

        // Affichage des réponses de la question
        $can_delete = 0;
        foreach($question->get_answers() as $answer=>$value){

          echo "<div id='answer_error".$question->get_id().":".$answer."' class='error'>".form_error("answer_title".$question->get_id().":".$answer)."</div>";
          echo "<textarea class='complete_answer' id='answer_id".$question->get_id().".".$answer."' name='answer_title".$question->get_id().":".$answer."' placeholder='Réponse' maxlength='200' onfocus=this.className='writting_textarea'; onblur=this.className='';>".$value."</textarea>";

	        // Au moins une réponses est nécessaire donc on n'affiche pas bouton supprimer pour la première réponse
          if($can_delete > 1){
            echo "<button class='submit_img' type='submit' name='submit' value='delete_answer:".$question->get_id().".".$answer."' title='Retirer la réponse'>";
            echo "<img src=".image('delete')." style='cursor: pointer;' onmouseover=showInformation('Supprimer//la//réponse//ci-dessus');  onmouseout=hideInformation();>";
            echo "</button>";
          }else{

            $can_delete++;
          }
        }

        echo "<button class='submit_img' type='submit' name='submit' value='add_answer:".$question->get_id()."'>";
        echo "<img src=".image('add')." style='cursor: pointer;' alt='Ajouter une réponse' onmouseover=showInformation('Nouvelle//réponse');  onmouseout=hideInformation();>";
        echo "</button>";
      }

      echo "</div>";
      $question_count++;
    }
  ?>
</body>
</html>
