<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS  CLAUDEY Lionel Mai 2018      
 *
 *  GESTION DES CHAPITRES  LISTE - AJOUTER- MODIFIER- SUPPRIMER -ACTIVER- DESACTIVER
 * 
 */

namespace Backend;

use Commun\Manager;

require_once('Model/Commun/newManager.php');

class PostManager extends Manager {

    /**
     * getMaxChapter() retourne le numero de chapitre le plus élevé pour un ouvrage . Chapitre uniquement racine (ART_PRECEDENT =0)
     * @param type $ouvId
     * @return type
     */
    public function getMaxChapter($ouvId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT MAX(ART_CHAPTER) FROM `p5_posts` WHERE OUVRAGE_OUV_ID = ? AND ART_PRECEDENT = ?');
        $req->execute(array($ouvId, 0));
        $chapter = $req->fetch();
        return $chapter;
    }

    /**
     * getPostsResume  recuperation des tous les posts  disable et enable en version courte (300 car.)
     * 
     * compte le nombre de commentaire liés au chapitre . recupère le statut du chapitre , l'auteur.
     * 
     * @param type $ouvId
     * @return type
     */
    public function getPostsResume($ouvId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID, LEFT(`ART_CONTENT`,300) AS ART_CONTENT,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,
    DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESACTIVE,ART_IMAGE,ART_AUTEUR,p5_statut_post_STATUT_POST_ID,
    COUNT(p5_comments.p5_posts_ART_ID) AS NBCOMMENT,p5_ouvrage.OUV_TITRE,USER_PSEUDO,STATUT_POST_LIBELLE,ART_PRECEDENT FROM p5_posts 
    LEFT JOIN p5_comments ON p5_posts.ART_ID = p5_comments.p5_posts_ART_ID INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_ouvrage ON p5_posts.OUVRAGE_OUV_ID = p5_ouvrage.OUV_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR  WHERE p5_posts.OUVRAGE_OUV_ID = ?  AND p5_posts.ART_PRECEDENT = ? group by p5_posts.ART_TITLE ORDER BY ART_CHAPTER DESC ');

        $req->execute(array($ouvId, 0));
        return $req;
    }

    /**
     * getPostsSuite récupère les article dont le champ ART_PRECEDENT est <> 0 (donc des Suites )
     * @param type $ouvId
     * @return type
     */
    public function getPostsSuite($ouvId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID, LEFT(`ART_CONTENT`,300) AS ART_CONTENT,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,'
                . 'ART_DESACTIVE,ART_IMAGE,ART_AUTEUR, p5_statut_post_STATUT_POST_ID ,p5_ouvrage.OUV_TITRE,USER_PSEUDO,STATUT_POST_LIBELLE,ART_PRECEDENT FROM p5_posts INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_ouvrage ON p5_posts.OUVRAGE_OUV_ID = p5_ouvrage.OUV_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR WHERE OUVRAGE_OUV_ID = ?  AND p5_posts.ART_PRECEDENT <> ? ');
        $req->execute(array($ouvId, 0));
        return $req;
    }

    /**
     * getPost requête sur un chapitre depuis son Id joint des données de l'auteur et du statut d chapitre
     * 
     * @param type $postId
     * @return type
     */
    public function getPost($postId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID,ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT,ART_PRECEDENT, ART_DESACTIVE,OUVRAGE_OUV_ID,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr,ART_DESCRIPTION,ART_KEYWORDS,ART_IMAGE, ART_AUTEUR, p5_statut_post_STATUT_POST_ID,USER_PSEUDO,STATUT_POST_LIBELLE FROM p5_posts INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR  WHERE ART_ID = ?');
        $req->execute(array($postId));
        $post = $req->fetch();

        return $post;
    }

    /**
     * getSuite requête sur une suite (chapitre sans stitle sans titre art_precedent <> 0) depuis son Id joint des données de l'auteur et du statut d chapitre
     * @param type $suiteId
     * @return type
     */
    public function getSuite($suiteId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT ART_ID,ART_CONTENT, ART_DESACTIVE,DATE_FORMAT(DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS DATE_fr, ART_AUTEUR,ART_PRECEDENT, p5_statut_post_STATUT_POST_ID,USER_PSEUDO,STATUT_POST_LIBELLE FROM p5_posts INNER JOIN p5_statut_post ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_users ON p5_users.USER_ID = p5_posts.ART_AUTEUR  WHERE ART_ID = ?');
        $req->execute(array($suiteId));
        $suite = $req->fetch();

        return $suite;
    }

    /**
     * libelleStatutPost renvoi le libelle du statut dont l'Id est en paramètre
     * 
     * @param type $statut_id
     * @return type
     */
    public function libelleStatutPost($statut_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT STATUT_POST_LIBELLE FROM p5_statut_post WHERE STATUT_POST_ID = ?');
        $req->execute(array($statut_id));
        $libelleStatut = $req->fetch();

        return $libelleStatut;
    }

    /**
     * idStatut Renvoi l'id d'un statut depuis un libellé
     * @param type $libelleStatut
     * @return type
     */
    public function idStatut($libelleStatut) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT STATUT_POST_ID FROM p5_statut_post WHERE  STATUT_POST_LIBELLE = ?');
        $req->execute(array($libelleStatut));
        $idStatut = $req->fetch();

        return $idStatut;
    }

    /**
     * changeStatutPost change le statut du post ou de la suite update table p5_posts
     * 
     * @param type $idStatut
     * @param type $id
     * @return type
     */
    public function changeStatutPost($idStatut, $id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE `p5_posts` SET `p5_statut_post_STATUT_POST_ID`= ? WHERE `ART_ID`= ?');
        $req->execute(array($idStatut, $id));
        return $req;
        $req->closeCursor();
    }

    /**
     * récupère l'id du statut du post dont id est en paramètre
     * @param type $id
     * @return type
     */
    public function statutDuPost($id){
        $db = $this->dbConnect(); 
        $req =$db->prepare('SELECT p5_statut_post_STATUT_POST_ID FROM p5_posts WHERE ART_ID= ?');
        $req->execute(array($id));
        $statutId = $req->fetch();
        return $statutId;
        $req->closeCursor();
    }
    
    /**
     * addPost ajoute un chapitre racine à la table p5_posts.Le nouvel enregistrement est désactivé (ART_DESACTIVE=1) la methode retourne le dernier id utilisé .le champ ART_PRECEDENT sera mis à 0 par défaut 
     * 
     * @param type $chapter
     * @param type $title
     * @param type $subtitle
     * @param type $content
     * @param type $description
     * @param type $keywords
     * @param type $ouvId
     * @param type $auteur
     * @param type $statutId
     * @return type
     */
    public function addPost($chapter, $title, $subtitle, $content, $description, $keywords, $ouvId, $auteur, $statutId) {

        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_posts (ART_CHAPTER,ART_TITLE,ART_SUBTITLE,ART_CONTENT,DATE,ART_DESACTIVE,ART_DESCRIPTION,ART_KEYWORDS,OUVRAGE_OUV_ID,ART_AUTEUR,p5_statut_post_STATUT_POST_ID) VALUES(?,?,?,?,?,?,?,?,?,?,?)');
        $req->execute(array($chapter, $title, $subtitle, $content, $date, 1, $description, $keywords, $ouvId, $auteur, $statutId));
        $lastId = $db->lastInsertId();
        return $lastId;
    }

    
    /**
     * addSuite ajoute une suite  à la table p5_posts . une suite est un chapitre sans titre,soustitre,description ni keywords avec un Art_PRECEDENT <>0 
     * @param type $content
     * @param type $ouvId
     * @param type $precedent
     * @param type $auteur
     * @param type $statutId
     * @return type
     */
    public function addSuite($content, $ouvId, $precedent, $auteur, $statutId) {

        $date = (new \DateTime())->format('Y-m-d H:i:s');
        $db = $this->dbConnect();
        $req = $db->prepare('INSERT into p5_posts (ART_CHAPTER,ART_CONTENT,DATE,ART_DESACTIVE,OUVRAGE_OUV_ID,ART_PRECEDENT,ART_AUTEUR,p5_statut_post_STATUT_POST_ID) VALUES(?,?,?,?,?,?,?,?)');
        $req->execute(array(0, $content, $date, 1, $ouvId, $precedent, $auteur, $statutId));
        $lastId = $db->lastInsertId();
        return $lastId;
    }

    /**
     * delPpost supprime un chapitre depuis son id 
     * 
     * récupere le chemin de l'image du chapitre , efface le fichier image du dossier uploads, 
     * supprime le chapitre de la table et 
     * supprime les commentaires associés de la table comments 
     * 
     * @param type $id
     * @return type
     */
    public function delPost($id) {
        $db = $this->dbConnect();
        $req0 = $db->prepare('SELECT ART_IMAGE FROM p5_posts WHERE ART_ID = ?');
        $req0->execute(array($id));
        $image = $req0->fetch();
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

    /**
     * updatePost met à jour un enregistrement de la table p5_posts depuis son id 
     * @param type $chapter
     * @param type $title
     * @param type $subtitle
     * @param type $content
     * @param type $disable
     * @param type $id
     * @param type $description
     * @param type $keywords
     * @param type $image
     * @param type $ouvId
     * @param type $auteur
     * @param type $statutId
     * @return type
     */
    public function updatePost($chapter, $title, $subtitle, $content, $disable, $id, $description, $keywords, $image, $ouvId, $auteur, $statutId) {
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
        $req->execute(array($chapter, $title, $subtitle, $content, $date, $disable, $description, $keywords, $image, $ouvId, $auteur, $statutId, $id));

        return $req;
    }

    /**
     * updateSuite met à jour un enregistrement de la table p5_posts depuis son id pour une suite
     * @param type $content
     * @param type $disable
     * @param type $id
     * @param type $ouvId
     * @param type $precedent
     * @param type $auteur
     * @param type $statutId
     * @return type
     */
    public function updateSuite($content, $disable, $id, $ouvId, $precedent, $auteur, $statutId) {
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
        $req->execute(array(0, $content, $date, $disable, $ouvId, $precedent, $auteur, $statutId, $id));

        return $req;
    }

    /**
     * Active les chapitres d'un ouvrage  ART_DESACTIVE =0
     * @param type $ouvid
     * @return type
     */
    public function enablePostsBook($ouvid) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE OUVRAGE_OUV_ID= ?');
        $req->execute(array(0, $ouvid));
        return $req;
    }

    /**
     * désactive les chapitres d'un ouvrage  ART_DESACTIVE =1
     * @param type $ouvid
     * @return type
     */
    public function disablePostsBook($ouvid) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE OUVRAGE_OUV_ID= ?');
        $req->execute(array(1, $ouvid));
        return $req;
    }

    /**
     * Active un chapitre depuis son id
     * @param type $id
     * @return type
     */
    public function enablePost($id) {
        $activ = 0;
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE ART_ID= ?');
        $req->execute(array($activ, $id));
        return $req;
    }

    /**
     * Desactive un chapitre depuis son id 
     * @param type $id
     * @return type
     */
    public function disablePost($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET  ART_DESACTIVE = ? WHERE ART_ID= ?');
        $req->execute(array(1, $id));
        return $req;
    }

    /**
     * getSuivants() récupère les suites l'auteur et le statut dont l'id du chapitre precedent est en parametre
     * @param type $id
     * @return type
     */
    public function getSuivants($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_posts INNER JOIN p5_statut_post ON p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR WHERE p5_posts.ART_PRECEDENT = ? group by ART_ID  ');
        $req->execute(array($id));
        return $req;
    }

    /**
     * getSuivants2 recupere les scores jaime et jaimepas le statut, l'auteur le vote  pour chaque suite dont $id est le art_precedent 
     * @param type $id
     * @return type
     */
    public function getSuivants2($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *,USER_PSEUDO,STATUT_POST_LIBELLE,SUM(p5_vote_score.POSTS_SCORE_YES)AS JAIME,SUM(p5_vote_score.POSTS_SCORE_NO) AS JAIMEPAS FROM p5_posts INNER JOIN p5_statut_post ON p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID INNER JOIN P5_votes ON p5_posts.ART_ID=p5_votes.p5_posts_ART_ID INNER JOIN p5_vote_score ON p5_vote_score.p5_votes_VOTE_ID = p5_votes.VOTE_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR WHERE p5_posts.ART_PRECEDENT = ? group by ART_ID ');
        $req->execute(array($id));
        return $req;
    }

    /**
     * Concatene une suite avec le chapitre dont l'id est en parametre 
     * @param type $auteur
     * @param type $contenuSuite
     * @param type $chapitreId
     * @return type
     */
    public function concatSuite($auteur, $contenuSuite, $chapitreId) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_posts SET p5_posts.ART_CONTENT = CONCAT(ART_CONTENT," ", ? ," ",?) WHERE p5_posts.ART_ID=?');
        $req->execute(array($auteur, $contenuSuite, $chapitreId));
        return $req;
    }

    /**
     * Compte le nombre de visite par chapitre . renvoi les données du chapitre et de l'ouvrage 
     * @return type
     */
    public function countVisitPost() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, COUNT(p5_visites_posts.p5_POSTS_ART_ID)AS COMPTEUR FROM `p5_visites_posts` INNER JOIN p5_posts ON p5_posts.ART_ID=p5_visites_posts.p5_POSTS_ART_ID INNER JOIN p5_ouvrage ON p5_ouvrage.OUV_ID=p5_posts.OUVRAGE_OUV_ID WHERE 1 group by p5_visites_posts.p5_POSTS_ART_ID');
        $req->execute(array());
        return $req;
    }

    /**
     * compte le nombre de visite depuis le nombre de jours passé en paramètre
     * @param type $delayDay
     * @return type
     */
    public function countVisitPostSinceDay($delayDay) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, COUNT(p5_visites_posts.p5_POSTS_ART_ID)AS COMPTEUR FROM `p5_visites_posts` INNER JOIN p5_posts ON p5_posts.ART_ID=p5_visites_posts.p5_POSTS_ART_ID INNER JOIN p5_ouvrage ON p5_ouvrage.OUV_ID=p5_posts.OUVRAGE_OUV_ID WHERE (p5_visites_posts.VISITES_DATE > DATE_SUB(NOW(), INTERVAL ? DAY)) group by p5_visites_posts.p5_POSTS_ART_ID');
        $req->execute(array($delayDay));
        return $req;
    }

    /**
     * compte le nombre de visite depuis le nombre d'heures passé en paramètre
     * @param type $delayDay
     * @return type
     */
    public function countVisitPostSinceHour($delayHour) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, COUNT(p5_visites_posts.p5_POSTS_ART_ID) AS COMPTEUR FROM `p5_visites_posts` INNER JOIN p5_posts ON p5_posts.ART_ID=p5_visites_posts.p5_POSTS_ART_ID INNER JOIN p5_ouvrage ON p5_ouvrage.OUV_ID=p5_posts.OUVRAGE_OUV_ID WHERE (p5_visites_posts.VISITES_DATE > DATE_SUB(NOW(), INTERVAL ? HOUR)) group by p5_visites_posts.p5_POSTS_ART_ID');
        $req->execute(array($delayHour));
        return $req;
    }

    /**
     * compte le nombre de visite depuis le nombre de mois passé en paramètre
     * @param type $delayDay
     * @return type
     */
    public function countVisitPostSinceMonth($delayMonth) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *, COUNT(p5_visites_posts.p5_POSTS_ART_ID)AS COMPTEUR FROM `p5_visites_posts` INNER JOIN p5_posts ON p5_posts.ART_ID=p5_visites_posts.p5_POSTS_ART_ID INNER JOIN p5_ouvrage ON p5_ouvrage.OUV_ID=p5_posts.OUVRAGE_OUV_ID WHERE (p5_visites_posts.VISITES_DATE > DATE_SUB(NOW(), INTERVAL ? MONTH)) group by p5_visites_posts.p5_POSTS_ART_ID');
        $req->execute(array($delayMonth));
        return $req;
    }

}
