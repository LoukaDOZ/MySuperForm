<?php
//Controller qui gère la page de création de compte
class CreateAccount extends CI_Controller{

	private $hash;						//Variable contenant le hash des mot de passe
	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Utilisateur','user_table');
		$this->load->model('Object/User','user');

		$this->data = array();

		session_start();

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

		//On vérifie les champs
		$this->form_validation->set_rules('login','Ce nom d\'utilisateur','trim|callback_verify_text|callback_verify_login');
		$this->form_validation->set_rules('email','Cet email','trim|callback_verify_email');
		$this->form_validation->set_rules('password','Ce mot de passe','trim|callback_verify_text');
		$this->form_validation->set_rules('confirm_password','Le mot de passe de confirmation','trim|callback_verify_text|matches[password]');
		$this->form_validation->set_message('matches','{field} ne correspond pas.');

		//Si des champs sont incorrects
		if ($this->form_validation->run() == FALSE){

			//Affichage de la page
			$this->data = array('user'=>$this->user);

			$this->load->view('Template/Header',$this->data);
    	$this->load->view('Account/Create_account');

    }else{

			$this->hash = password_hash($this->input->post('password'),PASSWORD_DEFAULT);	//Sinon, hashage du mot de passe

			//Ajout d'un utilisateur
			if($this->user_table->add_user($this->input->post('login'),$this->hash,$this->input->post('email'))){

				copy('assets/Images/None.png','assets/Images/Avatars/'.$this->input->post('login').'.png');		//Si celà a réussi, on innitialise un avatar de base au nom de l'itilisateur
	      redirect('Connection');		//Redirection vers la page de connexion
			}else{

				redirect('Errors/create_account');	//Sinon, on est redirigé vers une page d'erreur
			}
		}
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

	//Méthode qui filtre un e-mail et renvoie des messages d'erreur en fonction du problème
	public function verify_email($email){

		//Si l'e-mail est vide
		if(!$email){

			$this->form_validation->set_message('verify_email','{field} est vide. Il doit être renseigné.');
			return FALSE;
		}

		//Si l'e-mail n'a pas une forme valide
		if(!filter_var($email,FILTER_VALIDATE_EMAIL)){

			$this->form_validation->set_message('verify_email','{field} n\'est pas valide.');
			return FALSE;
		}

		return TRUE; //Si aucune erreur n'est détectée, on indique que c'est bon
	}

	//Méthode qui filtre un nom d'utilisateur et renvoie des messages d'erreur en fonction du problème
	public function verify_login($login){

		//Si le nom est déjà pris
		if(!$this->user_table->check_user_login($login)){

			$this->form_validation->set_message('verify_login','{field} est déjà utilisé.');
			return FALSE;
		}

		return TRUE; //Si aucune erreur n'est détectée, on indique que c'est bon
	}
}
?>
