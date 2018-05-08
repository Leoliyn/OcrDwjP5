<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES COMMENTAIRES  LISTE - AJOUTER- MODIFIER- SUPPRIMER -SUPPRIMER -ACTIVER- DESACTIVER -SIGNALER -'DESIGNALER'
namespace OpenClassrooms\DWJP5\Backend\Model;
require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;

class CommentManager extends Manager {

    public function getComments($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_POSTS_ART_ID,p5_USERS_USER_ID,COMM_TITRE,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_POSTS_ART_ID = ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId));

        return $comments;
    }

    public function addComment($postId, $author, $comment) {
        $db = $this->dbConnect();
        $comments = $db->prepare('INSERT INTO p5_comments(post_id, author, comment, comment_date) VALUES(?, ?, ?, NOW())');
        $affectedLines = $comments->execute(array($postId, $author, $comment));

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
