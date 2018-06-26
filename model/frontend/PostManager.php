<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES CHAPITRES LISTE 
namespace Frontend;
require_once('Model/Commun/newManager.php');
use Commun\Manager;
class PostManager extends Manager {

//recuperation des posts enable
    public function getPostsResume($ouvId) {

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID, ART_CHAPTER,ART_TITLE,ART_SUBTITLE,SUBSTRING(ART_CONTENT,1,300) AS ART_CONTENT_RESUME, DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE FROM p5_posts WHERE ART_DESACTIVE = ? AND OUVRAGE_OUV_ID =? AND ART_PRECEDENT = ? ORDER BY ART_CHAPTER DESC ');
         $req->execute(array(0,$ouvId,0));
        return $req;
    }

    public function getPost($postId) {

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT, ART_DESACTIVE,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE FROM p5_posts WHERE ART_ID = ? AND ART_DESACTIVE = ?');
        $req->execute(array($postId,0));
        $post = $req->fetch();

        return $post;
    }
    public function getSuivant($id) {
     $db = $this->dbConnect();
     $req = $db->prepare('SELECT p5_posts_ART_ID_SUIVANT FROM p5_ARBRE_POSTS WHERE p5_posts_ART_ID = ? AND ART_DESACTIVE = ?');
     $req->execute(array($id,0));
     $suivant = $req->fetch();
     return $suivant;      
    }
    
     public function getPrecedent($id) {
     $db = $this->dbConnect();
      $req = $db->prepare('SELECT p5_posts_ART_ID FROM p5_ARBRE_POSTS WHERE p5_posts_ART_ID_SUIVANT = ? AND ART_DESACTIVE = ?');
     $req->execute(array($id,0));
     $precedent = $req->fetch();
     return $precedent;          
    }
    
       public function libelleStatutPost($statut_id){
     $db = $this->dbConnect();
     $req = $db->prepare('SELECT STATUT_POST_LIBELLE FROM p5_statut_post WHERE STATUT_POST_ID = ?');
     $req->execute(array($statut_id));
     $libelleStatut = $req->fetch();
     
     return $libelleStatut;  
    }
    
         public function addVisitPost($session_id, $post_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO p5_visites_posts (`VISITES_DATE`, `p5_POSTS_ART_ID`, `SESSION_ID`) VALUES (NOW(),?,?)');
        $req->execute(array($post_id, $session_id));

        return $req;
    }
    public function verifVisitPost($session_id, $post_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(VISITES_ID)FROM `p5_visites_posts` WHERE (p5_visites_posts.VISITES_DATE > DATE_SUB(NOW(), INTERVAL 5 MINUTE)) AND p5_visites_posts.SESSION_ID = ? AND p5_POSTS_ART_ID= ?');
        $req->execute(array($session_id, $post_id));
        $nbVisit = $req->fetchAll();
        return $nbVisit;
    }

}
