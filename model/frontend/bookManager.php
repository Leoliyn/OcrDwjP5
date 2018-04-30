<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES OUVRAGES  LISTES
namespace OpenClassrooms\DWJP5\frontend\Model;

require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;
class bookManager extends Manager {

    //renvoi ouvrage enable = 1
    public function getBooks() {

        $db = $this->dbConnect();
        $req = $db->query('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_AUTEUR,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE FROM ouvrage WHERE OUV_ENABLE=1');

        return $req;
    }

//       

    public function getBook($bookId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_AUTEUR,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE FROM p5_posts WHERE OUV_ID = ?');
        $req->execute(array($bookId));
        $post = $req->fetch();

        return $post;
    }

}
