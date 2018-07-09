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
<div id='toggleRecus'><i class="fa fa-toggle-on  fa-2x "></i>Messages reçus</div>
<?php require ('view/backend/messagesRecusView.php'); ?>
<div id='toggleEnvoyes'><i class="fa fa-toggle-on  fa-2x "></i>Messages envoyés</div>
<?php require ('view/backend/messagesEnvoyesView.php'); ?>
<?php $content = ob_get_clean(); ?>
<?php require('view/backend/template.php'); ?>
<script>
         $('#toggleRecus').click(function(){
            $('#recus').toggle("fast");
        });
        $('#toggleEnvoyes').click(function(){
            $('#envoyes').toggle("fast");
        });
      
       </script> 