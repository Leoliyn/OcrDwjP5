<?php
//╔═════════════════════════════╗  
//           PROJET 5 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Avril 2018           
//╚═════════════════════════════╝
//GESTION DES MESSAGES
namespace Frontend;
require_once('Model/Commun/newManager.php');
use Commun\Manager;
class MessageManager extends Manager {
    

    
    public function addMessage($destinataireId,$expediteurId,$objet,$content) {
        $db = $this->dbConnect();
        $message = $db->prepare('INSERT INTO p5_messages(MESS_OBJET, MESS_CONTENT,DESTINATAIRE,EXPEDITEUR,DATETIME) VALUES(?, ?, ?,?, NOW())');
        $affectedLines = $message->execute(array($objet,$content,$destinataireId,$expediteurId));
        return $affectedLines;
        $message->closeCursor ();
    }

      
    
    
}
