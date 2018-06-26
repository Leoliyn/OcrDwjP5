<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES COMMENTAIRES  LISTE - AJOUTER- MODIFIER- SUPPRIMER -SUPPRIMER -ACTIVER- DESACTIVER -SIGNALER -'DESIGNALER'


namespace Backend;
use Commun\Manager;
require_once('Model/Commun/newManager.php');
class CommentManager extends Manager {

    public function getComments($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_posts_ART_ID = ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId));

        return $comments;
    }
    
    ///////////////FONCTIONS AFFICHAGE  COMMENTAIRE ET ENFANTS
    public function getCommentsPremierNiveau($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_posts_ART_ID = ? AND p5_comments.COMM_PARENT= ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId,0));

        return $comments;
    }
     public function getCommentsChild($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_posts_ART_ID = ? AND p5_comments.COMM_PARENT <> ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId,0));

        return $comments;
    }
     public function getCommentsNiveau($postId,$niveau) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_posts_ART_ID = ? AND p5_comments.COMM_PARENT= ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId,$niveau));
        
        return $comments;
    }
    public function getCommentChild($commIdParent){
     $db = $this->dbConnect();
        $commentChild = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_comments.COMM_PARENT= ? ORDER BY COMM_DATE DESC');
         $commentChild->execute(array($commIdParent));
        return  $commentChild;   
    }

    public function addComment($postId, $author, $comment,$commParent) {
        $db = $this->dbConnect();
        $comments = $db->prepare('INSERT INTO p5_comments(p5_POSTS_ART_ID, p5_USERS_USER_ID, COMM_CONTENU, COMM_DATE,COMM_PARENT) VALUES(?, ?, ?, NOW()),?');
        $affectedLines = $comments->execute(array($postId, $author, $comment,$commParent));

        return $affectedLines;
    }

    public function getComment($commentId) {
        $db = $this->dbConnect();
        $comment = $db->prepare('SELECT id, author, comment, DATE_FORMAT(comment_date, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM p5_comments WHERE id = ? ORDER BY comment_date DESC');
        $comment->execute(array($commentId));

        return $comment;
    }

    public function disableComment($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  DISABLE = ?  WHERE COMM_ID= ?');
        $reqcomment->execute(array(1, $commId));

        return $reqcomment;
    }

    public function enableSignal($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  SIGNALE = ?  WHERE COMM_ID =  ?');
        $reqcomment->execute(array(1, $commId));

        return $reqcomment;
    }

    public function enableComment($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  DISABLE = ?  WHERE COMM_ID = ?');
        $reqcomment->execute(array(0, $commId));

        return $reqcomment;
    }

    public function disableSignal($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  SIGNALE = ?  WHERE COMM_ID = ?');
        $reqcomment->execute(array(0, $commId));

        return $reqcomment;
    }

}
