<?php
//Controller qui gère la page des erreurs
class Errors extends CI_Controller{

	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Object/User','user');
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

	//Méthode en cas d'erreur de création de compte
	public function create_account(){

		//Affichage de la page
		$this->data = array('user'=>$this->user,
												'redirections'=>array("Page d'accueil"=>'',
																 							"Page de connection"=>'Connection'),
												'error'=>"Impossible de créer un nouvel utilisateur, réessayez plus tard");

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Template/Error',$this->data);
	}

	//Méthode en cas d'erreur de changement des informations du compte
	public function change(){

		//Affichage de la page
		$this->data = array('user'=>$this->user,
												'redirections'=>array("Page d'accueil"=>'',
																 							"Mon SuperCompte"=>'MyAccount'),
												'error'=>"Impossible de changer vos informations, réessayez plus tard");

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Template/Error',$this->data);
	}

	//Méthode en cas d'erreur d'accès à un formulaire non ouvert
	public function form(){

		//Affichage de la page
		$this->data = array('user'=>$this->user,
												'redirections'=>array("Page d'accueil"=>'',
																 							"Mon SuperCompte"=>'MyAccount',
																							"Trouver un SuperFormulaire"=>"Find",
																							"Mes SuperFormulaires"=>"MyForms"),
												'error'=>"Impossible de charger le formulaire car il n'existe pas ou car il n'est pas ouvert, réessayez plus tard");

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Template/Error',$this->data);
	}

	//Méthode en cas d'erreur lors de l'enregistrement des réponses à un formulaire
	public function answer(){

		//Affichage de la page
		$this->data = array('user'=>$this->user,
												'redirections'=>array("Page d'accueil"=>'',
																 							"Mon SuperCompte"=>'MyAccount',
																							"Trouver un SuperFormulaire"=>"Find",
																							"Mes SuperFormulaires"=>"MyForms"),
												'error'=>"Impossible de sauvegarder vos réponses, réessayez plus tard");

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Template/Error',$this->data);
	}

	//Méthode en cas d'erreur lors de l'enregistrement des modifications d'un formulaire
	public function modify(){

		//Affichage de la page
		$this->data = array('user'=>$this->user,
												'redirections'=>array("Page d'accueil"=>'',
																 							"Mon SuperCompte"=>'MyAccount',
																							"Trouver un SuperFormulaire"=>"Find",
																							"Mes SuperFormulaires"=>"MyForms"),
												'error'=>"Impossible de sauvegarder le formulaire, réessayez plus tard");

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Template/Error',$this->data);
	}

	//Méthode en cas d'erreur de modification de l'état d'un formulaire
	public function state(){

		//Affichage de la page
		$this->data = array('user'=>$this->user,
												'redirections'=>array("Page d'accueil"=>'',
																 							"Mon SuperCompte"=>'MyAccount',
																							"Trouver un SuperFormulaire"=>"Find",
																							"Mes SuperFormulaires"=>"MyForms"),
												'error'=>"Impossible de changer l'état du formulaire, réessayez plus tard");

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Template/Error',$this->data);
	}

	//Méthode en cas d'erreur de changement de publication
	public function publishing(){

		//Affichage de la page
		$this->data = array('user'=>$this->user,
												'redirections'=>array("Page d'accueil"=>'',
																 							"Mon SuperCompte"=>'MyAccount',
																							"Trouver un SuperFormulaire"=>"Find",
																							"Mes SuperFormulaires"=>"MyForms"),
												'error'=>"Impossible de publier ou privatiser le formulaire, réessayez plus tard");

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Template/Error',$this->data);
	}
}
?>
