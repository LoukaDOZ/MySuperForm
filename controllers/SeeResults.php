<?php
//Controller qui gère la page qui affiche les réponses aux question d'un formulaire
class SeeResults extends CI_Controller{

	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Object/Form','form');
		$this->load->model('Object/Question','question');
		$this->load->model('Object/Questions_loader','questions_loader');
		$this->load->model('Object/Result','result');
		$this->load->model('Formulaire','form_table');
		$this->load->model('Questions','question_table');
		$this->load->model('Reponse','answer_table');
		$this->load->model('Utilisateur','user_table');
		$this->load->model('Object/User','user');

		session_start();

		//Si le nom d'utilisateur et le mot de passe sont enregistrés dans des variables de session
		if(isset($_SESSION['login']) && isset($_SESSION['password'])){

			$this->user->set_user($_SESSION['login'],$_SESSION['password']); //On innitialise un objet utilisateur contenant le nom d'utilisateur et le mot de passe
		}

		//Si le nom d'utilisateur existe
		if(!$this->user_table->check_user_login($this->user->get_login())){

			//Et si le mot de passe est correct
			if(password_verify($this->user->get_password(),$this->user_table->get_user_password($this->user->get_login()))){

				$this->user->set_connected();		//On définie l'utilisateur comme connecté
				$this->user->set_email($this->user_table->get_user_email($this->user->get_login())); //On récupère son e-mail
			}
		}

		//S'il n'est pas conncté
		if(!$this->user->get_connected()){

			redirect('Connection'); //il est redirigé vers la page de connexion
		}

		$this->data = array();

		//Si l'utilisateur est passé par la page de modification de formulaire ou de réponse au formulaires, on détruit la session
		//Avec celà, on évite que l'utilisateur provoque des problèmes en utilisant l'url pour se déplacer dans le site
		if(isset($_SESSION['start'])){

		  unset($_SESSION['start']);
		  unset($_SESSION['questions']);
		  unset($_SESSION['title']);
		  unset($_SESSION['description']);
		}

		$this->load->view('Template/Header',array('user'=>$this->user));	//Affichage de la petite zone dédiée au compte
  }

	//Méthode qui s'occupe de la page de qui affiche les réponses aux question
	public function form($key){

		//Si le formulaire n'est pas désactivé auparavant
		if($this->form_table->get_form_state($key) != 2){

			redirect('MyForms');	//Redirection vers la page des formulaires
		}

		//Innitialisation d'un objet formulaire
		$this->form->set_form($key,$this->form_table->get_form_title($key),$this->form_table->get_form_description($key),null,$this->form_table->get_form_number_of_answer($key));

		//Innitialisation des question correspondant au formulaire
		$form_questions = $this->question_table->return_all_question_information($this->form->get_key());
		$this->form->load_questions($form_questions);

		//Innitialisation des résultats des question correspondant au formulaire
		$questions_results = $this->answer_table->return_all_answer_information($this->form->get_key());
		$this->form->load_results($questions_results);

		//Affichage de la page
		$this->header_button = array("Mes SuperFormulaires"=>'MyForms');
		$this->data = array('form'=>$this->form);

		$this->load->view('Form/Results',$this->data);
	}
}
?>
