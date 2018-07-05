<?php
/**
 * Container dashboard global
 */
?>

<?php ob_start(); ?>

<div class='resume'>

    <a href="indexadmin.php?action=newUser" title="Ajouter un article"><i class="fa fa-plus-square  fa-4x "></i>   Ajouter un utilisateur..</a>
</div>
<?php require ('view/backend/dashBoardUsersView.php'); ?>
<?php require ('view/backend/dashBoardBooksView.php'); ?>

<?php require ('view/backend/dashBoardVotesView.php'); ?>
<?php require ('view/backend/dashBoardVisitesView.php'); ?>

<?php $content = ob_get_clean(); ?>
<?php require('view/backend/template.php'); ?>
