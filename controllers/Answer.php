<?php
//Controller qui gére la page reponsee à un formulaire
class Answer extends CI_Controller{

	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Object/Form','form');
		$this->load->model('Object/Question','question');
		$this->load->model('Object/Questions_loader','questions_loader');
		$this->load->model('Formulaire','form_table');
		$this->load->model('Utilisateur','user_table');
		$this->load->model('Questions','question_table');
		$this->load->model('Reponse','answer_table');
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

		$this->data = array();
  }

	//Méthode pour la réponse à un formulaire
	//key est la clé du formulaire transmise par l'url
	public function form($key){

		//Si la clé ne correspond à aucun formulaire ou que celui-çi ne soit pas ouvert, l'utilisateur est redirigé sur une page d'erreur
		//Cette vérification protège du fait que l'utilisateur puisse réecrire l'url sans passer par le controller Find qui se charge normalement de vérifier qu'une clé existe
		if($this->form_table->check_form_key($key) || $this->form_table->get_form_state($key) != 1){

			redirect('Errors/form');
		}

		//Si l'utilisateur à déjà remplis des champs
		if(isset($_SESSION['start'])){

			//On innitialiste un objet formulaire  avec ces données
			$this->form->load_questions(unserialize($_SESSION['questions']),FALSE);
			$this->form->set_form($key,$_SESSION['title'],$_SESSION['description']);
		}else{

			//Sinon on innitialise un objet formulaire avec ses informations récupérées dans la base de données
			$this->form->set_form($key,$this->form_table->get_form_title($key),$this->form_table->get_form_description($key));

			//Innitialisation des questions
			$form_questions = $this->question_table->return_all_question_information($this->form->get_key());
			$this->form->load_questions($form_questions);
		}

		//Création d'un objet utilisateur qui contient les informations liées au créateur du formulaire auquel l'utilisateur répond
		$creator = new User();
		$creator->set_user($this->form_table->get_form_login($this->form->get_key()));

		//Pour chaques questions
		foreach($this->form->get_questions() as $question){

			//Si la question est de type boutons à choix multiples
			if($question->get_type() == 'checkbox'){

				$selection = array();

				//On ajoute dans selection, qui sera ici un tableau, toutes les cases qui ont été cochées
				foreach($question->get_answers() as $answer=>$value){

					if($this->input->post($question->get_id().'radio'.$answer)){

						$selection[$answer] = $answer;
					}
				}

				$question->set_selected_answer($selection); //On définie les réponses à la question
			}else{

				//Sinon on vérifie qu'une réponse à bien été entrée ou sélectionnée
				$this->form_validation->set_rules($question->get_id(),$question->get_id(),'required');
				$this->form_validation->set_message('required','Vous n\'avez pas répondu à cette question.');

				$question->set_selected_answer($this->input->post($question->get_id())); //On définie la seule réponse à la question
			}
		}

		//Si au moins une réponse, hormis les cases à cocher, n'a pas été répondu
		if($this->form_validation->run() == FALSE){

			//Ces variables session contiennent les informations du formulaire (questions, titre, description, réponses de l'utilisateur,...)
			//Elle premettent de sauvegarder les dernière modifications de l'utilisateur sans avoir à passer par la base de données
			$_SESSION['start'] = 'on';					//On indique qu'il ne faut plus passer par la base de données
			$_SESSION['questions'] = serialize($this->form->get_questions()); //Stocke les objets questions
			$_SESSION['title'] = $this->form->get_title();		//Stocke le titre du formulaire
			$_SESSION['description'] = $this->form->get_description();	//Stocke la description du formulaire

			//On charge la page de réponse au formulaire
			$this->data = array('user'=>$this->user,
													'creator'=>$creator,
													'form'=>$this->form);

			$this->load->view('Template/Header',$this->data);
			$this->load->view('Form/Answer',$this->data);
			$this->load->view('Form/Answers_references',$this->data);
		}else{

			//Sinon pour, chaques questions
			foreach($this->form->get_questions() as $question){

				//Si le type de la question est champ de texte ou zone de texte ou date
				if($question->get_type() == 'text' || $question->get_type() == 'textarea' || $question->get_type() == 'date'){

					//On stocke la réponse répondu dans la base de données
					if(!$this->answer_table->add_answer($question->get_id(),$this->form->get_key(),$question->get_selected_answer())){

						redirect('Errors/answer');  //Si ça na pas marché, on est redirigé vers une page d'erreur
					}
				}

				//Si le type de la question est liste déroulante ou boutons à choix unique
				if($question->get_type() == 'list' || $question->get_type() == 'radio'){

					//Pour chaques réponses qui étaient possibles
					foreach($question->get_answers() as $answer=>$value){

						//On vérifie à quelle réponse, parmis toutes celles possibles, la réponse sélectionnée appartient
						if($question->get_selected_answer() == $answer){

							//On stocke la réponse sélectionnée dans la base de données
							if(!$this->answer_table->add_answer($question->get_id(),$this->form->get_key(),$value)){

								redirect('Errors/answer');  //Si ça na pas marché, on est redirigé vers une page d'erreur
							}
						}
					}
				}

				//Si le type de la question est boutons à choix multiples
				if($question->get_type() == 'checkbox'){

					//Pour chaques réponses qui étaient possibles
					foreach($question->get_answers() as $answer=>$value){

						//Pour chaques réponses sélectionnées
						foreach($question->get_selected_answer() as $selection){

							//On vérifie à quelle réponse, parmis toutes celles possibles, la réponse sélectionnée appartient
							if($selection == $answer){

								//On stocke la réponse sélectionnée dans la base de données
								if(!$this->answer_table->add_answer($question->get_id(),$this->form->get_key(),$value)){

									redirect('Errors/answer');  //Si ça na pas marché, on est redirigé vers une page d'erreur
								}
							}
						}
					}
				}
			}

			//On ajoute qu'une personne de plus a répondu
			if(!$this->form_table->edit_form_number_of_answer($this->form->get_key(),$this->form_table->get_form_number_of_answer($key) + 1)){

				redirect('Errors/answer');  //Si ça na pas marché, on est redirigé vers une page d'erreur
			}

			redirect('Confirmation/answer/'.$this->form->get_key()); //on redirige l'utilisateur vers la page de confimation
		}
	}
}
?>
