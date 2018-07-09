<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS     
 * CLAUDEY Lionel  2018  
 * GESTION DES COMMENTAIRES  LISTE - AJOUTER- SIGNALEMENT - DESIGNALEMENT
 */

namespace Frontend;

require_once('Model/Commun/newManager.php');

use Commun\Manager;

class CommentManager extends Manager {

    /**
     * Ajoute un commentaire à la table 
     * @param type $postId 
     * @param type $author
     * @param type $comment
     * @param type $commParent
     * @return type
     */
    public function addComment($postId, $author, $comment, $commParent) {
        $db = $this->dbConnect();
        $comments = $db->prepare('INSERT INTO p5_comments(p5_POSTS_ART_ID, p5_USERS_USER_ID, COMM_CONTENU, COMM_DATE,COMM_PARENT) VALUES(?, ?, ?, NOW(),?)');
        $affectedLines = $comments->execute(array($postId, $author, $comment, $commParent));
        return $affectedLines;
    }

    /**
     * Désactive le comentaire DISABLE =1
     * @param type $commId
     * @return type
     */
    public function disableComment($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  DISABLE = ?  WHERE COMM_ID= ?');
        $reqcomment->execute(array(1, $commId));

        return $reqcomment;
    }

    /**
     * Signal  le commentaire 
     * @param type $commId
     * @return type
     */
    public function enableSignal($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  SIGNALE = ?  WHERE COMM_ID =  ?');
        $reqcomment->execute(array(1, $commId));

        return $reqcomment;
    }

    /**
     * Active le commentaire 
     * @param type $commId
     * @return type
     */
    public function enableComment($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  DISABLE = ?  WHERE COMM_ID = ?');
        $reqcomment->execute(array(0, $commId));

        return $reqcomment;
    }

    /**
     * "designale" le commentaire SIGNAL =0
     * @param type $commId
     * @return type
     */
    public function disableSignal($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  SIGNALE = ?  WHERE COMM_ID = ?');
        $reqcomment->execute(array(0, $commId));

        return $reqcomment;
    }

// uniquement les comm enable 
    /**
     * récupère les commentaires racine ( parent =0) non disable , récupere les données users (auteur)
     * @param type $postId
     * @return type
     */
    public function getCommentsPremierNiveau($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_posts_ART_ID = ? AND p5_comments.COMM_PARENT= ? AND DISABLE = ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId, 0, 0));
        return $comments;
    }

    /**
     * getCommentsChild()retourne les commentaires enfant (de parent <> 0 et disable =0)d'un article
     * @param type $postId
     * @return type
     */
    public function getCommentsChild($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_posts_ART_ID = ? AND p5_comments.COMM_PARENT <> ? AND DISABLE = ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId, 0, 0));

        return $comments;
    }

}
