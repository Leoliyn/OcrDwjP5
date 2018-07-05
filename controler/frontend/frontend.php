<?php

/**
 * PROJET 5 DWJ OPENCLASSROOMS    CLAUDEY Lionel  2018      
 */
require_once('Model/Frontend/PostManager.php');
require_once('Model/Frontend/CommentManager.php');
require_once('Model/Frontend/BookManager.php');
require_once('Model/Frontend/UsersManager.php');
require_once('Model/Frontend/MessageManager.php');

// AUTOLOAD Fonctionne en local pb chez 000webhost.com
//require'Model/Commun/SplClassLoader.php';
//$OCFramLoader = new SplClassLoader('Frontend', '/Model');
//$OCFramLoader->register();

class FrontendControler {

    /**
     * Liste des ouvrages enable appel vue liste des ouvrages
     */
    public function listOuvrages() {
        $bookManager = new Frontend\BookManager();
        $books = $bookManager->getBooks();

        require('view/frontend/listBooksView.php');
    }

    /**
     * Liste de tous les chapitres depuis getPostsResume avec XX  premiers caractères - Données de l'Ouvrage - appel vue listPostsView        
     */
    public function listPostsResume() {
        $postManager = new Frontend\PostManager();
        $bookManager = new Frontend\BookManager();
        $posts = $postManager->getPostsResume($_GET['ouv_id']);
        $book = $bookManager->getBook($_GET['ouv_id']);

        require('view/frontend/listPostsView.php');
    }

    /**
     *  recupere données du chapitre  Données Ouvrage et Les commentaires- puis vue postView
     * @throws Exception
     */
    public function post() {
        $postManager = new Frontend\PostManager();
        $commentManager = new Frontend\CommentManager();

        $bookManager = new Frontend\BookManager();
        $article = $postManager->getPost($_GET['id']);

        if ($article) {
            $comments = $commentManager->getCommentsPremierNiveau($_GET['id']);
            $commentsChild = $commentManager->getCommentsChild($_GET['id']);
            $book = $bookManager->getBook($_GET['ouv_id']);
            $posts = $postManager->getPostsResume($_GET['ouv_id']);
            require('view/frontend/postView.php');
        } else {
            throw new Exception('Chapitre inconnu');
        }
    }

    /**
     * methode recupere les données d'un chapitre les c ommebntaires les données de l'ouvrage ainsi que 
     * verifie que la session n'a pas visionné le chapitre depuis moins de 5 minutes sinon ajoute une visite dans la table de visites 
     * appel vue postView 
     * @param type $session_id
     * @throws Exception
     */
    public function postSession($session_id) {

        $postManager = new Frontend\PostManager();
        $commentManager = new Frontend\CommentManager();

        $bookManager = new Frontend\BookManager();
        $article = $postManager->getPost($_GET['id']);

        if ($article) {
            $comments = $commentManager->getCommentsPremierNiveau($_GET['id']);
            $commentsChild = $commentManager->getCommentsChild($_GET['id']);
            $book = $bookManager->getBook($_GET['ouv_id']);
            $posts = $postManager->getPostsResume($_GET['ouv_id']);
            $dejaVu = $postManager->verifVisitPost($session_id, $_GET['id']);
            if ($dejaVu[0][0] == 0) {
                $newVisit = $postManager->addVisitPost($session_id, $_GET['id']);
            }
            require('view/frontend/postView.php');
        } else {
            throw new Exception('Chapitre inconnu');
        }
    }

///═════════════════════════════════════════════
//                 METHODES LIEES AUX COMMENTAIRES 
//═════════════════════════════════════════════

    /**
     *       COMM                  active un commentaire puis appel post() ( la visite n'est pas comptabilisée lors de l'appel de post') )
     * @throws Exception
     */
    public function activeComment() {
        $commentManager = new Frontend\CommentManager();
        $comment = $commentManager->enableComment($_GET['commId']);
        if ($comment) {
            post();
        } else {
            throw new Exception('Elément inconnu ');
        }
    }

    /**
     *  COMM           désactive un commentaire puis appel post()
     * @throws Exception
     */
    public function desactiveComment() {
        $commentManager = new Frontend\CommentManager();
        $comment = $commentManager->disableComment($_GET['commId']);
        if ($comment) {
            post();
        } else {
            throw new Exception('Elément inconnu ');
        }
    }

    /**
     * COMM  signal  un commentaire  envoi message aux admins puis appel post()
     * @throws Exception
     */
    public function activeSignal() {
        $commentManager = new Frontend\CommentManager();
        $comment = $commentManager->enableSignal($_GET['commId']);
        if ($comment) {
            $this->messageSignalementCommentaire();
            $this->post();
        } else {
            throw new Exception('Elément inconnu 351');
        }
    }

    /**
     * COMM   disabled signalement d'un commentaire 
     * @throws Exception
     */
    public function desactiveSignal() {

        $commentManager = new Frontend\CommentManager();
        $comment = $commentManager->disableSignal($_GET['commId']);
        if ($comment) {
            $this->post();
        } else {
            throw new Exception ('Elément inconnu 366');
        }
    }
    /**
     * MESS- Envoi message suite signalement commentaire
     */
   
    public function messageSignalementCommentaire(){
//Messagerie
    $userManager = new Frontend\UsersManager();
    $bookManager = new Frontend\BookManager();
    $book = $bookManager->getBooksRights($_GET['ouv_id']);
    $root = $userManager->listSuperadmin();
    $objet = 'Signalement commentaire  ';
    $contenu = 'Un signalement de commentaire est levé par '.$_SESSION['user'];
    while ($adminBook = $book->fetch()) {

    $this->messageSystem($adminBook['p5_USERS_USER_ID'], $_SESSION['userId'], $objet, $contenu);
    // envoyer mess aux administrateur de louvrage
}
while ($rootBook = $root->fetch()) {

    $this->messageSystem($rootBook['USER_ID'], $_SESSION['userId'], $objet, $contenu);
    // envoyer mess aux Superadmin 
}
/// fin messagerie
}

/**
 * Ajoute un commentaire au chapitre puis lance la fonction post()
 * @param type $id
 * @param type $auteur
 * @param type $comment
 * @param type $parent
 * @param type $ouvId
 */
public function ajoutComment($id, $auteur, $comment, $parent, $ouvId) {
$regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\?!:]+)";
$commentManager = new Frontend\commentManager();
$comment = $commentManager->addComment($id, $auteur, $comment, $parent);
$_GET['ouv_id'] = $ouvId;
$_GET['id'] = $id;
$this->post();
}

/**
 *  Fonction préparation puis envoi du courriel de contact- lance le script modal.php
 * @param type $nom
 * @param type $mailExpediteur
 * @param type $texte
 * @return string
 */
public function message($nom, $mailExpediteur, $texte) {
// Mettez ici votre adresse valide
$passage_ligne = "\n";
$to = "lionel.claudey@laposte.net";

// Sujet du message 
$subject = "Message Internaute pour LES ROMANS COLLABORATIFS";

// Corps du message, écrit en texte et encodage iso-8859-1
$message = "Message de :" . $nom . "  Adresse Mail : " . $mailExpediteur . $passage_ligne . $texte;

// En-têtes du message
$headers = ""; // on vide la variable
$headers = "From: Webmaster Site <claudey@lionelclaudey.com>\n"; // ajout du champ From
// $headers = $headers."MIME-Version: 1.0\n"; // ajout du champ de version MIME
$headers = $headers . "Content-type: text/plain; charset=iso-8859-1\n"; // ajout du type d'encodage du corps
// Appel à la fonction mail
?> <script>alert('Fonction mail non encore active sur hébergeur'); </script>
<?php
if (mail($to, $subject, $message, $headers) == TRUE) {
    $info = "Envoi du mail réussi.";
} else {
    $info = "Erreur : l'envoi du mail a échoué.";
}
return $info;
}

public function infoMail($info) {
require('view/frontend/modal.php');
}

/**
 * methode inscription des utilisateurs 
 * ajout un user dans la base de données (sans droit) recuopere la liste des admins et envoi un message relatif à la demande 
 * appel de post()
 * @param type $userName
 * @param type $userLastname
 * @param type $userPseudo
 * @param type $userMail
 * @param type $userPasswd
 * @param type $userStatut
 */
public function inscription($userName, $userLastname, $userPseudo, $userMail, $userPasswd, $userStatut) {

$usersManager = new Frontend\UsersManager();
$newUser = $usersManager->addUser($userName, $userLastname, $userPseudo, $userMail, $userPasswd, $userStatut);
$root = $usersManager->listSuperadmin();
$objet = 'Demande inscription   ';
$contenu = 'demande inscription pour :  ' . $userName . ' ' . $userLastname . ' pseudo :' . $userPseudo . ' email : ' . $userMail;
while ($rootBook = $root->fetch()) {

    $this->messageSystem($rootBook['USER_ID'], $rootBook['USER_ID'], $objet, $contenu);

    // envoyer mess aux Superadmin 
}
?>
<script>alert('Votre inscription est enregistrée');</script>
<?php $this->post();
}

/**
 * Ajout d'un message dans la table message 
 * @param type $destinataire
 * @param type $expediteur
 * @param type $objet
 * @param type $contenu
 */
public function messageSystem($destinataire, $expediteur, $objet, $contenu) {

$messageManager = new Frontend\MessageManager();
$envoi = $messageManager->addMessage($destinataire, $expediteur, $objet, $contenu);
}

/**
 * Methode erreur puis appel vue errurView
 * @param type $message
 */
public function erreur($message) {
$_POST['message'] = $message;
require_once('view/frontend/erreurView.php');
}

}
