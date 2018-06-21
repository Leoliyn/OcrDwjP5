<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DE L'UTILISATEUR   CHANGER PSSQWD -CONNEXION -hash
namespace Frontend;
//require_once("model/commun/Manager.php");
//use OpenClassrooms\DWJP5\Commun\Model\Manager;

class usersManager extends Manager {
    
    // Vérification que le mail existe dans la table users
    public function emailExist($mail) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_users WHERE USER_MAIL = ? ');
        $req->execute(array($mail));
        $row = $req->fetchAll(); 
        $nbrow = count($row);
        if($nbrow === 1) {
        return $row;
        } 
    }
    
            

    // Méthode création bcrypt du password
    public function passwordUser($password) {
        $mdp = password_hash($password,PASSWORD_BCRYPT);
        return $mdp;
    }
// méthode update mot de passe de ppseudo
  public function updatePsswd($userPsswd,$pseudo) {
        $psswd = $this->passwordUser($userPsswd);
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_users SET  USER_PASSWD=? WHERE USER_PSEUDO= ?');
        $req->execute(array($psswd, $pseudo));
        return $req;
    }
 // Méthode update passwd de admin    
    public function updatePsswdAdmin($userPsswd) {
        $psswd = $this->passwordUser($userPsswd);
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_users SET  USER_PASSWD=? WHERE USER_PSEUDO= ?');
        $req->execute(array($psswd, 'admin'));
        return $req;
    }

    
   // Verifie qu'une seule répose à la requete sur le login puis verify par bcript que le mot de passe est bon  
  public function connexion($pseudo, $password) {
        $db = $this->dbConnect();
        $nbrow = 0;
        $req = $db->prepare('SELECT * FROM p5_users WHERE USER_PSEUDO = ? ');
        $req->execute(array($pseudo));
        $row = $req->fetchAll();
        $nbrow = count($row);
        $verify = password_verify($password,$row[0]['USER_PASSWD']);
        if($verify && ($nbrow === 1)) {
        return 1;
        }else {
        return 0;  //  pb d'identification 
    }
      

}

/////////////////////////////////
 // methode de création d un utilisateur 
    public function createUser($userName, $userLastname, $userPseudo, $userMail, $userPsswd, $userstatut) {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO users (USER_NAME,USER_LASTNAME,USER_PSEUDO,USER_MAIL,USER_PSSWD,USER_STATUT)VALUES
(:userName,:userLastname,:userPseudo,:userMail,:userPsswd,:userstatut');
        $req->execute(array($userName, $userLastname, $userPseudo, $userMail, $userPsswd, $userstatut));
        return $req;
    }

    public function getUser($id) {
        $db = $this->dbConnect();
        $req = $db->query('SELECT USER_NAME,USER_LASTNAME,USER_PSEUDO,USER_MAIL,USER_PSSWD,USER_STATUT FROM users WHERE USER_ID= ?');
        $req->execute(array($id));
        return $req;
    }

    public function delUser($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM users WHERE USER_ID = :id');
        $req->execute(array($id));
        return $req;
    }
   
    public function updateUser($userName, $userLastname, $userPseudo, $userMail, $userPsswd, $userstatut) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE users SET  USER_NAME= ?,USER_LASTNAME=?,USER_PSEUDO=?,USER_MAIL=?,USER_PASSWD=?,USER_STATUT=?, WHERE id= ?');
        $req->execute(array($userName, $userLastname, $userPseudo, $userMail, $userPsswd, $userstatut));
    }

/////////////////////////////////
}