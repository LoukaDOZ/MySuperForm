<?php
//Controller qui gère la page de connexion
class Connection extends CI_Controller{

	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues
	private $header_button;		//Variable contenant un tableau avec les informations de la barre de navigation

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Utilisateur','user_table');
		$this->load->model('Object/User','user');

		session_start();

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

		//On vérifie le nom d'utilisateur et le mot de passe
		$this->form_validation->set_rules('login','Le nom d\'utilisateur','trim|callback_verify_text');
		$this->form_validation->set_rules('password','Le mot de passe','trim|callback_verify_text|callback_verify_password');

		//Si les informations sont incorrectes
		if ($this->form_validation->run() == FALSE){

			//On charge la page de connexion
			$this->header_button = array("Page d'accueil"=>'');
			$this->data = array('header_button'=>$this->header_button,
													'user'=>$this->user);

			$this->load->view('Template/Header',$this->data);
      $this->load->view('Account/Connect');
    }else{

			$_SESSION['login'] = $this->input->post('login');
			$_SESSION['password'] = $this->input->post('password');

			redirect('');	//On redirige vers la page d'accueil
    }
	}

	//Méthode qui déconnecte l'utilisateur
	public function disconnect(){

		//On le déconnecte en supprimant les variables de session
		unset($_SESSION['login']);
		unset($_SESSION['password']);

		redirect('');		//On le revoie vers la page d'accueil
	}

	//Méthode qui déconnecte l'utilisateur de force pour qu'il se reconnecte
	public function reconnection(){

		session_destroy();	//On le déconnecte en supprimant les variables de session
		redirect('Connection');	//On le renvoie vers la page de connexion
	}

	//Méthode qui filtre ce que l'tilisateur entre et renvoie des messages d'erreur en fonction du problème
	public function verify_text($text){

		//Si cest vide
		if(!$text){

			$this->form_validation->set_message('verify_text','{field} est vide. Il doit être renseigné.');
			return FALSE;
		}

		//Si le texte a moins de 5 caractères
		if(strlen($text) < 5){

			$this->form_validation->set_message('verify_text','{field} doit contenir au moins 5 caractères.');
			return FALSE;
		}

		//Si le texte a plus de 30 caractères
		if(strlen($text) > 30){

			$this->form_validation->set_message('verify_text','{field} doit contenir moins de 30 caractères.');
			return FALSE;
		}

		return TRUE; //Si aucune erreur n'est détectée, on indique que c'est bon
	}

	//Méthode qui vérifie que les informations rentrées correspondent bien à un compte
	public function verify_password($password){

		//Si le nom d'utilisateur existe
		if(!$this->user_table->check_user_login($this->input->post('login'))){

			//Et si le mot de passe est incorrect
			if(!password_verify($password,$this->user_table->get_user_password($this->input->post('login')))){

				$this->form_validation->set_message('verify_password',"{field} est incorrect.");
				return FALSE;
			}
		}else{

			//Sinon
			$this->form_validation->set_message('verify_password',"Le nom d'utilisateur est incorrect.");
			return FALSE;
		}

		return TRUE; //Si aucune erreur n'est détectée, on indique que c'est bon
	}
}
?>
