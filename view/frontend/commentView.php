
<?php
/**
 * Container commentaire racine
 */
?>

<div class='resume'>
    <p> <i class="fa fa-comments-o fa-2x "></i>Début de conversation</p>
    <p><strong><?= htmlspecialchars($commentParent[$cle]['USER_PSEUDO']) ?></strong> le <?= htmlspecialchars($commentParent[$cle]['COMM_date_fr']) ?></p>
    <div >  
        <p><?= nl2br(htmlspecialchars($commentParent[$cle]['COMM_CONTENU'])) ?></p>

    </div>
    <?php
    $commentSignale = htmlspecialchars($commentParent[$cle]['SIGNALE']);

    if ($statut):
        if ($commentSignale):
            ?>
            <i class="fa fa-thumbs-down  fa-2x red"> </i>
        <?php else: ?>
            <a href="index.php?action=enableSignal&amp;commId=<?= htmlspecialchars($commentParent[$cle]['COMM_ID']) ?>&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>" title="Cliquez pour signaler le commentaire"><i class="fa fa-thumbs-o-up  fa-2x vert"></i></a>
    <?php endif; ?>

        <form action="index.php?action=addComment" method="post">
            <input class="form-control"type="hidden" id="postId" name="postId" value="<?= htmlspecialchars($_GET['id']) ?>" />

            <input class="form-control" type="hidden" id="author" name="author" value="<?= $_SESSION['user'] ?> "readOnly/>
            <input class="form-control" type="hidden" id="authorId" name="authorId" value="<?= $_SESSION['userId'] ?> "readOnly/>
            <?php
            $idcom = null;
            if ($commentParent[$cle]['COMM_ID']):
                $idcom = htmlspecialchars($commentParent[$cle]['COMM_ID']);

            else:
                $idcom = 0;

            endif;
            ?>
            <input class="form-control" type="hidden" id="precedent" name="precedent" value="<?= $idcom ?> "readOnly/>
            <input class="form-control" type="hidden" id="ouvId" name="ouvId" value="<?= $ouvId ?> "readOnly/>
            <label for="comment">Saisissez votre commentaire</label><br />
            <textarea class="form-control" id="comment" name="comment"></textarea><br />
            <input class="btn btn-primary" type="submit" name="envoyerComm" value="Envoyez" /> 
        </form>
<?php endif; ?>
</div>
