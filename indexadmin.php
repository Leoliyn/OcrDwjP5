<?php
session_start();
$_SESSION['title']='Les Romans Collaboratifs';
//ini_set('display_errors', 1);
//////////////////////////////////////////////////////
//if(!empty($_POST) OR !empty($_FILES))
//{
//    $_SESSION['sauvegarde'] = $_POST ;
//    $_SESSION['sauvegardeFILES'] = $_FILES ;
//    
//    $fichierActuel = $_SERVER['PHP_SELF'] ;
//    if(!empty($_SERVER['QUERY_STRING']))
//    {
//        $fichierActuel .= '?' . $_SERVER['QUERY_STRING'] ;
//    }
//    
//    header('Location: ' . $fichierActuel);
//    exit;
//}
//
//if(isset($_SESSION['sauvegarde']))
//{
//    $_POST = $_SESSION['sauvegarde'] ;
//    $_FILES = $_SESSION['sauvegardeFILES'] ;
//    
//    unset($_SESSION['sauvegarde'], $_SESSION['sauvegardeFILES']);
//}
/////////////////////////////////////////////////////////////////
//╔═════════════════════════════╗  
//║           PROJET 5DWJ OPENCLASSROOMS         ║
//║         CLAUDEY Lionel Avril 2018           ║
//╚═════════════════════════════╝
require_once'controler/backend/backend.php'; 
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
        $user= new BackendControler();
                  $psswd= $user->motDePasseOublie($mail);
        
   }
    elseif ((isset($_SESSION['user'])) AND (isset($_SESSION['userId'])) ) 
    {
       
            if(isset($_GET['action'])AND ($_GET['action']=='enablePost')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
              $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
                  $post= new BackendControler();
                  $publicationPost= $post->publierPost();
               
                    
                }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour publier un chapitre");
                }    
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disablePost')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                  $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
                    $post= new BackendControler();
                    $desactivPost= $post->desactiverPost();
               
                }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour publier un chapitre");
                }
           
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableBook')AND (isset($_GET['ouv_id']))
                    AND (isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1)))
            {
                $controler= new BackendControler(); 
            $user = $controler->activerBook();
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableBook')AND (isset($_GET['ouv_id']))
                    AND (isset($_SESSION['superAdmin']))AND ($_SESSION['superAdmin']==1))
            {
                $controler= new BackendControler(); 
            $user = $controler-> desactiverBook();
           
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='delPost')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
                $post= new BackendControler();
                $delPost= $post->supprimePost();
           
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès");
                }  
           
            }
            ////////////////// DELSUITE
            elseif(isset($_GET['action'])AND ($_GET['action']=='delSuite')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights']))AND (isset($_GET['precedent']))AND (isset($_GET['auteur'])))
            {
                $droits= unserialize($_SESSION['Rights']);
                if(($droits[$_GET['ouv_id']])|| ($_GET['auteur']==$_SESSION['userId'])){
                    $suite= new BackendControler();
                   $suppr= $suite->  supprimeSuite();
       
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès");
                }  
           
            }
            //////////////////////////////////////////////////////////////////
            elseif(isset($_GET['action'])AND ($_GET['action']=='delOuvrage')AND (isset($_GET['ouv_id'])))
            {
                if(isset($_SESSION['superAdmin'])AND $_SESSION['superAdmin']== 1){
                    $controler= new BackendControler(); 
            $user = $controler->       supprimeOuvrage();
         
                }else{
                    $controler= new BackendControler(); 
            $user = $controler-> supprimeOuvrageUser();  
                 
                }
            }
            elseif((isset($_GET['action']))AND ($_GET['action']=='post')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
              $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]){
                    $post= new BackendControler();
                    $lePost= $post->post();
                   
                }else{
                    throw new Exception("Vous n'avez pas les droits d'accès");
                }  
             }
            elseif((isset($_GET['action']))AND ($_GET['action']=='message')AND (isset($_SESSION['userId'])))
            {
         $mess= new BackendControler();
                  $message= $mess-> messagerie(); 
                     
               
             }elseif((isset($_GET['action']))AND ($_GET['action']=='newMessage')AND (isset($_SESSION['userId'])))
            {
         $controler= new BackendControler(); 
            $user = $controler-> formNewMessage();   
                  
               
             }elseif((isset($_POST['action']))AND ($_POST['action']=='addMessage')AND (isset($_SESSION['userId'])))
            {
                  $mess= new BackendControler();
                  $message= $mess-> envoiMessage($_POST['destinataire'], $_POST['expediteur'], $_POST['objet'], $_POST['contenu']);   
                  
               
             }
            elseif((isset($_POST['action']))AND ($_POST['action']=='ordreMessagerieRecus')AND (isset($_SESSION['userId'])))
            {
                   $mess= new BackendControler();
                   $message= $mess->  ordreMessagerieRecus();  
                   
               
                  
     

            }elseif((isset($_POST['action']))AND ($_POST['action']=='ordreMessagerieEnvoyes')AND (isset($_SESSION['userId'])))
            {
                    $mess= new BackendControler();
                    $message= $mess->  ordreMessagerieEnvoyes();   
               
                  
     

            }elseif((isset($_GET['action']))AND ($_GET['action']=='votation')AND (isset($_GET['precedent']))AND (isset($_GET['bulletin']))AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
              $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]){
                    $controler= new BackendControler(); 
            $vote = $controler->vote();   
          
                }else{
                    throw new Exception("Pb Vote . Alertez vore administrateur");
                }   
        
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableComment')AND (isset($_GET['commId']))AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                 $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
                    $post= new BackendControler();
                  $publicationPost= $post->desactiveComment();
              
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour modifier un commentaire");
                }  
       
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableComment')AND (isset($_GET['commId']))AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                 $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
                     $post= new BackendControler();
                  $publicationPost= $post->activeComment();
              
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour modifier un chapitre");
                }  
      
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableSignal')AND (isset($_GET['id'])))
            {
                $signal= new BackendControler();
        $activ= $signal->activeSignal();
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableSignal')AND (isset($_GET['id'])))
            {
                $signal= new BackendControler();
        $activ= $signal->desactiveSignal();
            
          
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='updatePost')AND (isset($_GET['id'])))
            {
            $post= new BackendControler();
            $form = $post->formModifyPost();
            }elseif(isset($_GET['action'])AND ($_GET['action']=='updateSuite')AND (isset($_GET['id'])))
            {
                $post= new BackendControler();
            $formMS = $post-> formModifySuite();
            
           
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='updateBook')AND (isset($_GET['ouv_id'])))
                {
                             if(isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1))
            {
                                 $controler= new BackendControler(); 
            $user = $controler->formModifyBook();
            
            }else{
                $controler= new BackendControler(); 
            $user = $controler->   formModifyBookUser(); 
          
            }
                }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majPost')AND (isset($_POST['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
             $droits= unserialize($_SESSION['Rights']);
                if(($droits[$_POST['ouv_id']]=='ADMINISTRATEUR')||(($droits[$_POST['ouv_id']]=='REDACTEUR'))){
                       $post= new BackendControler();
            $maj = $post->majPost($_POST['ouv_id']);
           
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour modifier un chapitre");
                }   
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majSuite')AND (isset($_POST['ouv_id']))AND (isset($_SESSION['Rights']))AND(isset($_POST['auteur'])))
            {
                
            $droits= unserialize($_SESSION['Rights']);
                if((($droits[$_POST['ouv_id']])AND ($_POST['auteur']==$_SESSION['userId']))||($droits[$_POST['ouv_id']]=='ADMINISTRATEUR')){
                    $post= new BackendControler();
                    $suite = $post-> majSuite();
                
            
            }else{
                   throw new Exception("Vous n'avez pas les droits d'accès pour modifier ce texte !!");
                }   
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majBook')AND (isset($_POST['ouv_id']) ))
            {
             $book= new BackendControler();
                    $maj = $book-> majBook();
        
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='newPost')AND (isset($_GET['ouv_id'])))
            {
                $post= new BackendControler();
            $form = $post->formNewPost($_GET['ouv_id']);
           
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='newBook'))
            {
                $book= new BackendControler();
                    $new = $book-> formNewBook();
        
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='book')AND (isset($_GET['ouv_id'])))
            {
               if(isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1))
            {
                     $book= new BackendControler();
                    $ouvrage = $book-> book();
        
            }else {
                 $book= new BackendControler();
                    $ouvrage = $book-> bookUser();
            
            }
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='changePsswd'))
            {
             $post= new BackendControler();
            $changePwd = $post->changePsswd();   
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='addPost')AND (isset($_POST['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                  $droits= unserialize($_SESSION['Rights']);
                if(($droits[$_POST['ouv_id']]=='ADMINISTRATEUR')||($droits[$_POST['ouv_id']]=='REDACTEUR')){
            $post= new BackendControler();
            $ajout = $post->ajouterPost($_POST['ouv_id']);
                    
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour ajouter un chapitre");
                }
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='addBook')AND (isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1)))
            {
                $controler= new BackendControler(); 
            $user = $controler->ajouterOuvrage();
            
            }
              elseif (isset($_GET['action'])AND ( $_GET['action'] == 'chgtStatutSuite')AND ( isset($_GET['libelle']))AND ( isset($_GET['ouv_id']))AND ( isset($_SESSION['Rights'])))
                {
            $droits = unserialize($_SESSION['Rights']);
            if (($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR')) {
                 $suite= new BackendControler();
                 $statut= $suite->changementStatutSuite($_GET['libelle']);
                
            } elseif (($droits[$_GET['ouv_id']]  AND ( ($_GET['libelle'] == 'PROPOSE') || ($_GET['libelle'] == 'REDACTION')))) 
                {
                $suite= new BackendControler();
                $statut= $suite->changementStatutSuite($_GET['libelle']);
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès pour effectuer ces changements");
            }
            } 
            elseif (isset($_GET['action'])AND ( $_GET['action'] == 'chgtStatut')AND ( isset($_GET['libelle']))AND ( isset($_GET['ouv_id']))AND ( isset($_SESSION['Rights'])))
                {
            $droits = unserialize($_SESSION['Rights']);
            if (($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR')) {
                $post= new BackendControler();
                $statut= $post->changementStatut($_GET['libelle']);
                
                
                
            } elseif (($droits[$_GET['ouv_id']] == 'REDACTEUR' AND ( ($_GET['libelle'] == 'PROPOSE') || ($_GET['libelle'] == 'REDACTION')))||($_GET['auteur']==$_SESSION['userId'])) 
                {
               $post= new BackendControler();
                $statut= $post->changementStatut($_GET['libelle']);
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès pour effectuer ces changements");
            }
            } elseif((isset($_GET['action']))AND ( isset($_POST['precedent']))AND ( isset($_POST['ouv_id']))AND ( $_GET['action'] == 'addSuite')AND ( isset($_SESSION['Rights']))){
             $droits = unserialize($_SESSION['Rights']);
               if ($droits[$_POST['ouv_id']]) {
                   $post= new BackendControler();
                    $suite = $post->  ajouterSuite();
                
               
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès pour continuer ");
            }  
            } elseif((isset($_GET['action']))AND ( isset($_GET['id']))AND ( isset($_GET['ouv_id']))AND ( $_GET['action'] == 'newSuite')AND ( isset($_SESSION['Rights']))){
             $droits = unserialize($_SESSION['Rights']);
               if ($droits[$_GET['ouv_id']]) {
                    $post= new BackendControler();
            $formNS = $post-> formNewSuite($_GET['id'],$_GET['ouv_id']);
                
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès continuer ");
            }     
        }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'dashboard')AND ( ($_SESSION['superAdmin']==1))) 
            {
            $controler= new BackendControler(); 
            $taboard = $controler-> cokpit();
           
         }
         elseif ((isset($_GET['action']))AND ( isset($_GET['vote_id']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'fermetureVote')AND ( ($_SESSION['superAdmin']==1))) 
            {
             $controler= new BackendControler(); 
            $vote = $controler-> closeVote($_GET['vote_id']);
           
         }
         elseif ((isset($_GET['action']))AND ( isset($_GET['vote_id']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'ouvertureVote')AND ( ($_SESSION['superAdmin']==1))) 
            {
                  $controler= new BackendControler(); 
            $vote = $controler-> openVote($_GET['vote_id']);
           
         }
         elseif ((isset($_GET['action']))AND ( isset($_GET['duree']))AND ( isset($_GET['dateFin']))AND ( isset($_GET['vote_id']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'prolongeVote')AND ( ($_SESSION['superAdmin']==1))) 
            {
             $controler= new BackendControler(); 
            $vote = $controler->modifDureeVote($_GET['vote_id'],$_GET['dateFin'],$_GET['duree']);
            
         }
         elseif ((isset($_GET['action']))AND ( isset($_GET['art_id']))AND ( isset($_GET['vote_id']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'delVote')AND ( ($_SESSION['superAdmin']==1))) 
            {
                  $controler= new BackendControler(); 
            $vote = $controler-> supprimeVote($_GET['vote_id'],$_GET['art_id']);
           
            
         }elseif ((isset($_GET['action']))AND ( isset($_GET['suiteId']))AND ( isset($_GET['ouv_id']))AND ( isset($_GET['auteur']))AND ( isset($_SESSION['Rights']))AND ( $_GET['action'] == 'concatSuite')AND ( isset($_GET['ouv_id']))AND ( isset($_GET['vote_id']))) 
            {
             $droits = unserialize($_SESSION['Rights']);
            if (($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR')) {
                   $controler= new BackendControler(); 
            $suite = $controler->   integrationSuite($_GET['suiteId'],$_GET['auteur'],$_GET['vote_id']);
           
            }else{
                throw new Exception("Vous n'avez pas les droits d'accès continuer ");
            }
         }
         
         
         elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id'])) AND( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'rightsBook')AND ( ($_SESSION['superAdmin']==1))) 
            {
             $controler= new BackendControler(); 
            $book = $controler->accesBook($_GET['ouv_id']);
            
            }elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id'])) AND( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'delAcces')AND ( ($_SESSION['superAdmin']==1))) 
            {
             $ouvrage= new BackendControler();
            $suppr = $ouvrage-> supprimeAccesOuvrage($_GET['user_id'],$_GET['ouv_id'],$_GET['statut_id']);  
                
            
            
            
            }elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id'])) AND( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'newBookAcces')AND ( ($_SESSION['superAdmin']==1))) 
            {
                $ouvrage= new BackendControler();
            $acces = $ouvrage->   formNewBookAcces($_GET['ouv_id']);
          
            
            }elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id'])) AND( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'addAccesBook')AND ( ($_SESSION['superAdmin']==1))) 
            {
                $book= new BackendControler();
            $acces = $book-> addAccesOuvrage($_GET['ouv_id'],$_POST['user'],$_POST['statut']);
        
            
            }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'delUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                 $user= new BackendControler();
            $del = $user->supprimeUser($_GET['id']);
                      
                     
             }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'initUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                  $user= new BackendControler();
            $init = $user-> initUser($_GET['id']);
                 
                    
               }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'majUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                    $user= new BackendControler();
            $maj = $user->majUser($_POST['user_id'], $_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'], $_POST['superviseur']);
                  
            
                  }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'newUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                   $user= new BackendControler();
            $form = $user->  formNewUser();
          
                 
                  }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'addUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                   $controler= new BackendControler();
            $user = $controler->ajouterUser($_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'],$_POST['passwd'], $_POST['superviseur']);
                      
         
               }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'updateUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                   $controler= new BackendControler(); 
            $user = $controler->userGet();
               
        
              
              
              
            }  elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id']))AND ( $_GET['action'] == 'listPosts')AND ( isset($_SESSION['Rights']))) 
            {
            $droits = unserialize($_SESSION['Rights']);
            if ($droits[$_GET['ouv_id']]) {
                $liste= new BackendControler();
                $listeDesPosts= $liste->listPosts($_GET['ouv_id']);
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès pour afficher les chapitres de cet ouvrage");
            }
        } else {
                    if($_SESSION['superAdmin']==1){
                        $ouvrage= new BackendControler();
                  $list= $ouvrage-> listOuvrages();
                      
                    
                    }else {
                $ouvrage= new BackendControler();
                  $list= $ouvrage->  listOuvragesUser($_SESSION['userId']);
                              
                        
               
                    }
                    
                    }
    }
    elseif(isset($_POST['usrname'])AND isset($_POST['passwd']))
    {
       $user= new BackendControler();
        $verif= $user->verifUser(); 
      
       if($verif=== TRUE){
      header('Location: indexadmin.php');exit();
       }else {  
          header('Location: indexadmin.php?action=reconnexion');exit(); 
    
       }
    }
    elseif (isset($_GET['action'])AND($_GET['action'] == 'connexion') )
        {         
        $user= new BackendControler();
        $ident= $user->identification(TRUE);
            
        }
    elseif (isset($_GET['action'])AND($_GET['action'] == 'reconnexion') )
        {         
           
        $user= new BackendControler();
        $ident= $user->identification(FALSE);
            
        }
  
        
    else { 
      
        $user= new BackendControler();
        $ident= $user->identification(TRUE);
    }
 
}
catch(Exception $e) {
$message = $e->getMessage();
//
switch($e->getCode()){
        case 23000:
            $message="Problème d'intégrité- Vous essayez de supprimer une donnée en lien avec d'autres enregistrement ou bien vous essayez de duppliquer une donnée déjà existante . exemple pseudo déjà présent. ";
            break;
        case 42000:
            $message="Erreur d'exécution système. ";
            break;       
    }

//if($e->getCode()===23000){
//    $message='Entrée dupliquée';
//}
$error= new BackendControler();
$affError= $error->erreur($message);
   
}
