<?php
//Controller qui gère la page de confimation des réponses à un formulaire
class Confirmation extends CI_Controller{

	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Object/User','user');
		$this->load->model('Formulaire','form_table');
		$this->load->model('Utilisateur','user_table');

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

		$this->data = array();

		//Si l'utilisateur est passé par la page de modification de formulaire ou de réponse au formulaires, on détruit les variables de session suivantes
		//Avec celà, on évite que l'utilisateur provoque des problèmes en utilisant l'url pour se déplacer dans le site
		if(isset($_SESSION['start'])){

		  unset($_SESSION['start']);
		  unset($_SESSION['questions']);
		  unset($_SESSION['title']);
		  unset($_SESSION['description']);
		}
  }

	public function answer($key){

		//Création d'un objet utilisateur qui contient les informations liées au créateur du formulaire auquel l'utilisateur à répondu
		$creator = new User();
		$creator->set_user($this->form_table->get_form_login($key));

		$this->data = array('user'=>$this->user,
												'creator'=>$creator);
		//Affichage de la page
		$this->load->view('Template/Header',$this->data);
		$this->load->view('Form/Confirmed',$this->data);
	}
}
?>
