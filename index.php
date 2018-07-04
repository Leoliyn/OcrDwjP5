<?php

session_start();
$_SESSION['id'] = session_id();
$_SESSION['title'] = 'Les Romans Collaboratifs';
ini_set('display_errors', 1);
//╔═════════════════════════════╗  
//║           PROJET 5 DWJ OPENCLASSROOMS         ║
//║         CLAUDEY Lionel  2018                  ║
//╚═════════════════════════════╝

require_once 'controler/frontend/frontend.php';

try { // On essaie
    if (isset($_GET['action'])AND ( $_GET['action'] == 'listPosts')AND ( isset($_GET['ouv_id']))) {
        $controler = new FrontendControler();
        $liste = $controler->listPostsResume();
    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'post')AND ( isset($_GET['id']))AND ( isset($_GET['ouv_id']))) {
        $controler = new FrontendControler();
        $sess = $controler->postSession($_SESSION['id']);
    } elseif ((isset($_GET['action']))AND ( $_GET['action'] == 'inscription')AND ( isset($_POST['email']))AND ( isset($_POST['password']))AND ( isset($_POST['pseudo']))AND ( isset($_POST['prenom']))AND ( isset($_POST['nom']))) {
        $controler = new FrontendControler();
        $ins = $controler->inscription($_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'], $_POST['password'], 0);
    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'addComment')) {
        $controler = new FrontendControler();
        $comm = $controler->ajoutComment($_POST['postId'], $_POST['authorId'], $_POST['comment'], $_POST['precedent'], $_POST['ouvId']);
    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'enableSignal')AND ( isset($_GET['id']))) {
        $controler = new FrontendControler();
        $act = $controler->activeSignal();
    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'disableSignal')AND ( isset($_GET['id']))) {
        $controler = new FrontendControler();
        $des = $controler->desactiveSignal();
    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'disableComment')AND ( isset($_GET['commId']))AND ( isset($_GET['id']))AND ( isset($_GET['ouv_id']))AND ( isset($_SESSION['Rights']))) {
        $droits = unserialize($_SESSION['Rights']);
        if ($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR') {
            $controler = new FrontendControler();
            $desC = $controler->desactiveComment();
        } else {
            throw new Exception("Vous n'avez pas les droits d'accès pour modifier un commentaire");
        }
    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'enableComment')AND ( isset($_GET['commId']))AND ( isset($_GET['id']))AND ( isset($_GET['ouv_id']))AND ( isset($_SESSION['Rights']))) {
        $droits = unserialize($_SESSION['Rights']);
        if ($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR') {
            $controler = new FrontendControler();
            $actC = $controler->activeComment();
        } else {
            throw new Exception("Vous n'avez pas les droits d'accès pour modifier un chapitre");
        }
    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'message')) {
        $controler = new FrontendControler();
        $infoMail = $controler->message($_POST['nomMessage'], $_POST['email'], $_POST['message']);
        if ($infoMail) {
            ?>
            <script>
                alert("Message envoyé!");
            </script>
            <?php

        } else {
            ?>

            <script>
                alert("Réessayez plus tard!");

            </script> 
            <?php

        }
        $controler = new FrontendControler();
        $list = $controler->listOuvrages();
    } else {       //throw new Exception ("pas ok onrentre pas dans  : ".$_GET['action'].$_POST['email'].$_POST['password'].$_POST['pseudo'].$_POST['prenom'].$_POST['nom']);
        $controler = new FrontendControler();
        $listeO = $controler->listOuvrages();
    }
} catch (Exception $e) {
    $message = $e->getMessage();
    $error = new frontendControler();
    $affError = $error->erreur($message);
}