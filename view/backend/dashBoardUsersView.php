<?php
/**
 * container de gestion users dans dashboard
 */
?>
<div class='resume'>
    <h3>   Utilisateurs   </h3>

    <table class="table">
        <tr><td>UTILISATEUR ID</td><td>PSEUDO</td><td>NOM</td><td>PRENOM</td><td>E-MAIL</td><td>ROOT</td><td>SUPPR</td><td>UPDATE</td><td>REINIT</td></tr>

        <?php while ($donnees = $listUsers->fetch()) { ?>
            <tr class =""><td><span class="badge"><?= $donnees['USER_ID'] ?></span></td>
                <td><?= $donnees['USER_PSEUDO'] ?></td>
                <td><?= $donnees['USER_NAME'] ?></td>
                <td><?= $donnees['USER_LASTNAME'] ?></td>
                <td><?= $donnees['USER_MAIL'] ?></td>
                <td><?= $donnees['ROOT'] ?></td>
                <td><a  data-toggle="modal" data-target="#delUserModal<?= $donnees['USER_ID'] ?>" href="#"><i class="fa fa-trash  fa-2x"></i></td>
                <td><a href="indexadmin.php?action=updateUser&amp;id='<?= $donnees['USER_ID'] ?>"><i class="fa  fa-edit  fa-2x "></i></a></td>
                <td><a  data-toggle="modal" data-target="#initUserModal<?= donnees['USER_ID'] ?>" href="#"><i class="fa fa-recycle  fa-2x"></i></a></td></tr>

            <?php require 'view/backend/modalDelUserView.php' ?>
            <?php require 'view/backend/modalInitUserView.php' ?>
        <?php }
        ?>
    </table></div>






