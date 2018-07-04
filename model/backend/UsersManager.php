<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS    CLAUDEY Lionel  2018   
 * GESTION DE L'UTILISATEUR   CHANGER PSSQWD -CONNEXION -hash
 */

namespace Backend;

use Commun\Manager;

require_once('Model/Commun/newManager.php');

class UsersManager extends Manager {

    /**
     * Vérification que le mail existe dans la table users
     * @param type $mail
     * @return type
     */
    public function emailExist($mail) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_users WHERE USER_MAIL = ? ');
        $req->execute(array($mail));
        $row = $req->fetchAll();
        $req->closeCursor();
        $nbrow = count($row);
        if ($nbrow === 1) {
            return $row;
        }
    }

    /**
     * Retourne la liste des statuts utilisateurs
     * @return type
     */
    public function getStatuts() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_statut_liste WHERE ? ');
        $req->execute(array(1));

        return $req;
        $req->closeCursor();
    }

    /**
     * Retourne la liste de t ous les  utilisateurs de la table users 
     * @return type
     */
    public function getUsers() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_users WHERE ? ');
        $req->execute(array(1));

        return $req;
        $req->closeCursor();
    }

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
        $req = $db->prepare('UPDATE p5_users SET  USER_PASSWD= ? WHERE USER_PSEUDO= ?');
        $req->execute(array($psswd, $pseudo));
        return $req;
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
        $req->closeCursor();
        $nbrow = count($row);
        $verify = password_verify($password, $row[0]['USER_PASSWD']);
        if ($verify && ($nbrow === 1)) {
            //$tabUser= $row[0];
            return $row; // retour utilisateur
        } else {
            return FALSE;  //  pb d'identification 
        }
    }

    /**
     * récupère les données d'un utilisateur depuis son id 
     * @param type $id
     * @return type
     */
    public function getUser($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT USER_ID,USER_NAME,USER_LASTNAME,USER_PSEUDO,USER_MAIL,USER_PASSWD,ROOT FROM p5_users WHERE USER_ID = ?');
        $req->execute(array($id));
        $user = $req->fetchAll();
        return $user[0];
        $req->closeCursor();
    }

    /**
     * Update un utilisateur depuis son id
     * @param type $userId
     * @param type $userName
     * @param type $userLastname
     * @param type $userPseudo
     * @param type $userMail
     * @param type $userStatut
     */
    public function updateUser($userId, $userName, $userLastname, $userPseudo, $userMail, $userStatut) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_users SET  USER_NAME= ?,USER_LASTNAME=?,USER_PSEUDO=?,USER_MAIL=?,ROOT=? WHERE USER_ID= ?');
        $req->execute(array($userName, $userLastname, $userPseudo, $userMail, $userStatut, $userId));
        $req->closeCursor();
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
     * Supprime un utilisateur depuis son id
     * @param type $user_id
     */
    public function delUser($user_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM `p5_users` WHERE USER_ID = ?');
        $req->execute(array($user_id));
        $req->closeCursor();
    }

    /**
     * Update le mot de passe d'un utilisateur depuis son id 
     * @param type $user_id
     * @param type $passwd
     */
    public function initUser($user_id, $passwd) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_users SET USER_PASSWD = ?  WHERE USER_ID = ?');
        $req->execute(array($passwd, $user_id));
        $req->closeCursor();
    }

}
