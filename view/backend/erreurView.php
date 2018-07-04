<?php
/**
 * Page erreur Affiche $message remonte de Exception 
 */
?>
<?php ob_start(); ?>
<div class='resume'>
    <h6> <?= $_POST['message'] ?></h6> 
</div>  

<p> <a href="<?= $_SERVER['HTTP_REFERER'] ?>"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a></p>

<?php $content = ob_get_clean(); ?>
<?php require('view/backend/template.php'); ?>