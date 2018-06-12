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
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
                  publierPost();
                }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour publier un chapitre");
                }    
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disablePost')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
                  $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]=='ADMINISTRATEUR'){
               desactiverPost();
                }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour publier un chapitre");
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
                    throw new Exception("Vous n'avez pas les droits d'accès(delpost ligne 59 indexAdmin");
                }  
           
            }
            ////////////////// DELSUITE
            elseif(isset($_GET['action'])AND ($_GET['action']=='delSuite')AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights']))AND (isset($_GET['precedent']))AND (isset($_GET['auteur'])))
            {
                $droits= unserialize($_SESSION['Rights']);
                if(($droits[$_GET['ouv_id']])|| ($_GET['auteur']==$_SESSION['userId'])){
            supprimeSuite();
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès");
                }  
           
            }
            //////////////////////////////////////////////////////////////////
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
                    throw new Exception("Vous n'avez pas les droits d'accès");
                }  
             }
            elseif((isset($_GET['action']))AND ($_GET['action']=='message')AND (isset($_SESSION['userId'])))
            {
         
                   messagerie();   
               
             }elseif((isset($_GET['action']))AND ($_GET['action']=='newMessage')AND (isset($_SESSION['userId'])))
            {
         
                   formNewMessage();   
               
             }elseif((isset($_POST['action']))AND ($_POST['action']=='addMessage')AND (isset($_SESSION['userId'])))
            {
         
                   envoiMessage($_POST['destinataire'], $_POST['expediteur'], $_POST['objet'], $_POST['contenu']);   
               
             }
            elseif((isset($_POST['action']))AND ($_POST['action']=='ordreMessagerieRecus')AND (isset($_SESSION['userId'])))
            {
         
                   ordreMessagerieRecus();   
               
                  
     

            }elseif((isset($_POST['action']))AND ($_POST['action']=='ordreMessagerieEnvoyes')AND (isset($_SESSION['userId'])))
            {
         
                   ordreMessagerieEnvoyes();   
               
                  
     

            }elseif((isset($_GET['action']))AND ($_GET['action']=='votation')AND (isset($_GET['precedent']))AND (isset($_GET['bulletin']))AND (isset($_GET['id']))AND (isset($_GET['ouv_id']))AND (isset($_SESSION['Rights'])))
            {
              $droits= unserialize($_SESSION['Rights']);
                if($droits[$_GET['ouv_id']]){
             vote();   
                }else{
                    throw new Exception("Pb Vote . Alertez vore administrateur");
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
            }elseif(isset($_GET['action'])AND ($_GET['action']=='updateSuite')AND (isset($_GET['id'])))
            {
            formModifySuite();
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
                if(($droits[$_POST['ouv_id']]=='ADMINISTRATEUR')||(($droits[$_POST['ouv_id']]=='REDACTEUR'))){
            majPost();
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour modifier un chapitre");
                }   
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majSuite')AND (isset($_POST['ouv_id']))AND (isset($_SESSION['Rights']))AND(isset($_POST['auteur'])))
            {
                
            $droits= unserialize($_SESSION['Rights']);
                if((($droits[$_POST['ouv_id']])AND ($_POST['auteur']==$_SESSION['userId']))||($droits[$_POST['ouv_id']]=='ADMINISTRATEUR')){
            majSuite();
            }else{
                   throw new Exception("Vous n'avez pas les droits d'accès pour modifier ce texte !!");
                }   
            
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majBook'))
            {
            majBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='newPost')AND (isset($_GET['ouv_id'])))
            {
            formNewPost($_GET['ouv_id']);
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
                if(($droits[$_POST['ouv_id']]=='ADMINISTRATEUR')||($droits[$_POST['ouv_id']]=='REDACTEUR')){
            ajouterPost($_POST['ouv_id']);
            }else{
                    throw new Exception("Vous n'avez pas les droits d'accès pour ajouter un chapitre");
                }
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='addBook')AND (isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1)))
            {
            ajouterOuvrage();
            }
              elseif (isset($_GET['action'])AND ( $_GET['action'] == 'chgtStatutSuite')AND ( isset($_GET['libelle']))AND ( isset($_GET['ouv_id']))AND ( isset($_SESSION['Rights'])))
                {
            $droits = unserialize($_SESSION['Rights']);
            if (($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR')) {
                changementStatutSuite($_GET['libelle']);
            } elseif (($droits[$_GET['ouv_id']]  AND ( ($_GET['libelle'] == 'PROPOSE') || ($_GET['libelle'] == 'REDACTION')))) 
                {
                changementStatutSuite($_GET['libelle']);
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès pour effectuer ces changements");
            }
            } 
            elseif (isset($_GET['action'])AND ( $_GET['action'] == 'chgtStatut')AND ( isset($_GET['libelle']))AND ( isset($_GET['ouv_id']))AND ( isset($_SESSION['Rights'])))
                {
            $droits = unserialize($_SESSION['Rights']);
            if (($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR')) {
                changementStatut($_GET['libelle']);
                
                
            } elseif (($droits[$_GET['ouv_id']] == 'REDACTEUR' AND ( ($_GET['libelle'] == 'PROPOSE') || ($_GET['libelle'] == 'REDACTION')))||($_GET['auteur']==$_SESSION['userId'])) 
                {
                changementStatut($_GET['libelle']);
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès pour effectuer ces changements");
            }
            } elseif((isset($_GET['action']))AND ( isset($_POST['precedent']))AND ( isset($_POST['ouv_id']))AND ( $_GET['action'] == 'addSuite')AND ( isset($_SESSION['Rights']))){
             $droits = unserialize($_SESSION['Rights']);
               if ($droits[$_POST['ouv_id']]) {
                ajouterSuite();
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès pour continuer ");
            }  
            } elseif((isset($_GET['action']))AND ( isset($_GET['id']))AND ( isset($_GET['ouv_id']))AND ( $_GET['action'] == 'newSuite')AND ( isset($_SESSION['Rights']))){
             $droits = unserialize($_SESSION['Rights']);
               if ($droits[$_GET['ouv_id']]) {
                formNewSuite($_GET['id'],$_GET['ouv_id']);
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès continuer ");
            }     
        }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'dashboard')AND ( ($_SESSION['superAdmin']==1))) 
            {
            cokpit();
         }
         elseif ((isset($_GET['action']))AND ( isset($_GET['vote_id']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'fermetureVote')AND ( ($_SESSION['superAdmin']==1))) 
            {
            closeVote($_GET['vote_id']);
         }
         elseif ((isset($_GET['action']))AND ( isset($_GET['vote_id']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'ouvertureVote')AND ( ($_SESSION['superAdmin']==1))) 
            {
            openVote($_GET['vote_id']);
         }
         elseif ((isset($_GET['action']))AND ( isset($_GET['duree']))AND ( isset($_GET['dateFin']))AND ( isset($_GET['vote_id']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'prolongeVote')AND ( ($_SESSION['superAdmin']==1))) 
            {
            modifDureeVote($_GET['vote_id'],$_GET['dateFin'],$_GET['duree']);
         }
         elseif ((isset($_GET['action']))AND ( isset($_GET['art_id']))AND ( isset($_GET['vote_id']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'delVote')AND ( ($_SESSION['superAdmin']==1))) 
            {
            supprimeVote($_GET['vote_id'],$_GET['art_id']);
            
         }elseif ((isset($_GET['action']))AND ( isset($_GET['suiteId']))AND ( isset($_GET['ouv_id']))AND ( isset($_GET['auteur']))AND ( isset($_SESSION['Rights']))AND ( $_GET['action'] == 'concatSuite')AND ( isset($_GET['ouv_id']))AND ( isset($_GET['vote_id']))) 
            {
             $droits = unserialize($_SESSION['Rights']);
            if (($droits[$_GET['ouv_id']] == 'ADMINISTRATEUR')) {
                
            integrationSuite($_GET['suiteId'],$_GET['auteur'],$_GET['vote_id']);
            }else{
                throw new Exception("Vous n'avez pas les droits d'accès continuer ");
            }
         }
         
         
         elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id'])) AND( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'rightsBook')AND ( ($_SESSION['superAdmin']==1))) 
            {
            accesBook($_GET['ouv_id']);
            }elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id'])) AND( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'delAcces')AND ( ($_SESSION['superAdmin']==1))) 
            {
            supprimeAccesOuvrage($_GET['user_id'],$_GET['ouv_id'],$_GET['statut_id']);
            }elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id'])) AND( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'newBookAcces')AND ( ($_SESSION['superAdmin']==1))) 
            {
            formNewBookAcces($_GET['ouv_id']);
            
            }elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id'])) AND( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'addAccesBook')AND ( ($_SESSION['superAdmin']==1))) 
            {
         addAccesOuvrage($_GET['ouv_id'],$_POST['user'],$_POST['statut']);
            
            }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'delUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                     supprimeUser($_GET['id']);
                      
             }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'initUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                     initUser($_GET['id']);
               }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'majUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                  majUser($_POST['user_id'], $_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'], $_POST['superviseur']);
            
                  }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'newUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                  formNewUser();
         
                  }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'addUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                  ajouterUser($_POST['nom'], $_POST['prenom'], $_POST['pseudo'], $_POST['email'],$_POST['passwd'], $_POST['superviseur']);
         
               }elseif ((isset($_GET['action']))AND ( isset($_SESSION['superAdmin']))AND ( $_GET['action'] == 'updateUser')AND ( ($_SESSION['superAdmin']==1))) 
            {
                userGet();
                        
            }  elseif ((isset($_GET['action']))AND ( isset($_GET['ouv_id']))AND ( $_GET['action'] == 'listPosts')AND ( isset($_SESSION['Rights']))) 
            {
            $droits = unserialize($_SESSION['Rights']);
            if ($droits[$_GET['ouv_id']]) {
                listPosts($_GET['ouv_id']);
            } else {
                throw new Exception("Vous n'avez pas les droits d'accès pour afficher les chapitres de cet ouvrage");
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
