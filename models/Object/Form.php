<?php
//Objet contenant les informations d'un formulaire
class Form extends CI_Model{

  private $key;               //Clé du formualire
  private $state;             //Etat du formulaire
  private $title;             //Titre du formulaire
  private $description;       //Description du formulaire
  private $public;            //Si le formulaire est publique
  private $number_of_answer;  //Nombre de personnes ayant répondu au formulaire
  private $questions;         //Questions du formulaire
  private $results;           //Résulats des réponses au formulaire
  private $last_question_id;  //Plus grand ID parmis questions du formulaire

  public function __construct(){

    parent::__construct();

    $this->questions = array();
    $this->results = array();
    $this->last_question_id = 0;
  }

  //Méthode qui innitialiste un formulaire
  public function set_form($key,$title = '',$description = '',$state = '',$number_of_answer = 0,$public = 0){

    $this->key = $key;
    $this->title = $title;
    $this->description = $description;
    $this->state = $state;
    $this->number_of_answer = $number_of_answer;
    $this->public = $public;
  }

  //Méthode qui donne un titre au formulaire
  public function set_title($title = ''){

    $this->title = $title;
  }

  //Méthode qui donne une description au formulaire
  public function set_description($description = ''){

    $this->description = $description;
  }

  //Méthode qui innitialise et enregistre les questions du formulaire
  public function load_questions($questions = array(),$reload = TRUE){

    $loader = new Questions_loader();

    //Si les questions ne sont pas déjà chagées(que se sont déjà des objets)
    if($reload){

      //On les innitialise en objets
      $this->questions = $loader->load($questions);
    }else{

      //Sinon, on les stocke directement
      $this->questions = $questions;
    }

    //On récupère le plus ID parmis les questions du formulaire
    $this->last_question_id = $loader->get_last_question_id($this->questions);
  }

  //Méthode qui ajoute une question au formulaire
  public function add_question($type){

		$this->questions[count($this->questions) + 1] = new Question($this->last_question_id + 1,'',$type);

    //Si le type de la question est champ de texte ou zone de texte ou liste déroulante
    if($type == 'list' || $type == 'radio' || $type == 'checkbox'){

      //On ajoute de 2 réponses obligatoires à la quéstion
      $this->questions[count($this->questions)]->set_answer($this->questions[count($this->questions)]->get_last_answer_id() + 1);
      $this->questions[count($this->questions)]->set_answer($this->questions[count($this->questions)]->get_last_answer_id() + 1);
    }
  }

  //Méthode qui supprime une question du formulaire
  public function delete_question($id){

    unset($this->questions[$id]);
  }

  //Méthode qui ajoute une réponse à une question
  public function add_answer($which_question){

    $this->questions[$which_question]->set_answer($this->questions[$which_question]->get_last_answer_id() + 1);
  }

  //Méthode qui supprime une réponse d'une question
  public function delete_answer($which_question,$which_answer){

    $this->questions[$which_question]->delete_answer($which_answer);
  }

  //Méthode qui innitialise et enregistre les résultats des réponses à un formulaire
  public function load_results($results){

    //Pour chaques résultats
    foreach($results as $result){

      //Si un résultats à déjà été enregistré
      if($this->results){

        $existing_answer = FALSE; //On suppose que personne n'a déjà répondu cette possibilité
        //Pour chaques résultats déjà enregistré
        foreach($this->results as $loaded_result){

          //Si cette possibilité à déjà été répondu
          if($loaded_result->get_question_id() == $result['idQuestion'] && $loaded_result->get_result() === $result['reponse']){

            $existing_answer = TRUE;
            break;
          }
        }

        //Si cette possibilité à déjà été répondu
        if($existing_answer){

          $loaded_result->increase_count(); //On augmente le conteur de réponses à cette question
        }else{

          //Sinon, on innitialise un nouveau résultat
          $this->results[count($this->results) + 1] = new Result($result['idQuestion'],$result['reponse']);
        }
      }else{

        //Si aucun Résulat n'a été enregistré, on en initialiste un premier
        $this->results[count($this->results) + 1] = new Result($result['idQuestion'],$result['reponse']);
      }
    }
  }

  //Méthode pour récupérer la clé du formulaire
  public function get_key(){

    return $this->key;
  }

  //Méthode pour récupérer le titre du formulaire
  public function get_title(){

    return $this->title;
  }

  //Méthode pour récupérer la description du formulaire
  public function get_description(){

    return $this->description;
  }

  //Méthode pour récupérer l'état du formulaire
  public function get_state(){

    return $this->state;
  }

  //Méthode pour récupérer le nombre de personnes ayant répondu au formulaire
  public function get_number_of_answer(){

    return $this->number_of_answer;
  }

  //Méthode pour récupérer les questions du formulaire
  public function get_questions(){

    return $this->questions;
  }

  //Méthode pour récupérer les résultats des questions du formulaire
  public function get_results(){

    return $this->results;
  }

  //Méthode pour récupérer si le formulaire est public
  public function get_public(){

    if($this->public == 1){

      return TRUE;
    }else{

      return FALSE;
    }
  }
}
?>
