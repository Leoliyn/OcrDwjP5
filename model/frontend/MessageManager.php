<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS         CLAUDEY Lionel  2018     
 *     GESTION DES MESSAGES frontend
 */

namespace Frontend;

require_once('Model/Commun/newManager.php');

use Commun\Manager;

class MessageManager extends Manager {

    /**
     * Ajoute un message à la table p5_messages
     * 
     * La date du message sera la date  de l'enregistrement 
     * 
     * @param type $destinataireId
     * @param type $expediteurId
     * @param type $objet
     * @param type $content
     * @return type
     */
    public function addMessage($destinataireId, $expediteurId, $objet, $content) {
        $db = $this->dbConnect();
        $message = $db->prepare('INSERT INTO p5_messages(MESS_OBJET, MESS_CONTENT,DESTINATAIRE,EXPEDITEUR,DATETIME) VALUES(?, ?, ?,?, NOW())');
        $affectedLines = $message->execute(array($objet, $content, $destinataireId, $expediteurId));
        return $affectedLines;
        $message->closeCursor();
    }

}
