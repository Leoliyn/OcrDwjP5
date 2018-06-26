<?php
session_start();
$_SESSION['id']=session_id();
ini_set('display_errors', 1);
//╔═════════════════════════════╗  
//║           PROJET 5 DWJ OPENCLASSROOMS         ║
//║         CLAUDEY Lionel Avril 2018             ║
//╚═════════════════════════════╝

require_once 'controler/frontend/frontend.php';

try { // On essaie
    if (isset($_GET['action'])AND ( $_GET['action'] == 'listPosts')AND ( isset($_GET['ouv_id']))) {
    listPostsResume();
    // a supprimer après test
//    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'post')AND ( isset($_GET['id']))) {
//    post();

    } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'post')AND ( isset($_GET['id']))AND ( isset($_GET['ouv_id']))) {
    postSession($_SESSION['id']);
      } elseif ((isset($_GET['action']))AND ( $_GET['action'] == 'inscription')AND ( isset($_POST['email']))AND ( isset($_POST['password']))AND ( isset($_POST['pseudo']))AND ( isset($_POST['prenom']))AND ( isset($_POST['nom']))) {
   
     inscription($_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'], $_POST['password'], 0);
     
     
     } elseif (isset($_GET['action'])AND ( $_GET['action'] == 'addComment') ) {
//throw new Exception( 'postid:'.$_POST['postId'].' auteur '.$_POST['authorId'].'comment :'.$_POST['comment'].' parent:'.$_POST['precedent'].' opuvid: '.$_POST['ouvId']);

         
    ajoutComment($_POST['postId'],$_POST['authorId'],$_POST['comment'],$_POST['precedent'],$_POST['ouvId']);
    
     
  }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableSignal')AND (isset($_GET['id'])))
            {
                
        activeSignal();
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableSignal')AND (isset($_GET['id'])))
            {
               
       desactiveSignal();
            
           }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableComment')AND (isset($_GET['commId']))AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                 $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
                   
                 desactiveComment();
              
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour modifier un commentaire");
                }  
       
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableComment')AND (isset($_GET['commId']))AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                 $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
                    
          activeComment();
              }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour modifier un chapitre");
                }  
      
             
            
            } else {       //throw new Exception ("pas ok onrentre pas dans  : ".$_GET['action'].$_POST['email'].$_POST['password'].$_POST['pseudo'].$_POST['prenom'].$_POST['nom']);
  listOuvrages()  ;    
        
///////////////// FIN NOUVEAU SCRIPT        
    }
    
//
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

}
catch(Exception $e) { // S'il y a eu une erreur, alors...
echo 'Erreur : ' . $e->getMessage();

}
