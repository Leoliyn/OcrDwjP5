<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES COMMENTAIRES  LISTE - AJOUTER- SIGNALEMENT - DESIGNALEMENT
namespace Frontend;

//require_once("model/commun/Manager.php");
//use Model\Commun;
class CommentManager extends Manager {

    public function getComments($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,COMM_ARTID,COMM_PSEUDO,COMM_TITRE,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr FROM p5_comments WHERE COMM_ARTID = ? AND DISABLE= ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId,0));
        return $comments;
    }

    public function addComment($postId, $author, $comment) {
        $db = $this->dbConnect();
        $comments = $db->prepare('INSERT INTO p5_comments(COMM_ARTID, COMM_PSEUDO, COMM_CONTENU, COMM_DATE) VALUES(?, ?, ?, NOW())');
        $affectedLines = $comments->execute(array($postId, $author, $comment));

        return $affectedLines;
    }

    public function getComment($commentId) {
        $db = $this->dbConnect();
        $comment = $db->prepare('SELECT COMM_ID, COMM_PSEUDO, COMM_CONTENU, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS comment_date_fr FROM p5_comments WHERE COMM_ID= ? AND DISABLE= ? ORDER BY comment_date DESC');
        $comment->execute(array($commentId,0));
        return $comment;
    }

    public function enableSignal($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  SIGNALE = ?  WHERE COMM_ID =  ?');
        $reqcomment->execute(array(1, $commId));
        return $reqcomment;
    }

    
}
