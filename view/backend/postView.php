<?php $title = 'Jean FORTEROCHE'; ?>

<?php ob_start(); 
// Statut de l'utilisateur 
$statut= null;
$droits = unserialize($_SESSION['Rights']);
if (isset($_POST['ouv_id'])){
    $ouvId= $_POST['ouv_id']; 
}
if (isset($_GET['ouv_id'])){
    $ouvId= $_GET['ouv_id']; 
}

if (isset($droits[$ouvId])) {
    $statut = $droits[$ouvId];

}else {
    throw new Exception("Vous n'avez pas de droit d'accès 9 pview");
}
?>
<?php

$data = $article;
$desactive = htmlspecialchars($data['ART_DESACTIVE']);
    if(($statut=='LECTEUR')AND ($desactive)){
        
    }else{
?>
<div class=''>
     <div class='icone-admin right outils'>
  <?php
  $redaction=' ';$propose=' ';$accepte=' ';$refuse=' '; $vote=' ';
if($statutPost['STATUT_POST_LIBELLE']=='REDACTION'){
//    echo ' <i class="fa fa-wrench  fa-2x "></i>';
   $redaction= ' outils ';
}elseif($statutPost['STATUT_POST_LIBELLE']=='PROPOSE'){
//    echo'<i class="fa fa-lock  fa-2x "></i>';
   $propose=' outils ';
}elseif($statutPost['STATUT_POST_LIBELLE']=='ACCEPTE'){
//    echo'<i class="fa fa-thumbs-o-up  fa-2x "></i>';
   $accepte=' outils ';
}elseif($statutPost['STATUT_POST_LIBELLE']=='REFUSE'){
//    echo'<i class="fa fa-thumbs-o-down  fa-2x "></i>';
   $refuse=' outils ';
}elseif($statutPost['STATUT_POST_LIBELLE']=='VOTE'){
//    echo' <i class="fa fa-balance-scale  fa-2x "></i>';
   $vote=' outils ';
}
  ?>

 
 
 

     </div>
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

    <p><em>le <?= $data['DATE_fr'] ?></em>par :<?= htmlspecialchars($data['USER_PSEUDO']) ?></p>
    <p><?= ($data['ART_CONTENT']) ?></p>
    <div class='icone-admin'>
        
<?php

if(($statut == 'ADMINISTRATEUR')){
if ($desactive) {
    echo '<a href="indexadmin.php?action=enablePost&amp;id=' . htmlspecialchars($data['ART_ID']) . '&amp;ouv_id='.$_GET['ouv_id'].'" title="Cliquez pour publiez l\'article"><i class="fa fa-eye-slash  fa-2x "></i></a>';
} else {
    echo '<a href="indexadmin.php?action=disablePost&amp;id=' . htmlspecialchars($data['ART_ID']) . '&amp;ouv_id='.$_GET['ouv_id'].'" title="Mettre l\'article en cours de rédaction"><i class="fa fa-eye  fa-2x "></i></a>';
}
 
?>


        <a href="indexadmin.php?action=updatePost&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Modifiez l'article"><i class="fa  fa-edit  fa-2x "></i></a>
        <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($data['ART_ID']) ?>" title="Supprimez l'article"><i class="fa fa-remove  fa-2x"></i></a>
 <?php } ?>
        <a href="indexadmin.php?action=listPosts&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Retour à la liste"><i class="fa fa-arrow-left  fa-2x "></i></a>
        
      
 <?php
 if(($statut == 'REDACTEUR')||($statut == 'ADMINISTRATEUR')){
     ?>
 
    
            <i class="fa fa-wrench  fa-2x <?= $redaction ?>"></i>  
            <i class="fa fa-lock  fa-2x <?= $propose ?>"></i>
 <?php
 }
 if($statut == 'ADMINISTRATEUR'){
 ?>
            <i class="fa fa-thumbs-o-up  fa-2x <?= $accepte ?>"></i>
            <i class="fa fa-thumbs-o-down  fa-2x <?= $refuse ?>"></i>
<!--            <i class="fa fa-balance-scale  fa-2x <?= $vote ?>"></i>-->
            <?php
 }
 ?>
          </div> 
</div>
<?php
    }
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

while ($suite = $suites->fetch()) {
    
    // score du vote de la suite 
$jaime = 0;
$jaimepas=0;
foreach($tableauScores as $element)
{
   
    if($element['SUITE_ID']== $suite['ART_ID']){
        $jaime=$element['JAIME'];
        $jaimepas=$element['JAIMEPAS'];
    }
}

if(($suite['ART_AUTEUR']== $_SESSION['userId'])||($statut=='ADMINISTRATEUR')){
    $redaction='';$propose='';$accepte='';$refuse=''; $vote='';
if($suite['STATUT_POST_LIBELLE']=='REDACTION'){
//    echo ' <i class="fa fa-wrench  fa-2x "></i>';
   $redaction= ' outils ';
}elseif($suite['STATUT_POST_LIBELLE']=='PROPOSE'){
//    echo'<i class="fa fa-lock  fa-2x "></i>';
   $propose=' outils ';
}elseif($suite['STATUT_POST_LIBELLE']=='ACCEPTE'){
//    echo'<i class="fa fa-thumbs-o-up  fa-2x "></i>';
   $accepte=' outils ';
}elseif($suite['STATUT_POST_LIBELLE']=='REFUSE'){
//    echo'<i class="fa fa-thumbs-o-down  fa-2x "></i>';
   $refuse=' outils ';
}elseif($suite['STATUT_POST_LIBELLE']=='VOTE'){
//    echo' <i class="fa fa-balance-scale  fa-2x "></i>';
   $vote=' outils ';
}elseif($suite['STATUT_POST_LIBELLE']=='TERMINE'){
//    echo' <i class="fa fa-balance-scale  fa-2x "></i>';
   $vote=' termine ';
}

?>
    <div class='resume'>
<p><strong><?= htmlspecialchars($suite['USER_PSEUDO']) ?></strong> le <?= htmlspecialchars($suite['DATE']) ?></p>
<p><?= nl2br(($suite['ART_CONTENT'])) ?></p>
<?php 
    if(($suite['STATUT_POST_LIBELLE']=='VOTE')AND($statut<>'ADMINISTRATEUR')){
       echo ' <i class="fa fa-thumbs-up  fa-2x "></i>
        <i class="fa fa-thumbs-down  fa-2x "></i>';   
}else{
?>
<div class='icone-admin'>
 <?php    
    if(($statut=='ADMINISTRATEUR')||(($suite['STATUT_POST_LIBELLE']=='REDACTION') ||($suite['STATUT_POST_LIBELLE']=='PROPOSE') )){

        ?>
    
                <a href="indexadmin.php?action=updateSuite&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>" title="Modifiez la suite"><i class="fa  fa-edit  fa-2x "></i></a>
                <a href="#" data-toggle="modal" data-target="#deleteModal<?= htmlspecialchars($suite['ART_ID']) ?>" title="Supprimez la suite"><i class="fa fa-remove  fa-2x"></i></a>
                <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=REDACTION&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='en cours de rédaction'><i class="fa fa-wrench  fa-2x <?= $redaction ?>"></i> </a> 
                <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=PROPOSE&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='proposer le post à la validation'><i class="fa fa-lock  fa-2x <?= $propose ?>"></i></a> 
               <?php
    }
               if($statut=='ADMINISTRATEUR'){
                 ?>
                <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=ACCEPTE&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='Validé'><i class="fa fa-thumbs-o-up  fa-2x <?= $accepte ?>"></i></a>
                <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=REFUSE&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='Refusé'><i class="fa fa-thumbs-o-down  fa-2x <?= $refuse ?>"></i></a>
                <a href='indexadmin.php?action=chgtStatutSuite&amp;libelle=VOTE&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='Vote en cours'> <i class="fa fa-balance-scale  fa-2x <?= $vote ?>"></i><span class="badge"><?= $jaime.' ' ?> J'aime</span><span class="badge"><?= $jaimepas.' ' ?>j'aime pas.</span></a>
<?php
               }
               ?>
</div>
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
    <?php
}
?>
    </div>
<?php

}elseif($suite['STATUT_POST_LIBELLE']=='VOTE'){
?>

    <div class='avoter'>
<p><strong><?= htmlspecialchars($suite['USER_PSEUDO']) ?></strong> le <?= htmlspecialchars($suite['DATE']) ?></p>
<p><?= nl2br(($suite['ART_CONTENT'])) ?></p>
<div class='icone-admin'>
  
    <a href='indexadmin.php?action=votation&amp;bulletin=YES&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='jaime'><i class="fa fa-thumbs-up  fa-2x <?= $vote ?>"></i><span class="badge"><?= $jaime?></span></a>
     <a href='indexadmin.php?action=votation&amp;bulletin=NO&amp;id=<?= htmlspecialchars($suite['ART_ID']) ?>&amp;ouv_id=<?= $_GET['ouv_id'] ?>&amp;precedent=<?= $suite['ART_PRECEDENT'] ?>&amp;auteur=<?= $suite['ART_AUTEUR'] ?>'title='jaimepas'><i class="fa fa-thumbs-down  fa-2x <?= $vote ?>"></i><span class="badge"><?= $jaimepas ?></span></a>

</div></div>
<?php    
}
}
?>
<h2>Commentaire(s)</h2>
<?php
//Fonction affichage recursif dans la vue . Ordonne les dépendances des commentaires 
//dans les tableaux $comments et $commensChild founis par le controleur
function rechercheEnfant($tableau,$id,$statut){
    echo'<ul>';
    //global $data;
    
    foreach($tableau as $cle => $element){
       
     if($tableau[$cle]['COMM_PARENT']== $id){
       echo '<i class="fa fa-arrow-down fa-2x"></i><li class = "listComm"> ';
       require('view/backend/commentViewChild.php'); 
     echo'</li><ul>';
         rechercheEnfant($tableau,$tableau[$cle]['COMM_ID'],$statut);
         echo'</ul>'; 
     }else{
  
    } } 
    echo'</ul>'; 
}
///////////Fin fonction Affichage
$commentParent = $comments->fetchAll();
$commentChild =$commentsChild -> fetchAll();
foreach($commentParent as $cle => $element)//parcours de chaque element du tab parent
    {
    require('view/backend/commentView.php');
   //$controler= new BackendControler();
   //$position= $controler->rechercheEnfant($commentChild,$commentParent[$cle]['COMM_ID'],$statut);
   rechercheEnfant($commentChild,$commentParent[$cle]['COMM_ID'],$statut); 
 echo '</div>';//fermeture container commentView.php
}

    ?>

    <?php $content = ob_get_clean(); ?>

    <?php require('view/backend/template.php'); ?>

