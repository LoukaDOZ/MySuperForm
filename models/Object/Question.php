<?php
//Objet contenat les informations des questions
class Question extends CI_Model{

  private $id;        //ID de la question
  private $question;  //Titre de la question
  private $type;      //Type de la question
  private $answers;   //Réponses à cette question
  private $selected;  //résultat de cette réponse

  public function __construct($id = '',$question = '',$type = ''){

    parent::__construct();

    $this->id = $id;
    $this->question = $question;
    $this->type = $type;
    $this->answers = array();
  }

  //Méthode qui définie les réponses de la question
  public function set_answer($id,$answer = ''){

    $this->answers[$id] = $answer;
  }

  //Méthode qui donne un titre à la question
  public function set_question($question = ''){

    $this->question = $question;
  }

  //Méthode qui détermine la ou les résultats de cette question
  function set_selected_answer($selection){

    $this->selected = $selection;
  }

  //Méthode qui supprime une réponse
  public function delete_answer($id){

    unset($this->answers[$id]);
  }

  //Méthode qui renvoie le plus ID parmis les réponses de la question
  public function get_last_answer_id(){

    $id = 0;

    //Pour chaques réponses
    foreach($this->answers as $answer=>$value){

      //Si sont ID est plus quand que celui enregistré
      if($answer > $id){

        $id = $answer;  //Celui enregistré change
      }
    }

    return $id;
  }

  //Méthode qui renvoie l'ID de la question
  public function get_id(){

    return $this->id;
  }

  //Méthode qui renvoie le titre de la question
  public function get_question(){

    return $this->question;
  }

  //Méthode qui renvoie le type de la question
  public function get_type(){

    return $this->type;
  }

  //Méthode qui renvoie les réponses à la question
  public function get_answers(){

    return $this->answers;
  }

  //Méthode qui renvoie la ou les résultats de cette question
  public function get_selected_answer(){

    return $this->selected;
  }

  //Méthode qui renvoie le nombre de réponses
  public function get_number_of_answer(){

    $count = 0;
    foreach($this->answers as $answer){

      $count++;
    }

    return $count;
  }
}
?>
