<?php
/**
 * container Votes dans  dashboard
 */
?>
<div id='votes'>
<div class='resume'>

    <h3 >   Vote(s) Ouvert(s)  </h3>

    <?php while ($donneesVotes = $votesListe->fetch()): ?>
        <div class="well"> 
            <div class="row">
                <div class="col-sm-3">
                    Vote N°:  
                    <button type="button" class="">
                        <span class="badge badge-light"><?= $donneesVotes['VOTE_ID'] ?></span>
                    </button>

                    <?php
                    $dateDebut = new datetime($donneesVotes['VOTE_DATEDEBUT']);
                    $dateD = $dateDebut->format('d-m-Y H:i');
                    $dateFin = new datetime($donneesVotes['VOTE_DATEFIN']);
                    $dateF = $dateFin->format('d-m-Y H:i');
                    ?>

                </div>
                <div class="col-sm-3">
                    Début : 
                    <button type="button" class="">
                        <span class="fa fa-calendar fa-2x"></span><?= $dateD ?>
                    </button>


                </div>
                <div class="col-sm-3">
                    Fin   : 
                    <button type="button" class="">
                        <span class="fa fa-calendar fa-2x"></span> <?= $dateF ?>
                    </button>
                </div>
                <div class="col-sm-3">
                    Proposé par :  
                    <button type="button" class="">
                        <span class="fa fa-user fa-2x"></span> <?= $donneesVotes['USER_PSEUDO'] ?>
                    </button>

                </div></div>
            <span><ul class="list-group">
                    Suite proposée pour :
                    <li class="list-group-item">Chapitre N°: <?= $donneesVotes['NUM_CHAPITRE'] ?></li>
                    <li class="list-group-item">Titre: <?= $donneesVotes['TITRE_PRECEDENT'] ?></li>
                    <li class="list-group-item"><?= $donneesVotes['CONTENU_SUITE'] ?></li>
                </ul></span>

            <div class="row">
                <div class="col-sm-2">

                    <?php $jaime = 0; ?>
                    <?php $jaimepas = 0; ?>
                    <?php
                    foreach ($tableauScores as $element):
                        if ($element['p5_votes_VOTE_ID'] == $donneesVotes['VOTE_ID']):
                            $jaime = $element['JAIME'];
                            $jaimepas = $element['JAIMEPAS'];
                        endif;
                    endforeach;
                    ?>

                    <i class="fa fa-thumbs-up  fa-2x "></i><span class="badge"><?= $jaime ?></span>
                    <i class="fa fa-thumbs-down  fa-2x "></i><span class="badge"><?= $jaimepas ?></span>  
                </div>
                <div class="col-sm-1">
                    <a href="indexadmin.php?action=fermetureVote&amp;vote_id=
    <?= $donneesVotes['VOTE_ID'] ?>">
                        <i class="fa fa-unlock  fa-2x" title="Fermer le Vote"> </i></a>

                </div>
                <div class="col-sm-1">
                    <a href="indexadmin.php?action=prolongeVote&amp;vote_id=<?= $donneesVotes['VOTE_ID'] ?>&amp;dateFin=<?= $donneesVotes['VOTE_DATEFIN'] ?>&amp;duree=7"><i class="fa fa-mail-forward  fa-2x " title="Prolonger la durée du Vote de 7 jours"></i></a>



                </div>
                <div class="col-sm-1">
                    <a href="indexadmin.php?action=prolongeVote&amp;vote_id=<?= $donneesVotes['VOTE_ID'] ?>&amp;dateFin=<?= $donneesVotes['VOTE_DATEFIN'] ?>&amp;duree=-7"><i class="fa  fa-mail-reply fa-2x " title="Raccourcir la durée du Vote de 7 jours"></i></a>



                </div>
                <div class="col-sm-1">
                    <a  data-toggle="modal" data-target="#integrationModal<?= $donneesVotes['VOTE_ID'] ?>" href="#"><i class="fa fa-copy  fa-2x "title="Intégrer la Suite au chapitre"> </i></a>




                </div>
                <div class="col-sm-1">

                    <a  data-toggle="modal" data-target="#delVoteModal<?= $donneesVotes['VOTE_ID'] ?>" href="#"><i class="fa fa-close  fa-2x "title="Supprimer le vote"></i></a>


                </div>
                <div class="col-sm-6">

                </div></div></div>

        <?php require'view/backend/modalIntegrationView.php'; ?>
        <?php require'view/backend/modalDelVoteView.php'; ?>


<?php endwhile; ?>
</div>
<!--VOTES FERMES-->
<div  class='resume'>
    <h3 >   Vote(s) Fermé(s)  </h3>

<?php while ($donneesVotes = $votesListeClose->fetch()): ?>

        <div class="well"> 
            <div class="row">
                <div class="col-sm-3">
                    Vote N°:  
                    <button type="button" class="">
                        <span class="badge badge-light"><?= $donneesVotes['VOTE_ID'] ?></span>
                    </button>
                    <?php
                    $dateDebut = new datetime($donneesVotes['VOTE_DATEDEBUT']);
                    $dateD = $dateDebut->format('d-m-Y H:i');
                    $dateFin = new datetime($donneesVotes['VOTE_DATEFIN']);
                    $dateF = $dateFin->format('d-m-Y H:i');
                    ?>
                </div>
                <div class="col-sm-3">
                    Début : 
                    <button type="button" class="">
                        <span class="fa fa-calendar fa-2x"></span> <?= $dateD ?>
                    </button>


                </div>
                <div class="col-sm-3">
                    Fin   : 
                    <button type="button" class="">
                        <span class="fa fa-calendar fa-2x"></span> <?= $dateF ?>
                    </button>

                </div>
                <div class="col-sm-3">
                    Proposé par :  
                    <button type="button" class="">
                        <span class="fa fa-user fa-2x"></span> <?= $donneesVotes['USER_PSEUDO'] ?>
                    </button>

                </div></div>
            <span><ul class="list-group">
                    Suite proposée pour :
                    <li class="list-group-item">Chapitre N°: <?= $donneesVotes['NUM_CHAPITRE'] ?></li>
                    <li class="list-group-item">Titre: <?= $donneesVotes['TITRE_PRECEDENT'] ?></li>
                    <li class="list-group-item"><?= $donneesVotes['CONTENU_SUITE'] ?></li>
                </ul></span>

            <div class="row">
                <div class="col-sm-2">

                    <?php $jaime = 0; ?>
                    <?php $jaimepas = 0; ?>
                    <?php foreach ($tableauScores as $element): ?>
                        <?php
                        if ($element['p5_votes_VOTE_ID'] == $donneesVotes['VOTE_ID']):
                            $jaime = $element['JAIME'];
                            $jaimepas = $element['JAIMEPAS'];
                        endif;
                    endforeach;
                    ?>



                    <i class="fa fa-thumbs-up  fa-2x "></i><span class="badge"><?= $jaime ?></span>
                    <i class="fa fa-thumbs-down  fa-2x "></i><span class="badge"><?= $jaimepas ?></span>  
                </div>
                <div class="col-sm-1">
                    <a href="indexadmin.php?action=ouvertureVote&amp;vote_id=<?= $donneesVotes['VOTE_ID'] ?>"><i class="fa fa-lock  fa-2x" title="Ouvrir le Vote"> </i></a>

                </div>
                <div class="col-sm-1">
                    <a href="indexadmin.php?action=prolongeVote&amp;vote_id=<?= $donneesVotes['VOTE_ID'] ?>&amp;dateFin=<?= $donneesVotes['VOTE_DATEFIN'] ?>&amp;duree=-7"><i class="fa fa-mail-forward  fa-2x " title="Raccourcir le Vote de 7 jours"></i></a>
                </div>
                <div class="col-sm-1">
                    <a  data-toggle="modal" data-target="#integrationModal<?= $donneesVotes['VOTE_ID'] ?>" href="#"><i class="fa fa-copy  fa-2x "title="Intégrer la Suite au chapitre"> </i></a>

                </div>
                <div class="col-sm-1">

                    <a  data-toggle="modal" data-target="#delVoteModal<?= $donneesVotes['VOTE_ID'] ?>" href="#"><i class="fa fa-close  fa-2x "title="Supprimer le vote"></i></a>
                </div>
                <div class="col-sm-6">

                </div></div></div>

    <?php require'view/backend/modalIntegrationView.php'; ?>
    <?php require'view/backend/modalDelVoteView.php'; ?>
<?php endwhile; ?>

</div>
</div>










