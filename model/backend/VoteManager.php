<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES MESSAGES
namespace OpenClassrooms\DWJP5\Backend\Model;

require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;
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
    
    
    
 // recuperation idvote   
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
public function countScoreYes($vote_id){
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT SUM(POSTS_SCORE_YES) AS JAIME FROM p5_vote_score WHERE p5_votes_VOTE_ID = ? ');
        $vote = $req->execute (array($vote_id));
        if($req->rowCount()){
        $scoreYes = $req->fetch();
        } else {
            $scoreYes = 0;
        }
        return $scoreYes;
    }

    public function countScoreNo($vote_id) {
        $db = $this->dbConnect();
        $req = $db->prepare('SELECT SUM(POSTS_SCORE_NO) AS JAIMEPAS FROM p5_vote_score WHERE p5_votes_VOTE_ID = ? ');
        $vote = $req->execute(array($vote_id));
        if ($req->rowCount()) {
            $scoreNo = $req->fetch();
        } else {
            $scoreNo = 0;
        }
        return $scoreNo;
    }

// Utilite a voir 
    public function getCountVote($art_id){
        $db = $this->dbConnect();
      $req = $db->prepare('SELECT * FROM p5_votes WHERE p5_posts_ART_ID = ? AND p5_votes.VOTE_OUVERT= ?'); 
      $vote = $req -> execute (array($art_id,1));
      $count = $req->rowCount();
      return $count;
    }
   public function fermetureVote($art_id){
        $db = $this->dbConnect();
      $req = $db->prepare('UPDATE p5_votes SET p5_votes.VOTE_OUVERT= ? WHERE p5_posts_ART_ID = ?  '); 
      $vote = $req -> execute (array(0,$art_id,));
      return $vote;
    }
    public function ouvertureVote($art_id){
        $db = $this->dbConnect();
      $req = $db->prepare('UPDATE p5_votes SET p5_votes.VOTE_OUVERT= ? WHERE p5_posts_ART_ID = ?  '); 
      $vote = $req -> execute (array(1,$art_id,));
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