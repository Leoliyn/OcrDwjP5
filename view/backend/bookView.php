<?php
/**
 * Affiche les données d un ouvrage 
 */
?>
<?php ob_start(); ?>
<?php
$statut = $_SESSION['superAdmin'];
$data = $book;
$niveau = 0;
$droits = unserialize($_SESSION['Rights']);
if ((isset($droits[$data['OUV_ID']]))AND ( $data['OUV_ENABLE'] == 0)AND ( $droits[$data['OUV_ID']] == 'LECTEUR')) {
    $niveau = 2;
}
if ($niveau == 2) {
    
} else {
    ?>
    <div class='resume'>
        <h2>
            <i class="fa fa-book fa-1x"></i>:  <?= htmlspecialchars($data['OUV_TITRE']) ?></h2>
        <h3> <?= htmlspecialchars($data['OUV_SOUSTITRE']) ?> </h3>          
        <p><em>Description :  <?= htmlspecialchars($data['OUV_DESCRIPTION']) ?></em></p>
        <div class='contenu'>
            <?php $contenu = $data['OUV_PREFACE']; ?>     
            <?= $contenu ?>
            <br />
            <br /> 
        </div> 
        <div class='icone-admin row'>
            <?php
            $desactive = htmlspecialchars($data['OUV_ENABLE']);
            if ($statut == 1) {
                if (!$desactive) {
                    ?>
                    <div class ="col-sm-1">
                        <a href="indexadmin.php?action=enableBook&amp;ouv_id=<?= htmlspecialchars($data['OUV_ID']) ?>" title="Cliquez pour activer  l'ouvrage"><i class="fa fa-eye-slash  fa-2x "></i></a>
                    </div>
                <?php } else { ?>
                    <div class ="col-sm-1">
                        <a href="indexadmin.php?action=disableBook&amp;ouv_id=<?= htmlspecialchars($data['OUV_ID']) ?>" title="Cliquez pour désactiver  l'ouvrage"><i class="fa fa-eye  fa-2x "></i></a>
                    </div>

                <?php } ?>

                <div class ="col-sm-1">
                    <a href="indexadmin.php?action=updateBook&amp;ouv_id=<?= htmlspecialchars($data['OUV_ID']) ?>" title="Modifiez l'ouvrage"><i class="fa  fa-edit  fa-2x "></i></a>
                </div> <div class ="col-sm-1">
                    <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($data['OUV_ID']) ?>" title="Supprimez l'ouvrage"><i class="fa fa-remove  fa-2x"></i></a>
                </div> <div class ="col-sm-1">
                    <a href="indexadmin.php?action=listBooks" title="Retour à la liste des ouvrages"><i class="fa fa-arrow-left  fa-2x "></i></a>
                </div>
                <?php
            } else {
                if (!$desactive) {
                    ?>
                    <div class ="col-sm-1">
                        <i class="fa fa-eye-slash  fa-2x "></i>
                    </div>
                <?php } else { ?>
                    <div class ="col-sm-1">
                        <i class="fa fa-eye  fa-2x "></i>
                    </div>

                <?php } ?>
                <div class ="col-sm-1">
                    <a href="indexadmin.php?action=listBooks" title="Retour à la liste des ouvrages"><i class="fa fa-arrow-left  fa-2x "></i></a> 
                </div>

            <?php } ?> 
        </div>
        <!-- Modal -->
        <div class="modal fade" id="deleteModal<?= htmlspecialchars($data['OUV_ID']) ?>" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">

                        <h4><span class="fa fa-trash"></span> <?= htmlspecialchars($data['OUV_ID']) ?> Etes-vous sur de vouloir supprimer l'ouvrage ?</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form" action="indexadmin.php" method="get">
                            <input type="hidden" class="form-control" id="action" name="action"value="delBook">
                            <input type="hidden" class="form-control" id="ouv_id" name="id"value="<?= htmlspecialchars($data['OUV_ID']) ?>">   
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
    </div>
<?php } ?>
<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
