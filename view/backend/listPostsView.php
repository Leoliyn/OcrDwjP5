<?php $title = 'Jean FORTEROCHE'; ?>

<?php ob_start(); ?>
<div class='resume'>
    <a href="indexadmin.php?action=newPost" title="Ajouter un article"><i class="fa fa-plus-square  fa-4x "></i>   Ajouter un chapitre..</a>
</div>
<?php
while ($data = $posts->fetch()) {
    ?>

    <div class='resume'>
        <?php
    $file="./uploads/".htmlspecialchars($data['ART_IMAGE']);
    if(is_file($file))
  {
        ?>
        <img src='./uploads/<?= htmlspecialchars($data['ART_IMAGE']) ?>' class="miniature" />
        <?php
  }
        ?>
        <h3>
            <p>Chapitre:  <?= htmlspecialchars($data['ART_CHAPTER']) ?></p>
            <?= htmlspecialchars($data['ART_TITLE']) ?>

        </h3>

        <p><em>le <?= htmlspecialchars($data['DATE_fr']) ?></em></p>
        <div class='contenu'>
            <?php
            $contenu = $data['ART_CONTENT'];
            /* $resume=substr($contenu,1,350); */
            ?>
            <?= $contenu ?>
            <br />
            <br />
        </div>
        <div class='icone-admin'>
            <a href="indexadmin.php?action=post&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>" title="Accédez aux commentaires"><div class ='nbcomm'><?= htmlspecialchars($data['NBCOMMENT']) ?></div><i class="fa fa-commenting-o fa-2x"></i></a>
            <a href="indexadmin.php?action=updatePost&amp;id=<?= $data['ART_ID'] ?>" title="Modifiez l'article"><i class="fa  fa-edit  fa-2x "></i></a>

            <?php
            $desactive = htmlspecialchars($data['ART_DESACTIVE']);
            if ($desactive) {
                echo '<a href="indexadmin.php?action=enablePost&amp;id=' . htmlspecialchars($data['ART_ID']) . '" title="Cliquez pour publiez l\'article"><i class="fa fa-eye-slash  fa-2x "></i></a>';
            } else {
                echo '<a href="indexadmin.php?action=disablePost&amp;id=' . htmlspecialchars($data['ART_ID']) . '" title="Mettre l\'article en cours de rédaction"><i class="fa fa-eye  fa-2x "></i></a>';
            }
            ?> 


            <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($data['ART_ID']) ?>" title="Supprimez l'article"><i class="fa fa-remove  fa-2x"></i></a>
        </div> 
    </div>
    <!-- Modal -->
    <div class="modal fade" id="deleteModal<?= htmlspecialchars($data['ART_ID']) ?>" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <h4><span class="fa fa-trash"></span> Etes-vous sur de vouloir supprimer l'article ?<br/>(Tous les commentaires seront supprimés également)</h4>
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
    <?php
}
$posts->closeCursor();
?>
<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>




