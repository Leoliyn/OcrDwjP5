<?php
/**
 * vue Chapitre  frontend
 */
?>

<?php ob_start(); ?>

<?php

//____________________________________________________________________________________________________   
    //Fonction affichage recursif dans la vue . Ordonne les dépendances des commentaires 
//dans les tableaux $comments et $commensChild founis par le controleur
function rechercheEnfant($tableau, $id, $statut) {
    echo'<ul>';
    global $data, $ouvId;
    foreach ($tableau as $cle => $element) {

        if ($tableau[$cle]['COMM_PARENT'] == $id) {
            echo '<i class="fa fa-arrow-down fa-2x"></i><li class = "listComm"> ';
            require('view/frontend/commentViewChild.php');

            echo'</li><ul>';
            rechercheEnfant($tableau, $tableau[$cle]['COMM_ID'], $statut);
            echo'</ul>';
        } else {
            
        }
    }
    echo'</ul>';
}

///////////Fin fonction Affichage
//_______________________________________________________________________________


$page='postView.php';
$dataBook = $book[0];

$description =htmlspecialchars($dataBook['OUV_DESCRIPTION']);
$bookPreface =htmlspecialchars($dataBook['OUV_PREFACE']);
$bookTitre =htmlspecialchars($dataBook['OUV_TITRE']);
$bookSoustitre =htmlspecialchars($dataBook['OUV_SOUSTITRE']);
$ouvId =htmlspecialchars($dataBook['OUV_ID']);
$contentMenu = "";
$titleOuv=$bookTitre;
 
// Statut de l'utilisateur 
$statut= null;
if (isset($_SESSION['Rights'])) {
    $droits = unserialize($_SESSION['Rights']);


    if (isset($droits[$ouvId])) {
        $statut = $droits[$ouvId];
    }
}



while ($data = $posts->fetch()) {

            setlocale(LC_CTYPE, 'fr_FR.UTF-8');
            $titre = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $data['ART_TITLE']);
            $titre = strtr($titre, "'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ", "-aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy");
            $titre = strtr($titre, " ", "_");
            ?>

    <?php
    
    $contentMenu .= "<li><a href='";

    $contentMenu .= "chapitre-";
    $contentMenu .= htmlspecialchars($data['ART_CHAPTER']);
    $contentMenu .= "-".$titre ."-";
    $contentMenu .= htmlspecialchars($data['ART_ID']);
    $contentMenu .= "-".$ouvId;
    $contentMenu .= ".html'>";
   
    $contentMenu .= "<span class='icochap  fa-1x'>            
                        </span><span class='numeroChapitre'>N°: "; 
    $contentMenu .= htmlspecialchars($data['ART_CHAPTER']);
    $contentMenu .= " </span><span>"; 
    $contentMenu .= htmlspecialchars($data['ART_TITLE']);
    $contentMenu .= "</a></span> </li>";
 
}

$posts->closeCursor();

?>

<?php
$data = $article;
$title = "Chapitre " . htmlspecialchars($data['ART_CHAPTER']) . ',' . htmlspecialchars($data['ART_TITLE'])."-".$_SESSION['title'];
$description = htmlspecialchars($data['ART_DESCRIPTION']);
$keywords = htmlspecialchars($data['ART_KEYWORDS']);
$image = 'uploads/' . htmlspecialchars($data['ART_IMAGE']);
?> 
    

<div> <a id="hautSectionArticle"class="updown down-arrow " href="#basSectionArticle" data-toggle="tooltip" title="Bas du chapitre">
            <span class="glyphicon glyphicon-chevron-down"></span>
    </a></div>

   
 <h3> <i class="fa fa-file-o   fa-1x"></i> <?= htmlspecialchars($data['ART_TITLE']) ?></h3>

    

    <p><em>le <?= htmlspecialchars($data['DATE_fr']) ?></em></p>
  
<?= $data['ART_CONTENT'] ?>
<a  id="basSectionArticle" class="updown down-arrow " href="#hautSectionArticle" data-toggle="tooltip" title="Bas du chapitre">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </a>
</div>

 <?php 
  if($statut){
 ?>
  <h2>Votre commentaire</h2>
<?php require 'view/frontend/formulaireComment.php'; ?>

<?php
 }else{
     ?>

    <h7>Inscrivez vous pour voter et laisser vos impressions ou <a href="indexadmin.php" data-toggle="tooltip" title="Administration ">connectez-vous!</a></h7>
 
  <?php require 'view/frontend/formulaireInscription.php'; ?>   
 <?php   
   
 }

$commentParent = $comments->fetchAll();
$commentChild =$commentsChild -> fetchAll();
foreach($commentParent as $cle => $element)//parcours de chaque element du tab parent
    {
   
    require('view/frontend/commentView.php');
     rechercheEnfant($commentChild,$commentParent[$cle]['COMM_ID'],$statut); 
}

 
 while ($comment = $comments->fetch()) {
    ?>
    <div>
    <?php
    $commentSignale = $comment['SIGNALE'];
    if ($commentSignale) {
        echo '<i class="fa fa-thumbs-down  fa-2x red"></i>';
    } else {
        echo '<a href="signalement' . htmlspecialchars($comment['COMM_ID']) . "-" . htmlspecialchars($data['ART_ID']) . '" title="Cliquez pour signaler le commentaire"><i class="fa fa-thumbs-o-up  fa-2x vert"></i></a>';
    }
    ?>
        <p><strong><?= htmlspecialchars($comment['COMM_PSEUDO']) ?> </strong>a écrit le
        <?= htmlspecialchars($comment['COMM_date_fr']) ?>
        </p>
        <div>
            <p>
            <?= nl2br(htmlspecialchars($comment['COMM_TITRE'])) ?>
            </p>
            <p>
            <?= nl2br(htmlspecialchars($comment['COMM_CONTENU'])) ?>
            </p>

        </div>

    </div>

                <?php
            }
            $comments->closeCursor();
            ?>
<?php $content = ob_get_clean(); ?>
<?php ob_start(); ?>

<div id="myCarousel" class="carousel slide" data-ride="carousel">

    <div class="carousel-inner" role="listbox">
        <div class="item active">
<?php
$filename = "uploads/";
$filename .= htmlspecialchars($data['ART_IMAGE']);

if (file_exists($filename)) {
    ?>
                <img src="uploads/<?= htmlspecialchars($data['ART_IMAGE']) ?>" alt="illustration <?= htmlspecialchars($data['ART_TITLE']) ?><?= htmlspecialchars($data['ART_SUBTITLE']) ?><?= htmlspecialchars($data['ART_CHAPTER']) ?>" width="1200" height="700">
                <?php } else {
                ?>
                <img src="public/images/1.jpg" alt="illustration <?= htmlspecialchars($data['ART_TITLE']) ?>" "<?= htmlspecialchars($data['ART_SUBTITLE']) ?>" Chapitre "<?= htmlspecialchars($data['ART_CHAPTER']) ?>" width="1200" height="700">

                <?php
            }
            ?>
                 <div class="carousel-caption">
                <h2>Chapitre
            <?= htmlspecialchars($data['ART_CHAPTER']) ?>
                </h2>
                <h1>
                 <?= htmlspecialchars($data['ART_TITLE']) ?>
                </h1>
                <h3>
                 <?= htmlspecialchars($data['ART_SUBTITLE']) ?>
                </h3>

            </div>
        </div>
    </div>

</div>
 <?php $slider = ob_get_clean(); ?>
<?php require('template.php'); ?>
