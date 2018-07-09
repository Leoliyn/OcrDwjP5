<?php

/**
 *  PROJET 5 DWJ OPENCLASSROOMS CLAUDEY Lionel  2018 
 * Backend controler
 */
//AUTOLOAD fonctionne en local pas chez 000webhost.com
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
//                     CHAP    METHODES LIEES AUX CHAPITRES
// ═════════════════════════════════════════════

    /**
     *  CHAP- Liste des chapitres en résumé  et liste des suites existantes 
     * 
     * Appel model et require view listPostsView
     * @param type $ouvId
     * @throws Exception
     * @param type $ouvId
     */
    public function listPosts($ouvId) {
        $postManager = new Backend\PostManager();
        $posts = $postManager->getPostsResume($ouvId);
        $postsSuite = $postManager->getPostsSuite($ouvId);
        require('view/backend/listPostsView.php');
    }

    /**
     *  CHAP -  recupere les données du chapitre l'ouvrage auquel  il appartient  le statut du chapitre
     * les suites ,controle les scrutins , recupere les scorres dans un tableau
     *   et les coommentaires et leurs enfants 
     * 
     * @throws Exception
     */
    public function post() {

        $postManager = new Backend\PostManager();
        $commentManager = new Backend\CommentManager();
        $bookManager = new Backend\BookManager();
        $ouvrage = $bookManager->getBook($_GET['ouv_id']);
        $article = $postManager->getPost($_GET['id']);
        $statutPost = $postManager->libelleStatutPost($article['p5_statut_post_STATUT_POST_ID']);
        $suites = $postManager->getSuivants($_GET['id']);
        $voteManager = new Backend\VoteManager();
        /* contrelo des vote en fin de delai  */
        $voteControle = $voteManager->controleVote();
        $listStatutVote = $voteManager->listStatutVote();
        /* RESULTAT VOTES */
        $lesScores = $voteManager->lesScores();
        $tableauScores = $lesScores->fetchAll();
        if ($article) {
            /* les commentaires */
            $comments = $commentManager->getCommentsPremierNiveau($_GET['id']);
            $commentsChild = $commentManager->getCommentsChild($_GET['id']);
            require('view/backend/postView.php');
        } else {
            throw new Exception('Chapitre inconnu');
        }
    }

    /**
     * Supprime un chapitre 
     * 
     * recupere dionnéed u post dont l'id est en paramètre 
     * supprime le chapitre dont l'id est en paramètre 
     * envoi un  message à l'auteur du post avertissant de la suppression en donnant le titre de l'article supprimé
     * 
     * @throws Exception
     */
    public function supprimePost() {

        $postManager = new Backend\PostManager();
        $article = $postManager->getPost($_GET['id']);
        $post = $postManager->delPost($_GET['id']);
        $delDestinataire = 0;
        $delExpediteur = 0;
        if ($post) {
            $objet = 'Supression du post ' . $article['ART_TITLE'];
            $contenu = 'Post Supprimé';
            $auteurId = $article['ART_AUTEUR'];
            $this->messageSystem($auteurId, $_SESSION['userId'],$delDestinataire,$delExpediteur,  $objet, $contenu);
            $this->listPosts($_GET['ouv_id']);
        } else {
            throw new Exception('Elément inconnu ');
        }
    }

    /**
     * CHAP- désactive un chapitre 
     * @throws Exception
     */
    public function desactiverPost() {

        $postManager = new Backend\PostManager();
        $post = $postManager->disablePost($_GET['id']);
        if ($post) {
            $this->post();
        } else {
            throw new Exception('Chapitre inconnu ');
        }
    }

    /**
     * CHAP- Appel formulaire de mise à  jour du chapitre recupere données du chapitre et de l'ouvrage
     */
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

    /**
     * CHAP-    Appel formulaire Nouveau chapitre chapitre recupere données ouvrage et chapitre max 
     * @param type $ouvId
     */
    public function formNewPost($ouvId) {

        $postManager = new Backend\PostManager();
        $bookManager = new Backend\BookManager();
        $ouvrage = $bookManager->getBook($ouvId);
        $chapter = $postManager->getMaxChapter($ouvId);
        require('view/backend/newPostView.php');
    }

    /**
     * CHAP       Misee à jour du chapitre formatage avec regex  
     * récuperation de l'id du  statut du post à modifier
     * si update réussi on appelle post()
     * @throws Exception
     */
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

    /**
     * CHAP fonction upload image du chapitre  dans le formulaire de maj ou création du chapitre
     * @param type $postId
     * @return string
     */
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
            $_FILES['uploaded_file']['name'] = 'ouvrage' . $article['OUVRAGE_OUV_ID'] . '_chapitre' . $article['ART_CHAPTER'] . $extension_upload;

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

    /**
     * CHAP- Ajoute un chapitre à la base de données 
     * upload l'image du chapitre 
     * envoi messages aux utilsateurs concernés
     */
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
        $delDestinataire = 0;
        $delExpediteur = 0;
        $postManager = new Backend\PostManager();
        $idStatutDuPost = $postManager->idStatut($statut_post);
        $dernierId = $postManager->addPost($chapter, $titre, $subtitle, $_POST['art_content'], $description, $keywords, $ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
        $image = $this->uploadImage($dernierId);
        $article = $postManager->updatePost($chapter, $titre, $subtitle, $_POST['art_content'], 1, $dernierId, $description, $keywords, $image, $ouvId, $auteur, $idStatutDuPost['STATUT_POST_ID']);
        $_GET['id'] = $dernierId;
        $_GET['ouv_id'] = $ouvId;
        /* Messagerie */
        $userManager = new Backend\UsersManager();
        $bookManager = new Backend\BookManager();
        $book = $bookManager->getBooksRights($ouvId);
        $article = $postManager->getPost($_GET['id']);
        $root = $userManager->listSuperadmin();
        $objet = 'Création d\'un chapitre ';
        $contenu = 'Un chapitre est ajouté à l\'ouvrage  <b> <a href="indexadmin.php?action=lisPosts&amp;ouv_id= ' . $ouvId . '">Ouvrage '. $ouvId . '</a> de titre :' . $titre . ' </b> Résumé contenu : <i>' . substr($_POST['art_content'], 0, 150) . '(...)</i>';
        $auteurId = $auteur;
        while ($adminBook = $book->fetch()) {

            $this->messageSystem($adminBook['p5_USERS_USER_ID'], $auteurId,$delDestinataire,$delExpediteur, $objet, $contenu);
            /* envoyer mess aux administrateur de l'ouvrage */
        }
        while ($rootBook = $root->fetch()) {

            $this->messageSystem($rootBook['USER_ID'], $auteurId,$delDestinataire,$delExpediteur,  $objet, $contenu);
            /* envoyer mess aux Superadmin */
        }
        /* fin messagerie */
        $this->post();
    }

    /**
     * CHAP  -   Publie un chapitre ( rendre visible )
     * @throws Exception
     */
    public function publierPost() {

        $postManager = new Backend\PostManager();
        $post = $postManager->enablePost($_GET['id']);
        if ($post) {
            $this->post();
        } else {
            throw new Exception('Chapitre inconnu ');
        }
    }

    /**
     * CHAP  - changement du statut du chapitre - desactive le post car  modif envoi messages
     * 
     * recupere l'id du statut dont le libellé est en paramètre $idStatut 
     * change le statut du chapitre depuis son id 
     * Protection qu'un non admin modifie statut post deja accepte ou refuse ou vote 
     * recupere les donnée du chapitre pourenvoyer un message aux admins ou auteur selon  le cas 
     * 
     * @param type $libelleStatut
     * @throws Exception
     */
    public function changementStatut($libelleStatut) {
        $postManager = new Backend\PostManager();
        $post = null;
        $droits = unserialize($_SESSION['Rights']);
        $idStatutActuel = $postManager->statutDuPost($_GET['id']); //id du statut actuel
        $statutActuel = $postManager->libelleStatutPost($idStatutActuel[0]);
        $idStatut = $postManager->idStatut($libelleStatut); // on recupere l id du nouveau libelle 
        $delDestinataire = 0;
        $delExpediteur = 0;
        if (($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR') || (($droits[$_GET['ouv_id']] <> 'ADMINISTRATEUR')AND ( ($statutActuel[0] == 'REDACTION') || ($statutActuel[0] == 'PROPOSE')))) {

            $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'], $_GET['id']);
        }
        if ($post) {
            $article = $postManager->getPost($_GET['id']);
            $objet = 'Changement de statut ';
            $contenu = '<a href="indexadmin.php?action=listPosts&apm;ouv_id='.$_GET['ouv_id'].'">Ouvrage'.$_GET['ouv_id'].'</a> :Le Chapitre ( ' . $article['ART_CHAPTER'] . ' titre: ' . $article['ART_TITLE'] . '. <b>Change de statut pour ' . $libelleStatut . '</b> de contenu : <i>' . substr($article['ART_CONTENT'], 0, 150) . '(...)</i>';
            $auteurId = $article['ART_AUTEUR'];
            if ($_SESSION['userId'] == $auteurId) {
                $bookManager = new Backend\BookManager();
                $userManager = new Backend\UsersManager();
                $book = $bookManager->getBooksRights($article['OUVRAGE_OUV_ID']);
                $root = $userManager->listSuperadmin();

                while ($adminBook = $book->fetch()) {
                    $this->messageSystem($adminBook['p5_USERS_USER_ID'], $_SESSION['userId'], $delDestinataire, $delExpediteur, $objet, $contenu);
                    // envoyer mess aux administrateur de louvrage
                }
                while ($rootBook = $root->fetch()) {
                    $this->messageSystem($rootBook['USER_ID'], $_SESSION['userId'], $delDestinataire, $delExpediteur, $objet, $contenu);
                    // envoyer mess aux Superadmin 
                }
            } else {
                // auteur prevenu si chgt de statut des chapitre dont il est l'auteur
                if($auteurId==$_SESSION['userId']){
                    $deldestinataire=1;
                }
                $this->messageSystem($auteurId, $_SESSION['userId'], $delDestinataire, $delExpediteur, $objet, $contenu);
            }

            $this->desactiverPost();
            // execution de post() dans desactiverPOst()
        } else {
            throw new Exception('Changement impossible ou vérrouillé ');
        }
    }

//   ════════════════════════
//  METHODES LIEES AUX MESSAGES INTERNES 
//   ════════════════════════

    /**
     * MESS-   appelle vue  formulaire nouveau message
     * récupère la liste des utilisateurs 
     */
    public function formNewMessage() {
        // /liste des utilisateurs
        $userManager = new Backend\UsersManager();
        $users = $userManager->getUsers();
        require('view/backend/newMessageView.php');
    }

    /**
     * MESS Positionne en lu non lu ou corbeille les messages reçus  dont les id sont dans le tableau en paramètre
     * apelle la methode messagerie 
     */
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

    /**
     * MESS -Positionne en lu non lu ou corbeille les messages envoyés  dont les id sont dans le tableau en paramètre
     * apelle la methode messagerie 
     */
    public function ordreMessagerieEnvoyes() {
        $messageManager = new Backend\MessageManager();
        $tab = $_POST['listeId'];
        if (isset($_POST['corbeille'])) {
            $messages = $messageManager->desactiverMessagesExpediteur($tab);
        }

        $this->messagerie();
    }

    /**
     * MESS Envoi (ajoute) un message 
     * @param type $destinataire
     * @param type $expediteur
     * @param type $objet
     * @param type $contenu
     */
    public function messageSystem($destinataire, $expediteur,$delDestinataire,$delExpediteur, $objet, $content) {

        $messageManager = new Backend\MessageManager();
        if($destinataire == $expediteur){
            $delExpediteur=1;
        }
        $envoi = $messageManager->addMessage($destinataire, $expediteur,$delDestinataire,$delExpediteur, $objet, $content);
    }

    /**
     * Ajoute un message puis appelle methode messagerie()
     * @param type $destinataire
     * @param type $expediteur
     * @param type $objet
     * @param type $contenu
     */
    public function envoiMessage($destinataireId, $expediteurId,$delDestinataire,$delExpediteur, $objet, $content) {

        $this->messageSystem($destinataireId, $expediteurId,$delDestinataire,$delExpediteur, $objet, $content);
        $this->messagerie();
    }

    //   ═════════════════════════════════════════════
    //    MESS- Envoi message suite signalement commentaire
    //   ═════════════════════════════════════════════

    /**
     * MESS Ajoute messages de signalement commentaire aux superAdmin(ROOT) et administrateur d'ouvrage 
     *  
     */
    public function messageSignalementCommentaire() {
//Messagerie
        $userManager = new Backend\UsersManager();
        $bookManager = new Backend\BookManager();
        $book = $bookManager->getBooksRights($_GET['ouv_id']);
        $root = $userManager->listSuperadmin();
        $objet = 'Signalement commentaire  ';
        $contenu = 'Un signalement de commentaire est levé par ' . $_SESSION['user'];
        $delDestinataire=0;
        $delExpediteur=0;
        while ($adminBook = $book->fetch()) {

            $this->messageSystem($adminBook['p5_USERS_USER_ID'], $_SESSION['userId'],$delDestinataire, $delExpediteur, $objet, $contenu);
            // envoyer mess aux administrateur de louvrage
        }
        while ($rootBook = $root->fetch()) {

            $this->messageSystem($rootBook['USER_ID'], $_SESSION['userId'],$delDestinataire, $delExpediteur,  $objet, $contenu);
            // envoyer mess aux Superadmin 
        }
    }

    /**
     * MESS Ajoute un message aux superAdmin lors de demande d'inscription au site 
     */
    public function messageInscription() {
        $userManager = new Backend\UsersManager();
        $root = $userManager->listSuperadmin();
        $objet = 'Demande inscription   ';
        $delDestinataire=0;
        $delExpediteur=0;
        $contenu = 'demande inscription pour :  ' . $_POST['nom'] . ' ' . $_POST['prenom'] . ' pseudo :' . $_POST['pseudo'] . ' email : ' . $_POST['email'];
        while ($rootBook = $root->fetch()) {

            $this->messageSystem($rootBook['USER_ID'], $rootBook['USER_ID'],$delDestinataire, $delExpediteur, $objet, $contenu);
            // envoyer mess aux Superadmin 
        }
    }

    
    /**
     * MESS methode appelle des messages recus , envoyés , nb message non lu , et nettoyage de la table message 
     * suppression des enregistrements(messages) à la corbeille par l'expediteur ET le destinataire  
     */
    public function messagerie() {
        $messageManager = new Backend\MessageManager();
        $nettoyageMessagerie = $messageManager->cleanMessagerie();
        $messagesReçus = $messageManager->getMessagesReceived($_SESSION['userId']);
        $messagesEnvoyes = $messageManager->getMessagesSend($_SESSION['userId']);
        $message = $messageManager->nbMessagesNonLu($_SESSION['userId']);
        $nbMess = $message->fetchAll();
        $_SESSION['nbMess'] = $nbMess[0]['NB'];

        require('view/backend/messagerieView.php');
    }

//═════════════════════════════════════════════
//                   METHODES LIEES AUX SUITES 
//═════════════════════════════════════════════

    /**
     *  SUI- Integration de la suite au texte du chapitre Aposition du nom de l'auteur  -suppression de la suite deu scrutin et du vote 
     * @param type $suiteId
     * @param type $auteur
     * @param type $voteId
     * @throws exception
     */
    public function integrationSuite($suiteId, $auteur, $voteId) {
        $postManager = new Backend\PostManager();
        $voteManager = new Backend\VoteManager();
        $delDestinataire = 0;
        $delExpediteur = 0;
        $suite = $postManager->getPost($suiteId);
        $contenuSuite = $suite['ART_CONTENT'] . '<br /><b> Fin suite de ' . $auteur . '.</b><br />';
        $precedent = $suite['ART_PRECEDENT'];
        $insertionAuteur = '<b><br /><br />Suite de ' . $auteur . '<br /><br /></b>';
        $concatenation = $postManager->concatSuite($insertionAuteur, $contenuSuite, $precedent);

        if ($concatenation) {
            $suiteId = $suite['ART_ID'];
            $effaceScores = $voteManager->delScores($voteId);
            $effaceVote = $voteManager->delVote($voteId);
            $delSuite = $postManager->delPost($suiteId);
        } else {
            throw new exception('Echec de l\'intégration ');
        }
//        $objet = 'Integration de votre texte  ';
//        $contenu = "Bravo '.$auteur.' , votre texte est intégré à l\'ouvrage";
//        $this->messageSystem($auteur, $_SESSION['userId'],$delDestinataire, $delExpediteur,  $objet, $contenu);
        $this->cokpit();
    }

////═════════════════════════════════════════════
    //       
//═════════════════════════════════════════════

    /**
     * SUI-  recupere données de la suite - Suppression de la  suite - ajout message à l'auteur - appel methode post()  
     * @throws Exception  
     */
    public function supprimeSuite() {

        $postManager = new Backend\PostManager();
        $article = $postManager->getPost($_GET['id']);
        $suite = $postManager->delPost($_GET['id']);
$delDestinataire=0;
        $delExpediteur=0;
        if ($suite) {
            $objet = 'Supression ' . $article['ART_ID'];
            $contenu = 'Suppression du contenu suivant : ' . $article['ART_CONTENT'];
            $auteurId = $article['ART_AUTEUR'];

            $this->messageSystem($auteurId, $_SESSION['userId'],$delDestinataire, $delExpediteur, $objet, $contenu);
            $_GET['id'] = $_GET['precedent'];

            $this->post();
        } else {
            throw new Exception('Elément inconnu');
        }
    }

    /**
     * SUI     ajout suite a la bdd  Gere les messages internes aux utilisateurs concernés 
     * @throws Exception
     */
    public function ajouterSuite() {
        $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
        $chapter = preg_replace($regex, '', $_POST['art_chapter']);
        $precedent = preg_replace($regex, '', $_POST['precedent']);
        $contenu =  $_POST['art_content'];
        $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
        $auteur = preg_replace($regex, '', $_POST['auteur']);
        $statut_post = preg_replace($regex, '', $_POST['statut_post']);
        $postManager = new Backend\PostManager();
        $idStatutDuPost = $postManager->idStatut($statut_post);
        $dernierId = $postManager->addSuite($contenu, $ouvId, $precedent, $auteur, $idStatutDuPost['STATUT_POST_ID']);

        if (!$idStatutDuPost) {
            throw new Exception('Pb idstatutdupost');
        }
        if (!$dernierId) {
            throw new Exception('Pb addsuite');
        }
        $_GET['id'] = $dernierId;
        $_GET['ouv_id'] = $ouvId;

        //Messagerie interne
        $userManager = new Backend\UsersManager();
        $bookManager = new Backend\BookManager();
        $book = $bookManager->getBooksRights($ouvId);
        $article = $postManager->getPost($_GET['id']);
        $root = $userManager->listSuperadmin();
        $objet = 'Création d\'une suite ';
        $contenu = 'Une suite est en cours de rédaction  pour l\'ouvrage  <b><a href="indexadmin.php?action=lisPosts&amp;ouv_id= ' . $ouvId . '">Ouvrage '. $ouvId . '</a>  </b> Résumé contenu : <i>' . substr($_POST['art_content'], 0, 150) . '(...)</i>';
        $auteurId = $auteur;
        $delDestinataire=0;
        $delExpediteur=0;
        while ($adminBook = $book->fetch()) {
     
            $this->messageSystem($adminBook['p5_USERS_USER_ID'],$auteurId,$delDestinataire, $delExpediteur, $objet, $contenu);
            // envoyer mess aux administrateur de louvrage
        }
        while ($rootBook = $root->fetch()) {
    
            $this->messageSystem($rootBook['USER_ID'], $auteurId,$delDestinataire, $delExpediteur, $objet, $contenu);
            // envoyer mess aux Superadmin 
        }
        /// fin messagerie

        $this->listPosts($ouvId);
    }

    /**
     * SUI mise à jour (update) de la suite 
     * @throws Exception
     */
    public function majSuite() {
        $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";
        $id = preg_replace('([^0-9]+)', '', $_POST['art_id']);

        $ouvId = preg_replace($regex, '', $_POST['ouv_id']);
        $ouvrage = preg_replace($regex, '', $_POST['ouvrage']);
        $precedent = preg_replace($regex, '', $_POST['precedent']);
        $auteur = preg_replace($regex, '', $_POST['auteur']);
        $statut_post = preg_replace($regex, '', $_POST['statut_post']);
        $postManager = new Backend\PostManager();
        $idStatutDuPost = $postManager->idStatut('REDACTION');
        $suite = $postManager->updateSuite($_POST['art_content'], 1, $id, $ouvId, $precedent, $auteur, $idStatutDuPost['STATUT_POST_ID']);
        if ($suite) {
            $_GET['id'] = $precedent;
            $_GET['ouv_id'] = $ouvId;
            $this->post();
        } else {
            throw new Exception('Le chapitre est introuvable');
        }
    }

//═════════════════════════════════════════════
// SUI -          formulaire saisie nouvelle suite 
//═════════════════════════════════════════════

    /**
     * Appelle formulaire de création Suite avec données de l'ouvrage dont l'id est en paramètre
     * @param type $precedent
     * @param type $ouvId
     */
    public function formNewSuite($precedent, $ouvId) {

        $bookManager = new Backend\BookManager();
        $ouvrage = $bookManager->getBook($ouvId);

        require('view/backend/newSuiteView.php');
    }

    /**
     * SUI Change le statut de la Suite (post) après recupération de l'id du staut depuis son libelle en paramètre
     * Si le statut est VOTE on appelle la methode vote qui ajoute un vote
     * un lecteur ou redacteur ne peuvent mdifier nu statut admin accepte refuse ou vote 
     * Ajout des messages à destination   
     * @param type $libelleStatut
     * @throws Exception
     */
    public function changementStatutSuite($libelleStatut) {
        $postManager = new Backend\PostManager();
        $voteManager = new Backend\VoteManager();
        $post = null;
        $droits = unserialize($_SESSION['Rights']);
        $idStatutActuel = $postManager->statutDuPost($_GET['id']); //id du statut actuel
        $statutActuel = $postManager->libelleStatutPost($idStatutActuel[0]);
        $idStatut = $postManager->idStatut($libelleStatut); // on recupere l id du libelle 
        $delDestinataire = 0;
        $delExpediteur = 0;
        $bookManager = new Backend\BookManager();
        $userManager = new Backend\UsersManager();
        $article = $postManager->getPost($_GET['id']);
        $book = $bookManager->getBooksRights($article['OUVRAGE_OUV_ID']);
        $root = $userManager->listSuperadmin();
        if (($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR') || (($droits[$_GET['ouv_id']] <> 'ADMINISTRATEUR')AND ( ($statutActuel[0] == 'REDACTION') || ($statutActuel[0] == 'PROPOSE')))) {

            $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'], $_GET['id']);
        }


        if ($post) {
            if ($libelleStatut == 'VOTE') {
                $vote = $voteManager->vote($_GET['id']); // vote début maintenant durée 15 jours statut vote ouvert 
                
            }
           
            $objet = 'Changement de statut ';
            $contenu = '<a href="indexadmin.php?action=listPosts&apm;ouv_id='.$_GET['ouv_id'].'">Ouvrage'.$_GET['ouv_id'].'</a> :La suite proposée ( ' . $article['ART_ID'] . ') <b>Change de statut pour ' . $libelleStatut . '</b> de contenu : <i>' . substr($article['ART_CONTENT'], 0, 150) . '(...)</i>';
            $auteurId = $article['ART_AUTEUR'];
            if ($_SESSION['userId'] == $auteurId) {
             
                
                while ($adminBook = $book->fetch()) {
                    $this->messageSystem($adminBook['p5_USERS_USER_ID'], $_SESSION['userId'],$delDestinataire,$delExpediteur, $objet, $contenu);
                    // envoyer mess aux accès  de louvrage
                }
                while ($rootBook = $root->fetch()) {
 
                    $this->messageSystem($rootBook['USER_ID'], $_SESSION['userId'],$delDestinataire, $delExpediteur,  $objet, $contenu);
                    // envoyer mess aux Superadmin 
                }
            } else {

                // auteur prevenu si chgt de statut des chapitre dont il est l'auteur
                $this->messageSystem($auteurId, $_SESSION['userId'],$delDestinataire, $delExpediteur,$objet, $contenu);
            }

            $_GET['id'] = $_GET['precedent'];
            $this->post();
        } else {
            throw new Exception('Changement impossible action verrouillée');
        }
    }

    /**
     *  SUI Appel formulaire modification Suite recupee données ouvrage et suite depuis $_GET['id']&$_GET['ouv_id']
     * @throws Exception
     */
    public function formModifySuite() {
        $postManager = new Backend\PostManager();
        $bookManager = new Backend\BookManager();
        $ouvrage = $bookManager->getBook($_GET['ouv_id']);
        $suite = $postManager->getSuite($_GET['id']);
        if ($suite) {
            require('view/backend/updateSuiteView.php');
        } else {
            throw new Exception('Chapitre inconnu ');
        }
    }

//═════════════════════════════════════════════
//                  METHODES LIEES AUX UTILISATEURS 
//═════════════════════════════════════════════

    /**
     * USER 
     * Renvoi false si uner inconnu ou pb d'identifiant 
     * FONCTION verifuser() instancie UsersManager méthode connexion()
     * récupère en varuiable de session userId et pseudo 
     *  recupere nb message non lu 
     * @return boolean
     */
    public function verifUser() {
        $userValid = FALSE;
        $userManager = new Backend\UsersManager();
        $user = $userManager->connexion(($_POST['usrname']), ($_POST['passwd'])); // pseudo + passwd
        if ($user != FALSE) {
            $_SESSION['userId'] = $user[0]['USER_ID'];
            $_SESSION['user'] = $_POST['usrname'];
            $_SESSION['superAdmin'] = $user[0]['ROOT'];
            $userValid = TRUE;
            $messageManager = new Backend\MessageManager();
            $message = $messageManager->nbMessagesNonLu($_SESSION['userId']);
            $nbMess = $message->fetchAll();
            $_SESSION['nbMess'] = $nbMess[0]['NB'];
        } else {
            $userValid = FALSE;
        }
        return $userValid;
    }

    /**
     * USER  si identification ok on affiche la page identification sinon page erreur
     * @param type $bool
     */
    public function identification($bool) {

        $param = $bool;
        if ($param === FALSE) {
            require('view/backend/erreurView.php');
        } else {

            require_once ('view/backend/identificationView.php');
        }
    }

    /**
     * USER-         Changement password utilisateur 
     */
    public function changePsswd() {

        if ((!empty($_POST['oldmdp']))AND ( strlen($_POST['mdp']) >= 6)AND ( $_POST['mdp'] === $_POST['remdp'])) {
            $pseudo = $_SESSION['user'];
            $userManager = new Backend\UsersManager();
            $connexion = $userManager->connexion($pseudo, $_POST['oldmdp']);
            if ($connexion) {
                $user = $userManager->updatePsswd($_POST['mdp'], $pseudo);
                $message = 'Mot de passe enregistré.';
            } else {
                $message = 'Identifiant incorrects ou le nouveau  mot de passe doit être au moins égal à 6 caractères Sinon Réessayez plus tard! ';
            }
        } else {
            $message = ' Un champ ne peut être vide.';
        }
        require ('view/backend/updatePasswdView.php');
    }

//═════════════════════════════════════════════
//   
//═════════════════════════════════════════════

    /**
     * USER-         code aléatoire pour procedure motde passe oublie 
     * @return string
     */
    public function codeValidation() {
        $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $code = '';
        $longeurChaine = strlen($chaine);
        echo $longeurChaine;
        $nbCaractereCode = 8;
        for ($i = 0; $i < $nbCaractereCode; $i++) {
            $code .= $chaine[rand(0, $longeurChaine - 1)];
        }
        return $code;
    }

//═════════════════════════════════════════════
//   
//═════════════════════════════════════════════ 

    /**
     *  USER-         message Email  pour procedure motde passe oublie
     * @param type $destinataire
     * @param type $texte
     * @return string
     */
    public function messagePasswdOublie($destinataire, $texte) {

        $passage_ligne = "\n";
        $to = $destinataire;

// Sujet du message 
        $subject = "Votre demande depuis le site Les romans collaboratifs";

// Corps du message, écrit en texte et encodage iso-8859-1
        $message = "Suite à votre demande, veuillez prendre note du code suivant :" . $texte . "\n";
        $message .= "Vous voudrez bien vous connecter et changer votre mot de passe dès la première connexion \n";
        $message .= "Bonne réception \n Webmaster LES ROMANS COLLABORATIFS";
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

    /**
     * USER-          procedure motde passe oublie 
     * @param type $mail
     */
    public function motDePasseOublie($mail) {
        // le mail existe il ?
        $userManager = new Backend\UsersManager();
        $emailExist = $userManager->emailExist($mail);
        if ($emailExist) {
            //S'il existe, on fabrique le code , on update le code , on envoi le message
            $pseudo = $emailExist[0]['USER_PSEUDO'];
            $code = $this->codeValidation();
            //echo $pseudo;
            // echo " code : ".$code;
            $updatePassword = $userManager->updatePsswd($code, $pseudo);
            $message = $this->messagePasswdOublie($mail, $code);
            //echo $message;
        } else {
            //echo "pas de mail exist";
        }
        // on retourne sur la page d'identification
        require ('view/backend/identificationView.php');
    }

    /**
     * USER-          suppression user puis appel de cokpit()
     * @param type $user_id
     * @throws Exception
     */
    public function supprimeUser($user_id) {
        if (($_SESSION['superAdmin'] == 1)AND ( $_SESSION['userId'] <> $user_id)) {
            $usersManager = new Backend\UsersManager();
            $delUser = $usersManager->delUser($user_id);

            $this->cokpit();
        } else {
            throw new Exception('Vous n\'avez pas les droits d\'accès pour effectuer cette opération ');
        }
    }

    /**
     * USER-          initialisation  user (mot de passe aléatoire )permet desactiver un utilisateur sans supprimer ses données commentaires votes etc ..
     * @param type $user_id
     * @throws Exception
     */
    public function initUser($user_id) {
        if (($_SESSION['superAdmin'] == 1)AND ( $_SESSION['userId'] <> $user_id)) {
            $usersManager = new Backend\UsersManager();
            $codeValidation = $this->codeValidation();
            $passwd = $usersManager->passwordUser($codeValidation);
            $initUser = $usersManager->initUser($user_id, $passwd);

            $this->cokpit();
        } else {
            throw new Exception('Vous n\'avez pas les droits d\'accès pour effectuer cette opération ');
        }
    }

    /**
     * USER-          update  user puis appel cokpit()
     * @param type $userId
     * @param type $userName
     * @param type $userLastname
     * @param type $userPseudo
     * @param type $userMail
     * @param type $userStatut
     */
    public function majUser($userId, $userName, $userLastname, $userPseudo, $userMail, $userStatut) {
        if ($_SESSION['superAdmin'] == 1) {
            $usersManager = new Backend\UsersManager();
            $majUser = $usersManager->updateUser($userId, $userName, $userLastname, $userPseudo, $userMail, $userStatut);
            $this->cokpit();
        }
    }

//═════════════════════════════════════════════
//   
//═════════════════════════════════════════════ 
    /**
     * USER-          get données pour un user
     */
    public function userGet() {
        $usersManager = new Backend\UsersManager();
        $user = $usersManager->getUser($_GET['id']);

        require('view/backend/updateUserView.php');
    }

    /**
     * USER-          ADD user puis ppel cokpit()
     * @param type $userName
     * @param type $userLastname
     * @param type $userPseudo
     * @param type $userMail
     * @param type $userPasswd
     * @param type $userStatut
     */
    public function ajouterUser($userName, $userLastname, $userPseudo, $userMail, $userPasswd, $userStatut) {
        if ($_SESSION['superAdmin'] == 1) {
            $usersManager = new Backend\UsersManager();
            $newUser = $usersManager->addUser($userName, $userLastname, $userPseudo, $userMail, $userPasswd, $userStatut);
            $this->cokpit();
        }
    }

//═════════════════════════════════════════════
//  
//═════════════════════════════════════════════ 
    /**
     *  USER-          acces formulaire nouvel utilisateur
     */
    public function formNewUser() {

        require('view/backend/newUserView.php');
    }

//═════════════════════════════════════════════
//                 METHODES LIEES AUX VOTES 
//═════════════════════════════════════════════
//

    /**
     * VOTE ajoute un buuletin dans l'urne  apres avoir recupere lid du vote verifie que le vote est bien unique
     * @throws Exception
     */
    public function vote() {

        $voteManager = new Backend\VoteManager();
        $voteId = $voteManager->quelVote($_GET['id']);
        $dejaVote = $voteManager->voteUnique($voteId);
        if ($dejaVote == 0) {
            $vote = $voteManager->addVote($_GET['bulletin'], $voteId);
        } else {
            throw new Exception('Vous avez déjà voté pour ce scrutin');
        }
        $_GET['id'] = $_GET['precedent'];
        $this->post();
    }

    /**
     * VOTE          modification de la date butoir d'un scrutin et appel cokpit()
     * @param type $vote_id
     * @param type $dateFin
     * @param type $duree
     */
    public function modifDureeVote($vote_id, $dateFin, $duree) {
        $voteManager = new Backend\VoteManager();
        $voteprolong = $voteManager->updateVoteDuree($vote_id, $dateFin, $duree);
        $this->cokpit();
    }

    /**
     * VOTE          Cloture le scrutin et appel copkpit()
     * @param type $art_id
     */
    public function closeVote($art_id) {
        $voteManager = new Backend\VoteManager();
        $voteClose = $voteManager->fermetureVote($art_id);
        $this->cokpit();
    }

    /**
     *  VOTE          Ouvre le scrutin et appel copkpit()
     * @param type $art_id
     */
    public function openVote($art_id) {
        $voteManager = new Backend\VoteManager();
        $voteClose = $voteManager->ouvertureVote($art_id);
        $this->cokpit();
    }

    /**
     *  VOTE          Supprime le scrutin les scores et remet la suite en statut accepte et appel cokpit()
     * @param type $vote_id
     * @param type $art_id
     */
    function supprimeVote($vote_id, $art_id) {
        $voteManager = new Backend\VoteManager();
        $postManager = new Backend\PostManager();
        $voteDel = $voteManager->delVote($vote_id);
        $idStatut = $postManager->idStatut('ACCEPTE'); // on recupere l id du libelle 
        $post = $postManager->changeStatutPost($idStatut['STATUT_POST_ID'], $art_id);
        $this->cokpit();
    }

//
//
//
//
//═════════════════════════════════════════════
//                 METHODES LIEES AUX OUVRAGES 
//═════════════════════════════════════════════

    /**
     * OUV       fonction upload image ouvrage  
     * @param type $bookId
     * @return string
     */
    public function uploadImageBook($bookId) {

        $bookManager = new Backend\BookManager();
        $book = $bookManager->getBook($bookId);
        $image = $book['OUV_IMAGE']; /// récupération

        if (!empty($_FILES['uploaded_imageBook']['name'])) {
            $extensions_valides = array('.jpg');
//1. strrchr renvoie l'extension avec le point (« . »).
//2. strtolower met l'extension en minuscules.
            $extension_upload = strtolower(strrchr($_FILES['uploaded_imageBook']['name'], '.'));
            $path = "uploads/";
            $_FILES['uploaded_imageBook']['name'] = 'ouvrage' . $book['OUV_ID'] . $extension_upload;

// On récupère les dimensions de l'image

            $dimensions = getimagesize($_FILES['uploaded_imageBook']['tmp_name']);
            $width_orig = $dimensions[0];
            $height_orig = $dimensions[1];
            //$ratio_orig = $width_orig / $height_orig;


            $path = $path . basename($_FILES['uploaded_imageBook']['name']);
            if (in_array($extension_upload, $extensions_valides)) {
                // Si le fichier existe on l'efface
                if (is_file($path)) {
                    $eff = unlink($path);
                }
                move_uploaded_file($_FILES['uploaded_imageBook']['tmp_name'], $path);
                $message = "Le fichier " . basename($_FILES['uploaded_imageBook']['name']) .
                        " à été uploadé";
                $image = $_FILES['uploaded_imageBook']['name'];
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

    /**
     * OUV      recupère données Ouvrage depuis un ID
     */
    public function book() {
        $bookManager = new Backend\BookManager();
    if (isset($_GET['ouv_id'])){
        $book = $bookManager->getBook($_GET['ouv_id']);
    }elseif(isset($_POST['ouv_id'])){
        $book = $bookManager->getBook($_POST['ouv_id']);
    }
        require('view/backend/bookView.php');
    }

    /**
     * OUV-         Ouvrage depuis un ID pour un utilisateur (non admin)
     */
    public function bookUser() {
        $bookManager = new Backend\BookManager();

        $book = $bookManager->getBookUser($_GET['ouv_id']);
        require('view/backend/bookView.php');
    }

    /**
     * OUV Liste des ouvrages pour un utilisateur
     * @param type $userId
     */
    public function listOuvragesUser($userId) {
        $bookManager = new Backend\BookManager();
        $books = $bookManager->getBooksUser($userId);

        require('view/backend/listBooksView.php');
    }

    /**
     * OUV Liste des ouvrages 
     */
    public function listOuvrages() {
        $bookManager = new Backend\BookManager(); //new Backend\BookManager();
        $books = $bookManager->getBooks();

        require('view/backend/listBooksView.php');
    }

    /**
     * OUV          suppression acces a ouvrage
     * @param type $userId
     * @param type $ouvId
     * @param type $statutId
     */
    public function supprimeAccesOuvrage($userId, $ouvId, $statutId) {
        $bookManager = new Backend\BookManager();
        $delAcces = $bookManager->delBookAcces($userId, $ouvId, $statutId);
        $this->accesBook($ouvId);
        $objet = 'Modification accès';
        $contenu = 'Votre accès à ouvrage n°' . $ouvId . ' est supprimé';
        $delDestinataire=0;
        $delExpediteur=0;
        $this->messageSystem($userId, $_SESSION['userId'],$delDestinataire, $delExpediteur, $objet, $contenu);
    }

    /**
     *  OUV        appelle formulaire nouvel acces a ouvrage recupere la liste des users et les droits 
     * @param type $ouvId
     */
    public function formNewBookAcces($ouvId) {
//liste des utilisateurs
        $userManager = new Backend\UsersManager();
        $users = $userManager->getUsers();
        $statuts = $userManager->getStatuts();
//liste des statut 
//vérification user un seul par ouvrage.........f
        require('view/backend/newBookAccesView.php');
    }

    /**
     * OUV- appel vue formulaire nouvel ouvrage
     */
    public function formNewBook() {

        require('view/backend/newBookView.php');
    }

    /**
     * OUV -   Mise à jour d'un Ouvrage 
     */
    public function majBook() {
        $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";

        $id = preg_replace('([^0-9]+)', '', $_POST['ouv_id']);
        $keywords = preg_replace($regex, '', $_POST['ouv_keywords']);
        $description = preg_replace($regex, '', $_POST['ouv_description']);
        $subtitle = preg_replace($regex, '', $_POST['ouv_soustitre']);
        $titre = preg_replace($regex, '', $_POST['ouv_titre']);
        $image = $this->uploadImageBook($id);
        if ($_SESSION['superAdmin'] == 1) {
            $bookManager = new Backend\BookManager();
            $book = $bookManager->updateBook($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $id, $image);
            $_GET['ouv_id'] = $id;
            $this->book();
        } else {
            $bookManager = new Backend\BookManager();
            $book = $bookManager->updateBookUser($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $id, $image);
            $_GET['ouv_id'] = $id;
            $this->book();
        }
    }

    /**
     * OUV -   Ajouter un acces pour un ouvrage - envoi message à l'utilisateur 
     * @param type $ouvId
     * @param type $userId
     * @param type $statutId
     * @throws Exception
     */
    public function addAccesOuvrage($ouvId, $userId, $statutId) {
        $bookManager = new Backend\BookManager();
        $verif = $bookManager->verifAccesBook($ouvId, $userId);
        if ($verif[0][0] == 0) {
            $acces = $bookManager->addAccesBook($ouvId, $userId, $statutId);
            $this->accesBook($ouvId);
            $objet = 'Modification accès';
            $contenu = 'Vous avez un  nouvel accès pour l\'ouvrage  n°' . $ouvId . ' Veuillez vous connecter et vérifier ';
            $delDestinataire=0;
            $delExpediteur=0;
            $this->messageSystem($userId, $_SESSION['userId'],$delDestinataire,$delExpediteur, $objet, $contenu);
        } else {

            throw new Exception('L\'utilisateur a déjà un accès pour cette ouvrage - Vous devez  supprimer l\'accès avant d\'en créer un nouveau ');
        }
    }

    /**
     * OUV -  1 Ouvrage depuis son ID -appelle vue formulaire modification de l'ouvrage
     */
    public function formModifyBook() {
        $bookManager = new Backend\BookManager(); //new Backend\BookManager();

        $book = $bookManager->getBook($_GET['ouv_id']);

        require('view/backend/updateBookView.php');
    }

    /**
     *  OUV -    1 Ouvrage depuis son ID - appelle vue formulaire modification de l'ouvrage
     */
    public function formModifyBookUser() {
        $bookManager = new Backend\BookManager();

        $book = $bookManager->getBookUser($_GET['ouv_id']);

        require('view/backend/updateBookView.php');
    }

    /**
     * /  OUV -  Ajouter un Ouvrage 
     * @throws Exception
     */
    public function ajouterOuvrage() {

        $regex = "([^a-zA-Z0-9 .,\'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]+)";


        $keywords = preg_replace($regex, '', $_POST['ouv_keywords']);
        $description = preg_replace($regex, '', $_POST['ouv_description']);
        $subtitle = preg_replace($regex, '', $_POST['ouv_soustitre']);
        $titre = preg_replace($regex, '', $_POST['ouv_titre']);


        if ($_SESSION['superAdmin'] == 1) {


            $bookManager = new Backend\BookManager();
            $dernierId = $bookManager->addBook($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords);
            $image = $this->uploadImageBook($dernierId);

            $book = $bookManager->updateBook($titre, $_POST['ouv_preface'], $subtitle, $description, $keywords, 0, $dernierId, $image);
            $this->listOuvrages();
        } else {
            throw new Exception('Vous n\'avez pas les droits pour ajouter un ouvrage.');
        }
    }

    /**
     * OUV -  Supprimer  un Ouvrage 
     */
    public function supprimeOuvrage() {

        $bookManager = new Backend\BookManager();
        $book = $bookManager->delBook($_GET['ouv_id']);
        $image = $bookManager->delBookImage($_GET['ouv_id']);

        $this->listOuvrages();
    }

    /**
     *  OUV -  Supprimer  un Ouvrage user
     */
    public function supprimeOuvrageUser() {

        $bookManager = new Backend\BookManager();
        $book = $bookManager->delBookUser($_GET['ouv_id']);
        $image = $bookManager->delBookImage($_GET['ouv_id']);

        $this->listOuvragesUser($_SESSION['userId']);
    }

    /**
     * OUV -  desactive un ouvrage ainsi que ts les chapitres
     * @throws Exception
     */
    public function desactiverBook() {

        $bookManager = new Backend\BookManager();
        $postManager = new Backend\postManager();
        $book = $bookManager->disableBook($_GET['ouv_id']);
        $post = $postManager->disablePostsBook($_GET['ouv_id']);
        if ($book AND $post) {
            $this->book();
        } else {
            throw new Exception('requête non aboutie');
        }
    }

    /**
     * OUV -  active un ouvrage sans activer les chapitres
     * @throws Exception
     */
    public function activerBook() {


        $bookManager = new Backend\BookManager();

        $book = $bookManager->enableBook($_GET['ouv_id']);
        if ($book) {
            $this->book();
        } else {
            throw new Exception('requête non aboutie');
        }
    }

    /**
     *  OUV  active les chapitres d'un ouvrage
     * @throws Exception
     */
    public function activerPostsBook() {


        $postManager = new Backend\postManager();
        $post = $postManager->enablePostsBook($_GET['ouv_id']);
        if ($post) {
            $this->book();
        } else {
            throw new Exception('requête non aboutie');
        }
    }

    /**
     * OUV -  liste des acces pour un ouvrage
     * @param type $ouvId
     */
    public function accesBook($ouvId) {
        $bookManager = new Backend\BookManager();
        $rightsBooks = $bookManager->getBooksRights($ouvId);
        require_once('view/backend/dashBoardBookAccesView.php');
    }

//═════════════════════════════════════════════
//                 METHODES LIEES AUX COMMENTAIRES 
//═════════════════════════════════════════════

    /**
     *  COMM                  active un commentaire 
     * @throws Exception
     */
    public function activeComment() {
        $commentManager = new Backend\CommentManager();
        $comment = $commentManager->enableComment($_GET['commId']);
        if ($comment) {
            $this->post();
        } else {
            throw new Exception('Elément inconnu 323');
        }
    }

    /**
     *  COMM           désactive un commentaire
     * @throws Exception
     */
    public function desactiveComment() {
        $commentManager = new Backend\CommentManager();
        $comment = $commentManager->disableComment($_GET['commId']);
        if ($comment) {
            $this->post();
        } else {
            throw new Exception('Elément inconnu 337');
        }
    }

    /**
     * COMM  signal  un commentaire 
     * @throws Exception
     */
    public function activeSignal() {
        $commentManager = new Backend\CommentManager();
        $comment = $commentManager->enableSignal($_GET['commId']);
        if ($comment) {
            $this->messageSignalementCommentaire();
            $this->post();
        } else {
            throw new Exception('Elément inconnu 351');
        }
    }

    /**
     * COMM   Supprime le signalement d'un commentaire 
     * @throws Exception
     */
    public function desactiveSignal() {

        $commentManager = new Backend\CommentManager();
        $comment = $commentManager->disableSignal($_GET['commId']);
        if ($comment) {
            $this->post();
        } else {
            throw new Exception('Elément inconnu ');
        }
    }

    /**
     * COMMUM   fonction tzableau de bord données users ouvrage scores votes utilisateurs 
     */
    public function cokpit() {
        $bookManager = new Backend\BookManager();
        $usersManager = new Backend\UsersManager();
        $voteManager = new Backend\VoteManager();
        $postManager = new Backend\PostManager();
        $listUsers = $usersManager->getUsers(); // Liste utilisateurs
        $listBooks = $bookManager->getBooks(); //Liste des ouvrages 
        $lesScores = $voteManager->lesScores();
        $tableauScores = $lesScores->fetchAll();
        $votesListe = $voteManager->voteList();
        $votesListeClose = $voteManager->voteListClose();
        $totalVisit = $postManager->countVisitPost();
        $visit24H = $postManager->countVisitPostSinceHour('24');
        $visit7J = $postManager->countVisitPostSinceDay('7');
        $visit1M = $postManager->countVisitPostSinceMonth('1');
        $visit6M = $postManager->countVisitPostSinceMonth('6');
        require_once('view/backend/dashBoardView.php');
    }

    /**
     * appel de la vue erreur 
     * @param type $message
     */
    public function erreur($message) {
        $_POST['message'] = $message;
        require_once('view/backend/erreurView.php');
    }

}
