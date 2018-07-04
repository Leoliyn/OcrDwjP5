<div class ='resume'>

    <p><strong>
<?= htmlspecialchars($tableau[$cle]['USER_PSEUDO']) ?>
        </strong> le 
<?= htmlspecialchars($tableau[$cle]['COMM_date_fr']) ?></p>

    <p><?= nl2br(htmlspecialchars($tableau[$cle]['COMM_CONTENU'])) ?>
    </p>


    <?php
    $commentdesactive = htmlspecialchars($tableau[$cle]['DISABLE']);
    $commentSignale = htmlspecialchars($tableau[$cle]['SIGNALE']);
    if ($statut == 'ADMINISTRATEUR') {
        if ($commentdesactive) {
            ?>

            <a href="indexadmin.php?action=enableComment&amp;commId=
        <?= htmlspecialchars($tableau[$cle]['COMM_ID']) ?>
               &amp;id=<?= htmlspecialchars($tableau[$cle]['p5_posts_ART_ID']) ?>
               &amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>" 
               title="Cliquez pour publiez le commentaire">
                <i class="fa fa-eye-slash  fa-2x "></i></a> -

    <?php } else { ?>

            <a href="indexadmin.php?action=disableComment&amp;commId=
        <?= htmlspecialchars($tableau[$cle]['COMM_ID']) ?>
               &amp;id=<?= htmlspecialchars($tableau[$cle]['p5_posts_ART_ID']) ?>
               &amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>"
               title="Cliquez pour désactiver le commentaire">
                <i class="fa fa-eye  fa-2x "></i></a> - 

    <?php } if ($commentSignale) { ?>

            <a href="indexadmin.php?action=disableSignal&amp;commId=
        <?= htmlspecialchars($tableau[$cle]['COMM_ID']) ?>
               &amp;id=<?= htmlspecialchars($tableau[$cle]['p5_posts_ART_ID']) ?>
               &amp;ouv_id='<?= htmlspecialchars($_GET['ouv_id']) ?>" title="Cliquez pour enlever l\'alerte">
                <i class="fa fa-thumbs-down  fa-2x red"> </i></a>
        <?php } else { ?>
            <a href="indexadmin.php?action=enableSignal&amp;commId=
        <?= htmlspecialchars($tableau[$cle]['COMM_ID']) ?>
               &amp;id=<?= htmlspecialchars($tableau[$cle]['p5_posts_ART_ID']) ?>
               &amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>"
               title="Cliquez pour signaler le commentaire">
                <i class="fa fa-thumbs-o-up  fa-2x vert"></i></a>
        <?php }
    } elseif (($statut == 'REDACTEUR') || ($statut == 'LECTEUR')) {
        if ($commentdesactive) { ?>
            <i class="fa fa-eye-slash  fa-2x "></i> - 
    <?php } else { ?>
            <i class="fa fa-eye  fa-2x "></i></a> - 
        <?php } if ($commentSignale) { ?>
        <i class="fa fa-thumbs-down  fa-2x red"> </i></a>
        <?php } else { ?>
        <a href="indexadmin.php?action=enableSignal&amp;commId=' . htmlspecialchars($tableau[$cle]['COMM_ID']) . '&amp;id=' . htmlspecialchars($tableau[$cle]['p5_posts_ART_ID']) . '&amp;ouv_id=' . htmlspecialchars($_GET['ouv_id']) .'" title="Cliquez pour signaler le commentaire"><i class="fa fa-thumbs-o-up  fa-2x vert"></i></a>
    <?php }
}
?>
</div>


