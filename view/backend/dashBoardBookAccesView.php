<?php $title = 'Jean FORTEROCHE'; ?>


<?php ob_start(); ?>



<div class='resume'>
    <h3> Droits d'accès aux ouvrages </h3>
 <?php
    echo '<table class="table">';
echo'<tr><td>UTILISATEUR ID</td><td>PSEUDO</td><td>NOM</td><td>PRENOM</td><td>E-MAIL</td><td>ROOT</td><td>SUPPR</td><td>UPDATE</td><td>REINIT</td></tr>';

while ($donnees = $rightsBooks->fetch()) {
}
echo'</table></div>';

 ?>  




<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
