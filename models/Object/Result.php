<?php
//Objet qui contient les informations des résultats après une réponse
class Result extends CI_Model{

  private $question;  //ID de la question correpondante
  private $result;    //résultat répondu à la question
  private $count;     //Nombre de résultats identiques à celui-çi

  public function __construct($question = '',$result = ''){

    parent::__construct();

    $this->question = $question;
    $this->result = $result;
    $this->count = 1;
  }

  //Méthode qui ajoute 1 au nombre de résultats identiques à celui-çi
  public function increase_count(){

    return $this->count++;
  }

  //Méthode qui renvoie l'ID de la question
  public function get_question_id(){

    return $this->question;
  }

  //Méthode qui renvoie le résultat répondu
  public function get_result(){

    return $this->result;
  }

  //Méthode qui renvoie le nombre de résultats identiques à celui-çi
  public function get_count(){

    return $this->count;
  }
}
?>
