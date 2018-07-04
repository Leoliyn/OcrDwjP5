<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS    CLAUDEY Lionel 2018  
 * GESTION DES VOTES
 */

namespace Backend;

use Commun\Manager;

require_once('Model/Commun/newManager.php');

class VoteManager extends Manager {

    /**
     * Retourne le nombre d'enregistrement trouvé dans p5_vote_score pour l'id du vote en parametre et l'id de l'utilisateur connecté 
     * @param type $voteId
     * @return type
     */
    public function voteUnique($voteId) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_vote_score WHERE p5_vote_score.p5_USERS_USER_ID = ? AND p5_vote_score.p5_votes_VOTE_ID= ? ');
        $vote = $req->execute(array($_SESSION['userId'], $voteId));
        $nbEnreg = $req->rowCount();
        return $nbEnreg;
    }

    /**
     * Ajoute le vote d'un utilisateur (connecté) , Yes ou NO pour un vote dont l'id est en paramètre
     * @param type $voix
     * @param type $voteId
     */
    public function addVote($voix, $voteId) {
        $db = $this->dbConnect();
        $date = (new \DateTime())->format('Y-m-d H:i:s');
        if ($voix == 'YES') {
            $req = $db->prepare('INSERT INTO `p5_vote_score`(`POSTS_SCORE_YES`, `POSTS_SCORE_NO`, `POSTS_SCORE_DATE`, `p5_USERS_USER_ID`, `p5_votes_VOTE_ID`) VALUES (?,?,?,?,?)');
            $vote = $req->execute(array(1, 0, $date, $_SESSION['userId'], $voteId));
        } elseif ($voix == 'NO') {
            $req = $db->prepare('INSERT INTO `p5_vote_score`(`POSTS_SCORE_YES`, `POSTS_SCORE_NO`, `POSTS_SCORE_DATE`, `p5_USERS_USER_ID`, `p5_votes_VOTE_ID`) VALUES (?,?,?,?,?)');
            $vote = $req->execute(array(0, 1, $date, $_SESSION['userId'], $voteId));
        }
    }

    /**
     * Supprime un vote dans p5_vote ( supprimera les scores de la table p5_vote_score)
     * @param type $vote_id
     * @return type
     */
    public function delVote($vote_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_votes WHERE VOTE_ID = ?');
        $vote = $req->execute(array($vote_id));
        return $req;
    }

    /**
     * récupère la liste des votes dan sla table p5_vote
     * @return type
     */
    public function listStatutVote() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT VOTE_ID,VOTE_OUVERT,p5_posts_ART_ID FROM p5_votes ');
        $listStatutVote = $req->execute();
        return $req;
    }

    /**
     * retourne la liste des scrutins  ouverts le contenu de la suite , le chapitre précedent (numero chapitre , titre), l'auteur 
     * @return type
     */
    public function voteList() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *,p5_posts.ART_PRECEDENT AS LE_PRECEDENT,p5_posts.ART_CONTENT AS CONTENU_SUITE,tposts.ART_TITLE AS TITRE_PRECEDENT,tposts.ART_CHAPTER AS NUM_CHAPITRE FROM p5_votes INNER JOIN p5_posts ON p5_votes.p5_posts_ART_ID=p5_posts.ART_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR INNER JOIN p5_posts as TP ON p5_posts.ART_PRECEDENT=TP.ART_ID  INNER JOIN p5_posts AS tposts ON p5_posts.ART_PRECEDENT=tposts.ART_ID WHERE p5_votes.VOTE_OUVERT=  ? GROUP BY VOTE_ID');
        $vote = $req->execute(array(1));
        return $req;
    }

    /**
     * retourne la liste des scrutins  fermés le contenu de la suite , le chapitre précedent (numero chapitre , titre), l'auteur 
     * @return type
     */
    public function voteListClose() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *,p5_posts.ART_PRECEDENT AS LE_PRECEDENT,p5_posts.ART_CONTENT AS CONTENU_SUITE,tposts.ART_TITLE AS TITRE_PRECEDENT,tposts.ART_CHAPTER AS NUM_CHAPITRE FROM p5_votes INNER JOIN p5_posts ON p5_votes.p5_posts_ART_ID=p5_posts.ART_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR INNER JOIN p5_posts as TP ON p5_posts.ART_PRECEDENT=TP.ART_ID  INNER JOIN p5_posts AS tposts ON p5_posts.ART_PRECEDENT=tposts.ART_ID WHERE p5_votes.VOTE_OUVERT=  ? GROUP BY VOTE_ID');
        $vote = $req->execute(array(0));
        return $req;
    }

    /**
     * récupère l'id du vote correspondant à l'id de la suite passé en paramètre et vote ouvert
     * @param type $suite_id
     * @return type
     */
    public function quelVote($suite_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT p5_votes.VOTE_ID FROM p5_votes WHERE p5_votes.p5_posts_ART_ID = ? AND p5_votes.VOTE_OUVERT= ?');
        $vote = $req->execute(array($suite_id, 1));
        $voteId = $req->fetch();
        return $voteId['VOTE_ID'];
    }

    /**
     * récupère les scores de chaque vote (somme de yes et somme de no)
     * @return type
     */
    public function lesScores() {
        $db = $this->dbConnect();
        // $req = $db->query('SELECT p5_votes_VOTE_ID,SUM(POSTS_SCORE_YES) AS JAIME,SUM(POSTS_SCORE_NO) AS JAIMEPAS FROM p5_vote_score GROUP BY p5_votes_VOTE_ID');  
        $req = $db->query('SELECT p5_votes_VOTE_ID,SUM(POSTS_SCORE_YES) AS JAIME,SUM(POSTS_SCORE_NO) AS JAIMEPAS,p5_votes.p5_posts_ART_ID AS SUITE_ID FROM p5_vote_score INNER JOIN p5_votes ON p5_vote_score.p5_votes_VOTE_ID= p5_votes.VOTE_ID GROUP BY p5_votes_VOTE_ID');
        return $req;
    }

    /**
     * Supprime les votes d'un scrutin passé en paramètre 
     * @param type $voteId
     * @return type
     */
    public function delScores($voteId) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM `p5_vote_score` WHERE p5_votes_VOTE_ID = ?');
        $delScore = $req->execute(array($voteId));
        return $req;
    }

    /**
     * Modifie la date de fin d'un scrutin du nombre de jours passé en paramètre 
     * 
     * Calcul la nouvel date de fin - update p5_votes nouvelle date de fin du vote dont l'id est en paramètre 
     * 
     * @param type $vote_id
     * @param type $dateFin
     * @param type $duree
     * @return type
     */
    public function updateVoteDuree($vote_id, $dateFin, $duree) {
        $db = $this->dbConnect();
        $reqDateFin = $db->prepare('SELECT DATE_ADD((SELECT p5_votes.VOTE_DATEFIN FROM `p5_votes` WHERE p5_votes.VOTE_ID =?), INTERVAL ? DAY) ');
        $result = $reqDateFin->execute(array($vote_id, $duree));
        $date = $reqDateFin->fetchAll();
        // recuperation de la date mofifiée
        echo $date[0][0];
        $req = $db->prepare('UPDATE `p5_votes` SET `VOTE_DATEFIN`=? WHERE p5_votes.VOTE_ID=?');
        $updateVote = $req->execute(array($date[0][0], $vote_id));
        return $date;
    }

    /**
     * Compte le nombre de vote ouvert pour un chapitre 
     * @param type $art_id
     * @return type
     */
    public function getCountVote($art_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT * FROM p5_votes WHERE p5_posts_ART_ID = ? AND p5_votes.VOTE_OUVERT= ?');
        $vote = $req->execute(array($art_id, 1));
        $count = $req->rowCount();
        return $count;
    }

    /**
     * fermeture d'un vote VOTE_OUVERT =0
     * @param type $vote_id
     * @return type
     */
    public function fermetureVote($vote_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_votes SET p5_votes.VOTE_OUVERT= ? WHERE p5_votes.VOTE_ID = ?  ');
        $vote = $req->execute(array(0, $vote_id,));
        return $vote;
    }

    /**
     * Ouverture d'un vote VOTE_OUVERT=1
     * @param type $vote_id
     * @return type
     */
    public function ouvertureVote($vote_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE p5_votes SET p5_votes.VOTE_OUVERT= ? WHERE p5_votes.VOTE_ID = ?   ');
        $vote = $req->execute(array(1, $vote_id,));
        return $vote;
    }

    /**
     * Ajoute un vote à la table p5_votes
     * 
     * Date début = maintenant date de fin calculé à + 14 jours  appel de getCountVote et si aucun vote ouvert n'existe pour cette suite
     * execute la requête 
     * 
     * @param type $id
     */
    public function vote($id) {
        $db = $this->dbConnect();
        $vote = $db->prepare('INSERT INTO p5_votes(VOTE_DATEDEBUT,VOTE_DATEFIN,VOTE_OUVERT,p5_posts_ART_ID) VALUES(?,?,?,?)');
        $dateDebut = (new \DateTime())->format('Y-m-d H:i:s');
        $dateFin = (new \DateTime('now +14 day'))->format('Y-m-d H:i:s');
        $nb = $this->getCountVote($id);
        if ($nb == 0) {
            $affectedLines = $vote->execute(array($dateDebut, $dateFin, 1, $id));
        }

        $vote->closeCursor();
    }

// 
    /**
     * Si la date de fin du vote est dépassée mferme le vote et le statut du post à termine
     * @return type
     */
    public function controleVote() {
        $db = $this->dbConnect();
        $req = $db->prepare('UPDATE `p5_votes` '
                . 'INNER JOIN `p5_posts` ON p5_votes.p5_posts_ART_ID = p5_posts.ART_ID '
                . 'INNER JOIN `p5_statut_post` ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID '
                . 'SET p5_votes.VOTE_OUVERT= ? , p5_posts.p5_statut_post_STATUT_POST_ID = (SELECT STATUT_POST_ID FROM `p5_statut_post` WHERE STATUT_POST_LIBELLE=?) WHERE p5_votes.VOTE_DATEFIN < now() AND p5_votes.VOTE_OUVERT= ?  ');
        $vote = $req->execute(array(0, 'TERMINE', 1));

        return $req;
    }

}
