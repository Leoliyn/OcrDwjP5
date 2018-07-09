<?php
/**
 * Container dashboard global
 */
?>

<?php ob_start(); ?>

<div class='resume'>

    <a href="indexadmin.php?action=newUser" title="Ajouter un article"><i class="fa fa-plus-square  fa-4x "></i>   Ajouter un utilisateur..</a>
</div>
<div id='toggleUsers'><i class="fa fa-toggle-on  fa-2x "></i>Gestion utilisateurs</div>
<?php require ('view/backend/dashBoardUsersView.php'); ?>
<div id='toggleBooks'><i class="fa fa-toggle-on  fa-2x "></i>Gestion Ouvrages</div>
    <?php require ('view/backend/dashBoardBooksView.php'); ?>
<div id='toggleVotes'><i class="fa fa-toggle-on  fa-2x "></i>Gestion Votes</div>
<?php require ('view/backend/dashBoardVotesView.php'); ?>
<div id='toggleVisites'><i class="fa fa-toggle-on  fa-2x "></i>Gestion Visites</div>
    <?php require ('view/backend/dashBoardVisitesView.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require('view/backend/template.php'); ?>
<script>
         $('#toggleUsers').click(function(){
            $('#users').toggle("fast");
        });
        $('#toggleBooks').click(function(){
            $('#books').toggle("fast");
        });
        $('#toggleVotes').click(function(){
            $('#votes').toggle("fast");
        });
        $('#toggleVisites').click(function(){
            $('#visites').toggle("fast");
        });
        
        
   
       </script> 
</script>
