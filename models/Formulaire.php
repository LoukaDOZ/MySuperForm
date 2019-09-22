<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formulaire extends CI_Model
{
	protected $table = 'formulaire';

	//Créer un nouveau formulaire
	//---> Renvoie un BOOLEAN
	public function add_form($cle, $login, $nomForm, $etat, $description)
	{
		$resultat = $this->db->set('cleFormulaire', $cle)
			        		->set('nomUtilisateur', $login)
			        		->set('nomFormulaire', $nomForm)
			        		->set('etat', $etat)
			        		->set('description', $description)
							->insert($this->table);
		return $resultat;
	}

	//Supprimer un formulaire
	//---> Renvoie un BOOLEAN
	public function delete_form($cle)
	{
		$resultat = $this->db->where('cleFormulaire', $cle)
							->delete($this->table);
		return $resultat;
	}

	//Modifier le titre d'un formulaire
	//---> Renvoie un BOOLEAN
	public function edit_form_title($cle, $new_title)
	{
		$resultat = $this->db->set('nomFormulaire', $new_title)
							->where('cleFormulaire', $cle)
							->update($this->table);
		return $resultat;
	}

	//Modifier l'état du formulaire
	//---> Renvoie un BOOLEAN
	public function edit_form_state($cle, $new_state)
	{
		$resultat = $this->db->set('etat', $new_state)
							->where('cleFormulaire', $cle)
							->update($this->table);
		return $resultat;
	}

	//Modifier la description d'un formulaire
	//---> Renvoie un BOOLEAN
	public function edit_form_description($cle, $new_description)
	{
		$resultat = $this->db->set('description', $new_description)
							->where('cleFormulaire', $cle)
							->update($this->table);
		return $resultat;
	}

	//Modifier le nombre de personnes ayant répondu au formulaire
	//---> Renvoie un BOOLEAN
	public function edit_form_number_of_answer($cle, $number)
	{
		$resultat = $this->db->set('nombreReponses', $number)
							->where('cleFormulaire', $cle)
							->update($this->table);
		return $resultat;
	}

	//Modifier si le formulaire est publique ou privé
	//---> Renvoie un BOOLEAN
	public function edit_form_publishing($cle, $public)
	{
		$resultat = $this->db->set('publique', $public)
							->where('cleFormulaire', $cle)
							->update($this->table);
		return $resultat;
	}

	//Vérifier l'existence de la clé du formulaire
	//---> Renvoie un BOOLEAN
	public function check_form_key($cle)
	{
		$verify = $this->db->select('cleFormulaire')
							->from($this->table)
							->where('cleFormulaire', $cle)
							->get()
							->result();

		if($verify == null) {
			return true; //TRUE = vide, donc le login n'existe pas
		}
		if($verify != null) {
			return false; //FALSE = rempli, donc le login existe pas
		}
	}

	//Récupérer les informations du formulaire
	//---> Renvoie un ARRAY (1 dimension)
	public function return_form_information($cle)
	{
		$resultat = $this->db->select('*')
							->from($this->table)
							->where('cleFormulaire', $cle)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0];
	}

	//Renvoie toutes les informations des formulaires associées
	//à un utilisateur
	//---> Renvoie un ARRAY (1 dimension)
	public function return_all_form_information($login)
	{
		$resultat = $this->db->select('*')
							->from($this->table)
							->where('nomUtilisateur', $login)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array;
	}

	//Renvoie la clée des formulaires publiques et ouverts
	//---> Renvoie un ARRAY
	public function get_all_public_forms()
	{
		$resultat = $this->db->select('cleFormulaire')
							->from($this->table)
							->where('etat', 1)
							->where('publique', 1)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array;
	}

	//Récupérer le nom du créateur du formulaire
	//---> Renvoie un STRING correspondant au nom de l'utilisateur
	public function get_form_login($cle)
	{
		$resultat = $this->db->select('nomUtilisateur')
							->from($this->table)
							->where('cleFormulaire', $cle)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["nomUtilisateur"];
	}

	//Récupérer le titre du formulaire
	//---> Renvoie un STRING correspondant au nom du formulaire
	public function get_form_title($cle)
	{
		$resultat = $this->db->select('nomFormulaire')
							->from($this->table)
							->where('cleFormulaire', $cle)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["nomFormulaire"];
	}

	//Récupérer l'état du formulaire
	//---> Renvoie un STRING correspondant à l'état du formulaire
	public function get_form_state($cle)
	{
		$resultat = $this->db->select('etat')
							->from($this->table)
							->where('cleFormulaire', $cle)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["etat"];
	}

	//Récupérer la description du formulaire
	//---> Renvoie un STRING correspondant à la description du formulaire
	public function get_form_description($cle)
	{
		$resultat = $this->db->select('description')
							->from($this->table)
							->where('cleFormulaire', $cle)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["description"];
	}

	//Récupérer le nombre de personnes ayant répondu au formulaire
	//---> Renvoie un INT
	public function get_form_number_of_answer($cle)
	{
		$resultat = $this->db->select('nombreReponses')
							->from($this->table)
							->where('cleFormulaire', $cle)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["nombreReponses"];
	}

	//Récupérer si le formulaire est publique (1) ou privé (0)
	//---> Renvoie un INT
	public function get_form_public($cle)
	{
		$resultat = $this->db->select('publique')
							->from($this->table)
							->where('cleFormulaire', $cle)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["public"];
	}
}
