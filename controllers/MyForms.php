<?php
//Controller qui gère la page d'affichage des formulaires de utilisateur
class MyForms extends CI_Controller{

	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues
	private $header_button;		//Variable contenant un tableau avec les informations de la barre de navigation

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Utilisateur','user_table');
		$this->load->model('Formulaire','form_table');
		$this->load->model('Object/Form','form');
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
  }

	public function index(){

		$forms_data = $this->form_table->return_all_form_information($this->user->get_login()); //Récupération des formulaires de l'utilisateur
		$i = 0;
		$forms = array();
		//Innitialisation d'objet formulaire pour chacun des formulaires de l'utilisateur
		foreach($forms_data as $form){

			$forms[$i] = new Form();
			$forms[$i]->set_form($form['cleFormulaire'],$form['nomFormulaire'],$form['description'],$form['etat'],$form['nombreReponses'],$form['publique']);
			$i++;
		}

		//Affichage de la page
		$this->data = array('forms'=>$forms,
												'user'=>$this->user);

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Form/User_form',$this->data);
	}

	//Méthode appelée lorsque l'utilisateur souhaite modifier un fomrulaire
	public function modify($key){

		redirect('Modify/form/'.$key);	//Redirection vers la page de modification
	}

	//Méthode appelée lorsque l'utilisateur souhaite activer un fomrulaire
	public function activate($key){

		//Modification de l'état du formulaire
		if(!$this->form_table->edit_form_state($key,1)){

			redirect('Errors/state');	//Si celà n'a pas fonctionné, redirection vers une page d'erreur correspondante
		}

		redirect('MyForms');	//Rechargement de la page
	}

	//Méthode appelée lorsque l'utilisateur souhaite périmer un fomrulaire
	public function expire($key){

		//Modification de l'état du formulaire
		if(!$this->form_table->edit_form_state($key,2)){

			redirect('Errors/state');	//Si celà n'a pas fonctionné, redirection vers une page d'erreur correspondante
		}

		redirect('MyForms');	//Rechargement de la page
	}

	//Méthode appelée lorsque l'utilisateur souhaite voir les résultats d'un fomrulaire
	public function see_results($key){

		redirect('SeeResults/form/'.$key);	//Redirection vers la page d'affichage des résultats
	}

	//Méthode appelée lorsque l'utilisateur souhaite supprimer un fomrulaire
	public function delete($key){

		//Suppression du formulaire
		if(!$this->form_table->delete_form($key)){

			redirect('Errors/state');	//Si celà n'a pas fonctionné, redirection vers une page d'erreur correspondante
		}

		redirect('MyForms');	//Rechargement de la page
	}

	//Méthode appelée lorsque l'utilisateur souhaite rendre publique son formulaire
	public function publish($key){

		//Suppression du formulaire
		if(!$this->form_table->edit_form_publishing($key,1)){

			redirect('Errors/publishing');	//Si celà n'a pas fonctionné, redirection vers une page d'erreur correspondante
		}

		redirect('MyForms');	//Rechargement de la page
	}

	//Méthode appelée lorsque l'utilisateur souhaite rendre privé son formulaire
	public function privatize($key){

		//Suppression du formulaire
		if(!$this->form_table->edit_form_publishing($key,0)){

			redirect('Errors/publishing');	//Si celà n'a pas fonctionné, redirection vers une page d'erreur correspondante
		}

		redirect('MyForms');	//Rechargement de la page
	}
}
?>
