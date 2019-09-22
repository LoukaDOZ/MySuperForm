<?php
//Controller qui gère la page de recherche de formulaire
class Find extends CI_Controller{

	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues
	private $header_button;		//Variable contenant un tableau avec les informations de la barre de navigation

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Formulaire','form_table');
		$this->load->model('Utilisateur','user_table');
		$this->load->model('Object/User','user');
		$this->load->model('Object/Form','form');

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

		//Si l'utilisateur est passé par la page de modification de formulaire ou de réponse au formulaires, on détruit la session
		//Avec celà, on évite que l'utilisateur provoque des problèmes en utilisant l'url pour se déplacer dans le site
		if(isset($_SESSION['start'])){

		  unset($_SESSION['start']);
		  unset($_SESSION['questions']);
		  unset($_SESSION['title']);
		  unset($_SESSION['description']);
		}
  }

	public function index(){

		//Vérification de la clé
		$this->form_validation->set_rules('key','Cette clé','trim|callback_verify_key');

		//Si la clé est invalide
		if($this->form_validation->run() == FALSE){

			$form_keys = $this->form_table->get_all_public_forms();
			$case = 0;
			$public_forms = array();
			foreach($form_keys as $form_key){

				foreach($form_key as $key){

					$public_forms[$case]['form'] = new Form();
					$public_forms[$case]['form']->set_form($key,$this->form_table->get_form_title($key),$this->form_table->get_form_description($key));
					$public_forms[$case]['creator_login'] = $this->form_table->get_form_login($key);
					$case++;
				}
			}

			//Affichage de la page
			$this->data = array('user'=>$this->user,
													'public_forms'=>$public_forms);

			$this->load->view('Template/Header',$this->data);
			$this->load->view('Form/Find');
    }else{

      redirect('Answer/form/'.$this->input->post('key')); //Sinon, redirection vers la page de réponse au formulaire correspondant
    }
	}

	//Méthode qui filtre une clé et renvoie des messages d'erreur en fonction du problème
	public function verify_key($key){

		//Si la clé est vide
		if(!$key){

			$this->form_validation->set_message('verify_key','{field} est vide. Elle doit être renseignée.');
			return FALSE;
		}

		//Si la clé n'existe pas
		if($this->form_table->check_form_key($key)){

			$this->form_validation->set_message('verify_key','{field} ne correspond à aucun formulaire.');
			return FALSE;
		}

		//Si le formulaire n'est pas activé
		if($this->form_table->get_form_state($key) != 1){

			$this->form_validation->set_message('verify_key','Ce formulaire existe bien mais il n\'est pas accessible.');
			return FALSE;
		}

		return TRUE; //Si aucune erreur n'est détectée, on indique que c'est bon
	}
}
?>
