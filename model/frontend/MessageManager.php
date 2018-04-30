<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
//GESTION DES MESSAGES
namespace OpenClassrooms\DWJP5\frontend\Model;

require_once("model/commun/Manager.php");
use OpenClassrooms\DWJP5\Commun\Model\Manager;
class MessageManager extends Manager {

    public function getMessages($userId) {
        $db = $this->dbConnect();
        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
        DATE_FORMAT(MESS_DATE, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,DESTINATAIRE,EXPEDITEUR FROM p5_messages WHERE DESTNATAIRE = ? OR  EXPEDITEUR= ? ORDER BY MESS_DATE DESC');
        $messages->execute(array($userId,$userId));
        return $messages;
    }

    public function addMessage($destinataireId,$expediteurId,$object,$content) {
        $db = $this->dbConnect();
        $message = $db->prepare('INSERT INTO p5_messages(MESS_OBJET, MESS_CONTENT,DESTINATAIRE,EXPEDITEUR,MESS_DATE) VALUES(?, ?, ?,?, NOW())');
        $affectedLines = $message->execute(array($objet,$content,$destinataire,$expediteur));
        return $affectedLines;
    }

   
    public function luMessages($messageId) {
        $db = $this->dbConnect();
        $lu = $db->prepare('UPDATE p5_messages SET  MESS_LU = ?  WHERE MESS_ID =  ?');
        $lu->execute(array(1, $messageId));
        return $lu;
    }
     public function delMessages($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_messages WHERE MESS_ID = ?');
        $req->execute(array($id));
        return $req;
    }
    
}
