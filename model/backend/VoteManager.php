<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES MESSAGES
namespace Backend;
use Commun\Manager;
require_once('Model/Commun/newManager.php');
class VoteManager extends Manager {
    
  // idem quelVote
//  public function rechercheVote_id($id){
//   $db = $this->dbConnect();
//  $reqVoteId = $db->prepare('SELECT p5_votes_VOTE_ID FROM p5_vote_score INNER JOIN p5_votes ON p5_votes.VOTE_ID = p5_vote_score.p5_votes_VOTE_ID WHERE (p5_votes.VOTE_OUVERT= ? AND p5_posts_ART_ID= ?) '); 
//  $idVote = $reqVoteId -> execute (array(1,$id));    
//  return   $idVote; 
//  }  
    
 public function voteUnique($voteId) {
  $db = $this->dbConnect();
  $req = $db->prepare('SELECT * FROM p5_vote_score WHERE p5_vote_score.p5_USERS_USER_ID = ? AND p5_vote_score.p5_votes_VOTE_ID= ?'); 
  $vote = $req -> execute (array($_SESSION['userId'],$voteId)); 
  $nbEnreg = $req->rowCount();
  return $nbEnreg;
      
  }
    
  public function aVote ($suite_id){
    $db = $this->dbConnect(); 
    $voteId = $this-> quelVote($suite_id);
    $aVote = $this-> voteUnique($voteId);
    return $aVote;
  }
      
  
      
 
 public function jeVote($voix,$suiteId) {
  $db = $this->dbConnect();
  $voteId = $this-> quelVote($suiteId);
  $dejaVote= $this-> voteUnique ($voteId);
  $date=(new \DateTime())->format('Y-m-d H:i:s');
  if($dejaVote == 0){
  if($voix=='YES'){
      $req = $db->prepare('INSERT INTO `p5_vote_score`(`POSTS_SCORE_YES`, `POSTS_SCORE_NO`, `POSTS_SCORE_DATE`, `p5_USERS_USER_ID`, `p5_votes_VOTE_ID`) VALUES (?,?,?,?,?)'); 
      $vote = $req -> execute (array(1,0,$date,$_SESSION['userId'],$voteId));      
  }elseif($voix=='NO'){
   $req = $db->prepare('INSERT INTO `p5_vote_score`(`POSTS_SCORE_YES`, `POSTS_SCORE_NO`, `POSTS_SCORE_DATE`, `p5_USERS_USER_ID`, `p5_votes_VOTE_ID`) VALUES (?,?,?,?,?)'); 
   $vote = $req -> execute (array(0,1,$date,$_SESSION['userId'],$voteId));        
  }
 
 }
 return $dejaVote;
 }
 
public function delVote($vote_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_votes WHERE VOTE_ID = ?');
        $vote = $req -> execute (array($vote_id));        
        return $req;
    } 
     public function voteList() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *,p5_posts.ART_PRECEDENT AS LE_PRECEDENT,p5_posts.ART_CONTENT AS CONTENU_SUITE,tposts.ART_TITLE AS TITRE_PRECEDENT,tposts.ART_CHAPTER AS NUM_CHAPITRE FROM p5_votes INNER JOIN p5_posts ON p5_votes.p5_posts_ART_ID=p5_posts.ART_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR INNER JOIN p5_posts as TP ON p5_posts.ART_PRECEDENT=TP.ART_ID  INNER JOIN p5_posts AS tposts ON p5_posts.ART_PRECEDENT=tposts.ART_ID WHERE p5_votes.VOTE_OUVERT=  ? GROUP BY VOTE_ID');
        $vote = $req -> execute (array(1));        
        return $req;
    }
 // Ancienne fonction avec score à condition que score existe
// public function voteList2() {
//        $db = $this->dbConnect();
//        $req = $db->prepare('SELECT *,p5_posts.ART_PRECEDENT AS LE_PRECEDENT,tposts.ART_TITLE AS TITRE_PRECEDENT,tposts.ART_CHAPTER AS NUM_CHAPITRE,SUM(p5_vote_score.POSTS_SCORE_YES)AS JAIME,SUM(p5_vote_score.POSTS_SCORE_NO) AS JAIMEPAS  FROM p5_votes INNER JOIN p5_posts ON p5_votes.p5_posts_ART_ID=p5_posts.ART_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR INNER JOIN p5_posts as TP ON p5_posts.ART_PRECEDENT=TP.ART_ID  INNER JOIN p5_vote_score ON p5_vote_score.p5_votes_VOTE_ID = p5_votes.VOTE_ID INNER JOIN p5_posts AS tposts ON p5_posts.ART_PRECEDENT=tposts.ART_ID WHERE p5_votes.VOTE_OUVERT=  ? GROUP BY VOTE_ID');
//        $vote = $req -> execute (array(1));        
//        return $req;
//    }
  // sauvegarde procedure  
// public function voteListClose() {
//        $db = $this->dbConnect();
//        $req = $db->prepare('SELECT *,tposts.ART_TITLE AS TITRE_PRECEDENT,tposts.ART_CHAPTER AS NUM_CHAPITRE,SUM(p5_vote_score.POSTS_SCORE_YES)AS JAIME,SUM(p5_vote_score.POSTS_SCORE_NO) AS JAIMEPAS  FROM p5_votes INNER JOIN p5_posts ON p5_votes.p5_posts_ART_ID=p5_posts.ART_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR INNER JOIN p5_posts as TP ON p5_posts.ART_PRECEDENT=TP.ART_ID  INNER JOIN p5_vote_score ON p5_vote_score.p5_votes_VOTE_ID = p5_votes.VOTE_ID INNER JOIN p5_posts AS tposts ON p5_posts.ART_PRECEDENT=tposts.ART_ID WHERE p5_votes.VOTE_OUVERT=  ? GROUP BY VOTE_ID');
//        $vote = $req -> execute (array(0));        
//        return $req;
//    }
public function voteListClose() {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT *,p5_posts.ART_PRECEDENT AS LE_PRECEDENT,p5_posts.ART_CONTENT AS CONTENU_SUITE,tposts.ART_TITLE AS TITRE_PRECEDENT,tposts.ART_CHAPTER AS NUM_CHAPITRE FROM p5_votes INNER JOIN p5_posts ON p5_votes.p5_posts_ART_ID=p5_posts.ART_ID INNER JOIN p5_users ON p5_users.USER_ID=p5_posts.ART_AUTEUR INNER JOIN p5_posts as TP ON p5_posts.ART_PRECEDENT=TP.ART_ID  INNER JOIN p5_posts AS tposts ON p5_posts.ART_PRECEDENT=tposts.ART_ID WHERE p5_votes.VOTE_OUVERT=  ? GROUP BY VOTE_ID');
        $vote = $req -> execute (array(0));        
        return $req;
    }
// // recuperation idvote   
public function quelVote($suite_id){
  $db = $this->dbConnect();
      $req = $db->prepare('SELECT p5_votes.VOTE_ID FROM p5_votes WHERE p5_votes.p5_posts_ART_ID = ? AND p5_votes.VOTE_OUVERT= ?'); 
      $vote = $req -> execute (array($suite_id,1));   
      $voteId=$req ->fetch();
      return $voteId['VOTE_ID'];
}    

  // compte score yes et no  
//    public function countScore($vote_id){
//  $db = $this->dbConnect();
//      $req = $db->prepare('SELECT COUNT(p5_vote_score.POSTS_SCORE_YES)AS JAIME,COUNT(p5_vote_score.POSTS_SCORE_NO)AS JAIMEPAS FROM p5_vote_score WHERE p5_vote_score.p5_votes_VOTE_ID = ? '); 
//      $vote = $req -> execute (array($vote_id));   
//      if($req->rowCount()){
//      $score = $req -> fetch();
//      }else{
//          $score=0;
//      }
//      return $score;
//} 
//public function countScoreYes($vote_id){
//        $db = $this->dbConnect();
//        $req = $db->prepare('SELECT SUM(POSTS_SCORE_YES) AS JAIME FROM p5_vote_score WHERE p5_votes_VOTE_ID = ? ');
//        $vote = $req->execute (array($vote_id));
//        if($req->rowCount()){
//        $scoreYes = $req->fetch();
//        } else {
//            $scoreYes = 0;
//        }
//        return $scoreYes;
//    }
////
//  public function countScoreNo($vote_id) {
//        $db = $this->dbConnect();
//        $req = $db->prepare('SELECT SUM(POSTS_SCORE_NO) AS JAIMEPAS FROM p5_vote_score WHERE p5_votes_VOTE_ID = ? ');
//        $vote = $req->execute(array($vote_id));
//        if ($req->rowCount()) {
//            $scoreNo = $req->fetch();
//        } else {
//            $scoreNo = 0;
//        }
//        return $scoreNo;
//    }
    public function lesScores(){
      $db = $this->dbConnect();
     // $req = $db->query('SELECT p5_votes_VOTE_ID,SUM(POSTS_SCORE_YES) AS JAIME,SUM(POSTS_SCORE_NO) AS JAIMEPAS FROM p5_vote_score GROUP BY p5_votes_VOTE_ID');  
      $req = $db->query('SELECT p5_votes_VOTE_ID,SUM(POSTS_SCORE_YES) AS JAIME,SUM(POSTS_SCORE_NO) AS JAIMEPAS,p5_votes.p5_posts_ART_ID AS SUITE_ID FROM p5_vote_score INNER JOIN p5_votes ON p5_vote_score.p5_votes_VOTE_ID= p5_votes.VOTE_ID GROUP BY p5_votes_VOTE_ID'); 
      return $req;  
    }
    
    public function delScores($voteId){
      $db = $this->dbConnect();
      $req = $db->prepare('DELETE FROM `p5_vote_score` WHERE p5_votes_VOTE_ID = ?');
      $delScore = $req -> execute (array($voteId));
      return $req;  
    }
    
  public function updateVoteDuree($vote_id,$dateFin,$duree){
      $db = $this->dbConnect();
      $reqDateFin = $db->prepare('SELECT DATE_ADD((SELECT p5_votes.VOTE_DATEFIN FROM `p5_votes` WHERE p5_votes.VOTE_ID =?), INTERVAL ? DAY) '); 
      $result = $reqDateFin -> execute (array($vote_id,$duree));
      $date = $reqDateFin ->fetchAll();
      // recuperation de la date mofifiée
    echo $date[0][0];
      $req = $db->prepare('UPDATE `p5_votes` SET `VOTE_DATEFIN`=? WHERE p5_votes.VOTE_ID=?'); 
      $updateVote = $req -> execute (array($date[0][0],$vote_id));
      return $date;  
    }
    
// Utilite a voir 
    public function getCountVote($art_id){
        $db = $this->dbConnect();
      $req = $db->prepare('SELECT * FROM p5_votes WHERE p5_posts_ART_ID = ? AND p5_votes.VOTE_OUVERT= ?'); 
      $vote = $req -> execute (array($art_id,1));
      $count = $req->rowCount();
      return $count;
    }
   public function fermetureVote($vote_id){
        $db = $this->dbConnect();
      $req = $db->prepare('UPDATE p5_votes SET p5_votes.VOTE_OUVERT= ? WHERE p5_votes.VOTE_ID = ?  '); 
      $vote = $req -> execute (array(0,$vote_id,));
      return $vote;
    }
    public function ouvertureVote($vote_id){
        $db = $this->dbConnect();
      $req = $db->prepare('UPDATE p5_votes SET p5_votes.VOTE_OUVERT= ? WHERE p5_votes.VOTE_ID = ?   '); 
      $vote = $req -> execute (array(1,$vote_id,));
      return $vote;
    }
    
    public function vote($id) {
        $db = $this->dbConnect();
        $vote = $db->prepare('INSERT INTO p5_votes(VOTE_DATEDEBUT,VOTE_DATEFIN,VOTE_OUVERT,p5_posts_ART_ID) VALUES(?,?,?,?)');
        $dateDebut = (new \DateTime())->format('Y-m-d H:i:s');
        $dateFin = (new \DateTime('now +14 day'))->format('Y-m-d H:i:s');
        $nb = $this-> getCountVote($id);
         if($nb==0){
        $affectedLines = $vote->execute(array($dateDebut,$dateFin ,1,$id));
           }
    
    $vote->closeCursor ();
}

// verifie date de fin non dépassée sinon statut TERMINE post et VOTE OUVERT =0
    public function controleVote() {
        $db = $this->dbConnect();
//        $req = $db->prepare('UPDATE `p5_votes` INNER JOIN `p5_posts` ON p5_votes.p5_posts_ART_ID = p5_posts.ART_ID'
//                . ' INNER JOIN `p5_statut_post` ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID'
//                . ' SET p5_votes.VOTE_OUVERT= ? , p5_posts.p5_statut_post_STATUT_POST_ID = '
//                . '(SELECT STATUT_POST_ID FROM `p5_statut_post` WHERE STATUT_POST_LIBELLE= ? )'
//                . ' WHERE p5_votes.VOTE_DATEFIN < ?');
        $req = $db->query('UPDATE `p5_votes` INNER JOIN `p5_posts` ON p5_votes.p5_posts_ART_ID = p5_posts.ART_ID INNER JOIN `p5_statut_post` ON p5_posts.p5_statut_post_STATUT_POST_ID = p5_statut_post.STATUT_POST_ID SET p5_votes.VOTE_OUVERT= 0 , p5_posts.p5_statut_post_STATUT_POST_ID = (SELECT STATUT_POST_ID FROM `p5_statut_post` WHERE STATUT_POST_LIBELLE="TERMINE") WHERE p5_votes.VOTE_DATEFIN < now() AND p5_votes.VOTE_OUVERT= 1  ');
       // $vote = $req->execute(array(0,'TERMINE','NOW()'));
        
        return $req;
    }

}   