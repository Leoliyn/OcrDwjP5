<div class='resume'>

    <p><strong><?= htmlspecialchars($commentParent[$cle]['USER_PSEUDO']) ?>
        </strong> le <?= htmlspecialchars($commentParent[$cle]['COMM_date_fr']) ?></p>
    <div >
        <p><?= htmlspecialchars($commentParent[$cle]['COMM_CONTENU']) ?></p>

    </div>
    <?php
    $commentdesactive = htmlspecialchars($commentParent[$cle]['DISABLE']);
    $commentSignale = htmlspecialchars($commentParent[$cle]['SIGNALE']);
    if ($statut == 'ADMINISTRATEUR') {
        if ($commentdesactive) {
            ?>
            <a href="indexadmin.php?action=enableComment&amp;commId='<?= htmlspecialchars($commentParent[$cle]['COMM_ID']) ?>&amp;id= <?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>" title="Cliquez pour publiez le commentaire"><i class="fa fa-eye-slash  fa-2x "></i></a> - 
            <?php } else { ?>
            <a href="indexadmin.php?action=disableComment&amp;commId=<?= htmlspecialchars($commentParent[$cle]['COMM_ID']) ?>&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>" title="Cliquez pour désactiver le commentaire"><i class="fa fa-eye  fa-2x "></i></a> - 
        <?php } ?>
    <?php if ($commentSignale) { ?>

            <a href="indexadmin.php?action=disableSignal&amp;commId=<?= htmlspecialchars($commentParent[$cle]['COMM_ID']) ?>&amp;id=' . htmlspecialchars($data['ART_ID']) .'&amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>" title="Cliquez pour enlever l\'alerte"><i class="fa fa-thumbs-down  fa-2x red"> </i></a>
            <?php } else { ?>
            <a href="indexadmin.php?action=enableSignal&amp;commId=<?php htmlspecialchars($commentParent[$cle]['COMM_ID']) ?>&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?php htmlspecialchars($_GET['ouv_id']) ?>" title="Cliquez pour signaler le commentaire"><i class="fa fa-thumbs-o-up  fa-2x vert"></i></a>
        <?php } ?>
    <?php } elseif (($statut == 'REDACTEUR') || ($statut == 'LECTEUR')) { ?>
        <?php if ($commentdesactive) { ?>
            <i class="fa fa-eye-slash  fa-2x "></i> - 
    <?php } else { ?>
            <i class="fa fa-eye  fa-2x "></i></a> -
    <?php } ?>

    <?php if ($commentSignale) { ?>
        <i class="fa fa-thumbs-down  fa-2x red"> </i></a>
        <?php } else { ?>
        <a href="indexadmin.php?action=enableSignal&amp;commId=' . htmlspecialchars($commentParent[$cle]['COMM_ID']) . '&amp;id=' . htmlspecialchars($data['ART_ID']) . '&amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>" title="Cliquez pour signaler le commentaire"><i class="fa fa-thumbs-o-up  fa-2x vert"></i></a>
        <?php } ?>
    <?php } ?>
       





    

