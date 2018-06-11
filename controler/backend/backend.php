<?php
//╔═════════════════════════════╗  
//           PROJET 5 DWJ OPENCLASSROOMS         
//           CLAUDEY Lionel Avril 2018           
//╚═════════════════════════════╝
require_once('model/backend/PostManager.php');
require_once('model/backend/CommentManager.php');
require_once('model/backend/UsersManager.php');
require_once('model/backend/BookManager.php');
require_once('model/backend/MessageManager.php');
require_once('model/backend/VoteManager.php');
function changePsswd() {
    
    if ((!empty($_POST['oldmdp']))AND ( strlen($_POST['mdp']) >= 6)AND ($_POST['mdp']===$_POST['remdp'])) {
        $pseudo = $_SESSION['usrname'];
        $userManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
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



//╔════════════════════════════════════════╗  
//   Liste des chapitres en résumé 
//╚════════════════════════════════════════╝
// 
function listPosts($ouvId) {
    $id=$ouvId;
  
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $posts = $postManager->getPostsResume($id);
    $postsSuite=$postManager->getPostsSuite($id);
   
    require('view/backend/listPostsView.php');
}

//╔════════════════════════════════════════╗  
//   Liste des chapitres en résumé 
//╚════════════════════════════════════════╝
//
function listPostsResume($ouvId) {
    $id=$ouvId;
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    
    $posts=$postManager->getPostsResume($id);
   

    require('view/backend/listPostsView.php');
}


//╔════════════════════════════════════════╗  
//   1 chapitre depuis ID + les commentaires de ce chapitre
//╚════════════════════════════════════════╝
//
function post() {

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $commentManager = new OpenClassrooms\DWJP5\Backend\Model\CommentManager();
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\bookManager();
    $ouvrage = $bookManager->getBook($_GET['ouv_id']);
    $article = $postManager->getPost($_GET['id']);
    $statutPost = $postManager->libelleStatutPost($article['p5_statut_post_STATUT_POST_ID']);
    $suites = $postManager->getSuivants($_GET['id']);
    $voteManager = new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
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
        throw new Exception('Chapitre inconnu 74');
    }
}
// recherche enfant de commentaire
function rechercheEnfant($tableau,$id,$statut){
    echo'<ul>';
    global $data;
    
    foreach($tableau as $cle => $element){
       
     if($tableau[$cle]['COMM_PARENT']== $id){
       echo '<i class="fa fa-arrow-down fa-2x"></i><li class = "listComm"> ';
       echo 'le stat : '.$statut;
      require('view/backend/commentViewChild.php'); 
     echo'</li><ul>';
         rechercheEnfant($tableau,$tableau[$cle]['COMM_ID'],$statut);
         echo'</ul>'; 
     }else{
  
    } } 
    echo'</ul>'; 
}
//function listeVotes(){
//      $voteManager= new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
//        $votesListe= $voteManager-> voteList(); 
//        return $votesListe;
//}

//Fonction depouillement scrutin 
//function depouillementYes($suite_id){
//    $voteManager= new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
//    $voteId= $voteManager-> quelVote($suite_id);
//    $scoreYes= $voteManager->countScoreYes($voteId);
//    return $scoreYes;
//    }
//function depouillementNo($suite_id){
//    $voteManager= new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
//    $voteId= $voteManager-> quelVote($suite_id);
//    $scoreNo= $voteManager->countScoreNo($voteId);
//    return $scoreNo;
//    }
 
//╔════════════════════════════════════════╗  
//   1 chapitre depuis son ID - vue formulaire modification chapitre
//╚════════════════════════════════════════╝
//
function formModifyPost() {
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\bookManager();
    $ouvrage = $bookManager -> getBook($_GET['ouv_id']);
    $article = $postManager->getPost($_GET['id']);
    if($article){
    require('view/backend/updatePostView.php');
}else {
    throw new Exception ('Chapitre inconnu 90');
}
}
function formModifySuite() {
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\bookManager();
    $ouvrage = $bookManager -> getBook($_GET['ouv_id']);
    $suite = $postManager->getSuite($_GET['id']);
    if($suite){
    require('view/backend/updateSuiteView.php');
}else {
    throw new Exception ('Chapitre inconnu 90');
}
}
//╔══════════════════════════════════════════╗  
//    vue formulaire nouveau chapitre
//╚══════════════════════════════════════════╝
//╔════════════════════════════════════════╗  
//   Récupération Chapitre num Max 
//╚════════════════════════════════════════╝
//
function formNewPost($ouvId) {


    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $ouvrage = $bookManager -> getBook($ouvId);
    $chapter = $postManager-> getMaxChapter($ouvId); 
    require('view/backend/newPostView.php');
}
// formulaire saisie Suite d'un lecteur 
function formNewSuite($precedent ,$ouvId) {


    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $ouvrage = $bookManager -> getBook($ouvId);
   // $chapter = $postManager-> getMaxChapter($ouvId); 
    require('view/backend/newSuiteView.php');
}
//╔══════════════════════════════════════════╗  
//    fonction upload image
//╚══════════════════════════════════════════╝
//

function uploadImage($postId) {

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
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

//╔══════════════════════════════════════════╗  
//    Fonction mise à jour (update) du chapitre
//╚══════════════════════════════════════════╝
///Lorsqu'on met à jour un article on le desactive par defaut ////////////////

  
function majPost() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
    $id = preg_replace('([^0-9]+)', '', $_POST['art_id']);
    $keywords = preg_replace($regex, '', $_POST['art_keywords']);
    $description = preg_replace($regex, '', $_POST['art_description']);
    $chapter = preg_replace($regex, '', $_POST['art_chapter']);
    $subtitle = preg_replace($regex, '', $_POST['art_subtitle']);
    $titre = preg_replace($regex, '', $_POST['art_title']); 
    $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
    $ouvrage =preg_replace($regex, '', $_POST['ouvrage']);
    $image = uploadImage($id);
    $auteur = preg_replace($regex, '', $_POST['auteur']);
    $statut_post = preg_replace($regex, '', $_POST['statut_post']);
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $idStatutDuPost=$postManager->idStatut($statut_post);
    $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $id, $description, $keywords, $image, $ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
    if($article){
    $_GET['id'] = $id;
    $_GET['ouv_id']=$ouvId;
    post();
}else{
    throw new Exception ('Le chapitre est introuvable');
}

    }
   
function majSuite() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
    $id = preg_replace('([^0-9]+)', '', $_POST['art_id']);
   
    $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
    $ouvrage = preg_replace($regex, '', $_POST['ouvrage']);
    $precedent = preg_replace($regex, '', $_POST['precedent']);
    $auteur = preg_replace($regex, '', $_POST['auteur']);
    $statut_post = preg_replace($regex, '', $_POST['statut_post']);
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $idStatutDuPost=$postManager->idStatut('REDACTION');
    //$idStatutDuPost=$postManager->idStatut($statut_post);
    $suite = $postManager->updateSuite($_POST['art_content'], 1, $id, $ouvId,$precedent, $auteur, $idStatutDuPost['STATUT_POST_ID']);
    if($suite){
    $_GET['id'] = $precedent;
    $_GET['ouv_id']=$ouvId;
    post();
}else{
    throw new Exception ('Le chapitre est introuvable');
}

    }
//function ajouterPost() {
//    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
//    $keywords = preg_replace($regex, '', $_POST['art_keywords']);
//    $description = preg_replace($regex, '', $_POST['art_description']);
//    $chapter = preg_replace($regex, '', $_POST['art_chapter']);
//    $subtitle = preg_replace($regex, '', $_POST['art_subtitle']);
//    $titre = preg_replace($regex, '', $_POST['art_title']);
//    $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
//    $auteur = preg_replace($regex, '', $_POST['auteur']);
//    $statut_post = preg_replace($regex, '', $_POST['statut_post']);
//
//    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
//    $dernierId = $postManager->addPost($chapter, $titre, $subtitle, $_POST['art_content'], $description, $keywords,$ouvId, $auteur);
//    $image = uploadImage($dernierId);
//    $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $dernierId, $description, $keywords, $image,$ouvId, $auteur);
//    $_GET['id'] = $dernierId;
//    $_GET['ouv_id']=$ouvId;
//    post();
//}
// SVG ajouterPost()
function ajouterPost() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
    $keywords = preg_replace($regex, '', $_POST['art_keywords']);
    $description = preg_replace($regex, '', $_POST['art_description']);
    $chapter = preg_replace($regex, '', $_POST['art_chapter']);
    $subtitle = preg_replace($regex, '', $_POST['art_subtitle']);
    $titre = preg_replace($regex, '', $_POST['art_title']);
    $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
    $auteur = preg_replace($regex, '', $_POST['auteur']);
    $statut_post = preg_replace($regex, '', $_POST['statut_post']);

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $idStatutDuPost = $postManager->idStatut($statut_post);
    $dernierId = $postManager->addPost($chapter, $titre, $subtitle, $_POST['art_content'], $description, $keywords, $ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
    $image = uploadImage($dernierId);
    $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $dernierId, $description, $keywords, $image, $ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
    $_GET['id'] = $dernierId;
    $_GET['ouv_id'] = $ouvId;
    //Messagerie
    $userManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\bookManager();
    $book = $bookManager->getBooksRights($ouvId);
    $article = $postManager->getPost($_GET['id']);
    $root = $userManager -> listSuperadmin();
    $objet = 'Création d\'un chapitre ';
    $contenu = 'Un chapitre est ajouté à l\ouvrage  <b> ' . $ouvId . ' de titre :' . $titre . ' </b> Résumé contenu : <i>' . substr($_POST['art_content'], 0, 150) . '(...)</i>';
    $auteurId = $auteur;
    while ($adminBook = $book->fetch()) {
    
    messageSystem($adminBook['p5_USERS_USER_ID'],$auteurId, $objet, $contenu);
    // envoyer mess aux administrateur de louvrage
    }
     while ($rootBook = $root->fetch()) {
    
    messageSystem($rootBook['USER_ID'],$auteurId , $objet, $contenu);
    // envoyer mess aux Superadmin 
    }
    /// fin messagerie
    post();
}

function ajouterSuite() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
    //$keywords = preg_replace($regex, '', $_POST['art_keywords']);
    //$description = preg_replace($regex, '', $_POST['art_description']);
    $chapter = preg_replace($regex, '', $_POST['art_chapter']);
    $precedent = preg_replace($regex, '', $_POST['precedent']);
    //$subtitle = preg_replace($regex, '', $_POST['art_subtitle']);
    //$titre = preg_replace($regex, '', $_POST['art_title']);
    $contenu = preg_replace($regex, '', $_POST['art_content']);
    $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
    $auteur = preg_replace($regex, '', $_POST['auteur']);
    $statut_post = preg_replace($regex, '', $_POST['statut_post']);

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $idStatutDuPost=$postManager->idStatut($statut_post);
    $dernierId = $postManager->addSuite($contenu, $ouvId, $precedent, $auteur, $idStatutDuPost['STATUT_POST_ID']);
   if(!$idStatutDuPost){throw new Exception('Pb idstatutdupost');}
    if(!$dernierId){throw new Exception('Pb addsuite');}
// $image = uploadImage($dernierId);
   // $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $dernierId, $description, $keywords, $image,$ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
    $_GET['id'] = $dernierId;
    $_GET['ouv_id']=$ouvId;
    
    //Messagerie
    $userManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\bookManager();
    $book = $bookManager->getBooksRights($ouvId);
    $article = $postManager->getPost($_GET['id']);
    $root = $userManager -> listSuperadmin();
    $objet = 'Création d\'une suite ';
    $contenu = 'Une suite est ajoutée à l\ouvrage  <b> ' . $ouvId . '  </b> Résumé contenu : <i>' . substr($_POST['art_content'], 0, 150) . '(...)</i>';
    $auteurId = $auteur;
    while ($adminBook = $book->fetch()) {
    
    messageSystem($adminBook['p5_USERS_USER_ID'],$auteurId, $objet, $contenu);
    // envoyer mess aux administrateur de louvrage
    }
     while ($rootBook = $root->fetch()) {
    
    messageSystem($rootBook['USER_ID'],$auteurId , $objet, $contenu);
    // envoyer mess aux Superadmin 
    }
    /// fin messagerie
    
    listPosts($ouvId);
      
}

//╔══════════════════════════════════════════╗  
//    Supprimer un chapitre
//╚══════════════════════════════════════════╝
//
function supprimePost() {

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $article = $postManager->getPost($_GET['id']);
    $post = $postManager->delPost($_GET['id']);
  if($post){
   $objet='Supression du post '.$article['ART_TITLE'];
   $contenu ='Post Supprimé';
   $auteurId =$article['ART_AUTEUR'];
   messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu );
  listPosts($_GET['ouv_id']);
  }else{
 throw new Exception ('Elément inconnu ');   
}
}

function supprimeSuite() {

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
     $article = $postManager->getPost($_GET['id']);
    $suite = $postManager->delPost($_GET['id']);
    
    if ($suite) {
        $objet = 'Supression '.$article['ART_ID'];
        $contenu = 'Suppression du contenu suivant : ' . $article['ART_CONTENT'];
        $auteurId =$article['ART_AUTEUR'];
        messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);
        $_GET['id'] = $_GET['precedent'];

        post();
    } else {
        throw new Exception('Elément inconnu');
    }
}

//╔══════════════════════════════════════════╗  
//    Désactive un chapitre ( non visible - en cours de rédaction)
//╚══════════════════════════════════════════╝
//

function desactiverPost() {

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $post = $postManager->disablePost($_GET['id']);
    if($post){
     $article = $postManager->getPost($_GET['id']);
   $objet = 'Désactivation Chapitre ';
   $contenu = 'La Chapitre ( '.$article['ART_CHAPTER'].') <b> est désactivé </b> de contenu : <i>'.substr($article['ART_CONTENT'], 0, 150).'(...)</i>';
   $auteurId =$article['ART_AUTEUR'];
   messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);     
    post();
}else{
 throw new Exception ('Chapitre inconnu 256');   
}
}

function desactiverSuite() {

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $suite = $postManager->disablePost($_GET['id']);
    if($suite){
        $article = $postManager->getPost($_GET['id']);
   $objet = 'Désactivation Suite  ';
   $contenu = 'La suite n° ( '.$article['ART_ID'].') <b> est désactivée </b> de contenu : <i>'.substr($article['ART_CONTENT'], 0, 150).'(...)</i>';
   $auteurId =$article['ART_AUTEUR'];
   messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);   
     $_GET['id']= $_GET['precedent'];  
    post();
}else{
 throw new Exception ('Chapitre inconnu 256');   
}
}

//╔══════════════════════════════════════════╗  
//    Publie un chapitre ( rendre visible )
//╚══════════════════════════════════════════╝
//
function publierPost() {

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $post = $postManager->enablePost($_GET['id']);
    if($post){
    $article = $postManager->getPost($_GET['id']);
   $objet = 'Publication  ';
   $contenu = 'La Chapitre ( '.$article['ART_ID'].') <b> est publié en ligne </b> de contenu : <i>'.substr($article['ART_CONTENT'], 0, 150).'(...)</i>';
   $auteurId =$article['ART_AUTEUR'];
   messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);   
    post();
}else{
 throw new Exception ('Chapitre inconnu 270');   
}
}
// CHanger le statut du post redaction propose refuse accepter vote + message aux auteurs
function changementStatutSuite($libelleStatut){
  $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
  $voteManager = new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
  $idStatut = $postManager->idStatut($libelleStatut);// on recupere l id du libelle 
  $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'],$_GET['id']);
  // pour test throw new Exception ($libelleStatut.' '.$idStatut['STATUT_POST_ID'].' et '.$_GET['id']);
   if($post){
       if($libelleStatut == 'VOTE'){
          $vote = $voteManager -> vote($_GET['id']);// vote début maintenant durée 15 jours statut vote ouvert 
          
       }
   $article = $postManager->getPost($_GET['id']);
   $objet = 'Changement de statut ';
   $contenu = 'La suite proposée ( '.$article['ART_ID'].') <b>Change de statut pour '.$libelleStatut.'</b> de contenu : <i>'.substr($article['ART_CONTENT'], 0, 150).'(...)</i>';
   $auteurId =$article['ART_AUTEUR'];
   messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);
   desactiverSuite();
   // execution de post() dans desactiverPOst()
}else{
 throw new Exception ('Changement impossible');   
} 
}


function changementStatut($libelleStatut){
  $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
  $idStatut = $postManager->idStatut($libelleStatut);// on recupere l id du libelle 
  $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'],$_GET['id']);
  
  if($post){
    $article = $postManager->getPost($_GET['id']);
   $objet = 'Changement de statut ';
   $contenu = 'La Chapitre ( '.$article['ART_CHAPTER'].' titre:)'.$article['ART_TITLE'].'. <b>Change de statut pour '.$libelleStatut.'</b> de contenu : <i>'.substr($article['ART_CONTENT'], 0, 150).'(...)</i>';
   $auteurId =$article['ART_AUTEUR'];
   messageSystem($auteurId,  $_SESSION['userId'], $objet, $contenu);
    desactiverPost();
   // execution de post() dans desactiverPOst()
}else{
 throw new Exception ('Changement impossible');   
} 
}

//

//╔══════════════════════════════════════════════╗  
//    si identification ok on affiche la page identification sinon page erreur 
//╚══════════════════════════════════════════════╝
//
function identification($bool) {

    $param = $bool;
    if ($param === FALSE) {
        require('view/backend/erreurView.php');
    } else {

        require_once ('view/backend/identificationView.php');
    }
}

//╔══════════════════════════════════════════╗  
//    Renvoi false si uner inconnu ou pb d'identifiant 
//╚══════════════════════════════════════════╝
//

// FONCTION verifuser() instancie UsersManager méthode connexion() 
//récupère en varuiable de session userId et pseudo 
function verifUser() {
    $userValid = FALSE;
    $userManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
    $user = $userManager->connexion(($_POST['usrname']), ($_POST['passwd']));// pseudo + passwd
    if ($user != FALSE) {
        $_SESSION['userId']= $user[0]['USER_ID'];
        $_SESSION['user'] = $_POST['usrname'];
        $_SESSION['superAdmin']= $user[0]['ROOT'];
        $userValid = TRUE;
   $messageManager = new OpenClassrooms\DWJP5\Backend\Model\MessageManager();
   $message = $messageManager->nbMessagesNonLu($_SESSION['userId']);
   $nbMess = $message->fetchAll();
   $_SESSION['nbMess']= $nbMess[0]['NB'];
    } else {
       $userValid = FALSE;
   }
   return $userValid;
}

//╔══════════════════════════════════════════╗  
//    active un commentaire 
//╚══════════════════════════════════════════╝
//
function activeComment() {
    $commentManager = new OpenClassrooms\DWJP5\Backend\Model\commentManager();
    $comment = $commentManager->enableComment($_GET['commId']);
    if($comment){
    post();
}else{
 throw new Exception ('Elément inconnu 323');   
}
}

//╔══════════════════════════════════════════╗  
//    déactive un commentaire 
//╚══════════════════════════════════════════╝
//
function desactiveComment() {
    $commentManager = new OpenClassrooms\DWJP5\Backend\Model\commentManager();
    $comment = $commentManager->disableComment($_GET['commId']);
    if($comment){
    post();
}else{
 throw new Exception ('Elément inconnu 337');   
}
}

//╔══════════════════════════════════════════╗  
//    signal  un commentaire 
//╚══════════════════════════════════════════╝
// 
function messageSignalementCommentaire(){
//Messagerie
    $userManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\bookManager();
    $book = $bookManager->getBooksRights($_GET['ouv_id']);
    //$article = $postManager->getPost($_GET['id']);
    $root = $userManager -> listSuperadmin();
    $objet = 'Signalement commentaire  ';
    $contenu = 'Un signalement de commentaire est levé par '.$_SESSION['user'];
    //$auteurId = $auteur;
    while ($adminBook = $book->fetch()) {
    
    messageSystem($adminBook['p5_USERS_USER_ID'],$_SESSION['userId'], $objet, $contenu);
    // envoyer mess aux administrateur de louvrage
    }
     while ($rootBook = $root->fetch()) {
    
    messageSystem($rootBook['USER_ID'],$_SESSION['userId'] , $objet, $contenu);
    // envoyer mess aux Superadmin 
    }
    /// fin messagerie

}
function activeSignal() {
    $commentManager = new OpenClassrooms\DWJP5\Backend\Model\commentManager();
    $comment = $commentManager->enableSignal($_GET['commId']);
    if($comment){
    messageSignalementCommentaire();    
    post();
}else{
 throw new Exception ('Elément inconnu 351');   
}
}
//╔══════════════════════════════════════════╗  
//    Supprime le signalement d'un commentaire 
//╚══════════════════════════════════════════╝
// 

function desactiveSignal() {

    $commentManager = new OpenClassrooms\DWJP5\Backend\Model\commentManager();
    $comment = $commentManager->disableSignal($_GET['commId']);
    if($comment){
    post();
}else{
 throw new Exception ('Elément inconnu 366');   
}
}
function codeValidation(){
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
 
function messagePasswdOublie($destinataire, $texte) {

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

function motDePasseOublie($mail) {
    // le mail existe il ?
     $userManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
     $emailExist = $userManager->emailExist($mail);
    if($emailExist){
        //S'il existe, on fabrique le code , on update le code , on envoi le message
        $pseudo=$emailExist[0]['USER_PSEUDO'];
        $code = codeValidation();
        //echo $pseudo;
       // echo " code : ".$code;
        $updatePassword = $userManager->updatePsswd($code,$pseudo);
        $message = messagePasswdOublie($mail,$code);
            //echo $message;
    }else {
        //echo "pas de mail exist";
    }
    // on retourne sur la page d'identification
    require ('view/backend/identificationView.php');   
} 

//╔════════════════════════════════════════╗  
//   Liste des ouvrages pour un utilisateur
//╚════════════════════════════════════════╝
// 
function listOuvragesUser($userId) {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $books = $bookManager->getBooksUser($userId);

    require('view/backend/listBooksView.php');
}

//╔════════════════════════════════════════╗  
//   Liste des ouvrages 
//╚════════════════════════════════════════╝
// 
function listOuvrages() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $books = $bookManager->getBooks();

    require('view/backend/listBooksView.php');
}
//suppression acces a ouvrage 
function supprimeAccesOuvrage($userId, $ouvId, $statutId) {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $delAcces = $bookManager->delBookAcces($userId, $ouvId, $statutId);
    accesBook($ouvId);
    $objet='Modification accès';
    $contenu ='Votre accès à ouvrage n°'.$ouvId.' est supprimé' ;
     messageSystem($userId,$_SESSION['userId'], $objet, $contenu);
    
}
function formNewBookAcces($ouvId) {
//liste des utilisateurs
     $userManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
     $users= $userManager -> getUsers();
     $statuts = $userManager ->getStatuts();
//liste des statut 
    //vérification user un seul par ouvrage.........f
require('view/backend/newBookAccesView.php');
}
 


function addAccesOuvrage($ouvId, $userId, $statutId) {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $verif = $bookManager->verifAccesBook($ouvId,$userId);
    if ($verif[0][0] == 0) {
        $acces = $bookManager->addAccesBook($ouvId, $userId, $statutId);
        accesBook($ouvId);
        $objet='Modification accès';
    $contenu ='Vous avez un  nouvel pour l\'ouvrage  n°'.$ouvId.' Veuillez vous connecter et vérifier ' ;
     messageSystem($userId,$_SESSION['userId'], $objet, $contenu);
    } else {
       
        throw new Exception('L\'utilisateur a déjà un accès pour cette ouvrage - Vous devez  supprimer l\'accès avant d\'en créer un nouveau ');
    }
}

//╔════════════════════════════════════════╗  
//   Ouvrage depuis un ID
//╚════════════════════════════════════════╝
//
function book() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->getBook($_GET['ouv_id']);
    require('view/backend/bookView.php');
}

//╔════════════════════════════════════════╗  
//   Ouvrage depuis un ID
//╚════════════════════════════════════════╝
//
function bookUser() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();

    $book = $bookManager->getBookUser($_GET['ouv_id']);
    require('view/backend/bookView.php');
}

//╔══════════════════════════════════════════╗  
//   1 Ouvrage depuis son ID - vue formulaire modification de l'ouvrage
//╚══════════════════════════════════════════╝
//
function formModifyBook() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();

    $book = $bookManager->getBook($_GET['ouv_id']);

    require('view/backend/updateBookView.php');
}

//╔══════════════════════════════════════════╗  
//   1 Ouvrage depuis son ID - vue formulaire modification de l'ouvrage
//╚══════════════════════════════════════════╝
//
function formModifyBookUser() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();

    $book = $bookManager->getBookUser($_GET['ouv_id']);

    require('view/backend/updateBookView.php');
}

//╔══════════════════════════════════════════╗  
//    vue formulaire nouvel ouvrage
//╚══════════════════════════════════════════╝
//
function formNewBook() {

    require('view/backend/newBookView.php');
}




//╔══════════════════════════════════════════╗  
//    Mise à jour d'un Ouvrage 
//╚══════════════════════════════════════════╝
//
function majBook() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";

    $id = preg_replace('([^0-9]+)', '', $_POST['ouv_id']);
    $keywords = preg_replace($regex, '', $_POST['ouv_keywords']);
    $description = preg_replace($regex, '', $_POST['ouv_description']);
    $subtitle = preg_replace($regex, '', $_POST['ouv_soustitre']);
    $titre = preg_replace($regex, '', $_POST['ouv_titre']);
   // $auteur = preg_replace($regex, '', $_POST['ouv_auteur']);
    if($_SESSION['superAdmin']==1){
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->updateBook($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $id);
    $_GET['ouv_id'] = $id;
    book();
    }else {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->updateBookUser($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $id);
    $_GET['ouv_id'] = $id;
    book();    
        
    }
}
    


//╔══════════════════════════════════════════╗  
//    Ajouter un Ouvrage 
//╚══════════════════════════════════════════╝
//
 
function ajouterOuvrage() {

    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";


    $keywords = preg_replace($regex, '', $_POST['ouv_keywords']);
    $description = preg_replace($regex, '', $_POST['ouv_description']);
    $subtitle = preg_replace($regex, '', $_POST['ouv_soustitre']);
    $titre = preg_replace($regex, '', $_POST['ouv_titre']);
   // $auteur = preg_replace($regex, '', $_POST['ouv_auteur']);

if($_SESSION['superAdmin']==1){
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->addBook($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords);
    listOuvrages();
}else{
 throw new Exception ('Vous n\'avez pas les droits pour ajouter un ouvrage.');     
}
}

//╔══════════════════════════════════════════╗  
//    Supprimer  un Ouvrage 
//╚══════════════════════════════════════════╝
//
function supprimeOuvrage() {

    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->delBook($_GET['ouv_id']);
    listOuvrages();
   }
function supprimeOuvrageUser() {

    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->delBookUser($_GET['ouv_id']);
    listOuvragesUser($_SESSION['userId']);
}

//╔══════════════════════════════════════════╗  
//    desactive un ouvrage ainsi que ts les chapitres
//╚══════════════════════════════════════════╝
//
function desactiverBook() {
       
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\postManager();
    $book = $bookManager->disableBook($_GET['ouv_id']);
    $post= $postManager ->disablePostsBook($_GET['ouv_id']);
   if($book AND $post){ 
       book();
     }else{
   throw new Exception('requête non aboutie');
        
    }
}

//╔══════════════════════════════════════════╗  
//    active un ouvrage sans activer les chapitres
//╚══════════════════════════════════════════╝
//
function activerBook() {
    
  
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    //$postManager = new OpenClassrooms\DWJP5\Backend\Model\postManager();
    $book = $bookManager->enableBook($_GET['ouv_id']);
      if($book){
    book();
    }else{
    throw new Exception('requête non aboutie');
        
    }
    
}
//╔══════════════════════════════════════════╗  
//    active les chapitres d'un ouvrage
//╚══════════════════════════════════════════╝
//
function activerPostsBook() {
    
   
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\postManager();
    $post= $postManager ->enablePostsBook($_GET['ouv_id']);
    if($post){
    book();
    }else{
    throw new Exception('requête non aboutie');
        
    }
    
}

//╔════════════════════════════════════════╗  
//   Liste des messages non supprimés pour un utilisateur
//╚════════════════════════════════════════╝
//
function listMessage($userId) {
    $messageManager = new OpenClassrooms\DWJP5\Backend\Model\MessageManager();
    $messages = $messageManager->getMessages($userId);
    require('view/backend/messagesView.php');
}



function voteControle() {
    
   
    $voteManager = new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
    $vote= $voteManager ->controleVote();
    if($vote){
   
    }else{
    throw new Exception('pas de vérification des votes');
        
    }
}
    
    function vote() {
    
   
    $voteManager = new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
    $vote= $voteManager ->jeVote($_GET['bulletin'],$_GET['id']);
    $_GET['id'] =$_GET['precedent'];
    
    post();
    
}

function dejaVote (){
   $voteManager = new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
   $aVote= $voteManager ->aVote($_GET['id']);
   //throw new Exception ('id'.$_GET['id'].' A vote ? : '.$aVote);
   //return $aVote;   
    
}  
function accesBook($ouvId){
       $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager(); 
       $rightsBooks =$bookManager->getBooksRights($ouvId);
     require_once('view/backend/dashBoardBookAccesView.php');
}

function cokpit() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $usersManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
    $voteManager= new OpenClassrooms\DWJP5\Backend\Model\VoteManager();
    $listUsers = $usersManager->getUsers(); // Liste utilisateurs
    $listBooks = $bookManager->getBooks(); //Liste des ouvrages 
    $lesScores = $voteManager -> lesScores();
    $tableauScores = $lesScores->fetchAll();
    
    $votesListe= $voteManager-> voteList(); 
    $votesListeClose= $voteManager-> voteListClose(); 
    require_once('view/backend/dashBoardView.php');
}

function modifDureeVote($vote_id,$dateFin,$duree){
  $voteManager= new OpenClassrooms\DWJP5\Backend\Model\VoteManager();   
 $voteprolong= $voteManager-> updateVoteDuree($vote_id,$dateFin,$duree);     
  cokpit(); 
}

function closeVote($art_id){
 $voteManager= new OpenClassrooms\DWJP5\Backend\Model\VoteManager();   
 $voteClose= $voteManager-> fermetureVote($art_id);    
 cokpit(); 
}
function openVote($art_id){
 $voteManager= new OpenClassrooms\DWJP5\Backend\Model\VoteManager();   
 $voteClose= $voteManager-> ouvertureVote($art_id);    
 cokpit(); 
// Supprime le vote les votes les scores et change le statut de la  suite en ACCEPTE
}
function supprimeVote($vote_id,$art_id){
 $voteManager= new OpenClassrooms\DWJP5\Backend\Model\VoteManager(); 
 $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
 $voteDel= $voteManager-> delVote($vote_id);
 $idStatut = $postManager->idStatut('ACCEPTE');// on recupere l id du libelle 
 $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'],$art_id);
 cokpit(); 
}

function supprimeUser($user_id){
 if(($_SESSION['superAdmin']==1)AND ($_SESSION['userId']<> $user_id)){
  $usersManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
  $delUser= $usersManager ->delUser($user_id); 
     
cokpit();  
    }else{
        throw new Exception('Vous n\'avez pas les droits d\'accès pour effectuer cette opération ');
    }
}



function initUser($user_id){
 if(($_SESSION['superAdmin']==1)AND ($_SESSION['userId']<> $user_id)){
  $usersManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
  $passwd = $usersManager -> passwordUser(codeValidation());
  $initUser= $usersManager ->initUser($user_id,$passwd); 
     
cokpit();  
    }else{
        throw new Exception('Vous n\'avez pas les droits d\'accès pour effectuer cette opération ');
    }
}

function majUser($userId, $userName, $userLastname, $userPseudo, $userMail, $userStatut){
 if($_SESSION['superAdmin']==1){
  $usersManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
  $majUser= $usersManager ->updateUser($userId,$userName, $userLastname, $userPseudo, $userMail, $userStatut); 
   cokpit();  
    
}
}
function userGet(){
  $usersManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();   
   $user= $usersManager ->getUser($_GET['id']); 
    
 require('view/backend/updateUserView.php');    
}

//function visuPost(){
//   
//  cokpit();
//    
// 
//}

function ajouterUser($userName, $userLastname, $userPseudo, $userMail,$userPasswd,$userStatut) {
 if($_SESSION['superAdmin']==1){
  $usersManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
  $newUser= $usersManager ->addUser($userName, $userLastname, $userPseudo, $userMail,$userPasswd, $userStatut); 
   cokpit();  
    
}   
}
function formNewUser() {

    require('view/backend/newUserView.php');
}
//function messagerie(){
//  $messageManager = new OpenClassrooms\DWJP5\Backend\Model\MessageManager();   
//  $messages= $messageManager ->getMessages($_SESSION['userId']); 
//    
// require('view/backend/messagerieView.php');    
//}

function messagerie() {
    $messageManager = new OpenClassrooms\DWJP5\Backend\Model\MessageManager();
    $messagesReçus = $messageManager->getMessagesReceived($_SESSION['userId']);
    $messagesEnvoyes = $messageManager->getMessagesSend($_SESSION['userId']);
    $message = $messageManager->nbMessagesNonLu($_SESSION['userId']);
    $nbMess = $message->fetchAll();
    $_SESSION['nbMess'] = $nbMess[0]['NB'];
    //$messagesReçusCorbeille = $messageManager ->getMessagesReceivedBasket($_SESSION['userId']);  
    //$messagesEnvoyesCorbeille = $messageManager ->getMessagesSendBasket($_SESSION['userId']); 
    require('view/backend/messagerieView.php');
}

function ordreMessagerieRecus() {
    $messageManager = new OpenClassrooms\DWJP5\Backend\Model\MessageManager();
    $tab = $_POST['listeId'];
    if (isset($_POST['nonlu'])) {
        $messages = $messageManager->setMessagesNotRead($tab);
    } elseif (isset($_POST['lu'])) {
        $messages = $messageManager->setMessagesRead($tab);
    } elseif (isset($_POST['corbeille'])) {
        $messages = $messageManager->desactiverMessagesDestinataire($tab);
    }

    messagerie();
}
function ordreMessagerieEnvoyes() {
    $messageManager = new OpenClassrooms\DWJP5\Backend\Model\MessageManager();
    $tab = $_POST['listeId'];
if (isset($_POST['corbeille'])) {
        $messages = $messageManager->desactiverMessagesExpediteur($tab);
    }

    messagerie();
}

function formNewMessage(){
   // /liste des utilisateurs
     $userManager = new OpenClassrooms\DWJP5\Backend\Model\UsersManager();
     $users= $userManager -> getUsers();
  require('view/backend/newMessageView.php');      
}


function envoiMessage($destinataire,$expediteur, $objet, $contenu) {

//    $messageManager = new OpenClassrooms\DWJP5\Backend\Model\MessageManager();
//    $envoi = $messageManager->addMessage($destinataire,$expediteur, $objet, $contenu);
    messageSystem($destinataire, $expediteur, $objet, $contenu);
    messagerie();
}

function messageSystem($destinataire, $expediteur, $objet, $contenu) {
    $messageManager = new OpenClassrooms\DWJP5\Backend\Model\MessageManager();
    $envoi = $messageManager->addMessage($destinataire, $expediteur, $objet, $contenu);
}
