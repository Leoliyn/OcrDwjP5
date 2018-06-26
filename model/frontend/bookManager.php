<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES OUVRAGES  LISTES
namespace Frontend;
require_once('Model/Commun/newManager.php');
use Commun\Manager;
class bookManager extends Manager {

    //renvoi ouvrage enable = 1
    public function getBooks() {

        $db = $this->dbConnect();
        $req = $db->query('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_IMAGE, OUV_ENABLE FROM p5_ouvrage WHERE OUV_ENABLE=1');

        return $req;
    }

//       

    public function getBook($bookId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE FROM p5_ouvrage WHERE OUV_ID = ?');
        $req->execute(array($bookId));
        $book = $req->fetchAll();

        return $book;
    }
    
   function getBooksRights($ouvId) { 
        $db = $this->dbConnect();
        $req = $db->prepare(' SELECT * FROM p5_ouvrage INNER JOIN p5_gere_ouvrage ON p5_ouvrage.OUV_ID=p5_gere_ouvrage.OUVRAGE_OUV_ID INNER JOIN p5_statut_liste ON p5_statut_liste.p5_STATUT_ID=p5_gere_ouvrage.p5_statut_liste_p5_STATUT_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_gere_ouvrage.p5_USERS_USER_ID WHERE p5_ouvrage.OUV_ID= ? ');
        $req->execute(array($ouvId));
        return $req;
        $req->closeCursor();
    }

}
