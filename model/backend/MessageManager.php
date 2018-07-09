<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS 
 * CLAUDEY Lionel  2018  
 * GESTION DES MESSAGES
 */

namespace Backend;

use Commun\Manager;

require_once('Model/Commun/newManager.php');

class MessageManager extends Manager {

    /**
     * getMessagesReceived() recupère les messages reçu lu & non lu et non à la corbeille par l'utilisateur 
     * @param type $userId
     * @return type
     */
    public function getMessagesReceived($userId) {
        $db = $this->dbConnect();
        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,DESTINATAIRE,EXPEDITEUR,p5_users.USER_PSEUDO  FROM p5_messages INNER JOIN p5_users ON EXPEDITEUR = p5_users.USER_ID WHERE (DESTINATAIRE = ? AND DEL_DESTINATAIRE= ?) ORDER BY MESS_date_fr DESC');
        $messages->execute(array($userId, 0));
        return $messages;
        $messages->closeCursor();
    }

    /**
     * getMessagesSend() récupère les message envoyés par l'utilisateur non lu lu et non corbeille 
     * @param type $userId
     * @return type
     */
    public function getMessagesSend($userId) {
        $db = $this->dbConnect();
        $messages = $db->prepare('SELECT MESS_ID,MESS_LU,MESS_OBJET,MESS_CONTENT,
        DATE_FORMAT(DATETIME, \'%d/%m/%Y à %Hh%imin%ss\') AS MESS_date_fr,
        DESTINATAIRE,EXPEDITEUR,
        user_expediteur.USER_PSEUDO AS EXPEDITEUR_PSEUDO,user_destinataire.USER_PSEUDO AS DESTINATAIRE_PSEUDO 
        FROM p5_messages 
        INNER JOIN p5_users AS user_expediteur ON EXPEDITEUR = user_expediteur.USER_ID 
        INNER JOIN p5_users AS user_destinataire ON DESTINATAIRE = user_destinataire.USER_ID 
        WHERE (EXPEDITEUR = ? AND DEL_EXPEDITEUR= ?)
        ORDER BY MESS_date_fr DESC');

        $messages->execute(array($userId, 0));
        return $messages;
        $messages->closeCursor();
    }

    /**
     * addMessage() Ajout enregistrement table p5_message
     * @param type $destinataireId
     * @param type $expediteurId
     * @param type $objet
     * @param type $content
     * @return type
     */
    public function addMessage($destinataireId, $expediteurId,$delDestinataire,$delExpediteur, $objet, $content) {
        $db = $this->dbConnect();
        $message = $db->prepare('INSERT INTO p5_messages(MESS_OBJET, MESS_CONTENT,DESTINATAIRE,EXPEDITEUR,DEL_DESTINATAIRE,DEL_EXPEDITEUR,DATETIME) VALUES(?,?,?, ?, ?,?, NOW())');
        $affectedLines = $message->execute(array($objet, $content, $destinataireId, $expediteurId,$delDestinataire,$delExpediteur));
        return $affectedLines;
        $message->closeCursor();
    }

    /**
     * setMessagesNotRead() positionne en non lu tous ls element du tableau en paramètre
     * @param type $tab
     */
    public function setMessagesNotRead($tab) {
        $db = $this->dbConnect();
        foreach ($tab as $key => $value) {

            //echo "{$key} => {$value}"."<br />";
            $nonlu = $db->prepare('UPDATE p5_messages SET  MESS_LU = ?  WHERE MESS_ID =  ?');
            $nonlu->execute(array(0, $value));
        }

        $nonlu->closeCursor();
    }

    /**
     * setMessagesRead() positionne à lu tous les element du tableau en paramètre
     * @param type $tab
     */
    public function setMessagesRead($tab) {
        $db = $this->dbConnect();
        foreach ($tab as $key => $value) {
            $lu = $db->prepare('UPDATE p5_messages SET  MESS_LU = ?  WHERE MESS_ID =  ?');
            $lu->execute(array(1, $value));
        }

        $lu->closeCursor();
    }

    /**
     * cleanMessagerie() supprime les messages dont l'expediteur et le destinataire on mit à la corbeille
     * @return type
     */
    public function cleanMessagerie() {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_messages WHERE DEL_DESTINATAIRE = ? AND DEL_EXPEDITEUR = ?');
        $req->execute(array(1, 1));
        return $req;
        $req->closeCursor();
    }

    /**
     * delMessages() supprime un enregistrelment de la table p5_messages
     * @param type $id
     * @return type
     */
    public function delMessages($id) {
        $db = $this->dbConnect();
        $req = $db->prepare('DELETE FROM p5_messages WHERE MESS_ID = ?');
        $req->execute(array($id));
        return $req;
        $req->closeCursor();
    }

    /**
     * desactiverMessagesDestinataire met à la corbeille les elements du tableau en parametre
     * @param type $tab
     */
    public function desactiverMessagesDestinataire($tab) {
        $db = $this->dbConnect();
        foreach ($tab as $key => $value) {
            //si erreur $del false et remontera au controleur 

            $del = $db->prepare('UPDATE p5_messages SET  DEL_DESTINATAIRE = ?  WHERE MESS_ID =  ?');
            $del->execute(array(1, $value));
        }
    }

    /**
     * ddesactiverMessagesExpediteur met à la corbeille les elements du tableau en parametre
     * @param type $tab
     */
    public function desactiverMessagesExpediteur($tab) {
        $db = $this->dbConnect();

        foreach ($tab as $key => $value) {
            //si erreur $del false et remontera au controleur 

            $del = $db->prepare('UPDATE p5_messages SET  DEL_EXPEDITEUR = ?  WHERE MESS_ID =  ?');
            $del->execute(array(1, $value));
        }
    }

    /**
     * nbMessagesNonLu compte le nombre de message non lu pour un utilisateur 
     * @param type $userId
     * @return type
     */
    public function nbMessagesNonLu($userId) {
        $db = $this->dbConnect();
        $messages = $db->prepare('SELECT COUNT(MESS_ID)AS NB FROM `p5_messages` WHERE MESS_LU=? AND DEL_DESTINATAIRE = ? AND DESTINATAIRE = ?');
        $messages->execute(array(0, 0, $userId));
        return $messages;
    }

}
