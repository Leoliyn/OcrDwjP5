<?php
//╔═════════════════════════════╗  
//           PROJET 5 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Avril 2018           
//╚═════════════════════════════╝
require_once('Model/Frontend/PostManager.php');
require_once('Model/Frontend/CommentManager.php');
require_once('Model/Frontend/BookManager.php');
require_once('Model/Frontend/UsersManager.php');
require_once('Model/Frontend/MessageManager.php');
//require_once('Model/Commun/Manager.php');
//require'Model/Commun/SplClassLoader.php';
//$OCFramLoader = new SplClassLoader('Frontend', '/Model');
//$OCFramLoader->register();
 
class FrontendControler {
    
   // Liste des ouvrages enable 
public function listOuvrages() {
    $bookManager = new Frontend\BookManager();
    $books = $bookManager->getBooks();

    require('view/frontend/listBooksView.php');
}

 
//╔════════════════════════════════════════╗  
//        List des chapitres depuis getPosts (uniquement publiés) 
//        - listPostsView
//╚════════════════════════════════════════╝
// 
public function listPosts() {
    $postManager = new Frontend\PostManager();
    $posts = $postManager->getPosts();

    require('view/frontend/listPostsView.php');
}

//╔════════════════════════════════════════════╗  
//      Liste de tous les chapitres depuis getPostsResume avec XX  
//      premiers caractères - Données de l'Ouvrage - listPostsView                                        
//╚════════════════════════════════════════════╝
// 
public function listPostsResume() {
    $postManager = new Frontend\PostManager();
    $bookManager = new Frontend\BookManager();
    $posts = $postManager->getPostsResume($_GET['ouv_id']);
    $book = $bookManager->getBook($_GET['ouv_id']) ;
   
    require('view/frontend/listPostsView.php');
}
 
//╔════════════════════════════════════════╗  
//   Un chapitre  . Données Ouvrage - Les commentaires- req postView
//╚════════════════════════════════════════╝
// 
public function post(){
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
        $dejaVu =$postManager->verifVisitPost($session_id, $_GET['id']);
        if ($dejaVu[0][0] == 0){
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
 

//═════════════════════════════════════════════
//      COMM                  active un commentaire 
//═════════════════════════════════════════════
     public function activeComment() {
        $commentManager = new Frontend\CommentManager();
        $comment = $commentManager->enableComment($_GET['commId']);
        if ($comment) {
            post();
        } else {
            throw new Exception('Elément inconnu 323');
        }
    }
//═════════════════════════════════════════════
//      COMM           désactive un commentaire
//═════════════════════════════════════════════
public function desactiveComment() {
    $commentManager = new Frontend\CommentManager();
    $comment = $commentManager->disableComment($_GET['commId']);
    if($comment){
   post();
}else{
 throw new Exception ('Elément inconnu 337');   
}
}
 //╔══════════════════════════════════════════╗  
//   COMM  signal  un commentaire 
//╚══════════════════════════════════════════╝
// 

 public function activeSignal() {
    $commentManager = new Frontend\CommentManager();
    $comment = $commentManager->enableSignal($_GET['commId']);
    if($comment){
    $this->messageSignalementCommentaire();    
    $this ->post();
}else{
 throw new Exception ('Elément inconnu 351');   
}
}
//╔══════════════════════════════════════════╗  
//  COMM   Supprime le signalement d'un commentaire 
//╚══════════════════════════════════════════╝
// 

public function desactiveSignal() {

    $commentManager = new Frontend\CommentManager();
    $comment = $commentManager->disableSignal($_GET['commId']);
    if($comment){
   $this->post();
}else{
 throw new Exception ('Elément inconnu 366');   
}
}

//   ═════════════════════════════════════════════
    //    MESS- Envoi message suite signalement commentaire
    //   ═════════════════════════════════════════════
public function messageSignalementCommentaire(){
//Messagerie
    $userManager = new Frontend\UsersManager();
    $bookManager = new Frontend\BookManager();
    $book = $bookManager->getBooksRights($_GET['ouv_id']);
    $root = $userManager -> listSuperadmin();
    $objet = 'Signalement commentaire  ';
    $contenu = 'Un signalement de commentaire est levé par '.$_SESSION['user'];
     while ($adminBook = $book->fetch()) {
    
    $this->messageSystem($adminBook['p5_USERS_USER_ID'],$_SESSION['userId'], $objet, $contenu);
    // envoyer mess aux administrateur de louvrage
    }
     while ($rootBook = $root->fetch()) {
    
$this ->messageSystem($rootBook['USER_ID'],$_SESSION['userId'] , $objet, $contenu);
    // envoyer mess aux Superadmin 
    }
    /// fin messagerie

}
//╔════════════════════════════════════════╗  
//   Ajoute un commentaire au chapitre puis lance la fonction post()
//╚════════════════════════════════════════╝
// 
public function ajoutComment($id, $auteur, $comment,$parent,$ouvId) {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\?!:]+)";
    $commentManager = new Frontend\commentManager();
    $comment = $commentManager->addComment($id, $auteur, $comment,$parent);
   // throw new Exception ('parent: '.$parent);
    $_GET['ouv_id']=$ouvId;
    $_GET['id']=$id;
    $this ->post();
}

//╔════════════════════════════════════════╗  
//   Fonction préparation puis envoi du courriel de contact
//     - lance le script modal.php
//╚════════════════════════════════════════╝
// 
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


public function inscription($userName, $userLastname, $userPseudo, $userMail, $userPasswd, $userStatut) {

    $usersManager = new Frontend\UsersManager();
   $newUser = $usersManager->addUser($userName, $userLastname, $userPseudo, $userMail, $userPasswd, $userStatut);
    $root = $usersManager->listSuperadmin();
    $objet = 'Demande inscription   ';
    $contenu = 'demande inscription pour :  ' . $userName . ' ' . $userLastname . ' pseudo :' . $userPseudo . ' email : ' . $userMail;
      while ($rootBook = $root->fetch()) {

        $this ->messageSystem($rootBook['USER_ID'], $rootBook['USER_ID'], $objet, $contenu);
        
        // envoyer mess aux Superadmin 
    }
    $this ->post();
}

public function messageSystem($destinataire, $expediteur, $objet, $contenu) {
        
        $messageManager = new Frontend\MessageManager();
        $envoi = $messageManager->addMessage($destinataire, $expediteur, $objet, $contenu);
    }
  
    public function erreur($message){
    $_POST['message']=$message;
  require_once('view/frontend/erreurView.php'); 
    
}
    
    
}





