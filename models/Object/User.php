<?php
//Objet qui contient les informations d'un utilisateur
class User extends CI_Model{

  private $login;     //Nom d'utilisateur
  private $email;     //E-mail
  private $password;  //Mot de passe
  private $connected; //Est-ce qu'il est connecté

  public function __construct(){

    parent::__construct();

    $this->login = '';
    $this->password = '';
    $this->email = '';
    $this->connected = FALSE; //De base, il n'est pas connecté
  }

  //Méthode qui innitialise un utilisateur
  public function set_user($login = '',$password = '',$email = ''){

    $this->login = $login;
    $this->password = $password;
    $this->email = $email;
  }

  //Méthode qui défini l'utilistauer comme connecté
  public function set_connected(){

    $this->connected = TRUE;
  }

  //Méthode qui donne une valeur au nom d'utilisateur
  public function set_login($login){

    $this->login = $login;
  }

  //Méthode qui donne une valeur au mot de passe
  public function set_password($password){

    $this->password = $password;
  }

  //Méthode qui donne une valeur à l'e-mail
  public function set_email($email){

    $this->email = $email;
  }

  //Méthode qui renvoie le nom d'utilisateur
  public function get_login(){

    return $this->login;
  }

  //Méthode qui renvoie le mot de passe
  public function get_password(){

    return $this->password;
  }

  //Méthode qui renvoie l'e-mail
  public function get_email(){

    return $this->email;
  }

  //Méthode qui renvoie s'il est connecté
  public function get_connected(){

    return $this->connected;
  }
}
?>
