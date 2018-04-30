<?php
//╔═════════════════════════════╗  
//           PROJET 4 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Février 2018           
//╚═════════════════════════════╝
require_once('model/frontend/PostManager.php');
require_once('model/frontend/CommentManager.php');
//require_once('model/frontend/BookManager.php');
require_once('model/commun/Manager.php');

//╔════════════════════════════════════════╗  
//        List des chapitres depuis getPosts (uniquement publiés) 
//        - listPostsView
//╚════════════════════════════════════════╝
// 
function listPosts() {
    $postManager = new OpenClassrooms\DWJP5\frontend\Model\PostManager();
    $posts = $postManager->getPosts();

    require('view/frontend/listPostsView.php');
}

//╔════════════════════════════════════════════╗  
//      Liste de tous les chapitres depuis getPostsResume avec XX  
//      premiers caractères - Données de l'Ouvrage - listPostsView                                        
//╚════════════════════════════════════════════╝
// 
function listPostsResume() {
    $postManager = new OpenClassrooms\DWJP5\frontend\Model\PostManager();
    //$bookManager = new OpenClassrooms\DWJP5\frontend\Model\BookManager();
    $posts = $postManager->getPostsResume();
    //$books = $bookManager->getBooks();
    require('view/frontend/listPostsView.php');
}

//╔════════════════════════════════════════╗  
//   Un chapitre  . Données Ouvrage - Les commentaires- req postView
//╚════════════════════════════════════════╝
// 
function post() {

    $postManager = new OpenClassrooms\DWJP5\frontend\Model\PostManager();
    $commentManager = new OpenClassrooms\DWJP5\frontend\Model\CommentManager();

    //$bookManager = new OpenClassrooms\DWJP5\frontend\Model\BookManager();
    $article = $postManager->getPost($_GET['id']);
    if ($article) {
        $comments = $commentManager->getComments($_GET['id']);
        //$books = $bookManager->getBooks();
        $posts = $postManager->getPostsResume();
        require('view/frontend/postView.php');
    } else {
        throw new Exception('Chapitre inconnu');
    }
}

//╔══════════════════════════════════════════╗  
//  Signale le commentaire  - lance la fonction post()
//╚══════════════════════════════════════════╝
// 
    
function activeSignal() {
    $commentManager = new OpenClassrooms\DWJP5\frontend\Model\commentManager();
    $comment = $commentManager->enableSignal($_GET['commId']);
    post();
}


//╔════════════════════════════════════════╗  
//   Ajoute un commentaire au chapitre puis lance la fonction post()
//╚════════════════════════════════════════╝
// 
function ajoutComment() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\?!:]+)";
    $id = preg_replace('([^0-9]+)', '', $_GET['id']);
    $auteur = preg_replace($regex, '', $_POST['author']);
    $comment = preg_replace($regex, '', $_POST['comment']);
    $commentManager = new OpenClassrooms\DWJP5\frontend\Model\commentManager();
    $comment = $commentManager->addComment($id, $auteur, $comment);
    post();
}

//╔════════════════════════════════════════╗  
//   Fonction préparation puis envoi du courriel de contact
//     - lance le script modal.php
//╚════════════════════════════════════════╝
// 
function message($nom, $mailExpediteur, $texte) {
// Mettez ici votre adresse valide
    $passage_ligne = "\n";
    $to = "lionel.claudey@laposte.net";

// Sujet du message 
    $subject = "Message Internaute pour Jean FORTEROCHE";

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

function infoMail($info) {
    require('view/frontend/modal.php');
}
