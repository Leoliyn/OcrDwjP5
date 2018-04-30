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
function listPosts() {
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $posts = $postManager->getPostsResume();

    require('view/backend/listPostsView.php');
}

//╔════════════════════════════════════════╗  
//   Liste des chapitres en résumé 
//╚════════════════════════════════════════╝
//
function listPostsResume() {
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $posts = $postManager->getPostsResume();

    require('view/backend/listPostsView.php');
}


//╔════════════════════════════════════════╗  
//   1 chapitre depuis ID + les commentaires de ce chapitre
//╚════════════════════════════════════════╝
//
function post() {
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $commentManager = new OpenClassrooms\DWJP5\Backend\Model\CommentManager();
    $article = $postManager->getPost($_GET['id']);

    if ($article) {
        $comments = $commentManager->getComments($_GET['id']);
        require('view/backend/postView.php');
    } else {
        throw new Exception('Chapitre inconnu');
    }
}

//╔════════════════════════════════════════╗  
//   1 chapitre depuis son ID - vue formulaire modification chapitre
//╚════════════════════════════════════════╝
//
function formModifyPost() {
    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $article = $postManager->getPost($_GET['id']);
    if($article){
    require('view/backend/updatePostView.php');
}else {
    throw new Exception ('Chapitre inconnu');
}
}

//╔══════════════════════════════════════════╗  
//    vue formulaire nouveau chapitre
//╚══════════════════════════════════════════╝
//╔════════════════════════════════════════╗  
//   Récupération Chapitre num Max 
//╚════════════════════════════════════════╝
//
function formNewPost() {


    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $chapter = $postManager->getMaxChapter(); 
    require('view/backend/newPostView.php');
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
    $image = uploadImage($id);

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();

    $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $id, $description, $keywords, $image);
    if($article){
    $_GET['id'] = $id;
    post();
}else{
    throw new Exception ('Le chapitre est introuvable');
}

    }

function ajouterPost() {
    $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
    $keywords = preg_replace($regex, '', $_POST['art_keywords']);
    $description = preg_replace($regex, '', $_POST['art_description']);
    $chapter = preg_replace($regex, '', $_POST['art_chapter']);
    $subtitle = preg_replace($regex, '', $_POST['art_subtitle']);
    $titre = preg_replace($regex, '', $_POST['art_title']);



    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $dernierId = $postManager->addPost($chapter, $titre, $subtitle, $_POST['art_content'], $description, $keywords);
    $image = uploadImage($dernierId);
    

    $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $dernierId, $description, $keywords, $image);
    $_GET['id'] = $dernierId;
    post();
}


//╔══════════════════════════════════════════╗  
//    Supprimer un chapitre
//╚══════════════════════════════════════════╝
//
function supprimePost() {

    $postManager = new OpenClassrooms\DWJP5\Backend\Model\PostManager();
    $post = $postManager->delPost($_GET['id']);
  if($post){
     listPostsResume();
  }else{
 throw new Exception ('Elément inconnu');   
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
    post();
}else{
 throw new Exception ('Chapitre inconnu');   
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
    post();
}else{
 throw new Exception ('Chapitre inconnu');   
}
}


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
 throw new Exception ('Elément inconnu');   
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
 throw new Exception ('Elément inconnu');   
}
}

//╔══════════════════════════════════════════╗  
//    signal  un commentaire 
//╚══════════════════════════════════════════╝
//   
function activeSignal() {
    $commentManager = new OpenClassrooms\DWJP5\Backend\Model\commentManager();
    $comment = $commentManager->enableSignal($_GET['commId']);
    if($comment){
    post();
}else{
 throw new Exception ('Elément inconnu');   
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
 throw new Exception ('Elément inconnu');   
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




//╔════════════════════════════════════════╗  
//   Ouvrage depuis un ID
//╚════════════════════════════════════════╝
//
function book() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
        $book = $bookManager->getBook($_GET['id']);
       require('view/backend/bookView.php');
}

//╔════════════════════════════════════════╗  
//   Ouvrage depuis un ID
//╚════════════════════════════════════════╝
//
function bookUser() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();

    $book = $bookManager->getBookUser($_GET['id']);
    require('view/backend/bookView.php');
}

//╔══════════════════════════════════════════╗  
//   1 Ouvrage depuis son ID - vue formulaire modification de l'ouvrage
//╚══════════════════════════════════════════╝
//
function formModifyBook() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();

    $book = $bookManager->getBook($_GET['id']);

    require('view/backend/updateBookView.php');
}

//╔══════════════════════════════════════════╗  
//   1 Ouvrage depuis son ID - vue formulaire modification de l'ouvrage
//╚══════════════════════════════════════════╝
//
function formModifyBookUser() {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();

    $book = $bookManager->getBookUser($_GET['id']);

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
    $_GET['id'] = $id;
    book();
    }else {
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->updateBookUser($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $id);
    $_GET['id'] = $id;
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
    $book = $bookManager->delBook($_GET['id']);
    listOuvrages();
   }
function supprimeOuvrageUser() {

    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->delBookUser($_GET['id']);
    listOuvragesUser($_SESSION['userId']);
}

//╔══════════════════════════════════════════╗  
//    desactive un ouvrage
//╚══════════════════════════════════════════╝
//
function desactiverBook() {
       if($_SESSION['superAdmin']==1){
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->disableBook($_GET['id']);
    book();
     }else{
    throw new Exception('Vous n\'avez pas les droits pour changer le statut');
        
    }
}

//╔══════════════════════════════════════════╗  
//    active un ouvrage
//╚══════════════════════════════════════════╝
//
function activerBook() {
    
    if($_SESSION['superAdmin']==1){
    $bookManager = new OpenClassrooms\DWJP5\Backend\Model\BookManager();
    $book = $bookManager->enableBook($_GET['id']);
    book();
    }else{
    throw new Exception('Vous n\'avez pas les droits pour publier un ouvrage');
        
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



