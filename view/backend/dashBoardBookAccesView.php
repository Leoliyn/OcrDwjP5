<?php $title = 'Jean FORTEROCHE'; ?>


<?php ob_start(); ?>

<div class='resume'>
   
    <a href="indexadmin.php?action=newBookAcces&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Ajouter un accès"><i class="fa fa-plus-square  fa-4x "></i>   Ajouter un accès..</a>


    
   </div>

<div class='resume'>
    <h3> Droits d'accès aux ouvrages </h3>
 <?php
    echo '<table class="table">';
echo'<tr><td>OUVRAGE ID</td><td>TITRE</td><td>SOUS TITRE</td><td>PSEUDO</td><td>E-MAIL</td><td>STATUT</td><td>SUPPR</td></tr>';

while ($donnees = $rightsBooks->fetch()) {
    echo'<tr><td>'.$donnees['OUV_ID'].'</td>'
           . '<td>'.$donnees['OUV_TITRE'].'</td>'
           . '<td>'.$donnees['OUV_SOUSTITRE'].'</td>'
 . '<td>'.$donnees['USER_PSEUDO'].'</td>'
             . '<td>'.$donnees['USER_MAIL'].'</td>'
            . '<td>'.$donnees['STATUT'].'</td>'
            . '<td><a href="indexadmin.php?action=delAcces&amp;user_id=' . $donnees['USER_ID'] . '&amp;ouv_id=' . $donnees['OUV_ID'].'&amp;statut_id=' . $donnees['p5_statut_liste_p5_STATUT_ID'].'"><i class="fa fa-trash  fa-2x"></i></td>'
    
           
            . '</tr>';
           


}
echo'</table>';
 ?> 
    <a href="indexadmin.php?action=dashboard" title="Retour à la liste"><i class="fa fa-arrow-left  fa-2x "></i></a>
</div>
 




<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
