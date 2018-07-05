<?php
/**
 * Page liste des ouvrages
 * 
 */
?>

<?php ob_start(); ?>
<?php if ($_SESSION['superAdmin'] == 1): ?>
<div class='resume'>

    <a href="indexadmin.php?action=newBook" title="Ajouter un ouvrage"><i class="fa fa-plus-square  fa-4x "></i>   Ajouter un ouvrage..</a>
</div>

<?php endif; ?> 

<!--tableau construction des droits utilisateurs-->
<?php
$droits = array();
while ($data = $books->fetch()):
$niveau = 0;
if($_SESSION['superAdmin'] == 1):
$niveau = 1;
$droits[$data['OUV_ID']] = 'ADMINISTRATEUR';
else:
$droits[$data['OUV_ID']] = $data['STATUT'];
endif;
if(($data['OUV_ENABLE'] == 0)AND ($droits[$data['OUV_ID']] == 'LECTEUR')):
$niveau = 2;
endif;
?>

<?php if ($niveau == 2): else: ?>
<div class='resume'>     
    Vous êtes :
<?php if ($niveau == 1): ?>

    SuperAdmin
    <?php else: ?>
    <?= htmlspecialchars($data['STATUT']); ?>
<?php endif; ?>


    <h2><i class="fa fa-book fa-1x"></i>:  <?= htmlspecialchars($data['OUV_TITRE']) ?></h2> 

    <h3> <?= htmlspecialchars($data['OUV_SOUSTITRE']) ?> </h3>          



    <p><em>Description :  <?= htmlspecialchars($data['OUV_DESCRIPTION']) ?></em></p>
    <div class='contenu'>
        <?php $contenu = $data['OUV_PREFACE']; ?>
<?= $contenu ?>
        <br />
        <br />
    </div>
    <div class='icone-admin row'>

<?php $enable = htmlspecialchars($data['OUV_ENABLE']); ?>
        <div class ="col-sm-1">
            <a href="indexadmin.php?action=listPosts&amp;ouv_id=<?= $data['OUV_ID'] ?>" title="Accès aux chapitres"><i class="fa fa-file-o   fa-2x"></i></a>
        </div>
<?php if($niveau === 1): ?>

        <div class ="col-sm-1">
            <a href="indexadmin.php?action=updateBook&amp;ouv_id=<?= $data['OUV_ID'] ?>" title="Modifiez l'ouvrage"><i class="fa  fa-edit  fa-2x "></i></a>
        </div>
        <div class ="col-sm-1">
            <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($data['OUV_ID']) ?>" title="Supprimez l'ouvrage"><i class="fa fa-remove  fa-2x"></i></a>

        </div>
<?php if (!$enable) : ?>
        <div class ="col-sm-1">
            <a href="indexadmin.php?action=enableBook&amp;ouv_id=<?= htmlspecialchars($data['OUV_ID']) ?>" title="Cliquez pour activer  l'ouvrage"><i class="fa fa-eye-slash  fa-2x "></i></a>
        </div>
<?php else: ?>
        <div class ="col-sm-1">
            <a href="indexadmin.php?action=disableBook&amp;ouv_id=<?= htmlspecialchars($data['OUV_ID']) ?>" title="Cliquez pour désactiver  l'ouvrage"><i class="fa fa-eye  fa-2x "></i></a>
        </div>
        <?php endif; ?>

        <?php else: ?>

<?php if (!$enable) : ?>
        <div class ="col-sm-1">
            <i class="fa fa-eye-slash  fa-2x "></i>
        </div>
<?php else: ?>
        <div class ="col-sm-1">
            <i class="fa fa-eye  fa-2x "></i>
            </div>
        <?php endif; ?>

<?php endif; ?>

    </div>

</div> 
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="deleteModal<?= htmlspecialchars($data['OUV_ID']) ?>" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">

                <h4><span class="fa fa-trash"></span> Etes-vous sur de vouloir supprimer l'ouvrage ?<br/>(Vérifiez qu'un ouvrage est actif)</h4>
            </div>
            <div class="modal-body">
                <form role="form" action="indexadmin.php" method="get">
                    <input type="hidden" class="form-control" id="action" name="action"value="delOuvrage">
                    <input type="hidden" class="form-control" id="id" name="ouv_id"value="<?= htmlspecialchars($data['OUV_ID']) ?>">   
                    <button type="submit" class="btn btn-block">Supprimer
                        <span class="glyphicon glyphicon-ok"></span>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove"></span> Annulation
                </button>

            </div>
        </div>
    </div>
</div>

<?php endwhile; ?>
<!--  Mise en variable de session du tableau des droits de l'utilisateur -->
<?php $_SESSION['Rights'] = serialize($droits); ?>
<?php $books->closeCursor(); ?>
<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>




