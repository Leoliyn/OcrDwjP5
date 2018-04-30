<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES CHAPITRES LISTE 
namespace OpenClassrooms\DWJP5\frontend\Model;
require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;

class PostManager extends Manager {

//recuperation des posts enable
    public function getPostsResume() {

        $db = $this->dbConnect();
        $req = $db->query('SELECT ART_ID, ART_CHAPTER,ART_TITLE,ART_SUBTITLE,SUBSTRING(ART_CONTENT,1,300) AS ART_CONTENT_RESUME, DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE FROM p5_posts WHERE ART_DESACTIVE = 0 ORDER BY ART_CHAPTER DESC ');
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
     $req = $db->prepare('SELECT p5_POSTS_ART_ID_SUIVANT FROM p5_ARBRE_POSTS WHERE p5_POSTS_ART_ID = ? AND ART_DESACTIVE = ?');
     $req->execute(array($id,0));
     $suivant = $req->fetch();
     return $suivant;      
    }
    
     public function getPrecedent($id) {
     $db = $this->dbConnect();
      $req = $db->prepare('SELECT p5_POSTS_ART_ID FROM p5_ARBRE_POSTS WHERE p5_POSTS_ART_ID_SUIVANT = ? AND ART_DESACTIVE = ?');
     $req->execute(array($id,0));
     $precedent = $req->fetch();
     return $precedent;          
    }
}
