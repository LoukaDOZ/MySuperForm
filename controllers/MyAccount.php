<?php
//Controller qui gère la page de gestion du compte
class MyAccount extends CI_Controller{

	private $hash;						//Variable contenant le hash des mot de passe
	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues

	public function __construct(){

		parent::__construct();

		//Chargement des models
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

		//Si l'utilisateur est passé par la page de modification de formulaire ou de réponse au formulaires, on détruit la session
		//Avec celà, on évite que l'utilisateur provoque des problèmes en utilisant l'url pour se déplacer dans le site
		if(isset($_SESSION['start'])){

		  unset($_SESSION['start']);
		  unset($_SESSION['questions']);
		  unset($_SESSION['title']);
		  unset($_SESSION['description']);
		}

		//Affichage de la page*
		$this->data = array('user'=>$this->user);

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Account/Account',$this->data);
  }

	public function index(){}

	//Méthode qui permet de changer de mot de passe
	public function change_login(){

		//Vérification des informations
		$this->form_validation->set_rules('new_login','Ce nom d\'utilisateur','trim|callback_verify_text');
		$this->form_validation->set_rules('password','Le mot de passe','trim|callback_verify_text|callback_verify_password');

		//Si les informations sont invalides
		if ($this->form_validation->run() == FALSE){

			$this->load->view('Change_files/Change_login'); //Affichage de la page
		}else{

			//Modification du compte
			if($this->user_table->edit_user_login($this->user->get_login(),$this->input->post('new_login'))){

				rename('assets/Images/Avatars/'.$this->user->get_login().'.png','assets/Images/Avatars/'.$this->input->post('new_login').'.png');	//Si celà a marché, on change aussi le nom de l'avatar par le nouveau nom d'utilisateur
				redirect('Connection/reconnection');	//Redirection vers la page de connexion
			}else{

				redirect('Errors/change');	//Sinon, on est redirigé vers une page d'erreur
			}
		}
	}

	//Méthode qui permet de changer d'e-mail
	public function change_email(){

		//Vérification des informations
		$this->form_validation->set_rules('new_email','Cet email','trim|callback_verify_email');
		$this->form_validation->set_rules('password','Le mot de passe','trim|callback_verify_text|callback_verify_password');

		//Si les informations sont invalides
		if ($this->form_validation->run() == FALSE){

			$this->load->view('Change_files/Change_email'); //Affichage de la page
		}else{

			//Modification du compte
			if($this->user_table->edit_user_email($this->user->get_login(),$this->input->post('new_email'))){

				$this->user->set_email($this->input->post('new_email'));	//Si celà a marché, on change aussi l'e-mail de l'objet utilisateur
				redirect('MyAccount');	//Rechargement de la page
			}else{

				redirect('Errors/change');	//Sinon, on est redirigé vers une page d'erreur
			}
		}
	}

	//Méthode qui permet de changer de mot de passe
	public function change_password(){

		//Vérification des informations
		$this->form_validation->set_rules('new_password','Ce mot de passe','trim|callback_verify_text');
		$this->form_validation->set_rules('confirm_password','Le mot de passe de confirmation','trim|callback_verify_text|matches[new_password]');
		$this->form_validation->set_rules('old_password','L\'ancien mot de passe','trim|callback_verify_text|callback_verify_password');
		$this->form_validation->set_message('matches','{field} ne correspond pas.');

		//Si les informations sont invalides
		if ($this->form_validation->run() == FALSE){

			$this->load->view('Change_files/Change_password',$this->data); //Affichage de la page
		}else{

			$this->hash = password_hash($this->input->post('new_password'),PASSWORD_DEFAULT); //Sinon, hashage du mot de passe

			//Modification du compte
			if($this->user_table->edit_user_password($this->user->get_login(),$this->hash)){

				redirect('Connection/reconnection');	//Si celà a marché, redirection vers la page de connexion
			}else{

				redirect('Errors/change');	//Sinon, on est redirigé vers une page d'erreur
			}
		}
	}

	//Méthode qui permet de changer d'avatar
	public function change_avatar(){

		$this->load->view('Change_files/Change_avatar'); //Affichage de la page

		$config['upload_path'] = './assets/Images/Avatars/';	//Dossier de stockage de l'avatar
		$config['allowed_types'] = 'jpg|png|jpeg';						//types acceptés
		$config['overwrite'] = TRUE;													//Renomage
		$config['file_name'] = $_SESSION['login'].'.png';	//Nom de l'image: nom d'utilisateur.png

		$this->load->library('upload',$config);	//Chargement de la librairie nécessaire pour télecharger une image

		//Si l'upload à fonctionné
		if($this->upload->do_upload('userfile')){

			redirect('MyAccount'); //Rechargement de la page
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

	public function verify_password($password){

		//Si le mot de passe est correct
		if(!password_verify($password,$this->user_table->get_user_password($this->user->get_login()))){

			$this->form_validation->set_message('verify_password',"{field} est incorrect.");
			return FALSE;
		}

		return TRUE; //Si aucune erreur n'est détectée, on indique que c'est bon
	}
}
?>
