<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reponse extends CI_Model
{
	protected $table = 'reponse';

	//Ajouter une réponse
	//---> Renvoie un BOOLEAN
	public function add_answer($id_question, $key, $answer)
	{
		$resultat = $this->db->set('idQuestion', $id_question)
			        		->set('cleFormulaire', $key)
			        		->set('reponse', $answer)
							->insert($this->table);
		return $resultat;
	}

	//Vérifier l'existence de la réponse
	//---> Renvoie un BOOLEAN
	public function check_answer_id($id_answer, $key)
	{
		$verify = $this->db->select('idReponse')
							->from($this->table)
							->where('idReponse', $id_answer)
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

	//Renvoie toutes les informations des réponses associées à un formulaire
	//---> Renvoie un ARRAY (1 dimension)
	public function return_all_answer_information($key)
	{
		$resultat = $this->db->select('*')
							->from($this->table)
							->where('cleFormulaire', $key)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array;
	}

	//
	//---> Renvoie un ARRAY (1 dimension)
	public function return_answer_by_id($id, $key)
	{
		$resultat = $this->db->select('reponse')
							->from($this->table)
							->where('idReponse', $id)
							->where('cleFormulaire', $key)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0];
	}
}
