<?php

/**
 *  PROJET 5 DWJ OPENCLASSROOMS   CLAUDEY Lionel 2018
 * 
 * 
 */

namespace Backend;

use Commun\Manager;

require_once('Model/Commun/newManager.php');

/**
 * GESTION DES COMMENTAIRES  LISTE - AJOUTER- MODIFIER- SUPPRIMER -SUPPRIMER -ACTIVER- DESACTIVER -SIGNALER -'DESIGNALER'
 */
class CommentManager extends Manager {

    /**
     * getCommentsPremierNiveau() retourne les commentaires racine (de parent 0)d'un article
     * 
     * 
     * @param type $postId
     * @return type
     */
    public function getCommentsPremierNiveau($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_posts_ART_ID = ? AND p5_comments.COMM_PARENT= ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId, 0));

        return $comments;
    }

    /**
     * getCommentsChild()retourne les commentaires enfant (de parent <> 0)d'un article
     * @param type $postId
     * @return type
     */
    public function getCommentsChild($postId) {
        $db = $this->dbConnect();
        $comments = $db->prepare('SELECT COMM_ID,p5_posts_ART_ID,p5_USERS_USER_ID,COMM_CONTENU,SIGNALE,DISABLE, DATE_FORMAT(COMM_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS COMM_date_fr,p5_users.USER_PSEUDO,COMM_PARENT FROM p5_comments INNER JOIN p5_users ON p5_comments.p5_USERS_USER_ID = p5_users.USER_ID WHERE p5_posts_ART_ID = ? AND p5_comments.COMM_PARENT <> ? ORDER BY COMM_DATE DESC');
        $comments->execute(array($postId, 0));

        return $comments;
    }

    /**
     * disableComment() update la table comment disable à 1 ( met le commentaire hors ligne)
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
     * enableSignal() signale un commentaire (champ signal à 1)
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
     * enableComment() update la table comment disable à 0 ( met le commentaire en ligne)
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
     * disableSignal() désignale un commentaire (champ signal à 0)
     * @param type $commId
     * @return type
     */
    public function disableSignal($commId) {
        $db = $this->dbConnect();
        $reqcomment = $db->prepare('UPDATE p5_comments SET  SIGNALE = ?  WHERE COMM_ID = ?');
        $reqcomment->execute(array(0, $commId));

        return $reqcomment;
    }

}
