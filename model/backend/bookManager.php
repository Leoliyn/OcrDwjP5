<?php
//╔═════════════════════════════╗  
//           PROJET 5 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Avril 2018           
//╚═════════════════════════════╝
//GESTION DES OUVRAGES  
namespace OpenClassrooms\DWJP5\backend\Model;
require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;

class BookManager extends Manager {
    
    
//methode qui retourne la liste des ouvrages / à UserId
    public function getBooksUser($userId) {
        $db = $this->dbConnect();
//        $req = $db->prepare("SELECT * FROM `p5_ouvrage` INNER JOIN `p5_gere_ouvrage` ON 
//        p5_ouvrage.OUV_ID = p5_gere_ouvrage.OUVRAGE_OUV_ID   
//        AND p5_gere_ouvrage.p5_USERS_USER_ID = ? ");
        $req = $db->prepare("SELECT * FROM `p5_ouvrage` 
        INNER JOIN `p5_gere_ouvrage` ON 
        p5_ouvrage.OUV_ID = p5_gere_ouvrage.OUVRAGE_OUV_ID  
        INNER JOIN `p5_statut_liste` ON
        p5_gere_ouvrage.p5_statut_liste_p5_STATUT_ID = p5_statut_liste.p5_STATUT_ID
        AND p5_gere_ouvrage.p5_USERS_USER_ID = ? ");
        $req->execute(array($userId));
        //$books = $req->fetch();
        //return $books
        return $req;
    }

    public function getBooks() {

        $db = $this->dbConnect();
        $req = $db->query('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE FROM p5_ouvrage');

        return $req;
    }

    public function getBooksEnable() {

        $db = $this->dbConnect();
        $req = $db->query('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE FROM p5_ouvrage WHERE OUV_ENABLE=1');

        return $req;
    }

    public function getBook($bookId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE FROM p5_ouvrage WHERE OUV_ID = ?');
        $req->execute(array($bookId));
        $book = $req->fetch();
        $req->closeCursor();
        return $book;
    }

    public function getBookUser($bookId) {
        $db = $this->dbConnect();
//        $req = $db->prepare('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE '
//                . 'FROM p5_ouvrage WHERE OUV_ID = '
//                . 'IN (SELECT p5_gere_ouvrage.OUVRAGE_OUV_ID FROM `p5_gere_ouvrage` WHERE p5_gere_ouvrage.OUVRAGE_OUV_ID = ? '
//                . 'AND p5_gere_ouvrage.p5_USERS_USER_ID = ? '
//                . 'AND p5_gere_ouvrage.p5_STATUT_LISTE_p5_STATUT_ID= '
//                . 'IN(SELECT p5_STATUT_ID FROM p5_STATUT_LISTE WHERE STATUT =?) )');
        $req = $db->prepare(' SELECT * FROM p5_ouvrage INNER JOIN p5_gere_ouvrage ON  p5_ouvrage.OUV_ID=p5_gere_ouvrage.OUVRAGE_OUV_ID  AND p5_ouvrage.OUV_ID= ?  AND p5_gere_ouvrage.p5_USERS_USER_ID = ? INNER JOIN p5_statut_liste ON p5_gere_ouvrage.p5_statut_liste_p5_STATUT_ID=p5_statut_liste.p5_STATUT_ID AND p5_statut_liste.STATUT= ?');
        $req->execute(array($bookId, $_SESSION['userId'], 'ADMINISTRATEUR'));
        $book = $req->fetch();
        $req->closeCursor();
        return $book;
    }
//A proteger seul root
    public function addBook($title, $preface, $subtitle, $description, $keywords) {


        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_ouvrage (OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE) VALUES(?,?,?,?,?,?,?)');
        $req->execute(array($title, $preface, $subtitle, $description, $keywords, 0));
        $req->closeCursor ();
    
    }


    public function delBook($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_ouvrage WHERE OUV_ID = ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor ();
    }
//Suppression de l'ouvrage si l'utilisateur en est administrateur . 
    public function delBookUser($id) {
        $db = $this->dbConnect();
        //$req = $db->prepare("DELETE FROM `p5_ouvrage` WHERE p5_ouvrage.OUV_ID IN (SELECT p5_gere_ouvrage.OUVRAGE_OUV_ID FROM `p5_gere_ouvrage` WHERE p5_gere_ouvrage.OUVRAGE_OUV_ID = ? AND p5_gere_ouvrage.p5_USERS_USER_ID = ? AND p5_gere_ouvrage.p5_STATUT_LISTE_p5_STATUT_ID= IN(SELECT p5_STATUT_ID FROM p5_STATUT_LISTE WHERE STATUT =?) )");
        $req = $db->prepare("DELETE FROM `p5_ouvrage` WHERE p5_ouvrage.OUV_ID IN (SELECT p5_gere_ouvrage.OUVRAGE_OUV_ID FROM `p5_gere_ouvrage` WHERE p5_gere_ouvrage.OUVRAGE_OUV_ID = ? AND p5_gere_ouvrage.p5_USERS_USER_ID = ? AND p5_gere_ouvrage.p5_STATUT_LISTE_p5_STATUT_ID IN(SELECT p5_statut_liste.p5_STATUT_ID from p5_statut_liste WHERE p5_statut_liste.STATUT= ?))");
        $req->execute(array($id, $_SESSION['userId'], 'ADMINISTRATEUR'));
        return $req;
        $req->closeCursor ();
    }
// Modofication de l'ouvrage si l'utilisateur en est asministrateur    
     public function updateBookUser($title, $preface, $subtitle, $description, $keywords, $enable, $id) {

        $db = $this->dbConnect();
        //$req = $db->prepare('UPDATE p5_ouvrage SET  OUV_TITRE=?,OUV_PREFACE=?,OUV_SOUSTITRE=?,OUV_DESCRIPTION=?,OUV_KEYWORDS=?,OUV_ENABLE=? WHERE OUV_ID = IN (SELECT p5_gere_ouvrage.OUVRAGE_OUV_ID FROM `p5_gere_ouvrage` WHERE p5_gere_ouvrage.OUVRAGE_OUV_ID = ? AND p5_gere_ouvrage.p5_USERS_USER_ID = ? AND p5_gere_ouvrage.p5_STATUT_LISTE_STATUT= ? )');
       $req = $db->prepare(' UPDATE p5_ouvrage SET  OUV_TITRE=?,OUV_PREFACE=?,OUV_SOUSTITRE=?,OUV_DESCRIPTION=?,OUV_KEYWORDS=?,OUV_ENABLE=? WHERE OUV_ID  IN (SELECT p5_gere_ouvrage.OUVRAGE_OUV_ID FROM `p5_gere_ouvrage` WHERE p5_gere_ouvrage.OUVRAGE_OUV_ID = ? AND p5_gere_ouvrage.p5_USERS_USER_ID = ? AND p5_gere_ouvrage.p5_STATUT_LISTE_p5_STATUT_ID IN(SELECT p5_statut_liste.p5_STATUT_ID from p5_statut_liste WHERE p5_statut_liste.STATUT=?))');
        $req->execute(array($title, $preface, $subtitle, $description, $keywords, $enable, $id, $_SESSION['userId'], 'ADMINISTRATEUR'));
        $req->closeCursor ();
    }
// Modification de l'ouvrage sans condition 
    public function updateBook($title, $preface, $subtitle, $description, $keywords, $enable, $id) {

        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_ouvrage SET  OUV_TITRE=?,OUV_PREFACE=?,OUV_SOUSTITRE=?,OUV_DESCRIPTION=?,OUV_KEYWORDS=?,OUV_ENABLE=? WHERE OUV_ID= ?');
        $req->execute(array($title, $preface, $subtitle, $description, $keywords, $enable, $id));
        $req->closeCursor ();
    }

    public function enableBook($id) {

        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_ouvrage SET  OUV_ENABLE = 1 WHERE OUV_ID= ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor ();
    }

    public function disableBook($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_ouvrage SET  OUV_ENABLE = 0 WHERE OUV_ID= ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor ();
    }
    
    
}
