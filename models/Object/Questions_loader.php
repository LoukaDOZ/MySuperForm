<?php
//Objet manipulant les données des questions
class Questions_loader extends CI_Model{

  public function __construct(){

    parent::__construct();
  }

  //Méthode qui renvoie les questions sous forme d'objets
  public function load($questions){

    $loaded_questions = array();

    //Pour chaques questions
    foreach($questions as $question){

      //Dans la base de données, les réponses sont stockées dans la table Question de cette manière: exemple: 1.5, question 1 réponse 5
      $answer_id = str_replace('.','',stristr($question['idQuestion'],'.')); //On récupère l'ID de la réponse

      //Si rien n'a été récupéré alors c'est une question
      if(!$answer_id){

        //Si cette question n'existe pas déjà
        if(!isset($loaded_questions[$question['idQuestion']])){

          //On l'innitialise avec un nouvel objet question
          $loaded_questions[$question['idQuestion']] = new Question($question['idQuestion'],$question['question'],$question['typeQuestion']);
        }else{

          //Sinon, on modifie ses informations
          $loaded_questions[$question['idQuestion']]->set_question($question['question']);
          $loaded_questions[$question['idQuestion']]->set_type($question['typeQuestion']);
        }
      }else{

        //Si c'est une réponse
        $question_id = stristr($question['idQuestion'],'.',TRUE); //On récupère l'ID de la question

        //Si cette question n'existe pas déjà
        if(!isset($loaded_questions[$question_id ])){

          //On l'innitialise avec un nouvel objet question
          $loaded_questions[$question_id] = new Question($question_id);
        }

        //On ajoute cette réponse à la question
        $loaded_questions[$question_id]->set_answer($answer_id,$question['question']);
      }
    }

    return $loaded_questions;
  }

  //Méthode qui renvoie le plus grand ID parmis des questions
  public function get_last_question_id($questions){

    $id = 0;

    //Pour chaques questions
    foreach($questions as $question){

      //Si son ID est plus grand que celui déjà enregistré
      if($question->get_id() > $id){

        $id = $question->get_id(); //L'ID enregistré change
      }
    }

    return $id;
  }
}
?>
