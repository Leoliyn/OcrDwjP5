<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES OUVRAGES  LISTES
namespace Frontend;

//require_once("Model/Commun/Manager.php");
//use Model\Commun;
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
    
    

}
