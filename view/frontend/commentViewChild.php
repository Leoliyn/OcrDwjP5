<?php
/**
 * Container commentaire enfant 
 */
?>
<div class ='resume'>

    <p><strong><?= htmlspecialchars($tableau[$cle]['USER_PSEUDO']) ?></strong> le <?= htmlspecialchars($tableau[$cle]['COMM_date_fr']) ?></p>
    <p><?= nl2br(htmlspecialchars($tableau[$cle]['COMM_CONTENU'])) ?></p>


    <?php
    $commentdesactive = htmlspecialchars($tableau[$cle]['DISABLE']);
    $commentSignale = htmlspecialchars($tableau[$cle]['SIGNALE']);
    $ouvId = htmlspecialchars($_GET['ouv_id']);
    if ($statut):
        if ($commentSignale) :
            echo '<i class="fa fa-thumbs-down  fa-2x red"> </i></a>';
        else:

            echo '<a href="index.php?action=enableSignal&amp;commId=' . htmlspecialchars($tableau[$cle]['COMM_ID']) . '&amp;id=' . htmlspecialchars($tableau[$cle]['p5_posts_ART_ID']) . '&amp;ouv_id=' . htmlspecialchars($_GET['ouv_id']) . '" title="Cliquez pour signaler le commentaire"><i class="fa fa-thumbs-o-up  fa-2x vert"></i></a>';
        endif;
    endif;
    if ($statut):

        require('view/frontend/formulaireReponseComment.php');
        ?>

    <?php endif; ?>

</div>