<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS    CLAUDEY Lionel  2018  
 * GESTION DE L'UTILISATEUR   CHANGER PSSQWD -CONNEXION -hash
 */

namespace Frontend;

require_once('Model/Commun/newManager.php');

use Commun\Manager;

class UsersManager extends Manager {

    /**
     * méthode creation hash du mot de passe en parametre
     * @param type $password
     * @return type
     */
    public function passwordUser($password) {
        $mdp = password_hash($password, PASSWORD_BCRYPT);
        return $mdp;
    }

    /**
     * update du mot de passe de l'utilisateur dont le pseudo est en pârametre 
     * @param type $userPsswd
     * @param type $pseudo
     * @return type
     */
    public function updatePsswd($userPsswd, $pseudo) {
        $psswd = $this->passwordUser($userPsswd);
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_users SET  USER_PASSWD=? WHERE USER_PSEUDO= ?');
        $req->execute(array($psswd, $pseudo));
        return $req;
    }

    /**
     * Verifie qu'une seule réponse à la requete sur le login puis verify par bcript que le mot de passe est bon  
     * 
     * @param type $pseudo
     * @param type $password
     * @return boolean
     */
    public function connexion($pseudo, $password) {
        $db = $this->dbConnect();
        $nbrow = 0;
        $req = $db->prepare('SELECT * FROM p5_users WHERE USER_PSEUDO = ? ');
        $req->execute(array($pseudo));
        $row = $req->fetchAll();
        $nbrow = count($row);
        $verify = password_verify($password, $row[0]['USER_PASSWD']);
        if ($verify && ($nbrow === 1)) {
            return 1;
        } else {
            return 0;  //  pb d'identification 
        }
    }

    /**
     * Ajoute un utilisateur dans la table p5_users
     * @param type $userName
     * @param type $userLastname
     * @param type $userPseudo
     * @param type $userMail
     * @param type $userPasswd
     * @param type $userStatut
     */
    public function addUser($userName, $userLastname, $userPseudo, $userMail, $userPasswd, $userStatut) {
        $db = $this->dbConnect();
        $psswd = $this->passwordUser($userPasswd);
        $req = $db->prepare('INSERT INTO p5_users (USER_NAME,USER_LASTNAME,USER_PSEUDO,USER_MAIL,USER_PASSWD,ROOT)VALUES(?,?,?,?,?,?)');
        $req->execute(array($userName, $userLastname, $userPseudo, $userMail, $psswd, $userStatut));
        $req->closeCursor();
    }

    /**
     * recupère les utilisateurs superadmin soit  : ROOT=1 
     * @return type
     */
    public function listSuperadmin() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_users WHERE p5_users.ROOT = ? ');
        $req->execute(array(1));
        return $req;
        $req->closeCursor();
    }

}
