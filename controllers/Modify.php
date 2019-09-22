<?php
//Controller qui gère la page de midification de formulaire
class Modify extends CI_Controller{

	private $data;						//Variable contenant un tableau avec les informations à transmettre aux vues
	private $header_button;		//Variable contenant un tableau avec les informations de la barre de navigation
	private $submit;					//Type d'action qu'a fait l'utilisateur

	public function __construct(){

		parent::__construct();

		//Chargement des models
		$this->load->model('Utilisateur','user_table');
		$this->load->model('Formulaire','form_table');
		$this->load->model('Questions','question_table');
		$this->load->model('Object/Form','form');
		$this->load->model('Object/Question','question');
		$this->load->model('Object/Questions_loader','questions_loader');
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
  }

	//Méthode qui gère la modification de formulaires
	public function form($key){

		//Si des modifications ont été apportées
		if(isset($_SESSION['start'])){

			//Innitialisation du formulaire avec les valeurs déjà connues
			$this->form->load_questions(unserialize($_SESSION['questions']),FALSE);
			$this->form->set_form($key,$_SESSION['title'],$_SESSION['description']);

			//Ajout des dernières modications sur le titre et la description
			$this->form->set_title($this->input->post('form_title'));
			$this->form->set_description($this->input->post('form_description'));

			//Ajout des dernières modications des questions
			foreach($this->form->get_questions() as $question){

				$question->set_question($this->input->post("question_title".$question->get_id()));

				//Ajout des dernières modications des réponses
				foreach($question->get_answers() as $answer=>$value){

					$question->set_answer($answer,$this->input->post("answer_title".$question->get_id().":".$answer));
				}
			}
		}else{
			//Si c'est l'utilisateur vient d'arriver sur la page -> il n'a pas encore fait de modifications

			$form_questions = array();

			//Si le formulaire existe
			if(!$this->form_table->check_form_key($key)){

				//Si le formulaire à déja été activé auparavant
				if($this->form_table->get_form_state($key) != 0){

					redirect('MyForms');	//Redirection vers la page des formulaires
				}

				//On innitialiste un objet formulaire avec ses informations et on récupère ses questions
				$this->form->set_form($key,$this->form_table->get_form_title($key),$this->form_table->get_form_description($key));
				$form_questions = $this->question_table->return_all_question_information($this->form->get_key());
			}else{

				//Sinon, on innitialiste un objet formulaire vide
				$this->form->set_form($key);
			}

			//Innitialisation et ajout des questions au formulaire
			$this->form->load_questions($form_questions);
		}

		//Si l'utilisateur à demandé l'ajout d'une question
		if($this->input->post('submit') === 'add_question'){

			//On vérifie qu'il à sélectionné un type
			$this->form_validation->set_rules('new_type','new_type','required');
			$this->form_validation->set_message('required','Vous n\'avez pas choisi de type pour la nouvelle question.');

			//Si un type à été sélectionné
			if($this->form_validation->run() == TRUE){

				$this->form->add_question($this->input->post('new_type')); //Ajoute de la question
			}
		}

		//Si l'utilisateur à demandé la suppression d'une question
		if(stristr($this->input->post('submit'),':',TRUE) === 'delete_question'){

			$id = str_replace(':','',stristr($this->input->post('submit'),':')); //On récupère l'ID de la question à supprimer
			$this->form->delete_question($id); //On supprime la question
		}

		//Si l'utilisateur à demandé d'ajouter une réponse
		if(stristr($this->input->post('submit'),':',TRUE) === 'add_answer'){

			$which_question = str_replace(':','',stristr($this->input->post('submit'),':')); //On récupère l'ID de la question
			$this->form->add_answer($which_question);	//On lui ajoute une rponse
		}

		//Si l'utilisateur à demandé de supprimer une réponse
		if(stristr($this->input->post('submit'),':',TRUE) === 'delete_answer'){

			$answer = str_replace(':','',stristr($this->input->post('submit'),':')); //On récupère la réponse demandée (sous forme question.reponse)
			$which_answer = str_replace('.','',stristr($answer,'.')); //On en récupère l'ID de la réponse
			$which_question = stristr($answer,'.',TRUE);	//Ansi que celui de la question

			$this->form->delete_answer($which_question,$which_answer);	//On supprime la réponse
		}

		//Si l'utilisateur à demandé de sauvegarder ou de sauvegarder et quitter
		if($this->input->post('submit') === 'save' || $this->input->post('submit') === 'save&quit'){

			//On vérifie qu'il y ait un titre
			$this->form_validation->set_rules('form_title','titre du formulaire','trim|required');
			$this->form_validation->set_message('required','Vous n\'avez rien entré comme {field}.');

			//Pour chaques questions
			foreach($this->form->get_questions() as $question){

				//On vérifie qu'il y ait un titre à la question
				$this->form_validation->set_rules('question_title'.$question->get_id(),'titre de la question','trim|required');

				//Pour chaques réponses
				foreach($question->get_answers() as $answer=>$value){

					//On vérifie qu'il y ait un titre à la réponse
					$this->form_validation->set_rules('answer_title'.$question->get_id().":".$answer,'titre de la réponse','trim|required');
				}
			}

			//Si tout est valide
			if($this->form_validation->run() == TRUE){

				//Si l'utilisdateur souhaite quitter après la sauvegarde
				if($this->input->post('submit') === 'save&quit'){

					//Si le formulaire existe
					if(!$this->form_table->check_form_key($key)){

						//On modifie son titre
						if(!$this->form_table->edit_form_title($this->form->get_key(),$this->form->get_title())){

							redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
						}

						//On modifie sa description
						if(!$this->form_table->edit_form_description($this->form->get_key(),$this->form->get_description())){

							redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
						}

						//Récupération des questions du formulaire enregistrées dans la base de données
						$previous_questions = $this->question_table->return_all_question_information($this->form->get_key());

						//Pour chacune de ces questions
						foreach($previous_questions as $previous_question){

							$has_been_deleted = TRUE;	//On suppose que l'itilisateur souhaite la supprimer
							//Pour chacune des questions que l'utilisateur souhaite enregistrer
							foreach($this->form->get_questions() as $question){

								//Si elle existe parmis celle de la base de données c'est que l'utilisateur ne veut pas la supprimer
								if($previous_question['idQuestion'] == $question->get_id()){

									$has_been_deleted = FALSE;
									break;
								}
							}

							//Si l'utilisateur souhaite supprimer la question
							if($has_been_deleted){

								//On la supprime
								if(!$this->question_table->delete_question($previous_question['idQuestion'],$this->form->get_key())){

									redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
								}
							}
						}

						//Pour chacune des questions que l'utilisateur souhaite enregistrer
						foreach($this->form->get_questions() as $question){

							//Si la question existe
							if(!$this->question_table->check_question_id($question->get_id(),$this->form->get_key())){

								//On la modifie avec de nouvelles valeurs
								if(!$this->question_table->edit_question($question->get_id(),$this->form->get_key(),$question->get_question())){

									redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
								}
							}else{

								//Sinon, en créé une nouvelle
								if(!$this->question_table->add_question($question->get_id(),$this->form->get_key(),$question->get_type(),$question->get_question())){

									redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
								}
							}

							//Pour chacune des réponses de la question
							foreach($question->get_answers() as $answer=>$value){

								//Si la réponse existe
								if(!$this->question_table->check_question_id($question->get_id().".".$answer,$this->form->get_key())){

									//On la modifie avec de nouvelles valeurs
									if(!$this->question_table->edit_question($question->get_id().".".$answer,$this->form->get_key(),$value)){

										redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
									}
								}else{

									//Sinon, en créé une nouvelle
									if(!$this->question_table->add_question($question->get_id().".".$answer,$this->form->get_key(),'',$value)){

										redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
									}
								}
							}
						}
					}else{
						//Si le formulaire n'existe pas

						//On ajoute le formualire à la base de données
						if(!$this->form_table->add_form($this->form->get_key(),$this->user->get_login(),$this->form->get_title(),0,$this->form->get_description())){

							redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
						}

						//Pour chacune des question que l'utilisateur souhaite enregistrer
						foreach($this->form->get_questions() as $question){

							//On ajoute la question à la base de données
							if(!$this->question_table->add_question($question->get_id(),$this->form->get_key(),$question->get_type(),$question->get_question())){

								redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
							}

							//Pour chacune des réponses de la question
							foreach($question->get_answers() as $answer=>$value){

								//On l'ajoute à la base de données
								if(!$this->question_table->add_question($question->get_id().".".$answer,$this->form->get_key(),'',$value)){

									redirect('Errors/modify'); //Si celà est impossible, on redirige vers une page appropriée
								}
							}
						}
					}

					//Suppression des variables de session liées à la modification de formulaire pour éviter des problèmes
					unset($_SESSION['start']);
					unset($_SESSION['questions']);
					unset($_SESSION['title']);
					unset($_SESSION['description']);
					redirect('MyForms'); //Redirection sur la page des formulaires de l'utilisateur
				}
			}
		}

		//Si le programme arrive ici, c'est que l'utilisateur ne souhaitait pas quitter

		//Ces variables permettent de "sauvegarder" dans un coin les objets pour éviter des pertes entre 2 submits
		$_SESSION['start'] = 'on';	//On indique donc que les variables de session concernant la modification de formulaire sont actives
		$_SESSION['questions'] = serialize($this->form->get_questions());
		$_SESSION['title'] = $this->form->get_title();
		$_SESSION['description'] = $this->form->get_description();
		//Affichage de la page, ce code est tout en bas pour éviter d'afficher la page si l'utilisateur ne souhaite pas y rester
		$this->data = array('form'=>$this->form,
												'user'=>$this->user);

		$this->load->view('Template/Header',$this->data);
		$this->load->view('Form/Modify',$this->data);
		$this->load->view('Form/Questions_references',$this->data);
	}

	//Méthode innitialise la clé d'un nouveau formulaire
	public function new_form(){

		$key = $this->get_random_key(); //Récupération d'une clé

		redirect('Modify/form/'.$key); //Redirection vers la modification de formulaire
	}

	//Méthode qui renvoie une clé aléatoire
	public function get_random_key(){

		$string = '';

		//Tant que la clé n'est pas unique par rapport à celles de la base de donnes ou qu'elle est vide
		while(!$this->form_table->check_form_key($string) || $string == ''){

			$length = 30; //Nombre de caractères de la clé
	    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //Caractères admis

			//Création d'une clé
	    for($i = 0; $i < $length; $i++){

		        $string .= $chars[rand(0, strlen($chars)-1)];
		    }
		}

		return $string;
	}
}
?>
