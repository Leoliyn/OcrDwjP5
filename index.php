<?php
session_start();
ini_set('display_errors', 1);
//╔═════════════════════════════╗  
//║           PROJET 5 DWJ OPENCLASSROOMS         ║
//║         CLAUDEY Lionel Avril 2018             ║
//╚═════════════════════════════╝

require_once 'controler/frontend/frontend.php';

try { // On essaie
    if (isset($_GET['action'])AND ( $_GET['action'] == 'listPosts')AND ( isset($_GET['ouv_id']))) {
    listPostsResume();
    } else {

//if (isset($_GET['action'])AND ( $_GET['action'] == 'post')AND ( isset($_GET['id']))) {
//    post();
//} elseif (isset($_GET['action'])AND ( $_GET['action'] == 'addComment') AND ( isset($_GET['id']))) {
//    if (empty($_POST['author']) || empty($_POST['comment'])) {
//        post();
//    } else {
//        ajoutComment();
//    }
//} elseif (isset($_GET['action'])AND ( $_GET['action'] == 'enableSignal')AND ( isset($_GET['id']))) {
//    activeSignal();
//} elseif (isset($_GET['action'])AND ( $_GET['action'] == 'message')) {
//    $infoMail = message($_POST['nomMessage'], $_POST['email'], $_POST['message']);
//    if ($infoMail) {
       ?>
     <!--   <script>
            alert("Message envoyé!");

        </script> -->
        <?php
//
//        listPostsResume();
//    } else {
//        ?>

       <!-- <script>
            alert("Réessayez plus tard!");

        </script> <input type="submit" value="?>" />-->
        <?php
//
//    }


 //listPostsResume();
//}
 ///////////////// NOUVEAU SCRIPT
        
  listOuvrages()  ;    
        
///////////////// FIN NOUVEAU SCRIPT        
    }
}
catch(Exception $e) { // S'il y a eu une erreur, alors...
echo 'Erreur : ' . $e->getMessage();

}
