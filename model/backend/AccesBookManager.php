<?php
//╔═════════════════════════════╗  
//           PROJET 5 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Avril 2018           
//╚═════════════════════════════╝
//GESTION DES OUVRAGES  
//namespace OpenClassrooms\DWJP5\Backend\Model;
//require_once("model/commun/Manager.php");
//use OpenClassrooms\DWJP5\Commun\Model\Manager;

namespace Backend;
use Commun\Manager;
require_once('Model/Commun/newManager.php');

class AccesBookManager extends Manager {



    ////////DROITS ACCES 
    // OBTENTION DE LA LISTE DES GESTIONNAIRES D'UN OUVRAGE
    public function getGestionnairesBook($bookId) {
        $db = $this->dbConnect();

        $req = $db->prepare('SELECT * FROM p5_USERS,p5_GERE_OUVRAGE INNER JOIN p5_GERE_OUVRAGE ON OUVRAGE_OUV_ID = ? ');

        $req->execute(array($bookId));
        $listeIdGestionnaire = $req->fetch();
        return $listeIdGestionnaire;
    }

    // OBTENTION DE LA LISTE DES OUVRAGES D'UN GESTIONNAIRE
    public function getBooksGestionnaire($userId) {
        $db = $this->dbConnect();

        $req = $db->prepare('SELECT * FROM p5_OUVRAGE,p5_GERE_OUVRAGE  INNER JOIN p5_GERE_OUVRAGE ON p5_USERS_USER_ID = ? ');

        $req->execute(array($userId));
        $listeOuvrage = $req->fetch();
        return $listeOuvrage;
    }

    // AJOUT D 1 ENTREGISTREMENT DANS LA TABLE p5_GERE_OUVRAGE
    public function addIdUserIdBook($userId, $bookId, $statut) {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_GERE_OUVRAGE (p5_USERS_USER_ID,OUVRAGE_OUV_ID,p5_STATUT_LISTE_STATUT) VALUES(?,?,?)');
        $req->execute(array($userId, $bookId, $statut));
        return $req;
    }

    // SUPPRESSION D 1 ENREGISTREMENT DE LA TABLE p5_GERE_OUVRAGE
    public function delIdUserIdBook($userId, $bookId) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_GERE_OUVRAGE WHERE p5_USERS_USER_ID = ? AND OUVRAGE_OUV_ID =?');
        $req->execute(array($userId, $bookId));
        return $req;
    }

    // SUPPRESSION ts ENREGISTREMENT pour 1 ouvrage DE LA TABLE p5_GERE_OUVRAGE
    public function delIdBook($bookId) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_GERE_OUVRAGE WHERE  OUVRAGE_OUV_ID =?');
        $req->execute(array($bookId));
        return $req;
    }

    public function delIdUser($userId) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_GERE_OUVRAGE WHERE p5_USERS_USER_ID = ? ');
        $req->execute(array($userId));
        return $req;
    }

    public function delStatut($statut) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_GERE_OUVRAGE WHERE p5_STATUT_LISTE_STATUT = ? ');
        $req->execute(array($statut));
        return $req;
    }

}
