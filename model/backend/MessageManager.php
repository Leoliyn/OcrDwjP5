<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES MESSAGES
namespace OpenClassrooms\DWJP5\Backend\Model;

require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;
class MessageManager extends Manager {
    
// RECUPERE LES MESSAGES DE USER EN TANT QUE DESTINATAIRE ET EXPEDITEUR AVEC SUPPRESSION à 0
    public function getMessages($userId) {
        $db = $this->dbConnect();
        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,DESTINATAIRE,EXPEDITEUR FROM p5_messages WHERE (DESTINATAIRE = ? AND DEL_DESTINATAIRE= ?) OR  (EXPEDITEUR= ? AND DEL_EXPEDITEUR= ?) ORDER BY DATETIME DESC');
        $messages->execute(array($userId,0,$userId,0));
        return $messages;
        $mesages->closeCursor ();
    }

    public function addMessage($destinataireId,$expediteurId,$object,$content) {
        $db = $this->dbConnect();
        $message = $db->prepare('INSERT INTO p5_messages(MESS_OBJET, MESS_CONTENT,DESTINATAIRE,EXPEDITEUR,MESS_DATE) VALUES(?, ?, ?,?, NOW())');
        $affectedLines = $message->execute(array($objet,$content,$destinataire,$expediteur));
        return $affectedLines;
        $message->closeCursor ();
    }

   
    public function luMessages($messageId) {
        $db = $this->dbConnect();
        $lu = $db->prepare('UPDATE p5_messages SET  MESS_LU = ?  WHERE MESS_ID =  ?');
        $lu->execute(array(1, $messageId));
        return $lu;
        $lu->closeCursor ();
    }
     public function delMessages($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_messages WHERE MESS_ID = ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor ();
    }
 //met le message suppr du cote destinatazire ou expediteur en fonction de $statut et de l id du mess
    public function desactiverMessage($messageId,$statut) {
      if ($statut == 'DESTINATAIRE'){
          $champs= 'DEL_DESTINATAIRE';
      }elseif ($statut == 'EXPEDITEUR'){
        $champs= 'DEL_EXPEDITEUR';   
      }
      //si erreur $del false et remontera au controleur 
        $db = $this->dbConnect();
        $del = $db->prepare('UPDATE p5_messages SET  $champs = ?  WHERE MESS_ID =  ?');
        $del->execute(array(1, $messageId));
        return $del;
    }
}
