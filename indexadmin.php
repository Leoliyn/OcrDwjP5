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
       
            if(isset($_GET['action'])AND ($_GET['action']=='enablePost')AND (isset($_GET['id'])))
            {
            publierPost();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disablePost')AND (isset($_GET['id'])))
            {
            desactiverPost();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableBook')AND (isset($_GET['id']))
                    AND (isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1)))
            {
            activerBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableBook')AND (isset($_GET['id']))
                    AND (isset($_SESSION['superAdmin']))AND ($_SESSION['superAdmin']==1))
            {
            desactiverBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='delPost')AND (isset($_GET['id'])))
            {
            supprimePost();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='delOuvrage')AND (isset($_GET['id'])))
            {
                if(isset($_SESSION['superAdmin'])AND $_SESSION['superAdmin']== 1){
                supprimeOuvrage();
                }else{
                supprimeOuvrageUser();    
                }
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='post')AND (isset($_GET['id'])))
            {
            post();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='disableComment')AND (isset($_GET['id'])))
            {
            desactiveComment();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='enableComment')AND (isset($_GET['id'])))
            {
            activeComment();
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
            elseif(isset($_GET['action'])AND ($_GET['action']=='updateBook')AND (isset($_GET['id'])))
                {
                             if(isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1))
            {
            formModifyBook();
            }else{
            formModifyBookUser();  
            }
                }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majPost'))
            {
            majPost();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='majBook'))
            {
            majBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='newPost'))
            {
            formNewPost();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='newBook'))
            {
            formNewBook();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='book')AND (isset($_GET['id'])))
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
            elseif(isset($_GET['action'])AND ($_GET['action']=='addPost'))
            {
            ajouterPost();
            }
            elseif(isset($_GET['action'])AND ($_GET['action']=='addBook')AND (isset($_SESSION['superAdmin'])AND ($_SESSION['superAdmin']==1)))
            {
            ajouterOuvrage();
            }
            elseif((isset($_GET['action']))AND (isset($_GET['id']))AND ($_GET['action']=='listPosts'))
            {
            listPosts();
            }
            else {
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
