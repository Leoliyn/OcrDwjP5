<?php
/**
 * Container global messagerie 
 * 
 */
?>
<?php ob_start(); ?>
<div class='resume'>
    <a href="indexadmin.php?action=newMessage" title="Ecrire un message"><i class="fa fa-plus-square  fa-4x "></i>   Ecrire un message..</a>
</div>
<?php require ('view/backend/messagesRecusView.php'); ?>
<?php require ('view/backend/messagesEnvoyesView.php'); ?>
<?php $content = ob_get_clean(); ?>
<?php require('view/backend/template.php'); ?>
