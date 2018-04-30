<?php $title = 'Jean FORTEROCHE'; ?>

<?php ob_start(); ?>


<?php
$data = $article;
?>
<div class=''>
    <?php
    $file="./uploads/".htmlspecialchars($data['ART_IMAGE']);
    if(is_file($file))
  {
    ?>
    <img src="<?= $file ?>" class="miniature" />
    <br />
    <?php
  }else{
  }
  
    ?>
    <h3>
    <?= htmlspecialchars($data['ART_TITLE']) ?>

    </h3>

    <p><em>le <?= $data['DATE_fr'] ?></em></p>
    <p><?= ($data['ART_CONTENT']) ?></p>
    <div class='icone-admin'>


<?php
$desactive = htmlspecialchars($data['ART_DESACTIVE']);
if ($desactive) {
    echo '<a href="indexadmin.php?action=enablePost&amp;id=' . htmlspecialchars($data['ART_ID']) . '" title="Cliquez pour publiez l\'article"><i class="fa fa-eye-slash  fa-2x "></i></a>';
} else {
    echo '<a href="indexadmin.php?action=disablePost&amp;id=' . htmlspecialchars($data['ART_ID']) . '" title="Mettre l\'article en cours de rédaction"><i class="fa fa-eye  fa-2x "></i></a>';
}
?>


        <a href="indexadmin.php?action=updatePost&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>" title="Modifiez l'article"><i class="fa  fa-edit  fa-2x "></i></a>
        <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($data['ART_ID']) ?>" title="Supprimez l'article"><i class="fa fa-remove  fa-2x"></i></a>
        <a href="indexadmin.php?" title="Retour à la liste"><i class="fa fa-arrow-left  fa-2x "></i></a>
    </div> 
</div>
<!-- Modal -->
<div class="modal fade" id="deleteModal<?= htmlspecialchars($data['ART_ID']) ?>" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">

                <h4><span class="fa fa-trash"></span> <?= htmlspecialchars($data['ART_ID']) ?> Etes-vous sur de vouloir supprimer l'article ?</h4>
            </div>
            <div class="modal-body">
                <form role="form" action="indexadmin.php" method="get">
                    <input type="hidden" class="form-control" id="action" name="action"value="delPost">
                    <input type="hidden" class="form-control" id="id" name="id"value="<?= htmlspecialchars($data['ART_ID']) ?>">   
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
<h2>Commentaire(s)</h2>
<?php
while ($comment = $comments->fetch()) {
    ?>
    <div class=''>
        <p><strong><?= htmlspecialchars($comment['COMM_PSEUDO']) ?></strong> le <?= htmlspecialchars($comment['COMM_date_fr']) ?></p>
        <div >  
            <p><?= nl2br(htmlspecialchars($comment['COMM_TITRE'])) ?></p>
            <p><?= nl2br(htmlspecialchars($comment['COMM_CONTENU'])) ?></p>

        </div>
    <?php
    $commentdesactive = htmlspecialchars($comment['DISABLE']);
    if ($commentdesactive) {
        echo '<a href="indexadmin.php?action=enableComment&amp;commId=' . htmlspecialchars($comment['COMM_ID']) . '&amp;id=' . htmlspecialchars($data['ART_ID']) . '" title="Cliquez pour publiez le commentaire"><i class="fa fa-eye-slash  fa-2x "></i></a> - ';
    } else {
        echo '<a href="indexadmin.php?action=disableComment&amp;commId=' . htmlspecialchars($comment['COMM_ID']) . '&amp;id=' . htmlspecialchars($data['ART_ID']) . '" title="Cliquez pour désactiver le commentaire"><i class="fa fa-eye  fa-2x "></i></a> - ';
    }
    $commentSignale = htmlspecialchars($comment['SIGNALE']);
    if ($commentSignale) {
        echo '<a href="indexadmin.php?action=disableSignal&amp;commId=' . htmlspecialchars($comment['COMM_ID']) . '&amp;id=' . htmlspecialchars($data['ART_ID']) . '" title="Cliquez pour enlever l\'alerte"><i class="fa fa-thumbs-down  fa-2x red"> </i></a>';
    } else {
        echo '<a href="indexadmin.php?action=enableSignal&amp;commId=' . htmlspecialchars($comment['COMM_ID']) . '&amp;id=' . htmlspecialchars($data['ART_ID']) . '" title="Cliquez pour signaler le commentaire"><i class="fa fa-thumbs-o-up  fa-2x vert"></i></a>';
    }
    ?>
    </div>

        <?php
    }
    ?>
    <?php $content = ob_get_clean(); ?>

    <?php require('view/backend/template.php'); ?>
