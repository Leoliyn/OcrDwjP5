<?php ob_start(); ?>

<div class='resume'>
    <a href="indexadmin.php?action=newBookAcces&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Ajouter un accès"><i class="fa fa-plus-square  fa-4x "></i>   Ajouter un accès..</a>
</div>

<div class='resume'>
    <h3> Droits d'accès aux ouvrages </h3>

    <table class="table">
        <tr><td>OUVRAGE ID</td><td>TITRE</td><td>SOUS TITRE</td><td>PSEUDO</td><td>E-MAIL</td><td>STATUT</td><td>SUPPR</td></tr>

        <?php while ($donnees = $rightsBooks->fetch()) { ?>
            <tr>
                <td><?= $donnees['OUV_ID'] ?></td>
                <td><?= $donnees['OUV_TITRE'] ?></td>
                <td><?= $donnees['OUV_SOUSTITRE'] ?></td>
                <td><?= $donnees['USER_PSEUDO'] ?></td>
                <td><?= $donnees['USER_MAIL'] ?></td>
                <td><?= $donnees['STATUT'] ?></td>
                <td><a  data-toggle="modal" data-target="#delAccesBookModal<?= $donnees['USER_ID'] ?>" href="#"><i class="fa fa-trash  fa-2x"></i></td>
            </tr>

            <?php require 'view/backend/modalDelAccesBookView.php'; ?>
        <?php } ?>
    </table>
    <a href="indexadmin.php?action=dashboard" title="Retour à la liste"><i class="fa fa-arrow-left  fa-2x "></i></a>

</div>

<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
