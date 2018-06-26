<?php
//╔═════════════════════════════╗  
//           PROJET 5 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Avril 2018           
//╚═════════════════════════════╝
//GESTION DES CHAPITRES  LISTE - AJOUTER- MODIFIER- SUPPRIMER -SUPPRIMER -ACTIVER- DESACTIVER
//namespace OpenClassrooms\DWJP5\Backend\Model;
//require_once("model/commun/Manager.php");
//use OpenClassrooms\DWJP5\Commun\Model\Manager;

namespace Backend;
use Commun\Manager;
require_once('Model/Commun/newManager.php');

class AuteurManager extends Manager {

       
    // OBTENTION DE LA LISTRE DES AUTEURS D'UN ARTICLE
    public function getAuteursPost($artId){
     $db = $this->dbConnect();
     $req = $db->prepare('SELECT * FROM p5_USERS INNER JOIN p5_AUTEUR ON p5_posts_ART_ID = ? ');
     $req->execute(array($artId));
     $listeIdAuteur = $req->fetch();
     return $listeIdAuteur; 
     $req->closeCursor ();
    }
    // OBTENTION DE LA LISTE DES POSTS D'UN AUTEUR
    public function getPostsAuteur($userId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_posts INNER JOIN p5_AUTEUR ON p5_USERS_USER_ID = ? ');
        $req->execute(array($userId));
        $listeIdAuteur = $req->fetch();
        $req->closeCursor();
        return $listeIdAuteur;
    }

    // AJOUT D 1 ENTREGISTREMENT DANS LA TABLE p5_AUTEUR
    public function addIdAuteurIdArticle($userId, $artId) {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_auteur (p5_USERS_USER_ID,p5_posts_ART_ID) VALUES(?,?)');
        $req->execute(array($userId, $artId));
        return $req;
        $req->closeCursor();
    }

    // SUPPRESSION D 1 ENREGISTREMENT DE LA TABLE p5_AUTEUR
    public function delIdAuteurIdArticle($userId, $artId) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_auteur WHERE p5_USERS_USER_ID = ? AND p5_posts_ART_ID =?');
        $req->execute(array($userId, $artId));
        return $req;
        $req->closeCursor();
    }

}
