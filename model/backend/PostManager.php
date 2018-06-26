<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES CHAPITRES  LISTE - AJOUTER- MODIFIER- SUPPRIMER -SUPPRIMER -ACTIVER- DESACTIVER
namespace Backend;
use Commun\Manager;
require_once('Model/Commun/newManager.php');


class PostManager extends Manager {

    //récupération du numéro de chapitre maximum
    public function getMaxChapter($ouvId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT MAX(ART_CHAPTER) FROM `p5_posts` WHERE OUVRAGE_OUV_ID = ? AND ART_PRECEDENT = ?');
        $req->execute(array($ouvId,0));
        $chapter = $req->fetch();

        return $chapter;
    }
  
    

    //recuperation des posts enable d'un ouvrage $ouvId
    public function getPosts($ouvId) {

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID, ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT, DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE,ART_AUTEUR,p5_statut_post_STATUT_POST_ID FROM p5_posts WHERE ART_DESACTIVE = ? AND OUVRAGE_OUV_ID = ?  AND ART_PRECEDENT = ? ORDER BY ART_CHAPTER DESC ');
        $req->execute(array(0,$ouvId,0));
        return $req;
    }

    // recuperation des tous les posts  disable et enable
    public function getPostsResume($ouvId) {
        $db = $this->dbConnect();

      $req = $db->prepare('SELECT ART_ID, LEFT(`ART_CONTENT`,300) AS ART_CONTENT,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,
    DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESACTIVE,ART_IMAGE,ART_AUTEUR,p5_statut_post_STATUT_POST_ID,
    COUNT(p5_comments.p5_posts_ART_ID) AS NBCOMMENT,p5_ouvrage.OUV_TITRE,USER_PSEUDO,STATUT_POST_LIBELLE,ART_PRECEDENT FROM p5_posts 
    LEFT JOIN p5_comments ON p5_posts.ART_ID = p5_comments.p5_posts_ART_ID INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_ouvrage ON p5_posts.OUVRAGE_OUV_ID = p5_ouvrage.OUV_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR  WHERE p5_posts.OUVRAGE_OUV_ID = ?  AND p5_posts.ART_PRECEDENT = ? group by p5_posts.ART_TITLE ORDER BY ART_CHAPTER DESC ');
          
        $req->execute(array($ouvId,0));
        return $req;
    }

    // recuperation des tous les posts  disable et enable sans les comm
    public function getPostsResume2($ouvId) {
        $db = $this->dbConnect();

        $req = $db->prepare('SELECT ART_ID, LEFT(`ART_CONTENT`,300) AS ART_CONTENT,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,
    DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESACTIVE,ART_IMAGE,ART_AUTEUR, p5_statut_post_STATUT_POST_ID ,p5_ouvrage.OUV_TITRE,USER_PSEUDO,STATUT_POST_LIBELLE FROM p5_posts INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_ouvrage ON p5_posts.OUVRAGE_OUV_ID = p5_ouvrage.OUV_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR WHERE OUVRAGE_OUV_ID = ?  AND p5_posts.ART_PRECEDENT = ? ');
        $req->execute(array($ouvId,0));
        return $req;
    }
    public function getPostsSuite($ouvId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID, LEFT(`ART_CONTENT`,300) AS ART_CONTENT,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,'
                . 'ART_DESACTIVE,ART_IMAGE,ART_AUTEUR, p5_statut_post_STATUT_POST_ID ,p5_ouvrage.OUV_TITRE,USER_PSEUDO,STATUT_POST_LIBELLE,ART_PRECEDENT FROM p5_posts INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_ouvrage ON p5_posts.OUVRAGE_OUV_ID = p5_ouvrage.OUV_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR WHERE OUVRAGE_OUV_ID = ?  AND p5_posts.ART_PRECEDENT <> ? ');
        $req->execute(array($ouvId,0));
        return $req;
    }
    public function getPost($postId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT,ART_PRECEDENT, ART_DESACTIVE,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE, ART_AUTEUR, p5_statut_post_STATUT_POST_ID,USER_PSEUDO,STATUT_POST_LIBELLE FROM p5_posts INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR  WHERE ART_ID = ?');
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }
  public function getSuite($suiteId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID,ART_CONTENT, ART_DESACTIVE,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr, ART_AUTEUR,ART_PRECEDENT, p5_statut_post_STATUT_POST_ID,USER_PSEUDO,STATUT_POST_LIBELLE FROM p5_posts INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR  WHERE ART_ID = ?');
        $req->execute(array($suiteId));
        $suite = $req->fetch();

        return $suite;
    }
//    public function addPost($chapter, $title, $subtitle, $content, $description, $keywords,$ouvId,$auteur) {
//
//        $date = (new \DateTime())->format('Y-m-d H:i:s');
//        $db = $this->dbConnect();
//        $req = $db->prepare('INSERT into p5_posts (ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT,DATE,ART_DESACTIVE,ART_DESCRIPTION,ART_KEYWORDS,OUVRAGE_OUV_ID,ART_AUTEUR) VALUES(?,?,?,?,?,?,?,?,?,?)');
//        $req->execute(array($chapter, $title, $subtitle, $content, $date, 1, $description, $keywords,$ouvId,$auteur));
//        $lastId = $db->lastInsertId();
//        return $lastId;
//    }

    // Renvoi l id du statut d'un post
//    public function idStatutPost($statut_post){
//     $db = $this->dbConnect();
//     $req = $db->prepare('SELECT STATUT_POST_ID FROM p5_statut_post WHERE STATUT_POST_LIBELLE = ?');
//     $req->execute(array($statut_post));
//     $idStatut = $req->fetch();
//     
//     return $idStatut;  
//    }
       // Renvoi le libelle du statut  depuis un id
    public function libelleStatutPost($statut_id){
     $db = $this->dbConnect();
     $req = $db->prepare('SELECT STATUT_POST_LIBELLE FROM p5_statut_post WHERE STATUT_POST_ID = ?');
     $req->execute(array($statut_id));
     $libelleStatut = $req->fetch();
     
     return $libelleStatut;  
    }
    
    // Renvoi l'id d'un statut depuis un libellé
    public function idStatut($libelleStatut){
     $db = $this->dbConnect();  
     $req = $db->prepare('SELECT STATUT_POST_ID FROM p5_statut_post WHERE  STATUT_POST_LIBELLE = ?');
     $req->execute(array($libelleStatut));
     $idStatut = $req->fetch();
     
     return $idStatut;  
    }
     
     
    // change le statut du post ou de la suite
    
        public function changeStatutPost($idStatut, $id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE `p5_posts` SET `p5_statut_post_STATUT_POST_ID`= ? WHERE `ART_ID`= ?');
        $req->execute(array($idStatut, $id));
        
        return $req;
    }
    
    
// le champ ART_PRECEDENT sera mis à 0 par défaut 
    public function addPost($chapter, $title, $subtitle, $content, $description, $keywords,$ouvId,$auteur,$statutId) {

        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_posts (ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT,DATE,ART_DESACTIVE,ART_DESCRIPTION,ART_KEYWORDS,OUVRAGE_OUV_ID,ART_AUTEUR,p5_statut_post_STATUT_POST_ID) VALUES(?,?,?,?,?,?,?,?,?,?,?)');
        $req->execute(array($chapter, $title, $subtitle, $content, $date, 1, $description, $keywords,$ouvId,$auteur,$statutId));
        $lastId = $db->lastInsertId();
        return $lastId;
    }
    public function addSuite($content,$ouvId,$precedent,$auteur,$statutId) {

        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_posts (ART_CHAPTER,ART_CONTENT,DATE,ART_DESACTIVE,OUVRAGE_OUV_ID,ART_PRECEDENT,ART_AUTEUR,p5_statut_post_STATUT_POST_ID) VALUES(?,?,?,?,?,?,?,?)');
        $req->execute(array(0,$content, $date, 1,$ouvId,$precedent,$auteur,$statutId));
        $lastId = $db->lastInsertId();
        return $lastId;
    }
    
    
// A faire
    public function addPostImg($image) {


        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_posts (ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT,DATE,ART_DESACTIVE,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE,ART_AUTEUR,p5_statut_post_STATUT_POST_ID) VALUES(?,?,?,?,?,?,?,?,?,?,?');
        $req->execute(array($chapter, $title, $subtitle, $content, $date, 1, $description, $keywords, $image,$auteur,$statutId));
        $lastId = $db->lastInsertId();
        return $lastId;
    }

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
        $req2 = $db->prepare('DELETE FROM p5_comments WHERE p5_posts_ART_ID = ?');
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
    public function updatePost($chapter, $title, $subtitle, $content, $disable, $id, $description, $keywords, $image,$ouvId,$auteur,$statutId) {
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
                . ' p5_posts.OUVRAGE_OUV_ID = ?,'
                . 'p5_posts.ART_AUTEUR= ?,'
                . 'p5_posts.p5_statut_post_STATUT_POST_ID= ? '
                . ' WHERE p5_posts.ART_ID = ?');
        $req->execute(array($chapter, $title, $subtitle, $content, $date, $disable, $description, $keywords, $image,$ouvId, $auteur,$statutId,$id));
        $lastId = $db->lastInsertId();
        return $req;
    }
 public function updateSuite($content, $disable, $id,$ouvId,$precedent,$auteur,$statutId) {
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE `p5_posts` SET  '
                . 'p5_posts.ART_CHAPTER = ?,'
                . 'p5_posts.ART_CONTENT= ? , '
                . 'p5_posts.DATE= ?,'
                . 'p5_posts.ART_DESACTIVE = ?,'
                . ' p5_posts.OUVRAGE_OUV_ID = ?,'
                . 'p5_posts.ART_PRECEDENT= ?,'
                . 'p5_posts.ART_AUTEUR= ?,'
                . 'p5_posts.p5_statut_post_STATUT_POST_ID= ? '
                . ' WHERE p5_posts.ART_ID = ?');
        $req->execute(array(0, $content, $date, $disable,$ouvId, $precedent, $auteur, $statutId, $id));
        $lastId = $db->lastInsertId();
        return $req;
      
    }
public function enablePostsBook($ouvid) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE OUVRAGE_OUV_ID= ?');
        $req->execute(array(0, $ouvid));
        return $req;
    }

    public function disablePostsBook($ouvid) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE OUVRAGE_OUV_ID= ?');
        $req->execute(array(1, $ouvid));
        return $req;
    }
 
    public function enablePost($id) {
        $activ = 0;
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE ART_ID= ?');
        $req->execute(array($activ, $id));
        return $req;
    }

    public function disablePost($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE ART_ID= ?');
        $req->execute(array(1, $id));
        return $req;
    }
    
    //OBTENTION DE LA LISTE DES SUITES PROPOSEES DONT LE CHAPITRE ACTUEL EST LE PRECEDENT
      public function getSuivants($id) {
        $db = $this->dbConnect();
//        $req = $db->prepare('SELECT *,USER_PSEUDO,STATUT_POST_LIBELLE FROM p5_posts INNER JOIN p5_users ON p5_posts.ART_AUTEUR = p5_users.USER_ID INNER JOIN p5_statut_post ON 
//p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID WHERE p5_posts.ART_PRECEDENT = ? ');
        $req = $db->prepare(' SELECT *,USER_PSEUDO,STATUT_POST_LIBELLE FROM p5_posts INNER JOIN p5_statut_post ON p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR WHERE p5_posts.ART_PRECEDENT = ? group by ART_ID  ');
        $req->execute(array($id));
        return $req;
    }
    
    public function getSuivants2($id) {
        $db = $this->dbConnect();
//        $req = $db->prepare('SELECT *,USER_PSEUDO,STATUT_POST_LIBELLE FROM p5_posts INNER JOIN p5_users ON p5_posts.ART_AUTEUR = p5_users.USER_ID INNER JOIN p5_statut_post ON 
//p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID WHERE p5_posts.ART_PRECEDENT = ? ');
        $req = $db->prepare('SELECT *,USER_PSEUDO,STATUT_POST_LIBELLE,SUM(p5_vote_score.POSTS_SCORE_YES)AS JAIME,SUM(p5_vote_score.POSTS_SCORE_NO) AS JAIMEPAS FROM p5_posts INNER JOIN p5_statut_post ON p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN P5_votes ON p5_posts.ART_ID=p5_votes.p5_posts_ART_ID INNER JOIN p5_vote_score ON p5_vote_score.p5_votes_VOTE_ID = p5_votes.VOTE_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR WHERE p5_posts.ART_PRECEDENT = ? group by ART_ID ');
        $req->execute(array($id));
        return $req;
    }

    //OBTENTION DE LA LISTE DES SUITES PROPOSEES DONT LE CHAPITRE ACTUEL EST LE PRECEDENT
     public function getPrecedent($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT p5_posts.ART_PRECEDENT FROM p5_posts WHERE p5_posts.ART_ID = ? ');
        $req->execute(array($id));
        $precedent = $req->fetch();
        return $precedent;
    }

    // OBTENION DE LA LISTE DES CHAPITRES SUIVANTS AU CHAPITRE EN REFERENCE 
// public function getSuivants($id) {
//     $db = $this->dbConnect();
//     $req = $db->prepare('SELECT p5_posts_ART_ID_SUIVANT FROM p5_ARBRE_POSTS WHERE p5_posts_ART_ID = ? AND ART_DESACTIVE = ?');
//     $req->execute(array($id,0));
//     $suivant = $req->fetch();
//     return $suivant;      
//    }

//    // OBTENTION DU CHAPITRE PRECEDENT de CHAPITRE ACTUEL 
//     public function getPrecedent($id) {
//     $db = $this->dbConnect();
//      $req = $db->prepare('SELECT p5_posts_ART_ID FROM p5_ARBRE_POSTS WHERE p5_posts_ART_ID_SUIVANT = ? AND ART_DESACTIVE = ?');
//     $req->execute(array($id,0));
//     $precedent = $req->fetch();
//     return $precedent;          
//    }
    public function concatSuite($auteur,$contenuSuite,$chapitreId) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET p5_posts.ART_CONTENT = CONCAT(ART_CONTENT," ", ? ," ",?) WHERE p5_posts.ART_ID=?');
        $req->execute(array($auteur,$contenuSuite,$chapitreId));
        return $req;
    } 
}
