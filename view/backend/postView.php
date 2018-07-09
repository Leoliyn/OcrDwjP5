<?php
/**
 * container chapitre 
 */
?>
<?php ob_start(); ?>

<?php
/**
 * Statut de l'utilisateur sur l'ouvrage
 */
$statut = null;
$droits = unserialize($_SESSION['Rights']);
if (isset($_POST['ouv_id'])):
    $ouvId = $_POST['ouv_id'];
endif;
if (isset($_GET['ouv_id'])):
    $ouvId = $_GET['ouv_id'];
endif;
if (isset($droits[$ouvId])) :
    $statut = $droits[$ouvId];
else:
    throw new Exception("Vous n'avez pas de droit d'accès ");
endif;
?>

<?php
$data = $article;
$desactive = htmlspecialchars($data['ART_DESACTIVE']);
?>    
<?php if (($statut == 'LECTEUR')AND ( $desactive)): ?>  
<?php else: ?>

    <div class=''>
        <div class='icone-admin right outils'>
            <?php
            // nom de la classe outils dans la variable selon le statut 
            $redaction = ' ';
            $propose = ' ';
            $accepte = ' ';
            $refuse = ' ';
            $vote = ' ';
            if($statutPost['STATUT_POST_LIBELLE'] == 'REDACTION'):
            $redaction = ' outils ';
            elseif($statutPost['STATUT_POST_LIBELLE'] == 'PROPOSE'):
            $propose = ' outils ';
            elseif($statutPost['STATUT_POST_LIBELLE'] == 'ACCEPTE'):
            $accepte = ' outils ';
            elseif($statutPost['STATUT_POST_LIBELLE'] == 'REFUSE'):
            $refuse = ' outils ';
            elseif($statutPost['STATUT_POST_LIBELLE'] == 'VOTE'):
            $vote = ' outils ';
            endif; // 
            ?>
        </div>

    <?php $file = "./uploads/" . htmlspecialchars($data['ART_IMAGE']); ?>
    <?php if (is_file($file)): ?>
            <img src="<?= $file ?>" class="miniature" />
            <br />
    <?php endif; ?>

        <h3><i class="fa fa-file-o   fa-1x"></i> <?= htmlspecialchars($data['ART_TITLE']) ?></h3>

        <p><em>le <?= $data['DATE_fr'] ?></em>par :<?= htmlspecialchars($data['USER_PSEUDO']) ?></p>
        <p><?= ($data['ART_CONTENT']) ?></p>
        <div class='icone-admin row'>



            <?php if (($statut == 'ADMINISTRATEUR')):
                if ($desactive) :
                    ?>
                    <div class ="col-sm-1">
                        <a href="indexadmin.php?action=enablePost&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Cliquez pour publiez l\'article"><i class="fa fa-eye-slash  fa-2x "></i></a>
                    </div>
        <?php else: ?>
                    <div class ="col-sm-1">
                        <a href="indexadmin.php?action=disablePost&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Mettre l\'article en cours de rédaction"><i class="fa fa-eye  fa-2x "></i></a>
                    </div>

        <?php endif; ?>


                <div class ="col-sm-1">
                    <a href="indexadmin.php?action=updatePost&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Modifiez l'article"><i class="fa  fa-edit  fa-2x "></i></a>
                </div><div class ="col-sm-1">
                    <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($data['ART_ID']) ?>" title="Supprimez l'article"><i class="fa fa-remove  fa-2x"></i></a>
                </div>
            <?php endif; ?>
            <div class ="col-sm-1">
                <a href="indexadmin.php?action=listPosts&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Retour à la liste"><i class="fa fa-arrow-left  fa-2x "></i></a>
            </div>


            <?php
            if (
                    (
                    ($statut == 'REDACTEUR')
                    AND ( $data['ART_AUTEUR'] == $_SESSION['userId'])
                    AND (
                    ($statutPost['STATUT_POST_LIBELLE'] == 'REDACTION') || ($statutPost['STATUT_POST_LIBELLE'] == 'PROPOSE'))
                    ) || ($statut == 'ADMINISTRATEUR')) : // protection modif d'un auteur sur un statut par admin '
                ?>

                <div class ="col-sm-1">
                    <a href='indexadmin.php?action=chgtStatut&amp;libelle=REDACTION&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='en cours de rédaction'><i class="fa fa-wrench  fa-2x <?= $redaction ?>"></i> </a> 
                </div>
                <div class ="col-sm-1">
                    <a href='indexadmin.php?action=chgtStatut&amp;libelle=PROPOSE&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='proposer le post à la validation'><i class="fa fa-lock  fa-2x <?= $propose ?>"></i></a> 
                </div>

        <?php
    endif;
    if ($statut == 'ADMINISTRATEUR'):
        ?>

                <div class ="col-sm-1">                             
                    <a href='indexadmin.php?action=chgtStatut&amp;libelle=ACCEPTE&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='Validé'><i class="fa fa-thumbs-o-up  fa-2x <?= $accepte ?>"></i></a>
                </div>  <div class ="col-sm-1">      
                    <a href='indexadmin.php?action=chgtStatut&amp;libelle=REFUSE&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>'title='Refusé'><i class="fa fa-thumbs-o-down  fa-2x <?= $refuse ?>"></i></a>

                </div>
    <?php endif; ?>
        </div> 
    </div>
<?php
endif;
?>
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
                    <input type="hidden" class="form-control" id="ouv_id" name="ouv_id"value="<?= $_GET['ouv_id'] ?>">   
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

<h2> Suite(s)</h2>
<?php
// tableau de liste vote idvote en indice et vote ouvert en valeur
$tabListVote = [];
while ($listVote = $listStatutVote->fetch()) {
    $tabListVote[$listVote['p5_posts_ART_ID']] = $listVote['VOTE_OUVERT'];
}
while ($suite = $suites->fetch()) {

    // score du vote de la suite 
    $jaime = 0;
    $jaimepas = 0;

    foreach ($tableauScores as $element) {

        if ($element['SUITE_ID'] == $suite['ART_ID']) {
            $jaime = $element['JAIME'];
            $jaimepas = $element['JAIMEPAS'];
        }
    }

    if (($suite['ART_AUTEUR'] == $_SESSION['userId']) || ($statut == 'ADMINISTRATEUR')) {
        $redaction = '';
        $propose = '';
        $accepte = '';
        $refuse = '';
        $vote = '';
        if ($suite['STATUT_POST_LIBELLE'] == 'REDACTION') {
//    echo ' <i class="fa fa-wrench  fa-2x "></i>';
            $redaction = ' outils ';
        } elseif ($suite['STATUT_POST_LIBELLE'] == 'PROPOSE') {
//    echo'<i class="fa fa-lock  fa-2x "></i>';
            $propose = ' outils ';
        } elseif ($suite['STATUT_POST_LIBELLE'] == 'ACCEPTE') {
//    echo'<i class="fa fa-thumbs-o-up  fa-2x "></i>';
            $accepte = ' outils ';
        } elseif ($suite['STATUT_POST_LIBELLE'] == 'REFUSE') {
//    echo'<i class="fa fa-thumbs-o-down  fa-2x "></i>';
            $refuse = ' outils ';
        } elseif ($suite['STATUT_POST_LIBELLE'] == 'VOTE') {
//    echo' <i class="fa fa-balance-scale  fa-2x "></i>';
            $vote = ' outils ';
        } elseif ($suite['STATUT_POST_LIBELLE'] == 'TERMINE') {
//    echo' <i class="fa fa-balance-scale  fa-2x "></i>';
            $vote = ' termine ';
        }
        ?>
        <div class='resume'>
            <p><strong><?= htmlspecialchars($suite['USER_PSEUDO']) ?></strong> le <?= htmlspecialchars($suite['DATE']) ?></p>
            <p><?= ($suite['ART_CONTENT']) ?></p>

                <?php if (($suite['STATUT_POST_LIBELLE'] == 'VOTE')AND ( $statut <> 'ADMINISTRATEUR')) { ?>
                <i class="fa fa-thumbs-up  fa-2x "></i><span class="badge"><?php $jaime ?></span>
                <i class="fa fa-thumbs-down  fa-2x "></i><span class="badge"><?php $jaimepas ?></span>  
        <?php } else { ?>

                <div class='icone-admin row'>
            <?php
            if (($statut == 'ADMINISTRATEUR') || (($suite['STATUT_POST_LIBELLE'] == 'REDACTION') || ($suite['STATUT_POST_LIBELLE'] == 'PROPOSE') )) {
                ?>
                        <div class ="col-sm-1">
                            <a href="indexadmin.php?action=updateSuite&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Modifiez la suite"><i class="fa  fa-edit  fa-2x "></i></a>
                        </div> <div class ="col-sm-1">
                            <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($suite['ART_ID']) ?>" title="Supprimez la suite"><i class="fa fa-remove  fa-2x"></i></a>
                        </div> <div class ="col-sm-1">          
                            <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=REDACTION&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='en cours de rédaction'><i class="fa fa-wrench  fa-2x <?= $redaction ?>"></i> </a> 
                        </div> <div class ="col-sm-1">         
                            <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=PROPOSE&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='proposer le post à la validation'><i class="fa fa-lock  fa-2x <?= $propose ?>"></i></a> 
                        </div>     
                <?php
            }
            if ($statut == 'ADMINISTRATEUR') {
                ?>
                        <div class ="col-sm-1">
                            <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=ACCEPTE&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='Validé'><i class="fa fa-thumbs-o-up  fa-2x <?= $accepte ?>"></i></a>
                        </div> <div class ="col-sm-1">
                            <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=REFUSE&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='Refusé'><i class="fa fa-thumbs-o-down  fa-2x <?= $refuse ?>"></i></a>
                        </div> <div class ="col-sm-3">        
                            <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=VOTE&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='Vote en cours'> <i class="fa fa-balance-scale  fa-2x <?= $vote ?>"></i><span class="badge"><?= $jaime . ' ' ?> J'aime</span><span class="badge"><?= $jaimepas . ' ' ?>j'aime pas.</span></a>
                        </div>

            <?php } ?>

                    <div class="modal fade" id="deleteModal<?= htmlspecialchars($suite['ART_ID']) ?>" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">

                                    <h4><span class="fa fa-trash"></span> <?= htmlspecialchars($suite['ART_ID']) ?> Etes-vous sur de vouloir supprimer l'article ?</h4>
                                </div>
                                <div class="modal-body">
                                    <form role="form" action="indexadmin.php" method="get">
                                        <input type="hidden" class="form-control" id="action" name="action"value="delSuite">
                                        <input type="hidden" class="form-control" id="id" name="id"value="<?= htmlspecialchars($suite['ART_ID']) ?>">  
                                        <input type="hidden" class="form-control" id="ouv_id" name="ouv_id" value="<?= $_GET['ouv_id'] ?>">
                                        <input type="hidden" class="form-control" id="auteur" name="auteur" value="<?= $suite['ART_AUTEUR'] ?>">
                                        <input type="hidden" class="form-control" id="precedent" name="precedent" value="<?= $suite['ART_PRECEDENT'] ?>">   
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

            <?php
        }
        ?>
        </div>
            <?php
        } elseif ($suite['STATUT_POST_LIBELLE'] == 'VOTE') {
            ?>

        <div class='avoter'>
            <p><strong><?= htmlspecialchars($suite['USER_PSEUDO']) ?></strong> le <?= htmlspecialchars($suite['DATE']) ?></p>
            <p><?= $suite['ART_CONTENT'] ?></p>
        <?php
        if ((isset($tabListVote[$suite['ART_ID']]))AND ( $tabListVote[$suite['ART_ID']] == 1)) {
            ?>
                <div class='icone-admin'>

                    <a href='indexadmin.php?action=votation&amp;bulletin=YES&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='jaime'><i class="fa fa-thumbs-up  fa-2x <?= $vote ?>"></i><span class="badge"><?= $jaime ?></span></a>
                    <a href='indexadmin.php?action=votation&amp;bulletin=NO&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='jaimepas'><i class="fa fa-thumbs-down  fa-2x <?= $vote ?>"></i><span class="badge"><?= $jaimepas ?></span></a>

                </div>
            <?php
        } else {
            ?>

                <div class='icone-admin'>
                    <i class="fa fa-thumbs-up  fa-2x "></i><span class="badge"><?= $jaime ?></span>
                    <i class="fa fa-thumbs-down  fa-2x "></i><span class="badge"><?= $jaimepas ?></span>
                    <span class="badge ">Vote suspendu !</span> 

                </div>
            <?php
        }
        ?>

        </div>
        <?php
    }
}
?>
<h2>Commentaire(s)</h2>
<?php

//Fonction affichage recursif dans la vue . Ordonne les dépendances des commentaires 
//dans les tableaux $comments et $commensChild founis par le controleur
function rechercheEnfant($tableau, $id, $statut) {
    echo'<ul>';
    //global $data;

    foreach ($tableau as $cle => $element) {

        if ($tableau[$cle]['COMM_PARENT'] == $id) {
            echo '<i class="fa fa-arrow-down fa-2x"></i><li class = "listComm"> ';
            require('view/backend/commentViewChild.php');
            echo'</li><ul>';
            rechercheEnfant($tableau, $tableau[$cle]['COMM_ID'], $statut);
            echo'</ul>';
        } else {
            
        }
    }
    echo'</ul>';
}

///////////Fin fonction Affichage
$commentParent = $comments->fetchAll();
$commentChild = $commentsChild->fetchAll();
foreach ($commentParent as $cle => $element) {//parcours de chaque element du tab parent
    require('view/backend/commentView.php');
    rechercheEnfant($commentChild, $commentParent[$cle]['COMM_ID'], $statut);
    echo '</div>'; //fermeture container commentView.php
}
?>
<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>

