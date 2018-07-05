<?php
/**
 * Page liste des chapitres d'un ouvrage
 * 
 */
?>
<?php ob_start(); ?>
<!--
Statut de l'utilisateur -->
<?php $droits = unserialize($_SESSION['Rights']); ?>
<?php
if (isset($droits[$_GET['ouv_id']])):
    $statut = $droits[$_GET['ouv_id']];
else: throw new Exception("Vous n'avez de droit d'accès ");
endif;
?>
<?php if (($statut == 'ADMINISTRATEUR') || ($statut == 'REDACTEUR')): ?>
    <div class='resume'>
        <a href="indexadmin.php?action=newPost&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Ajouter un article"><i class="fa fa-plus-square  fa-4x "></i>   Ajouter un chapitre..</a>
    </div>
<?php endif; ?>



<?php
$num_rows = 0;
$lesSuites = $postsSuite->fetchAll();
$num_rows = count($lesSuites);
$postsSuite->closeCursor();

while ($data = $posts->fetch()):
    $compteurSuite = 0;
    for ($i = 0; $i < $num_rows; $i++):
        if ($lesSuites[$i]['ART_PRECEDENT'] == $data['ART_ID']):
            $compteurSuite = $compteurSuite + 1;
        endif;
    endfor;
    $desactive = htmlspecialchars($data['ART_DESACTIVE']);
    // Si chapitre desactive et statut lecteur pas d'affichage d u post
    if (($statut == 'LECTEUR')AND ( $desactive)):
    else:
        ?>

        <div class='resume'>

            <?php
            $redaction = '';
            $propose = '';
            $accepte = '';
            $refuse = '';
            $vote = '';
            if ($data['STATUT_POST_LIBELLE'] == 'REDACTION'):
                $redaction = ' outils ';
            elseif ($data['STATUT_POST_LIBELLE'] == 'PROPOSE'):
                $propose = ' outils ';
            elseif ($data['STATUT_POST_LIBELLE'] == 'ACCEPTE'):
                $accepte = ' outils ';
            elseif ($data['STATUT_POST_LIBELLE'] == 'REFUSE'):
                $refuse = ' outils ';
            elseif ($data['STATUT_POST_LIBELLE'] == 'VOTE'):
                $vote = ' outils ';
            endif;
            ?>

        <?php $file = "./uploads/" . htmlspecialchars($data['ART_IMAGE']); ?>
        <?php if (is_file($file)): ?>
                <img src='./uploads/<?= htmlspecialchars($data['ART_IMAGE']) ?>' class="miniature" />
                    <?php endif; ?>
            <h3>
                <p><i class="fa fa-book fa-1x"></i> <?= htmlspecialchars($data['OUV_TITRE']) ?></p>
                <p><i class="fa fa-file-o   fa-1x"></i>  n°<?= htmlspecialchars($data['ART_CHAPTER']) ?>
        <?= htmlspecialchars($data['ART_TITLE']) ?></p>

            </h3>

            <p><em>le <?= htmlspecialchars($data['DATE_fr']) ?></em> par :<?= htmlspecialchars($data['USER_PSEUDO']) ?></p>
            <div class='contenu'>
        <?php $contenu = $data['ART_CONTENT']; ?>
        <?= $contenu ?>
                <br />
                <br />
            </div>
            <div class='icone-admin row '>


                <?php if (($statut == 'ADMINISTRATEUR') || (($data['STATUT_POST_LIBELLE'] == 'REDACTION')AND ( $_SESSION['userId'] == $data['ART_AUTEUR']))): ?>
                    <div class ="col-sm-1">
                        <a href="indexadmin.php?action=updatePost&amp;id=<?= $data['ART_ID'] ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Modifiez l'article"><i class="fa  fa-edit  fa-2x "></i></a>
                    </div>
                <?php endif; ?>

        <?php if ($statut == 'ADMINISTRATEUR'): ?>

            <?php if ($desactive) : ?>
                        <div class ="col-sm-1">
                            <a href="indexadmin.php?action=enablePost&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Cliquez pour publiez l\'article"><i class="fa fa-eye-slash  fa-2x "></i></a>
                        </div>

            <?php else: ?>
                        <div class ="col-sm-1">
                            <a href="indexadmin.php?action=disablePost&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Mettre l\'article en cours de rédaction"><i class="fa fa-eye  fa-2x "></i></a>
                        </div>

                    <?php
                    endif;
                else:
                    $desactive = htmlspecialchars($data['ART_DESACTIVE']);
                    if ($desactive):
                        ?>
                        <div class ="col-sm-1">   
                            <i class="fa fa-eye-slash  fa-2x "></i>
                        </div>
                    <?php else: ?>
                        <div class ="col-sm-1">
                            <i class="fa fa-eye  fa-2x "></i>
                        </div>
            <?php endif;
        endif;
        ?>
        <?php if ($statut == 'ADMINISTRATEUR'): ?>

                    <div class ="col-sm-1">
                        <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($data['ART_ID']) ?>" title="Supprimez l'article"><i class="fa fa-remove  fa-2x"></i></a>
                    </div>
                <?php endif; ?>

                <div class ="col-sm-2 bulleComm">
                    <a href="indexadmin.php?action=post&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= htmlspecialchars($_GET['ouv_id']) ?>" title="Accédez Au Chapitre,aux commentaires, aux suites"><div class ='nbcomm'><?= htmlspecialchars($data['NBCOMMENT']) ?></div><i class="fa fa-commenting-o fa-2x"></i></a>
                </div>
                <div class ="col-sm-1"></div>

        <?php if (($statut == 'REDACTEUR')AND ( ($data['STATUT_POST_LIBELLE'] == 'REDACTION') || ($data['STATUT_POST_LIBELLE'] == 'PROPOSE'))AND ( $_SESSION['userId'] == $data['ART_AUTEUR'])): ?>
                    <div class ="col-sm-1">
                        <a href='indexadmin.php?action=chgtStatut&amp;libelle=REDACTION&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='en cours de rédaction'><i class="fa fa-wrench  fa-2x <?= $redaction ?>"></i> </a> 
                    </div>
                    <div class ="col-sm-1">
                        <a href='indexadmin.php?action=chgtStatut&amp;libelle=PROPOSE&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='proposer le post à la validation'><i class="fa fa-lock  fa-2x <?= $propose ?>"></i></a> 
                    </div>

        <?php endif; ?>

        <?php if ($statut == 'ADMINISTRATEUR'): ?>

                    <div class ="col-sm-1">
                        <a href='indexadmin.php?action=chgtStatut&amp;libelle=REDACTION&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='en cours de rédaction'><i class="fa fa-wrench  fa-2x <?= $redaction ?>"></i> </a> 
                    </div>  <div class ="col-sm-1">
                        <a href='indexadmin.php?action=chgtStatut&amp;libelle=PROPOSE&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='proposer le post à la validation'><i class="fa fa-lock  fa-2x <?= $propose ?>"></i></a> 
                    </div>  <div class ="col-sm-1">                             
                        <a href='indexadmin.php?action=chgtStatut&amp;libelle=ACCEPTE&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='Validé'><i class="fa fa-thumbs-o-up  fa-2x <?= $accepte ?>"></i></a>
                    </div>  <div class ="col-sm-1">      
                        <a href='indexadmin.php?action=chgtStatut&amp;libelle=REFUSE&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='Refusé'><i class="fa fa-thumbs-o-down  fa-2x <?= $refuse ?>"></i></a>
                    </div>

                <?php endif; ?>

        <?php if (($statut == 'LECTEUR') || ($statut == 'ADMINISTRATEUR')): ?>

                    <div class ="col-sm-2 iconeSuite">
                        <a href="indexadmin.php?action=newSuite&amp;id=<?= $data['ART_ID'] ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Proposer une suite "><div class ='nbcomm'><?= $compteurSuite ?> </div><i class="fa fa-plus-square  fa-2x "></i> Suite ?</a>
                    </div> 
        <?php endif; ?>
            </div> 
        </div>
    <?php endif; ?>
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

                        <input type="hidden" class="form-control" id="ouv_id" name="ouv_id"value="<?= $_GET['ouv_id'] ?>">   
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
endwhile;
$posts->closeCursor();
$lesSuites = [];
$num_rows = 0;
?>
<?php $content = ob_get_clean(); ?>
<?php require('view/backend/template.php'); ?>




