<?php
session_start();
ini_set('display_errors', 1);

//╔═════════════════════════════╗  
//║           PROJET 4 DWJ OPENCLASSROOMS         ║
//║         CLAUDEY Lionel février 2018           ║
//╚═════════════════════════════╝
require_once('controler/backend/backend.php'); 
//require_once('controler/backend/backendBook.php'); 

try {
     
    if (isset($_GET['action']) AND ($_GET['action']=='deconnexion'))
    {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?');
        exit(); 
    }
    elseif (isset($_POST['emailForget']))
   {
        $mail= $_POST['emailForget'];
        
        motDePasseOublie($mail);
   }
    elseif ((isset($_SESSION['user'])) AND (isset($_SESSION['userId'])) ) 
    {
       
            if(isset($_GET['action'])AND ($_GET['action']=='enablePost')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
              $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]){
                  publierPost();
                }else{
                    throw new Exception("Vous n'avez pas les droits d/'accès pour publier un chapitre");
                }    
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disablePost')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                  $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]){
               desactiverPost();
                }else{
                    throw new Exception("Vous n'avez pas les droits d/'accès pour publier un chapitre");
                }
           
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableBook')AND (isset($_GET['ouv_id']))
                    AND (isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1)))
            {
            activerBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableBook')AND (isset($_GET['ouv_id']))
                    AND (isset($_SESSION['superAdmin']))AND ($_SESSION['superAdmin']==1))
            {
            desactiverBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='delPost')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
            supprimePost();
            }else{
                    throw new Exception("Vous n'avez pas les droits d/'accès(delpost ligne 59 indexAdmin");
                }  
           
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='delOuvrage')AND (isset($_GET['ouv_id'])))
            {
                if(isset($_SESSION['superAdmin'])AND $_SESSION['superAdmin']== 1){
                supprimeOuvrage();
                }else{
                supprimeOuvrageUser();    
                }
            }
            elseif((isset($_GET['action']))AND ($_GET['action']=='post')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
              $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]){
                   post();   
                }else{
                    throw new Exception("Vous n'avez pas les droits d/'accès ligne 78 idxAdm");
                }   
        
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
      
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableSignal')AND (isset($_GET['id'])))
            {
            activeSignal();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableSignal')AND (isset($_GET['id'])))
            {
            desactiveSignal();     
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='updatePost')AND (isset($_GET['id'])))
            {
            formModifyPost();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='updateBook')AND (isset($_GET['ouv_id'])))
                {
                             if(isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1))
            {
            formModifyBook();
            }else{
            formModifyBookUser();  
            }
                }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majPost')AND (isset($_POST['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
             $droits= unserialize($_SESSION['Rights']);
                if($droits[$_POST['ouv_id']]=='ADMINISTRATEUR'){
            majPost();
            }else{
                    throw new Exception("Vous n'avez pas les droits d/'accès pour modifier un chapitre");
                }   
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majBook'))
            {
            majBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='newPost')AND (isset($_GET['ouvId'])))
            {
            formNewPost($_GET['ouvId']);
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='newBook'))
            {
            formNewBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='book')AND (isset($_GET['ouv_id'])))
            {
               if(isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1))
            {
            book();
            }else {
            bookUser();
            }
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='changePsswd'))
            {
            changePsswd();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='addPost')AND (isset($_POST['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                  $droits= unserialize($_SESSION['Rights']);
                if($droits[$_POST['ouv_id']]=='ADMINISTRATEUR'){
            ajouterPost($_POST['ouv_id']);
            }else{
                    throw new Exception("Vous n'avez pas les droits d/'accès pour ajouter un chapitre");
                }
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='addBook')AND (isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1)))
            {
            ajouterOuvrage();
            }
            elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id']))AND ( $_GET['action'] == 'listPosts')AND ( isset($_SESSION['Rights']))) {
            $droits = unserialize($_SESSION['Rights']);
            if ($droits[$_GET['ouv_id']]) {
                listPosts($_GET['ouv_id']);
            } else {
                throw new Exception("Vous n'avez pas les droits d/'accès pour afficher les chapitres de cet ouvrage");
            }
        } else {
                    if($_SESSION['superAdmin']==1){
                       listOuvrages();
                    }else {
                listOuvragesUser($_SESSION['userId']);
                    }
                    
                    }
    }
    elseif(isset($_POST['usrname'])AND isset($_POST['passwd']))
    {
        
      
       if(verifUser()=== TRUE){
      header('Location: indexadmin.php');exit();
       }else {  
          header('Location: indexadmin.php?action=reconnexion');exit(); 
    
       }
    }
    elseif (isset($_GET['action'])AND($_GET['action'] == 'connexion') )
        {         
           
            identification(TRUE);
        }
    elseif (isset($_GET['action'])AND($_GET['action'] == 'reconnexion') )
        {         
           
            identification(FALSE);
        }
  
        
    else { 
      
        identification(TRUE);
    }
 
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
