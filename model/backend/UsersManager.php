<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DE L'UTILISATEUR   CHANGER PSSQWD -CONNEXION -hash
namespace OpenClassrooms\DWJP5\backend\Model;
require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;

class UsersManager extends Manager {
    
    // Vérification que le mail existe dans la table users
    public function emailExist($mail) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_users WHERE USER_MAIL = ? ');
        $req->execute(array($mail));
        $row = $req->fetchAll(); 
        $req->closeCursor ();
        $nbrow = count($row);
        if($nbrow === 1) {
        return $row;
        } 
    }
    public function getStatuts(){
       $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_statut_liste WHERE ? ');
        $req->execute(array(1));
       
        return $req;
        $req->closeCursor();  
     }   
    
     public function getUsers(){
       $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_users WHERE ? ');
        $req->execute(array(1));
       
        return $req;
        $req->closeCursor();  
     }        

    // Méthode création bcrypt du password
    public function passwordUser($password) {
        $mdp = password_hash($password,PASSWORD_BCRYPT);
        return $mdp;
    }
// méthode update mot de passe de pseudo
  public function updatePsswd($userPsswd,$pseudo) {
        $psswd = $this->passwordUser($userPsswd);
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_users SET  USER_PASSWD= ? WHERE USER_PSEUDO= ?');
        $req->execute(array($psswd, $pseudo));
        return $req;
        $req->closeCursor ();
    }
 // RECUPERE LE TABLEAU DE DROIT ACCES  D'UN UTILISATEUR //non utilisee ?????
 public function droitsUser($userId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_GERE_OUVRAGE WHERE p5_USERS_USER_ID = ? ');
        $req->execute(array($userId));
        return $req;
        $req->closeCursor();
    }

    // Verifie qu'une seule répose à la requete sur le login puis verify par bcript que le mot de passe est bon  
  public function connexion($pseudo, $password) {
        $db = $this->dbConnect();
        $nbrow = 0;
        $req = $db->prepare('SELECT * FROM p5_users WHERE USER_PSEUDO = ? ');
        $req->execute(array($pseudo));
        $row = $req->fetchAll();
        $req->closeCursor ();
        $nbrow = count($row);
        $verify = password_verify($password, $row[0]['USER_PASSWD']);
        if ($verify && ($nbrow === 1)) {
            //$tabUser= $row[0];
            return $row; // retour utilisateur
        } else {
            return FALSE;  //  pb d'identification 
        }
    }


 

    public function getUser($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT USER_ID,USER_NAME,USER_LASTNAME,USER_PSEUDO,USER_MAIL,USER_PASSWD,ROOT FROM p5_users WHERE USER_ID = ?');
        $req->execute(array($id));
        $user = $req->fetchAll();
        return $user[0];
        $req->closeCursor ();
    }

   //////////////////////////////// FONCTION  OBTENTION DES DROITS de l'utilisateur sur ouvrage ////////
//    public function getUserRights($id) {
//        $db = $this->dbConnect();
//        $req = $db->query('SELECT * FROM p5_gere_ouvrage INNER JOIN p5_statut_liste ON p5_gere_ouvrage.p5_statut_liste_p5_STATUT_ID = p5_statut_liste.p5_STATUT_ID  WHERE p5_gere_ouvrage.p5_USERS_USER_ID = ?');
//        $req->execute(array($id));
//        $result = $req->fetchAll();
//        return $result;
//        $req->closeCursor ();
//    }
    

   
    public function updateUser($userId,$userName, $userLastname, $userPseudo, $userMail,  $userStatut) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_users SET  USER_NAME= ?,USER_LASTNAME=?,USER_PSEUDO=?,USER_MAIL=?,ROOT=? WHERE USER_ID= ?');
        $req->execute(array($userName, $userLastname, $userPseudo, $userMail, $userStatut, $userId));
        $req->closeCursor ();
    }

 public function addUser($userName, $userLastname, $userPseudo, $userMail,$userPasswd,  $userStatut) {
        $db = $this->dbConnect();
        $psswd = $this->passwordUser($userPasswd);
        $req = $db->prepare('INSERT INTO p5_users (USER_NAME,USER_LASTNAME,USER_PSEUDO,USER_MAIL,USER_PASSWD,ROOT)VALUES(?,?,?,?,?,?)');
        $req->execute(array($userName, $userLastname, $userPseudo, $userMail,$psswd, $userStatut));
        $req->closeCursor ();
    }
  
    
     public function delUser($user_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM `p5_users` WHERE USER_ID = ?');
        $req->execute(array($user_id));
        $req->closeCursor ();
    }
  
    
   public function initUser($user_id,$passwd) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_users SET USER_PASSWD = ?  WHERE USER_ID = ?');
        $req->execute(array($passwd,$user_id));
        $req->closeCursor ();
    }
    
    
}