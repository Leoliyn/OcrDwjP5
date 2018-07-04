<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS
 * CLAUDEY Lionel  2018     
 * GESTION DES CHAPITRES LISTE 
 */

namespace Frontend;

require_once('Model/Commun/newManager.php');

use Commun\Manager;

class PostManager extends Manager {

    /**
     * récupère les posts d'un ouvrage qui sont enable et de precedent à 0 (non une suite )
     * @param type $ouvId
     * @return type
     */
    public function getPostsResume($ouvId) {

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID, ART_CHAPTER,ART_TITLE,ART_SUBTITLE,SUBSTRING(ART_CONTENT,1,300) AS ART_CONTENT_RESUME, DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE FROM p5_posts WHERE ART_DESACTIVE = ? AND OUVRAGE_OUV_ID =? AND ART_PRECEDENT = ? ORDER BY ART_CHAPTER DESC ');
        $req->execute(array(0, $ouvId, 0));
        return $req;
    }

    /**
     * Récupère le chapitre enable dont l'id est en paramètre
     * @param type $postId
     * @return type
     */
    public function getPost($postId) {

        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT, ART_DESACTIVE,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE FROM p5_posts WHERE ART_ID = ? AND ART_DESACTIVE = ?');
        $req->execute(array($postId, 0));
        $post = $req->fetch();
        return $post;
        $req->closeCursor();
    }

    /**
     * Ajoute un enregistrement a la table p5_vistes_post 
     * 
     * insertion de la date de la visite , de l'id du post, de l'id de session 
     * 
     * @param type $session_id
     * @param type $post_id
     * @return type
     */
    public function addVisitPost($session_id, $post_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT INTO p5_visites_posts (`VISITES_DATE`, `p5_POSTS_ART_ID`, `SESSION_ID`) VALUES (NOW(),?,?)');
        $req->execute(array($post_id, $session_id));
        return $req;
        $req->closeCursor();
    }

    /**
     * retourne le nombre d'enregistrement (visites ) pour le post dont l'id est en paramètre et dont l'id de session est en paramètre dans les 5 minutes.
     * @param type $session_id
     * @param type $post_id
     * @return type
     */
    public function verifVisitPost($session_id, $post_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT COUNT(VISITES_ID)FROM `p5_visites_posts` WHERE (p5_visites_posts.VISITES_DATE > DATE_SUB(NOW(), INTERVAL 5 MINUTE)) AND p5_visites_posts.SESSION_ID = ? AND p5_POSTS_ART_ID= ?');
        $req->execute(array($session_id, $post_id));
        $nbVisit = $req->fetchAll();
        return $nbVisit;
        $req->closeCursor();
    }

}
