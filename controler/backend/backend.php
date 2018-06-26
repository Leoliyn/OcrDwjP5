<?php
//╔═════════════════════════════╗  
//           PROJET 5 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Avril 2018           
//╚═════════════════════════════╝

//require'Model/Commun/SplClassLoader.php';
//$OCFramLoader = new SplClassLoader('Backend', '/Model');
//$OCFramLoader->register();
require_once('Model/Backend/PostManager.php');
require_once('Model/Backend/CommentManager.php');
require_once('Model/Backend/UsersManager.php');
require_once('Model/Backend/BookManager.php');
require_once('Model/Backend/MessageManager.php');
require_once('Model/Backend/VoteManager.php');


class BackendControler {
    
 //═════════════════════════════════════════════
//                            METHODES LIEES AUX CHAPITRES
// ═════════════════════════════════════════════

    
    
//   ═════════════════════════════════════════════
//         CHAP- Liste des chapitres en résumé  et liste des suites existantes 
//   ═════════════════════════════════════════════

    public function listPosts($ouvId) {
        $postManager = new Backend\PostManager();
        $posts = $postManager->getPostsResume($ouvId);
        $postsSuite = $postManager->getPostsSuite($ouvId);
        require('view/backend/listPostsView.php');
    }
   //   ═════════════════════════════════════════════ 
 //        CHAP - Affiche le chapitre recupere les données ouvrage le statut du chapitre
 //    les suites controle les scrutins , recupere les scorres dans un tableau
 //   et les coommentaires et leurs enfants  
    //   ═════════════════════════════════════════════
    public function post() {

    $postManager = new Backend\PostManager();
    $commentManager = new Backend\CommentManager();
    $bookManager = new Backend\BookManager();
    $ouvrage = $bookManager->getBook($_GET['ouv_id']);
    $article = $postManager->getPost($_GET['id']);
    $statutPost = $postManager->libelleStatutPost($article['p5_statut_post_STATUT_POST_ID']);
    $suites = $postManager->getSuivants($_GET['id']);
    $voteManager = new Backend\VoteManager();
    //contrelo des vote e nfin de delai     
    $voteControle = $voteManager->controleVote();
    // RESULTAT VOTES 
    $lesScores = $voteManager -> lesScores();
    $tableauScores = $lesScores->fetchAll();
    if ($article) {
        $comments = $commentManager->getCommentsPremierNiveau($_GET['id']);
        $commentsChild = $commentManager->getCommentsChild($_GET['id']);
        require('view/backend/postView.php');
    } else {
        throw new Exception('Chapitre inconnu');
    }
}
 //   ═════════════════════════════════════════════    
//       CHAP -             Supprimer un chapitre
//   ═════════════════════════════════════════════
    public function supprimePost() {

        $postManager = new Backend\PostManager();
        $article = $postManager->getPost($_GET['id']);
        $post = $postManager->delPost($_GET['id']);
        if ($post) {
            $objet = 'Supression du post ' . $article['ART_TITLE'];
            $contenu = 'Post Supprimé';
            $auteurId = $article['ART_AUTEUR'];
            $this->messageSystem($auteurId, $_SESSION['userId'], $objet, $contenu);
            $this->listPosts($_GET['ouv_id']);
        } else {
            throw new Exception('Elément inconnu ');
        }
        
    }
 //   ═════════════════════════════════════════════   
//       CHAP- désactive un chapitre un chapitre et envoi un message à l'auteur
    //   ═════════════════════════════════════════════   
    public function desactiverPost() {

        $postManager = new Backend\PostManager();
        $post = $postManager->disablePost($_GET['id']);
        if ($post) {
            $article = $postManager->getPost($_GET['id']);
            $objet = 'Désactivation Chapitre ';
            $contenu = 'La Chapitre ( ' . $article['ART_CHAPTER'] . ') <b> est désactivé </b> de contenu : <i>' . substr($article['ART_CONTENT'], 0, 150) . '(...)</i>';
            $auteurId = $article['ART_AUTEUR'];
            $this->messageSystem($auteurId, $_SESSION['userId'], $objet, $contenu);
            $this->post();
        } else {
            throw new Exception('Chapitre inconnu 256');
        }
    }
//   ═════════════════════════════════════════════   
//       CHAP-           Appel formulaire de mise à  jour du chapitre 
//                    recupere données du chapitre et de l'ouvrage
//   ═════════════════════════════════════════════   
   public function formModifyPost() {
       
        $postManager = new Backend\PostManager();
        $bookManager = new Backend\BookManager();
        $ouvrage = $bookManager->getBook($_GET['ouv_id']);
        $article = $postManager->getPost($_GET['id']);
        if ($article) {
            require('view/backend/updatePostView.php');
        } else {
            throw new Exception('Chapitre inconnu');
        }
    }
//   ═════════════════════════════════════════════   
//   CHAP-    Appel formulaire Nouveau chapitre chapitre 
//              recupere données ouvrage et chapitre max 
 //   ═════════════════════════════════════════════   
    public function formNewPost($ouvId) {

        $postManager = new Backend\PostManager();
        $bookManager = new Backend\BookManager();
        $ouvrage = $bookManager->getBook($ouvId);
        $chapter = $postManager->getMaxChapter($ouvId);
        require('view/backend/newPostView.php');
    }
   //   ═════════════════════════════════════════════   
//             CHAP       Misee à jour du chapitre formatage avec regex  
    //   ═════════════════════════════════════════════   
    public function majPost() {
        $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
        $id = preg_replace('([^0-9]+)', '', $_POST['art_id']);
        $keywords = preg_replace($regex, '', $_POST['art_keywords']);
        $description = preg_replace($regex, '', $_POST['art_description']);
        $chapter = preg_replace($regex, '', $_POST['art_chapter']);
        $subtitle = preg_replace($regex, '', $_POST['art_subtitle']);
        $titre = preg_replace($regex, '', $_POST['art_title']);
        $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
        $ouvrage = preg_replace($regex, '', $_POST['ouvrage']);
        $image = $this->uploadImage($id);
        $auteur = preg_replace($regex, '', $_POST['auteur']);
        $statut_post = preg_replace($regex, '', $_POST['statut_post']);
        $postManager = new Backend\PostManager();
        $idStatutDuPost = $postManager->idStatut($statut_post);
        $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $id, $description, $keywords, $image, $ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
        if ($article) {
            $_GET['id'] = $id;
            $_GET['ouv_id'] = $ouvId;
            $this->post();
        } else {
            throw new Exception('Le chapitre est introuvable');
        }
    }

///   ═════════════════════════════════════════════   
//CHAP       fonction upload image du chapitre  dans le formulaire de maj 
//                           ou création du chapitre
//   ═════════════════════════════════════════════   
    public function uploadImage($postId) {

        $postManager = new Backend\PostManager();
        $article = $postManager->getPost($postId);
        $image = $article['ART_IMAGE']; /// récupération


        if (!empty($_FILES['uploaded_file']['name'])) {
            $extensions_valides = array('.jpg');
//1. strrchr renvoie l'extension avec le point (« . »).
//2. strtolower met l'extension en minuscules.
            $extension_upload = strtolower(strrchr($_FILES['uploaded_file']['name'], '.'));
            $path = "uploads/";
            $_FILES['uploaded_file']['name'] = 'chapitre-' . $article['ART_CHAPTER'] . $extension_upload;

// On récupère les dimensions de l'image

            $dimensions = getimagesize($_FILES['uploaded_file']['tmp_name']);
            $width_orig = $dimensions[0];
            $height_orig = $dimensions[1];
            //$ratio_orig = $width_orig / $height_orig;


            $path = $path . basename($_FILES['uploaded_file']['name']);

            if (in_array($extension_upload, $extensions_valides)) {
                // Si le fichier existe on l'efface
                if (is_file($path)) {
                    unlink($path);
                }
                move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path);
                $message = "Le fichier " . basename($_FILES['uploaded_file']['name']) .
                        " à été uploadé";
                $image = $_FILES['uploaded_file']['name'];
                // On redimensionne le fichier puis on l'enregistre
                // Définition de la largeur et de la hauteur maximale
                $width = 1600;
                $height = 550;
                $ratio = $width / $height;
                $image_dst = imagecreatetruecolor($width, $height);
                $image_src = imagecreatefromjpeg($path);
                imagecopyresampled($image_dst, $image_src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                imagejpeg($image_dst, $path, 100);
            } else {
?>
                        <script>
                            alert("Une erreur s'est produite durant l'opération Veuillez vérifier le format du fichier( jpg ). Veuillez réessayer . Si le problème persiste , contactez votre administrateur ");
                        </script>
                <?php

            }
        }
        return $image;
    }
  //   ═════════════════════════════════════════════     
 //   CHAP-            Ajoute un chapitre à la base de données  - 
 //        permet l'upload d'une image et insertion du path dans la table 
 //         gere l'envoi des messages aux utilisateurs concernés
   //   ═════════════════════════════════════════════   
 public function ajouterPost() {
        $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
        $keywords = preg_replace($regex, '', $_POST['art_keywords']);
        $description = preg_replace($regex, '', $_POST['art_description']);
        $chapter = preg_replace($regex, '', $_POST['art_chapter']);
        $subtitle = preg_replace($regex, '', $_POST['art_subtitle']);
        $titre = preg_replace($regex, '', $_POST['art_title']);
        $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
        $auteur = preg_replace($regex, '', $_POST['auteur']);
        $statut_post = preg_replace($regex, '', $_POST['statut_post']);

        $postManager = new Backend\PostManager();
        $idStatutDuPost = $postManager->idStatut($statut_post);
        $dernierId = $postManager->addPost($chapter, $titre, $subtitle, $_POST['art_content'], $description, $keywords, $ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
        $image = $this->uploadImage($dernierId);
        $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $dernierId, $description, $keywords, $image, $ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
        $_GET['id'] = $dernierId;
        $_GET['ouv_id'] = $ouvId;
        //Messagerie
        $userManager = new Backend\UsersManager();
        $bookManager = new Backend\BookManager();
        $book = $bookManager->getBooksRights($ouvId);
        $article = $postManager->getPost($_GET['id']);
        $root = $userManager->listSuperadmin();
        $objet = 'Création d\'un chapitre ';
        $contenu = 'Un chapitre est ajouté à l\ouvrage  <b> ' . $ouvId . ' de titre :' . $titre . ' </b> Résumé contenu : <i>' . substr($_POST['art_content'], 0, 150) . '(...)</i>';
        $auteurId = $auteur;
        while ($adminBook = $book->fetch()) {

            $this->messageSystem($adminBook['p5_USERS_USER_ID'], $auteurId, $objet, $contenu);
            // envoyer mess aux administrateur de louvrage
        }
        while ($rootBook = $root->fetch()) {

            $this->messageSystem($rootBook['USER_ID'], $auteurId, $objet, $contenu);
            // envoyer mess aux Superadmin 
        }
        /// fin messagerie
        $this->post();
    }

//   ═════════════════════════════════════════════      
//   CHAP  -       Publie un chapitre ( rendre visible )
//   ═════════════════════════════════════════════   
public function publierPost() {

    $postManager = new Backend\PostManager();
    $post = $postManager->enablePost($_GET['id']);
    if($post){
    $article = $postManager->getPost($_GET['id']);
   $objet = 'Publication  ';
   $contenu = 'La Chapitre ( '.$article['ART_ID'].') <b> est publié en ligne </b> de contenu : <i>'.substr($article['ART_CONTENT'], 0, 150).'(...)</i>';
   $auteurId =$article['ART_AUTEUR'];
   $this ->messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);   
    $this->post();
}else{
 throw new Exception ('Chapitre inconnu ');   
}
}
//   ═════════════════════════════════════════════      
//   CHAP  -    changement du statut du chapitre - desactive le post car  modif
//   ═════════════════════════════════════════════   
public function changementStatut($libelleStatut){
  $postManager = new Backend\PostManager();
  $idStatut = $postManager->idStatut($libelleStatut);// on recupere l id du libelle 
  $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'],$_GET['id']);
  
  if($post){
    $article = $postManager->getPost($_GET['id']);
   $objet = 'Changement de statut ';
   $contenu = 'La Chapitre ( '.$article['ART_CHAPTER'].' titre:)'.$article['ART_TITLE'].'. <b>Change de statut pour '.$libelleStatut.'</b> de contenu : <i>'.substr($article['ART_CONTENT'], 0, 150).'(...)</i>';
   $auteurId =$article['ART_AUTEUR'];
   $this->messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);
    $this->desactiverPost();
   // execution de post() dans desactiverPOst()
}else{
 throw new Exception ('Changement impossible');   
} 
}

//   ═════════════════════════════════════════════
//  METHODES LIEES AUX MESSAGES INTERNES ══ METHODES LIEES AUX MESSAGES INTERNES
//   ═════════════════════════════════════════════

//  ═════════════════════════════════════════════   
//    MESS-    formulaire nouveau message
//   ═════════════════════════════════════════════   
public function formNewMessage(){
   // /liste des utilisateurs
     $userManager = new Backend\UsersManager();
     $users= $userManager -> getUsers();
  require('view/backend/newMessageView.php');      
}



//
//////   ═════════════════════════════════════════════   
//    MESS-    Messagerie Reception traitement du message en lu nonlu et corbeille
//   ═════════════════════════════════════════════   
public function ordreMessagerieRecus() {
    $messageManager = new Backend\MessageManager();
    $tab = $_POST['listeId'];
    if (isset($_POST['nonlu'])) {
        $messages = $messageManager->setMessagesNotRead($tab);
    } elseif (isset($_POST['lu'])) {
        $messages = $messageManager->setMessagesRead($tab);
    } elseif (isset($_POST['corbeille'])) {
        $messages = $messageManager->desactiverMessagesDestinataire($tab);
    }

    $this->messagerie();
}
//═════════════════════════════════════════════
//  MESS- Messagerie Envoyés  traitement du message en lu nonlu et corbeille 
//═════════════════════════════════════════════
public function ordreMessagerieEnvoyes() {
    $messageManager = new Backend\MessageManager();
    $tab = $_POST['listeId'];
if (isset($_POST['corbeille'])) {
        $messages = $messageManager->desactiverMessagesExpediteur($tab);
    }

    $this->messagerie();
}
//   ═════════════════════════════════════════════
//        MESS              fonction messagerie de base 
//═════════════════════════════════════════════
public function messageSystem($destinataire, $expediteur, $objet, $contenu) {
        
        $messageManager = new Backend\MessageManager();
        $envoi = $messageManager->addMessage($destinataire, $expediteur, $objet, $contenu);
    }
    //   ═════════════════════════════════════════════
    //    MESS- envoi message interne
    //   ═════════════════════════════════════════════
public function envoiMessage($destinataire,$expediteur, $objet, $contenu) {

    $this->messageSystem($destinataire, $expediteur, $objet, $contenu);
    $this ->messagerie();
}
  //   ═════════════════════════════════════════════
    //    MESS- Envoi message suite signalement commentaire
    //   ═════════════════════════════════════════════
public function messageSignalementCommentaire(){
//Messagerie
    $userManager = new Backend\UsersManager();
    $bookManager = new Backend\BookManager();
    $book = $bookManager->getBooksRights($_GET['ouv_id']);
    //$article = $postManager->getPost($_GET['id']);
    $root = $userManager -> listSuperadmin();
    $objet = 'Signalement commentaire  ';
    $contenu = 'Un signalement de commentaire est levé par '.$_SESSION['user'];
    //$auteurId = $auteur;
    while ($adminBook = $book->fetch()) {
    
    $this->messageSystem($adminBook['p5_USERS_USER_ID'],$_SESSION['userId'], $objet, $contenu);
    // envoyer mess aux administrateur de louvrage
    }
     while ($rootBook = $root->fetch()) {
    
    $this->messageSystem($rootBook['USER_ID'],$_SESSION['userId'] , $objet, $contenu);
    // envoyer mess aux Superadmin 
    }
    /// fin messagerie

}
//   ═════════════════════════════════════════════
    //    MESS- Envoi message demande d'inscription
    //   ═════════════════════════════════════════════
    public function messageInscription() {
        $userManager = new Backend\UsersManager();
        $root = $userManager->listSuperadmin();
        $objet = 'Demande inscription   ';
        $contenu = 'demande inscription pour :  ' . $_POST['nom'].' '.$_POST['prenom'].' pseudo :'.$_POST['pseudo'].' email : '.$_POST['email'];
        while ($rootBook = $root->fetch()) {

            $this->messageSystem($rootBook['USER_ID'], $rootBook['USER_ID'], $objet, $contenu);
            // envoyer mess aux Superadmin 
        }
    }

    //   ═════════════════════════════════════════════
    //    MESS- recuperation messages de la "boîte" BDD
    //   ═════════════════════════════════════════════

public function messagerie() {
    $messageManager = new Backend\MessageManager();
    $messagesReçus = $messageManager->getMessagesReceived($_SESSION['userId']);
    $messagesEnvoyes = $messageManager->getMessagesSend($_SESSION['userId']);
    $message = $messageManager->nbMessagesNonLu($_SESSION['userId']);
    $nbMess = $message->fetchAll();
    $_SESSION['nbMess'] = $nbMess[0]['NB'];
    
    require('view/backend/messagerieView.php');
}

//═══════════════════════════════════════  
//   MESS -  Liste des messages non supprimés pour un utilisateur
//═══════════════════════════════════════
//
public function listMessage($userId) {
    $messageManager = new Backend\MessageManager();
    $messages = $messageManager->getMessages($userId);
    require('view/backend/messagesView.php');
}
//═════════════════════════════════════════════
//                   METHODES LIEES AUX SUITES 
//═════════════════════════════════════════════
//
////═════════════════════════════════════════════
 //       SUI-          Integration de la suite au texte du chapitre
 //       Aposition du nom de l'auteur  -suppression de la suite deu scrutin et du vote 
//═════════════════════════════════════════════
public function integrationSuite($suiteId,$auteur,$voteId){
   $postManager = new Backend\PostManager(); 
   $voteManager = new Backend\VoteManager(); 
   
   $suite = $postManager -> getPost($suiteId);
   $contenuSuite= $suite['ART_CONTENT'].'<br /><b> Fin suite de '.$auteur.'.</b><br />';
   $precedent = $suite['ART_PRECEDENT'];
   $insertionAuteur = '<b><br /><br />Suite de '.$auteur.'<br /><br /></b>' ;
   $concatenation = $postManager ->concatSuite($insertionAuteur,$contenuSuite,$precedent);
   if($concatenation){
    $suiteId = $suite['ART_ID'];
    $effaceScores = $voteManager ->delScores($voteId);
    $effaceVote = $voteManager ->delVote($voteId)  ;      
    $delSuite = $postManager ->delPost($suiteId);
    
   }else {
     throw new exception ('Echec de l\intégration ');
}
   $this ->cokpit();
}
////═════════════════════════════════════════════
 //       SUI-          Suppression  suite a la bdd   
//═════════════════════════════════════════════
public function supprimeSuite() {

    $postManager = new Backend\PostManager();
     $article = $postManager->getPost($_GET['id']);
    $suite = $postManager->delPost($_GET['id']);
    
    if ($suite) {
        $objet = 'Supression '.$article['ART_ID'];
        $contenu = 'Suppression du contenu suivant : ' . $article['ART_CONTENT'];
        $auteurId =$article['ART_AUTEUR'];
        $this ->messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);
        $_GET['id'] = $_GET['precedent'];

        $this->post();
    } else {
        throw new Exception('Elément inconnu');
    }
}

//═════════════════════════════════════════════
//       SUI         Fonction ajouter suite a la bdd  
//        Gere les messages internes aux utilisateurs concernés 
//═════════════════════════════════════════════
    public function ajouterSuite() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
        $chapter = preg_replace($regex, '', $_POST['art_chapter']);
    $precedent = preg_replace($regex, '', $_POST['precedent']);
    $contenu = preg_replace($regex, '', $_POST['art_content']);
    $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
    $auteur = preg_replace($regex, '', $_POST['auteur']);
    $statut_post = preg_replace($regex, '', $_POST['statut_post']);
    $postManager = new Backend\PostManager();
    $idStatutDuPost=$postManager->idStatut($statut_post);
    $dernierId = $postManager->addSuite($contenu, $ouvId, $precedent, $auteur, $idStatutDuPost['STATUT_POST_ID']);
    
   if(!$idStatutDuPost){
       throw new Exception('Pb idstatutdupost');
       
   }
    if(!$dernierId){
        throw new Exception('Pb addsuite');
        
    }
     $_GET['id'] = $dernierId;
    $_GET['ouv_id']=$ouvId;
    
    //Messagerie interne
    $userManager = new Backend\UsersManager();
    $bookManager = new Backend\BookManager();
    $book = $bookManager->getBooksRights($ouvId);
    $article = $postManager->getPost($_GET['id']);
    $root = $userManager -> listSuperadmin();
    $objet = 'Création d\'une suite ';
    $contenu = 'Une suite est ajoutée à l\ouvrage  <b> ' . $ouvId . '  </b> Résumé contenu : <i>' . substr($_POST['art_content'], 0, 150) . '(...)</i>';
    $auteurId = $auteur;
    while ($adminBook = $book->fetch()) {
    
    $this ->messageSystem($adminBook['p5_USERS_USER_ID'],$auteurId, $objet, $contenu);
    // envoyer mess aux administrateur de louvrage
    }
     while ($rootBook = $root->fetch()) {
    
    $this ->messageSystem($rootBook['USER_ID'],$auteurId , $objet, $contenu);
    // envoyer mess aux Superadmin 
    }
    /// fin messagerie
    
    $this ->listPosts($ouvId);
      
}

 //═════════════════════════════════════════════   
//   SUI -      Fonction mise à jour (update) de la suite   
//        Lorsqu'on met à jour un article on le desactive par defaut 
//═════════════════════════════════════════════
public function majSuite() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
    $id = preg_replace('([^0-9]+)', '', $_POST['art_id']);
   
    $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
    $ouvrage = preg_replace($regex, '', $_POST['ouvrage']);
    $precedent = preg_replace($regex, '', $_POST['precedent']);
    $auteur = preg_replace($regex, '', $_POST['auteur']);
    $statut_post = preg_replace($regex, '', $_POST['statut_post']);
    $postManager = new Backend\PostManager();
    $idStatutDuPost=$postManager->idStatut('REDACTION');
    //$idStatutDuPost=$postManager->idStatut($statut_post);
    $suite = $postManager->updateSuite($_POST['art_content'], 1, $id, $ouvId,$precedent, $auteur, $idStatutDuPost['STATUT_POST_ID']);
    if($suite){
    $_GET['id'] = $precedent;
    $_GET['ouv_id']=$ouvId;
    $this ->post();
}else{
    throw new Exception ('Le chapitre est introuvable');
}

    }

//═════════════════════════════════════════════
// SUI -          formulaire saisie nouvelle suite 
//═════════════════════════════════════════════
    public function formNewSuite($precedent, $ouvId) {


        $postManager = new Backend\PostManager();
        $bookManager = new Backend\BookManager();
        $ouvrage = $bookManager->getBook($ouvId);

        require('view/backend/newSuiteView.php');
    }
    
   
//═════════════════════════════════════════════
//        SUI -          Désactiver une suite 
//═════════════════════════════════════════════
//
    public function desactiverSuite() {

        $postManager = new Backend\PostManager();
        $suite = $postManager->disablePost($_GET['id']);
        if ($suite) {
            $article = $postManager->getPost($_GET['id']);
            $objet = 'Désactivation Suite  ';
            $contenu = 'La suite n° ( ' . $article['ART_ID'] . ') <b> est désactivée </b> de contenu : <i>' . substr($article['ART_CONTENT'], 0, 150) . '(...)</i>';
            $auteurId = $article['ART_AUTEUR'];
            $this->messageSystem($auteurId, $_SESSION['userId'], $objet, $contenu);
            $_GET['id'] = $_GET['precedent'];
            $this->post();
        } else {
            throw new Exception('Chapitre inconnu 256');
        }
    }

//═════════════════════════════════════════════
//  SUI -  Changer le statut de la suite (redaction, propose, refuse,
//   accepte, vote + message aux auteurs) 
//   la suite est desactivé en cas de modif 
//═════════════════════════════════════════════
 
 public function changementStatutSuite($libelleStatut) {
        $postManager = new Backend\PostManager();
        $voteManager = new Backend\VoteManager();
        $idStatut = $postManager->idStatut($libelleStatut); // on recupere l id du libelle 
        $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'], $_GET['id']);
        // pour test throw new Exception ($libelleStatut.' '.$idStatut['STATUT_POST_ID'].' et '.$_GET['id']);
        if ($post) {
            if ($libelleStatut == 'VOTE') {
                $vote = $voteManager->vote($_GET['id']); // vote début maintenant durée 15 jours statut vote ouvert 
            }
            $article = $postManager->getPost($_GET['id']);
            $objet = 'Changement de statut ';
            $contenu = 'La suite proposée ( ' . $article['ART_ID'] . ') <b>Change de statut pour ' . $libelleStatut . '</b> de contenu : <i>' . substr($article['ART_CONTENT'], 0, 150) . '(...)</i>';
            $auteurId = $article['ART_AUTEUR'];
            $this->messageSystem($auteurId, $_SESSION['userId'], $objet, $contenu);
            $this->desactiverSuite();
            // execution de post() dans desactiverPOst()
        } else {
            throw new Exception('Changement impossible');
        }
    }

//═════════════════════════════════════════════
//          SUI -           Appel formulaire modification Suite 
//═════════════════════════════════════════════
public function formModifySuite() {
    $postManager = new Backend\PostManager();
    $bookManager = new Backend\BookManager();
    $ouvrage = $bookManager -> getBook($_GET['ouv_id']);
    $suite = $postManager->getSuite($_GET['id']);
    if($suite){
    require('view/backend/updateSuiteView.php');
}else {
    throw new Exception ('Chapitre inconnu ');
}
}
//═════════════════════════════════════════════
//                  METHODES LIEES AUX UTILISATEURS 
////═════════════════════════════════════════════
//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════
//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════
//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════//══ METHODES LIEES AUX UTILISATEURS ═════
 
//═════════════════════════════════════════════
//   USER-         Renvoi false si uner inconnu ou pb d'identifiant 
//        FONCTION verifuser() instancie UsersManager méthode connexion() 
//         récupère en varuiable de session userId et pseudo 
//         recupere nb message non lu 
//═════════════════════════════════════════════


public function verifUser() {
    $userValid = FALSE;
    $userManager = new Backend\UsersManager();
    $user = $userManager->connexion(($_POST['usrname']), ($_POST['passwd']));// pseudo + passwd
    if ($user != FALSE) {
        $_SESSION['userId']= $user[0]['USER_ID'];
        $_SESSION['user'] = $_POST['usrname'];
        $_SESSION['superAdmin']= $user[0]['ROOT'];
        $userValid = TRUE;
   $messageManager = new Backend\MessageManager();
   $message = $messageManager->nbMessagesNonLu($_SESSION['userId']);
   $nbMess = $message->fetchAll();
   $_SESSION['nbMess']= $nbMess[0]['NB'];
    } else {
       $userValid = FALSE;
   }
   return $userValid;
}

//╔══════════════════════════════════════════════╗  
//  USER  si identification ok on affiche la page identification sinon page erreur 
//╚══════════════════════════════════════════════╝
//
public function identification($bool) {

    $param = $bool;
    if ($param === FALSE) {
        require('view/backend/erreurView.php');
    } else {

        require_once ('view/backend/identificationView.php');
    }
}


//═════════════════════════════════════════════
//   USER-         Changement password utilisateur 
//═════════════════════════════════════════════
public function changePsswd() {
    
    if ((!empty($_POST['oldmdp']))AND ( strlen($_POST['mdp']) >= 6)AND ($_POST['mdp']===$_POST['remdp'])) {
        $pseudo = $_SESSION['usrname'];
        $userManager = new Backend\UsersManager();
        $connexion = $userManager->connexion($pseudo, $_POST['oldmdp']);
        if ($connexion) {
         $user = $userManager->updatePsswd($_POST['mdp'],$pseudo);
         $message = 'Mot de passe enregistré.';
         
        } else {
            $message = 'Identifiant incorrects ou le nouveau  mot de passe doit être au moins égal à 6 caractères Sinon Réessayez plus tard! ';
        }
    }else {
        $message = ' Un champ ne peut être vide.';
    }
    require ('view/backend/updatePasswdView.php');
}

//═════════════════════════════════════════════
//   USER-         code aléatoire pour procedure motde passe oublie 
//═════════════════════════════════════════════
public function codeValidation(){
    $chaine="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $code='';
    $longeurChaine= strlen($chaine);
    echo $longeurChaine;
    $nbCaractereCode= 8;
    for ($i = 0; $i < $nbCaractereCode; $i++)
 {
 $code .= $chaine[rand(0, $longeurChaine - 1)];
 }
   return $code;
}
//═════════════════════════════════════════════
//   USER-         message e amil  pour procedure motde passe oublie 
//═════════════════════════════════════════════ 
public function messagePasswdOublie($destinataire, $texte) {

    $passage_ligne = "\n";
    $to = $destinataire;

// Sujet du message 
    $subject = "Votre demande depuis le blog de  Jean FORTEROCHE";

// Corps du message, écrit en texte et encodage iso-8859-1
    $message = "Suite à votre demande, veuillez prendre note du code suivant :".$texte."\n";
    $message .= "Vous voudrez bien vous connecter et changer votre mot de passe dès la première connexion \n";
    $message .= "Bonne réception \n Webmaster blog de jean FORTEROCHE";
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
//═════════════════════════════════════════════
//   USER-          procedure motde passe oublie 
//═════════════════════════════════════════════ 
public function motDePasseOublie($mail) {
    // le mail existe il ?
     $userManager = new Backend\UsersManager();
     $emailExist = $userManager->emailExist($mail);
    if($emailExist){
        //S'il existe, on fabrique le code , on update le code , on envoi le message
        $pseudo=$emailExist[0]['USER_PSEUDO'];
        $code = $this->codeValidation();
        //echo $pseudo;
       // echo " code : ".$code;
        $updatePassword = $userManager->updatePsswd($code,$pseudo);
        $message = $this->messagePasswdOublie($mail,$code);
            //echo $message;
    }else {
        //echo "pas de mail exist";
    }
    // on retourne sur la page d'identification
    require ('view/backend/identificationView.php');   
} 
//═════════════════════════════════════════════
//   USER-          suppression user
//═════════════════════════════════════════════ 
public function supprimeUser($user_id){
 if(($_SESSION['superAdmin']==1)AND ($_SESSION['userId']<> $user_id)){
  $usersManager = new Backend\UsersManager();
  $delUser= $usersManager ->delUser($user_id); 
     
$this->cokpit();  
    }else{
        throw new Exception('Vous n\'avez pas les droits d\'accès pour effectuer cette opération ');
    }
}

//═════════════════════════════════════════════
//   USER-          initialisation  user (mot de passe aléatoire )
//═════════════════════════════════════════════ 

public function initUser($user_id){
 if(($_SESSION['superAdmin']==1)AND ($_SESSION['userId']<> $user_id)){
  $usersManager = new Backend\UsersManager();
  $passwd = $usersManager -> passwordUser(codeValidation());
  $initUser= $usersManager ->initUser($user_id,$passwd); 
     
$this->cokpit();  
    }else{
        throw new Exception('Vous n\'avez pas les droits d\'accès pour effectuer cette opération ');
    }
}
//═════════════════════════════════════════════
//   USER-          update  user 
//═════════════════════════════════════════════ 

public function majUser($userId, $userName, $userLastname, $userPseudo, $userMail, $userStatut){
 if($_SESSION['superAdmin']==1){
  $usersManager = new Backend\UsersManager();
  $majUser= $usersManager ->updateUser($userId,$userName, $userLastname, $userPseudo, $userMail, $userStatut); 
   $this->cokpit();  
    
}
}
//═════════════════════════════════════════════
//   USER-          get données pour un user
//═════════════════════════════════════════════ 

public function userGet(){
  $usersManager = new Backend\UsersManager();   
   $user= $usersManager ->getUser($_GET['id']); 
    
 require('view/backend/updateUserView.php');    
}
//═════════════════════════════════════════════
//   USER-          ADD user 
//═════════════════════════════════════════════ 


public function ajouterUser($userName, $userLastname, $userPseudo, $userMail,$userPasswd,$userStatut) {
 if($_SESSION['superAdmin']==1){
  $usersManager = new Backend\UsersManager();
  $newUser= $usersManager ->addUser($userName, $userLastname, $userPseudo, $userMail,$userPasswd, $userStatut); 
   $this->cokpit();  
    
}   
}
//═════════════════════════════════════════════
//   USER-          acces formulaire nouvel utilisateur
//═════════════════════════════════════════════ 

public function formNewUser() {

    require('view/backend/newUserView.php');
}

//═════════════════════════════════════════════
//                 METHODES LIEES AUX VOTES 
//═════════════════════════════════════════════
//

//═════════════════════════════════════════════
//       VOTE          controle les scrutin /date butoir  
//═════════════════════════════════════════════
public function voteControle() {
    
   
    $voteManager = new Backend\VoteManager();
    $vote= $voteManager ->controleVote();
    if($vote){
   
    }else{
    throw new Exception('pas de vérification des votes');
        
    }
}
 //═════════════════════════════════════════════
//       VOTE          fonction vote mise du bulletin dans l'urne  
//═════════════════════════════════════════════   
   public  function vote() {
    
   
    $voteManager = new Backend\VoteManager();
    $vote= $voteManager ->jeVote($_GET['bulletin'],$_GET['id']);
    $_GET['id'] =$_GET['precedent'];
    
    $this->post();
    
}
//═════════════════════════════════════════════
//       VOTE          verifie que l'user na pas deja vote dans le scrutin 
//═════════════════════════════════════════════
public function dejaVote (){
   $voteManager = new Backend\VoteManager();
   $aVote= $voteManager ->aVote($_GET['id']);
   
    
} 
//═════════════════════════════════════════════
//       VOTE          modification de la date butoir d'un scrutin  
//═════════════════════════════════════════════

public function modifDureeVote($vote_id,$dateFin,$duree){
  $voteManager= new Backend\VoteManager();   
 $voteprolong= $voteManager-> updateVoteDuree($vote_id,$dateFin,$duree);     
  $this->cokpit(); 
}
//═════════════════════════════════════════════
//       VOTE          Cloture le scrutin
//═════════════════════════════════════════════
public function closeVote($art_id){
 $voteManager= new Backend\VoteManager();   
 $voteClose= $voteManager-> fermetureVote($art_id);    
 $this->cokpit(); 
}
//═════════════════════════════════════════════
//       VOTE          Ouvre le scrutin
//═════════════════════════════════════════════
public function openVote($art_id){
 $voteManager= new Backend\VoteManager();   
 $voteClose= $voteManager-> ouvertureVote($art_id);    
 $this->cokpit(); 
// Supprime le vote les votes les scores et change le statut de la  suite en ACCEPTE
}
//═════════════════════════════════════════════
//       VOTE          Supprime le scrutin
//═════════════════════════════════════════════
function supprimeVote($vote_id,$art_id){
 $voteManager= new Backend\VoteManager(); 
 $postManager = new Backend\PostManager();
 $voteDel= $voteManager-> delVote($vote_id);
 $idStatut = $postManager->idStatut('ACCEPTE');// on recupere l id du libelle 
 $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'],$art_id);
 $this->cokpit(); 
}
//
//
//
//
//═════════════════════════════════════════════
//                 METHODES LIEES AUX OUVRAGES 
//═════════════════════════════════════════════
//
//
//   ═════════════════════════════════════════════   
//OUV       fonction upload image ouvrage  
//   ═════════════════════════════════════════════   

    public function uploadImageBook($bookId) {

        $bookManager = new Backend\BookManager();
        $book = $bookManager->getBook($bookId);
        $image = $book['OUV_IMAGE']; /// récupération


        if (!empty($_FILES['uploaded_file']['name'])) {
            $extensions_valides = array('.jpg');
//1. strrchr renvoie l'extension avec le point (« . »).
//2. strtolower met l'extension en minuscules.
            $extension_upload = strtolower(strrchr($_FILES['uploaded_file']['name'], '.'));
            $path = "uploads/";
            $_FILES['uploaded_file']['name'] = 'ouvrage-' . $book['OUV_ID'] . $extension_upload;

// On récupère les dimensions de l'image

            $dimensions = getimagesize($_FILES['uploaded_file']['tmp_name']);
            $width_orig = $dimensions[0];
            $height_orig = $dimensions[1];
            //$ratio_orig = $width_orig / $height_orig;


            $path = $path . basename($_FILES['uploaded_file']['name']);

            if (in_array($extension_upload, $extensions_valides)) {
                // Si le fichier existe on l'efface
                if (is_file($path)) {
                    unlink($path);
                }
                move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path);
                $message = "Le fichier " . basename($_FILES['uploaded_file']['name']) .
                        " à été uploadé";
                $image = $_FILES['uploaded_file']['name'];
                // On redimensionne le fichier puis on l'enregistre
                // Définition de la largeur et de la hauteur maximale
                $width = 1600;
                $height = 550;
                $ratio = $width / $height;
                $image_dst = imagecreatetruecolor($width, $height);
                $image_src = imagecreatefromjpeg($path);
                imagecopyresampled($image_dst, $image_src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                imagejpeg($image_dst, $path, 100);
            } else {
?>
                        <script>
                            alert("Une erreur s'est produite durant l'opération Veuillez vérifier le format du fichier( jpg ). Veuillez réessayer . Si le problème persiste , contactez votre administrateur ");
                        </script>
                <?php

            }
        }
        return $image;
    }
//
//
//════════════════════════════════════════  
//     OUV      Ouvrage depuis un ID
//════════════════════════════════════════
//
public function book() {
    $bookManager = new Backend\BookManager();
    $book = $bookManager->getBook($_GET['ouv_id']);
    require('view/backend/bookView.php');
}

//════════════════════════════════════════  
//   OUV-         Ouvrage depuis un ID pour un utilisateur 
//════════════════════════════════════════
//
public function bookUser() {
    $bookManager = new Backend\BookManager();

    $book = $bookManager->getBookUser($_GET['ouv_id']);
    require('view/backend/bookView.php');
}

//╔════════════════════════════════════════╗  
//   OUV Liste des ouvrages pour un utilisateur
//╚════════════════════════════════════════╝
// 
public function listOuvragesUser($userId) {
    $bookManager = new Backend\BookManager();
    $books = $bookManager->getBooksUser($userId);

    require('view/backend/listBooksView.php');
}

//╔════════════════════════════════════════╗  
//  OUV Liste des ouvrages 
//╚════════════════════════════════════════╝
// 
public function listOuvrages() {
    $bookManager = new Backend\BookManager();//new Backend\BookManager();
    $books = $bookManager->getBooks();

    require('view/backend/listBooksView.php');
}
//═════════════════════════════════════════════
//     OUV          suppression acces a ouvrage 
//═════════════════════════════════════════════

public function supprimeAccesOuvrage($userId, $ouvId, $statutId) {
    $bookManager = new Backend\BookManager();
    $delAcces = $bookManager->delBookAcces($userId, $ouvId, $statutId);
    $this->accesBook($ouvId);
    $objet='Modification accès';
    $contenu ='Votre accès à ouvrage n°'.$ouvId.' est supprimé' ;
     $this->messageSystem($userId,$_SESSION['userId'], $objet, $contenu);
    
}
//═════════════════════════════════════════════
//     OUV         formulaire nouvel acces a ouvrage 
//═════════════════════════════════════════════


public function formNewBookAcces($ouvId) {
//liste des utilisateurs
     $userManager = new Backend\UsersManager();
     $users= $userManager -> getUsers();
     $statuts = $userManager ->getStatuts();
//liste des statut 
    //vérification user un seul par ouvrage.........f
require('view/backend/newBookAccesView.php');
}
 
//══════════════════════════════════════════  
//   OUV- vue formulaire nouvel ouvrage
//══════════════════════════════════════════
//
public function formNewBook() {

    require('view/backend/newBookView.php');
}




//══════════════════════════════════════════  
//   OUV -   Mise à jour d'un Ouvrage 
//══════════════════════════════════════════
//
public function majBook() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";

    $id = preg_replace('([^0-9]+)', '', $_POST['ouv_id']);
    $keywords = preg_replace($regex, '', $_POST['ouv_keywords']);
    $description = preg_replace($regex, '', $_POST['ouv_description']);
    $subtitle = preg_replace($regex, '', $_POST['ouv_soustitre']);
    $titre = preg_replace($regex, '', $_POST['ouv_titre']);
    $image = $this->uploadImageBook($id);
    if($_SESSION['superAdmin']==1){
    $bookManager = new Backend\BookManager();
    $book = $bookManager->updateBook($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $id,$image);
    $_GET['ouv_id'] = $id;
    $this->book();
    }else {
    $bookManager = new Backend\BookManager();
    $book = $bookManager->updateBookUser($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $id, $image);
    $_GET['ouv_id'] = $id;
    $this->book();  
        
    }
}
    
//══════════════════════════════════════════  
//   OUV -   Ajouter un acces pour un ouvrage - envoi message à l'utilisateur 
//══════════════════════════════════════════
//
public function addAccesOuvrage($ouvId, $userId, $statutId) {
    $bookManager = new Backend\BookManager();
    $verif = $bookManager->verifAccesBook($ouvId,$userId);
    if ($verif[0][0] == 0) {
        $acces = $bookManager->addAccesBook($ouvId, $userId, $statutId);
        $this->accesBook($ouvId);
        $objet='Modification accès';
    $contenu ='Vous avez un  nouvel pour l\'ouvrage  n°'.$ouvId.' Veuillez vous connecter et vérifier ' ;
     $this->messageSystem($userId,$_SESSION['userId'], $objet, $contenu);
    } else {
       
        throw new Exception('L\'utilisateur a déjà un accès pour cette ouvrage - Vous devez  supprimer l\'accès avant d\'en créer un nouveau ');
    }
}


//═════════════════════════════════════════  
//  OUV -  1 Ouvrage depuis son ID - vue formulaire modification de l'ouvrage
//═════════════════════════════════════════
//
public function formModifyBook() {
    $bookManager = new Backend\BookManager();//new Backend\BookManager();

    $book = $bookManager->getBook($_GET['ouv_id']);

    require('view/backend/updateBookView.php');
}


//══════════════════════════════════════════  
//   OUV -    1 Ouvrage depuis son ID - vue formulaire modification de l'ouvrage
//══════════════════════════════════════════
//
public function formModifyBookUser() {
    $bookManager = new Backend\BookManager();

    $book = $bookManager->getBookUser($_GET['ouv_id']);

    require('view/backend/updateBookView.php');
}



//══════════════════════════════════════════  
//  OUV -  Ajouter un Ouvrage 
//══════════════════════════════════════════
//
 
public function ajouterOuvrage() {

    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";


    $keywords = preg_replace($regex, '', $_POST['ouv_keywords']);
    $description = preg_replace($regex, '', $_POST['ouv_description']);
    $subtitle = preg_replace($regex, '', $_POST['ouv_soustitre']);
    $titre = preg_replace($regex, '', $_POST['ouv_titre']);
    

if($_SESSION['superAdmin']==1){
    
      
    $bookManager = new Backend\BookManager();
    $dernierId = $bookManager->addBook($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords,$image);
    $image = $this->uploadImageBook($dernierId);
    $book = $bookManager->updateBook($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $id,$image);
    $this ->listOuvrages();
}else{
 throw new Exception ('Vous n\'avez pas les droits pour ajouter un ouvrage.');     
}
}




//═════════════════════════════════════════  
//  OUV -  Supprimer  un Ouvrage 
//══════════════════════════════════════════
//
public function supprimeOuvrage() {

    $bookManager = new Backend\BookManager();
    $book = $bookManager->delBook($_GET['ouv_id']);
    $image = $bookManager -> delBookImage($_GET['ouv_id']);
    
    $this->listOuvrages();
   }
 //═════════════════════════════════════════  
//  OUV -  Supprimer  un Ouvrage user
//══════════════════════════════════════════
//  
    public function supprimeOuvrageUser() {

        $bookManager = new Backend\BookManager();
        $book = $bookManager->delBookUser($_GET['ouv_id']);
        $image = $bookManager->delBookImage($_GET['ouv_id']);
      
        $this->listOuvragesUser($_SESSION['userId']);
    }

//══════════════════════════════════════════  
//  OUV -  desactive un ouvrage ainsi que ts les chapitres
//══════════════════════════════════════════
//
public function desactiverBook() {
       
    $bookManager = new Backend\BookManager();
    $postManager = new Backend\postManager();
    $book = $bookManager->disableBook($_GET['ouv_id']);
    $post= $postManager ->disablePostsBook($_GET['ouv_id']);
   if($book AND $post){ 
       $this->book();
     }else{
   throw new Exception('requête non aboutie');
        
    }
}

//══════════════════════════════════════════  
//  OUV -  active un ouvrage sans activer les chapitres
//══════════════════════════════════════════
//
public function activerBook() {
    
  
    $bookManager = new Backend\BookManager();
    
    $book = $bookManager->enableBook($_GET['ouv_id']);
      if($book){
    $this->book();
    }else{
    throw new Exception('requête non aboutie');
        
    }
    
}
//╔══════════════════════════════════════════╗  
//   OUV  active les chapitres d'un ouvrage
//╚══════════════════════════════════════════╝
//
public function activerPostsBook() {
    
   
    $postManager = new Backend\postManager();
    $post= $postManager ->enablePostsBook($_GET['ouv_id']);
    if($post){
    $this->book();
    }else{
    throw new Exception('requête non aboutie');
        
    }
    
}
//══════════════════════════════════════════  
//  OUV -  liste des acces pour un ouvrage
//══════════════════════════════════════════
public function accesBook($ouvId){
       $bookManager = new Backend\BookManager(); 
       $rightsBooks =$bookManager->getBooksRights($ouvId);
     require_once('view/backend/dashBoardBookAccesView.php');
}

//
//
//
//
//═════════════════════════════════════════════
//                 METHODES LIEES AUX COMMENTAIRES 
//═════════════════════════════════════════════
 

//═════════════════════════════════════════════
//      COMM                  active un commentaire 
//═════════════════════════════════════════════
    public function activeComment() {
        $commentManager = new Backend\CommentManager();
        $comment = $commentManager->enableComment($_GET['commId']);
        if ($comment) {
            $this->post();
        } else {
            throw new Exception('Elément inconnu 323');
        }
    }
//═════════════════════════════════════════════
//      COMM           désactive un commentaire
//═════════════════════════════════════════════
public function desactiveComment() {
    $commentManager = new Backend\CommentManager();
    $comment = $commentManager->disableComment($_GET['commId']);
    if($comment){
   $this->post();
}else{
 throw new Exception ('Elément inconnu 337');   
}
}

//╔══════════════════════════════════════════╗  
//   COMM  signal  un commentaire 
//╚══════════════════════════════════════════╝
// 

public function activeSignal() {
    $commentManager = new Backend\CommentManager();
    $comment = $commentManager->enableSignal($_GET['commId']);
    if($comment){
    $this->messageSignalementCommentaire();    
   $this-> post();
}else{
 throw new Exception ('Elément inconnu 351');   
}
}
//╔══════════════════════════════════════════╗  
//  COMM   Supprime le signalement d'un commentaire 
//╚══════════════════════════════════════════╝
// 

public function desactiveSignal() {

    $commentManager = new Backend\CommentManager();
    $comment = $commentManager->disableSignal($_GET['commId']);
    if($comment){
   $this-> post();
}else{
 throw new Exception ('Elément inconnu 366');   
}
}


//══════════════════════════════════════════  
//  COMMUM   fonction tzableau de bord 
//  données users ouvrage scores votes utilisateurs 
//══════════════════════════════════════════
// 

public function cokpit() {
    $bookManager = new Backend\BookManager();
    $usersManager = new Backend\UsersManager();
    $voteManager= new Backend\VoteManager();
    $postManager = new Backend\PostManager();
    $listUsers = $usersManager->getUsers(); // Liste utilisateurs
    $listBooks = $bookManager->getBooks(); //Liste des ouvrages 
    $lesScores = $voteManager -> lesScores();
    $tableauScores = $lesScores->fetchAll();
    $listPosts = $postManager ->
    $votesListe= $voteManager-> voteList(); 
    $votesListeClose= $voteManager-> voteListClose(); 
    require_once('view/backend/dashBoardView.php');
}


//═════════════════════════════════════════════
//     FIN                      FIN CLASS 
//═════════════════════════════════════════════
}

