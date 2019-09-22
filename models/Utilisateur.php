<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utilisateur extends CI_Model
{
	protected $table = 'utilisateur';

	//Indique si le login existe déjà (à mettre avant de créer le login)
	//---> Renvoie un BOOLEAN
	public function check_user_login($login)
	{
		//On cherche dans la base de données le login
		$verify = $this->db->select('nomUtilisateur')
							->from($this->table)
							->where('nomUtilisateur', $login)
							->get()
							->result();//Renvoie rien ou le nom s'il existe

		if($verify == null) {
			return true; //TRUE = vide, donc le login n'existe pas
		}
		if($verify != null) {
			return false; //FALSE = rempli, donc le login existe pas
		}
	}

	//Ajouter un nouvel utilisateur
	//---> Renvoie un BOOLEAN
	public function add_user($login, $password, $email)
	{
		$resultat = $this->db->set('nomUtilisateur', $login)
			        		->set('motDePasse', $password)
			        		->set('email', $email)
							->insert($this->table);

		return $resultat;
	}

	//Supprimer un utilisateur
	//Contrainte ON CASCADE, donc si l'utilisateur
	//possédait un formulaire, le formulaire sera automatiquement
	//supprimé, ainsi que les question et les réponses
	//---> Renvoie un BOOLEAN
	public function delete_user($login)
	{
		$resultat = $this->db->where('nomUtilisateur', $login)
							->delete($this->table);
		return $resultat;
	}

	//Modifie le nom de l'utilisateur
	//Contrainte ON CASCADE, donc si l'utilisateur
	//se voit modifier son login, cela se répercutera sur la
	//table FORMULAIRE qui contient aussi un 'nomUtilisateur'
	//(clé étrangère).
	//---> Renvoie un BOOLEAN
	public function edit_user_login($login, $new_login)
	{
		$resultat = $this->db->set('nomUtilisateur', $new_login)
							->where('nomUtilisateur', $login)
							->update($this->table);
		return $resultat;
	}

	//Modifie le mot de passe
	//---> Renvoie un BOOLEAN
	public function edit_user_password($login, $new_password)
	{
		$resultat = $this->db->set('motDePasse', $new_password)
							->where('nomUtilisateur', $login)
							->update($this->table);
		return $resultat;
	}

	//Modifie l'adresse e-mail
	//---> Renvoie un BOOLEAN
	public function edit_user_email($login, $new_email)
	{
		$resultat = $this->db->set('email', $new_email)
							->where('nomUtilisateur', $login)
							->update($this->table);
		return $resultat;
	}

	//Modifie l'image de profil
	//---> Renvoie un BOOLEAN
	public function edit_user_picture($login, $new_picture)
	{
		$resultat = $this->db->set('image', $new_picture)
							->where('nomUtilisateur', $login)
							->update($this->table);
		return $resultat;
	}

	//Récupérer les infromations de l'utilisateur
	//---> Renvoie un ARRAY (2 dimensions)
	public function return_user_information($login)
	{
		$resultat = $this->db->select('*')
							->from($this->table)
							->where('nomUtilisateur', $login)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array;
	}

	//Récupérer le mot de passe de l'utilisateur
	//---> Renvoie un STRING correspondant au mot de passe
	public function get_user_password($login)
	{
		$resultat = $this->db->select('motDePasse')
							->from($this->table)
							->where('nomUtilisateur', $login)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["motDePasse"];
	}

	//Récupérer l'email' de l'utilisateur
	//---> Renvoie un STRING correspondant à l'e-mail
	public function get_user_email($login)
	{
		$resultat = $this->db->select('email')
							->from($this->table)
							->where('nomUtilisateur', $login)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["email"];
	}

	//Récupérer le mot de passe de l'utilisateur
	//---> Renvoie un STRING correspondant au mot de passe
	public function get_user_picture($login)
	{
		$resultat = $this->db->select('image')
							->from($this->table)
							->where('nomUtilisateur', $login)
							->get()
							->result();

		$resultat_array = json_decode(json_encode($resultat), True);

		return $resultat_array[0]["image"];
	}
}
