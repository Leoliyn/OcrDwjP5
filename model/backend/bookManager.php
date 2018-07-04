<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS  CLAUDEY Lionel Mai 2018      
 *
 * GESTION DES OUVRAGES  
 * 
 */

namespace Backend;

use Commun\Manager;

require_once('Model/Commun/newManager.php');

class BookManager extends Manager {

    /**
     * Liste des ouvrages gerés pour un utilisateur    
     *
     * 
     */
    public function getBooksUser($userId) {
        $db = $this->dbConnect();
        $req = $db->prepare("SELECT * FROM `p5_ouvrage` 
        INNER JOIN `p5_gere_ouvrage` ON 
        p5_ouvrage.OUV_ID = p5_gere_ouvrage.OUVRAGE_OUV_ID  
        INNER JOIN `p5_statut_liste` ON
        p5_gere_ouvrage.p5_statut_liste_p5_STATUT_ID = p5_statut_liste.p5_STATUT_ID
        AND p5_gere_ouvrage.p5_USERS_USER_ID = ? ");
        $req->execute(array($userId));
        return $req;
    }

    /**
     *   getBooks() liste les ouvrages de la table p5_ouvrage
     *
     * 
     */
    public function getBooks() {

        $db = $this->dbConnect();
        $req = $db->query('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE FROM p5_ouvrage');
        return $req;
    }

    /**
     *  getBooksRights($ouvId) renvoi les droits d'accès pour un ouvrage  (user + statut)
     *
     * 
     */
    public function getBooksRights($ouvId) {
        $db = $this->dbConnect();
        $req = $db->prepare(' SELECT * FROM p5_ouvrage INNER JOIN p5_gere_ouvrage ON p5_ouvrage.OUV_ID=p5_gere_ouvrage.OUVRAGE_OUV_ID INNER JOIN p5_statut_liste ON p5_statut_liste.p5_STATUT_ID=p5_gere_ouvrage.p5_statut_liste_p5_STATUT_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_gere_ouvrage.p5_USERS_USER_ID WHERE p5_ouvrage.OUV_ID= ? ');
        $req->execute(array($ouvId));
        return $req;
        $req->closeCursor();
    }

    /**
     *  delBookAcces() supprime un enregistrement de la table gere_ouvrage 
     *
     * $userId id utilisateur
     * $ouvId id ouvrage
     * $statutId id statut 
     * 
     */
    public function delBookAcces($userId, $ouvId, $statutId) {
        $db = $this->dbConnect();
        $req = $db->prepare(' DELETE FROM p5_gere_ouvrage WHERE p5_USERS_USER_ID =? AND OUVRAGE_OUV_ID = ? AND p5_statut_liste_p5_STATUT_ID = ? ');
        $req->execute(array($userId, $ouvId, $statutId));
        return $req;
        $req->closeCursor();
    }

    /**
     *  delBookAcces() ajoute un enregistrement à la table gere_ouvrage 
     *
     * $userId id utilisateur
     * $ouvId id ouvrage
     * $statutId id statut 
     * 
     */
    public function addAccesBook($ouvId, $userId, $statutId) {
        $db = $this->dbConnect();
        $req = $db->prepare(' INSERT INTO p5_gere_ouvrage SET p5_USERS_USER_ID =? , OUVRAGE_OUV_ID = ? , p5_statut_liste_p5_STATUT_ID = ? ');
        $req->execute(array($userId, $ouvId, $statutId));
        return $req;
        $req->closeCursor();
    }

    /**
     *  verifAccesBook() compte le nombre d'enregistrement pour un ouvrage pour un utilisateur dans la table gere ouvrage 
     *
     * $userId id utilisateur
     * $ouvId id ouvrage
     * 
     */
    public function verifAccesBook($ouvId, $userId) {
        $db = $this->dbConnect();
        $acces = $db->prepare(' SELECT COUNT(*) FROM p5_gere_ouvrage WHERE p5_USERS_USER_ID =? AND OUVRAGE_OUV_ID = ?');
        $acces->execute(array($userId, $ouvId));
        $compte = $acces->fetchAll();
        return $compte;
    }

    /**
     *  getBook() renvoi l'enregistrement de la table p5_ouvrage d'un ouvrage depuis son id
     *
     * $bookId id ouvrage
     * 
     */
    public function getBook($bookId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT OUV_ID, OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_IMAGE,OUV_ENABLE FROM p5_ouvrage WHERE OUV_ID = ?');
        $req->execute(array($bookId));
        $book = $req->fetch();
        return $book;
        $req->closeCursor();
    }

    /**
     * getBookUser() retourne un ouvrage pour un utilisateur si administrateur
     *   
     * $bookId id ouvrage
     */
    public function getBookUser($bookId) {
        $db = $this->dbConnect();
        $req = $db->prepare(' SELECT * FROM p5_ouvrage INNER JOIN p5_gere_ouvrage ON  p5_ouvrage.OUV_ID=p5_gere_ouvrage.OUVRAGE_OUV_ID  AND p5_ouvrage.OUV_ID= ?  AND p5_gere_ouvrage.p5_USERS_USER_ID = ? INNER JOIN p5_statut_liste ON p5_gere_ouvrage.p5_statut_liste_p5_STATUT_ID=p5_statut_liste.p5_STATUT_ID AND p5_statut_liste.STATUT= ?');
        $req->execute(array($bookId, $_SESSION['userId'], 'ADMINISTRATEUR'));
        $book = $req->fetch();
        $req->closeCursor();
        return $book;
    }

    /**
     * addBook() insert un enregistrement dans la table p5_ouvrage 
     *   
     * La fonction retourne le derier id utilisé lors de cet enreg 
     */
    public function addBook($title, $preface, $subtitle, $description, $keywords) {


        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_ouvrage (OUV_TITRE,OUV_PREFACE,OUV_SOUSTITRE,OUV_DESCRIPTION,OUV_KEYWORDS,OUV_ENABLE) VALUES(?,?,?,?,?,?)');
        $req->execute(array($title, $preface, $subtitle, $description, $keywords, 0));
        $lastId = $db->lastInsertId();
        return $lastId;
        $req->closeCursor();
    }

    /**
     * delBookImage()supprime le fichier image d'un ouvrage 
     *   
     * recupere le nom du fichier à supprimer dan la table gere_ouvrage
     * puis l'efface du répertoire uploads
     */
    public function delBookImage($id) {
        $db = $this->dbConnect();
        $req0 = $db->prepare('SELECT OUV_IMAGE FROM p5_ouvrage WHERE OUV_ID = ?');
        $req0->execute(array($id));
        $image = $req0->fetch();
        //////////Suppression de l'mage associée///////////
        $dossier_traite = "uploads";
        $fichier = $image['OUV_IMAGE'];
        $chemin = $dossier_traite . "/" . $fichier; // On définit le chemin du fichier à effacer.
        $repertoire = opendir($dossier_traite);
        if (file_exists($chemin)) {
            if (!is_dir($chemin)) {

                unlink($chemin); // On efface.
            }
        }
    }

    /**
     * delBook() supprime un enregistrement d p5_ouvrage depuis un id
     * 
     * 
     * 
     * @param type $id
     * @return type
     */
    public function delBook($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_ouvrage WHERE OUV_ID = ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor();
    }

    /**
     * Suppression de l'ouvrage si l'utilisateur en est administrateur 
     * 
     * 
     * 
     * @param type $id
     * @return type
     */
    public function delBookUser($id) {
        $db = $this->dbConnect();
        $req = $db->prepare("DELETE FROM `p5_ouvrage` WHERE p5_ouvrage.OUV_ID IN (SELECT p5_gere_ouvrage.OUVRAGE_OUV_ID FROM `p5_gere_ouvrage` WHERE p5_gere_ouvrage.OUVRAGE_OUV_ID = ? AND p5_gere_ouvrage.p5_USERS_USER_ID = ? AND p5_gere_ouvrage.p5_STATUT_LISTE_p5_STATUT_ID IN(SELECT p5_statut_liste.p5_STATUT_ID from p5_statut_liste WHERE p5_statut_liste.STATUT= ?))");
        $req->execute(array($id, $_SESSION['userId'], 'ADMINISTRATEUR'));
        return $req;
        $req->closeCursor();
    }

    /**
     * updateBookUser() Modification ouvrage si l'utilisateur en est asministrateur 
     * 
     * 
     * @param type $title
     * @param type $preface
     * @param type $subtitle
     * @param type $description
     * @param type $keywords
     * @param type $enable
     * @param type $id
     * @param type $image
     */
    public function updateBookUser($title, $preface, $subtitle, $description, $keywords, $enable, $id, $image) {

        $db = $this->dbConnect();
        $req = $db->prepare(' UPDATE p5_ouvrage SET  OUV_TITRE=?,OUV_PREFACE=?,OUV_SOUSTITRE=?,OUV_DESCRIPTION=?,OUV_KEYWORDS=?,OUV_IMAGE=?,OUV_ENABLE=? WHERE OUV_ID  IN (SELECT p5_gere_ouvrage.OUVRAGE_OUV_ID FROM `p5_gere_ouvrage` WHERE p5_gere_ouvrage.OUVRAGE_OUV_ID = ? AND p5_gere_ouvrage.p5_USERS_USER_ID = ? AND p5_gere_ouvrage.p5_STATUT_LISTE_p5_STATUT_ID IN(SELECT p5_statut_liste.p5_STATUT_ID from p5_statut_liste WHERE p5_statut_liste.STATUT=?))');
        $req->execute(array($title, $preface, $subtitle, $description, $keywords, $image, $enable, $id, $_SESSION['userId'], 'ADMINISTRATEUR'));
        $req->closeCursor();
    }

    /**
     * Modification de l'ouvrage sans condition 
     * 
     * 
     * @param type $title
     * @param type $preface
     * @param type $subtitle
     * @param type $description
     * @param type $keywords
     * @param type $enable
     * @param type $id
     * @param type $image
     */
    public function updateBook($title, $preface, $subtitle, $description, $keywords, $enable, $id, $image) {

        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_ouvrage SET  OUV_TITRE=?,OUV_PREFACE=?,OUV_SOUSTITRE=?,OUV_DESCRIPTION=?,OUV_KEYWORDS=?,OUV_IMAGE=?,OUV_ENABLE=? WHERE OUV_ID= ?');
        $req->execute(array($title, $preface, $subtitle, $description, $keywords, $image, $enable, $id));
        $req->closeCursor();
    }

    /**
     * enableBook() update p5_ouvrage enable pour un ouvrage depuis son id
     * @param type $id
     * @return type
     */
    public function enableBook($id) {

        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_ouvrage SET  OUV_ENABLE = 1 WHERE OUV_ID= ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor();
    }

    /**
     * disableBook() update p5_ouvrage enable à 0 pour un ouvrage depuis son id
     * @param type $id
     * @return type
     */
    public function disableBook($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_ouvrage SET  OUV_ENABLE = 0 WHERE OUV_ID= ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor();
    }

}
