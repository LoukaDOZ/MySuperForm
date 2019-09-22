<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questions extends CI_Model
{
	protected $table = 'question';

	//Ajouter une question
	//---> Renvoie un BOOLEAN
	public function add_question($id, $key, $type, $question)
	{
		$resultat = $this->db->set('idQuestion', $id)
			        		->set('cleFormulaire', $key)
			        		->set('typeQuestion', $type)
			        		->set('question', $question)
							->insert($this->table);
		return $resultat;
	}

	//Supprimer une question
	//---> Renvoie un BOOLEAN
	public function delete_question($id, $key)
	{
		$resultat = $this->db->where('idQuestion', $id)
							->where('cleFormulaire', $key)
							->delete($this->table);
		return $resultat;
	}

	//Modifier le type de la question
	//---> Renvoie un BOOLEAN
	public function edit_question_type($id, $key, $new_type)
	{
		$resultat = $this->db->set('typeQuestion', $new_type)
							->where('idQuestion', $id)
							->where('cleFormulaire', $key)
							->update($this->table);
		return $resultat;
	}

	//Modifier la question
	//---> Renvoie un BOOLEAN
	public function edit_question($id, $key, $new_question)
	{
		$resultat = $this->db->set('question', $new_question)
							->where('idQuestion', $id)
							->where('cleFormulaire', $key)
							->update($this->table);
		return $resultat;
	}

	//Vérifier l'existence de la question
	//---> Renvoie un BOOLEAN
	public function check_question_id($id, $key)
	{
		$verify = $this->db->select('idQuestion')
							->from($this->table)
							->where('idQuestion', $id)
							->where('cleFormulaire', $key)
							->get()
							->result();

		if($verify == null) {
			return true; //Existe
		}
		if($verify != null) {
			return false; //N'existe pas
		}
	}

	//Renvoie toutes les informations concernant une question
	//---> Renvoie un ARRAY (1 dimension)
	public function return_question_information($id, $key)
	{
		$resultat = $this->db->select('*')
							->from($this->table)
							->where('idQuestion', $id)
							->where('cleFormulaire', $key)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0];
	}

	//Renvoie toutes les informations des questions associées
	//à un formulaire
	//---> Renvoie un ARRAY (1 dimension)
	public function return_all_question_information($key)
	{
		$resultat = $this->db->select('*')
							->from($this->table)
							->where('cleFormulaire', $key)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array;
	}

	//Renvoie le nombre de questions associé à un formulaire
	//---> Renvoie un STRING correspondant au nombre de questions
	public function return_number_of_question($key)
	{
		$resultat = $this->db->select('count(*)')
							->from($this->table)
							->where('cleFormulaire', $key)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["count(*)"];
	}
}
