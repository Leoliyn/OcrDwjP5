<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES CHAPITRES  LISTE - AJOUTER- MODIFIER- SUPPRIMER -SUPPRIMER -ACTIVER- DESACTIVER
namespace OpenClassrooms\DWJP5\Backend\Model;
require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;

class PostManager extends Manager {
// OK
    //récupération du numéro de chapitre maximum
    public function getMaxChapter($ouvId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT MAX(ART_CHAPTER) FROM `p5_posts` WHERE OUVRAGE_OUV_ID = ?');
        $req->execute(array($ouvId));
        $chapter = $req->fetch();

        return $chapter;
    }
// OK
    //recuperation des posts enable d'un ouvrage $ouvId
    public function getPosts($ouvId) {

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID, ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT, DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE FROM p5_posts WHERE ART_DESACTIVE = ? AND OUVRAGE_OUV_ID = ? ORDER BY ART_CHAPTER DESC ');
        $req->execute(array(0,$ouvId));
        return $req;
    }
// OK
    // recuperation des tous les posts  disable et enable
    public function getPostsResume($ouvId) {
        $db = $this->dbConnect();

        $req = $db->prepare('SELECT ART_ID, LEFT(`ART_CONTENT`,300) AS ART_CONTENT,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,
    DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESACTIVE,ART_IMAGE, COUNT(p5_comments.p5_POSTS_ART_ID) AS NBCOMMENT,p5_ouvrage.OUV_TITRE FROM p5_posts 
    LEFT JOIN p5_comments ON p5_posts.ART_ID = p5_comments.p5_POSTS_ART_ID  INNER JOIN p5_ouvrage ON p5_posts.OUVRAGE_OUV_ID = p5_ouvrage.OUV_ID   WHERE p5_posts.OUVRAGE_OUV_ID = ? group by p5_posts.ART_TITLE ORDER BY ART_CHAPTER DESC ');
        $req->execute(array($ouvId));
        return $req;
    }
    // OK
    // recuperation des tous les posts  disable et enable sans les comm
    public function getPostsResume2($ouvId) {
        $db = $this->dbConnect();

        $req = $db->prepare('SELECT ART_ID, LEFT(`ART_CONTENT`,300) AS ART_CONTENT,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,
    DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESACTIVE,ART_IMAGE FROM p5_posts WHERE OUVRAGE_OUV_ID = ?');
        $req->execute(array($ouvId));
        return $req;
    }
// ok
    public function getPost($postId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT, ART_DESACTIVE,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE FROM p5_posts WHERE ART_ID = ?');
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }
// AFAIRE
    public function addPost($chapter, $title, $subtitle, $content, $description, $keywords,$ouvId) {

        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_posts (ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT,DATE,ART_DESACTIVE,ART_DESCRIPTION,ART_KEYWORDS,OUVRAGE_OUV_ID) VALUES(?,?,?,?,?,?,?,?,?)');
        $req->execute(array($chapter, $title, $subtitle, $content, $date, 1, $description, $keywords,$ouvId));
        $lastId = $db->lastInsertId();
        return $lastId;
    }
// A faire
    public function addPostImg($image) {


        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_posts (ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT,DATE,ART_DESACTIVE,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE) VALUES(?,?,?,?,?,?,?,?,?)');
        $req->execute(array($chapter, $title, $subtitle, $content, $date, 1, $description, $keywords, $image));
        $lastId = $db->lastInsertId();
        return $lastId;
    }
// A FAIRE
    public function delPost($id) {
        $db = $this->dbConnect();
        $req0 = $db->prepare('SELECT ART_IMAGE FROM p5_posts WHERE ART_ID = ?');
        $req0->execute(array($id));
        $image = $req0->fetch();
        //////////Suppression de l'mage associée///////////
        $dossier_traite = "uploads";
        $fichier = $image['ART_IMAGE'];
        $chemin = $dossier_traite . "/" . $fichier; // On définit le chemin du fichier à effacer.
        $repertoire = opendir($dossier_traite);
        if (file_exists($chemin)) {
            if (!is_dir($chemin)) {

                unlink($chemin); // On efface.
            }
        }
        closedir($repertoire);
        $req = $db->prepare('DELETE FROM p5_posts WHERE ART_ID = ?');
        $req2 = $db->prepare('DELETE FROM p5_comments WHERE p5_POSTS_ART_ID = ?');
        $req->execute(array($id));
        $req2->execute(array($id));

        return $req;
    }
//A FAIRE
    public function updatePostImage($image, $id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_IMAGE=? WHERE ART_ID= ?');
        $req->execute(array($image, $id));
    }
// A  FAIRE
    public function updatePost($chapter, $title, $subtitle, $content, $disable, $id, $description, $keywords, $image,$ouvId) {
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE `p5_posts` SET  '
                . 'p5_posts.ART_CHAPTER = ?,'
                . 'p5_posts.ART_TITLE= ?,'
                . 'p5_posts.ART_SUBTITLE= ?,'
                . 'p5_posts.ART_CONTENT= ? , '
                . 'p5_posts.DATE= ?,'
                . 'p5_posts.ART_DESACTIVE = ?,'
                . 'p5_posts.ART_DESCRIPTION= ?,'
                . 'p5_posts.ART_KEYWORDS=?,'
                . 'p5_posts.ART_IMAGE=?,'
                . ' p5_posts.OUVRAGE_OUV_ID = ?'
                . ' WHERE p5_posts.ART_ID = ?');
        $req->execute(array($chapter, $title, $subtitle, $content, $date, $disable, $description, $keywords, $image,$ouvId,$id));
        $lastId = $db->lastInsertId();
        return $req;
    }
// A FAIRE 
    public function enablePost($id) {
        $activ = 0;
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE ART_ID= ?');
        $req->execute(array($activ, $id));
        return $req;
    }
// A FAIRE 
    public function disablePost($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE ART_ID= ?');
        $req->execute(array(1, $id));
        return $req;
    }
// A FAIRE 
    // OBTENION DE LA LISTE DES CHAPITRES SUIVANTS AU CHAPITRE EN REFERENCE 
 public function getSuivants($id) {
     $db = $this->dbConnect();
     $req = $db->prepare('SELECT p5_POSTS_ART_ID_SUIVANT FROM p5_ARBRE_POSTS WHERE p5_POSTS_ART_ID = ? AND ART_DESACTIVE = ?');
     $req->execute(array($id,0));
     $suivant = $req->fetch();
     return $suivant;      
    }
// A FAIRE 
    // OBTENTION DU CHAPITRE PRECEDENT de CHAPITRE ACTUEL 
     public function getPrecedent($id) {
     $db = $this->dbConnect();
      $req = $db->prepare('SELECT p5_POSTS_ART_ID FROM p5_ARBRE_POSTS WHERE p5_POSTS_ART_ID_SUIVANT = ? AND ART_DESACTIVE = ?');
     $req->execute(array($id,0));
     $precedent = $req->fetch();
     return $precedent;          
    }
    
}
