<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES MESSAGES

namespace Backend;
use Commun\Manager;
require_once('Model/Commun/newManager.php');
class MessageManager extends Manager {
    
// RECUPERE LES MESSAGES DE USER EN TANT QUE DESTINATAIRE ET EXPEDITEUR AVEC SUPPRESSION à 0
//    public function getMessages($userId) {
//        $db = $this->dbConnect();
//        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
//        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,DESTINATAIRE,EXPEDITEUR FROM p5_messages WHERE (DESTINATAIRE = ? AND DEL_DESTINATAIRE= ?) OR  (EXPEDITEUR= ? AND DEL_EXPEDITEUR= ?) ORDER BY DATETIME DESC');
//        $messages->execute(array($userId,0,$userId,0));
//        return $messages;
//        $messages->closeCursor ();
//    }

    public function getMessagesReceived($userId) {
        $db = $this->dbConnect();
        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,DESTINATAIRE,EXPEDITEUR,p5_users.USER_PSEUDO  FROM p5_messages INNER JOIN p5_users ON EXPEDITEUR = p5_users.USER_ID WHERE (DESTINATAIRE = ? AND DEL_DESTINATAIRE= ?) ORDER BY MESS_date_fr DESC');
        $messages->execute(array($userId,0));
        return $messages;
        $messages->closeCursor ();
    }
//    public function getMessagesReceivedBasket($userId) {
//        $db = $this->dbConnect();
//        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
//        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,DESTINATAIRE,EXPEDITEUR FROM p5_messages WHERE (DESTINATAIRE = ? AND DEL_DESTINATAIRE= ?) ORDER BY MESS_date_fr DESC');
//        $messages->execute(array($userId,1));
//        return $messages;
//        $messages->closeCursor ();
//    }
//    public function getMessagesSendBasket($userId) {
//        $db = $this->dbConnect();
//        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
//        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,DESTINATAIRE,EXPEDITEUR FROM p5_messages WHERE (EXPEDITEUR= ? AND DEL_EXPEDITEUR= ? ORDER BY MESS_date_fr DESC');
//        $messages->execute(array($userId,1));
//        return $messages;
//        $messages->closeCursor ();
//    }
    public function getMessagesSend($userId) {
        $db = $this->dbConnect();
//        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
//        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,DESTINATAIRE,EXPEDITEUR FROM p5_messages WHERE (EXPEDITEUR= ? AND DEL_EXPEDITEUR= ? ORDER BY MESS_date_fr DESC');
$messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,
        DESTINATAIRE,EXPEDITEUR,
        user_expediteur.USER_PSEUDO AS EXPEDITEUR_PSEUDO,user_destinataire.USER_PSEUDO AS DESTINATAIRE_PSEUDO 
        FROM p5_messages 
        INNER JOIN p5_users AS user_expediteur ON EXPEDITEUR = user_expediteur.USER_ID 
        INNER JOIN p5_users AS user_destinataire ON DESTINATAIRE = user_destinataire.USER_ID 
        WHERE (EXPEDITEUR = ? AND DEL_EXPEDITEUR= ?)
        ORDER BY MESS_date_fr DESC');
        
        $messages->execute(array($userId,0));
        return $messages;
        $messages->closeCursor ();
    }
    
    public function addMessage($destinataireId,$expediteurId,$objet,$content) {
        $db = $this->dbConnect();
        $message = $db->prepare('INSERT INTO p5_messages(MESS_OBJET, MESS_CONTENT,DESTINATAIRE,EXPEDITEUR,DATETIME) VALUES(?, ?, ?,?, NOW())');
        $affectedLines = $message->execute(array($objet,$content,$destinataireId,$expediteurId));
        return $affectedLines;
        $message->closeCursor ();
    }

      public function setMessagesNotRead($tab) {
        $db = $this->dbConnect();
        foreach ($tab as $key => $value) {
    
        //echo "{$key} => {$value}"."<br />";
        $nonlu = $db->prepare('UPDATE p5_messages SET  MESS_LU = ?  WHERE MESS_ID =  ?');
        $nonlu->execute(array(0, $value));
}
    
        $nonlu->closeCursor ();
    }
    public function setMessagesRead($tab) {
        $db = $this->dbConnect();
        foreach ($tab as $key => $value) {
        $lu = $db->prepare('UPDATE p5_messages SET  MESS_LU = ?  WHERE MESS_ID =  ?');
        $lu->execute(array(1, $value));
        }
       
        $lu->closeCursor ();
    }
   public function cleanMessagerie() {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_messages WHERE DEL_DESTINATAIRE = ? AND DEL_EXPEDITEUR = ?');
        $req->execute(array(1,1));
        return $req;
        $req->closeCursor ();
    }
     public function delMessages($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_messages WHERE MESS_ID = ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor ();
    }
 //met le message suppr du cote destinatazire ou expediteur en fonction de $statut et de l id du mess
    public function desactiverMessagesDestinataire($tab) {
      $db = $this->dbConnect();
//      if ($statut == 'DESTINATAIRE'){
//          $champs= 'DEL_DESTINATAIRE';
//      }elseif ($statut == 'EXPEDITEUR'){
//        $champs= 'DEL_EXPEDITEUR';   
//      } 
      foreach ($tab as $key => $value) {  
      //si erreur $del false et remontera au controleur 
        
        $del = $db->prepare('UPDATE p5_messages SET  DEL_DESTINATAIRE = ?  WHERE MESS_ID =  ?');
        $del->execute(array(1, $value));
   
       }
  
      }
      
      public function desactiverMessagesExpediteur($tab) {
      $db = $this->dbConnect();

      foreach ($tab as $key => $value) {  
      //si erreur $del false et remontera au controleur 
        
        $del = $db->prepare('UPDATE p5_messages SET  DEL_EXPEDITEUR = ?  WHERE MESS_ID =  ?');
        $del->execute(array(1, $value));
   
       }
    }
    
   public function nbMessagesNonLu($userId){
        $db = $this->dbConnect();
         $messages = $db->prepare('SELECT COUNT(MESS_ID)AS NB FROM `p5_messages` WHERE MESS_LU=? AND DEL_DESTINATAIRE = ? AND DESTINATAIRE = ?');
         $messages->execute(array(0,0, $userId));
         
         return $messages;
   }
    
    
    
}
