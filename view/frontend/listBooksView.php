<?php ob_start(); ?>



<?php
$page= 'listBookView.php';
$contentMenu = "";
$listeCarousel = '<div id="myCarousel" class="carousel slide" data-ride="carousel"><ol class="carousel-indicators">';
$carousel = '<div class="carousel-inner" role="listbox">';
$iteration=0;

while ($dataBook = $books->fetch())
{
//$auteur = 'Jean FORTEROCHE';
$description =htmlspecialchars($dataBook['OUV_DESCRIPTION']);
$bookPreface =htmlspecialchars($dataBook['OUV_PREFACE']);
$bookTitre =htmlspecialchars($dataBook['OUV_TITRE']);
$bookSoustitre =htmlspecialchars($dataBook['OUV_SOUSTITRE']); 
$title= $bookTitre;
$keywords=htmlspecialchars($dataBook['OUV_KEYWORDS']);
//$image ='public/images/couverture2.jpg';
    $contentMenu .= "<li><a href='";
    $contentMenu .= "ouvrage-";
    $contentMenu .= htmlspecialchars($bookTitre);
   
    $contentMenu .= htmlspecialchars($bookTitre);
    $contentMenu .= ".html'>";
    $contentMenu .= htmlspecialchars($bookTitre);
    $contentMenu .= "</a></li>";
///////////////////////////////////creation liste ol slider///////////////////////
    $listeCarousel .= '<li data-target="#myCarousel" data-slide-to="';
    $listeCarousel .= $iteration;
    $listeCarousel .= '" class="';
    if ($iteration == 0) {
        $listeCarousel .= 'active">';
    } else {
        $listeCarousel .= '">';
    }
    $listeCarousel .= '</li>';

////////////////////carousel/////////////////////
    $carousel .= '<div class="item ';
    ////
    if ($iteration == 0) {
        $carousel .= 'active ">';
    } else {
        $carousel .= '">';
    }

    $carousel .= '<img class="imgCarousel" src="uploads/' . htmlspecialchars($dataBook['OUV_IMAGE']) . '" alt="illustration chapitre" >';
    $carousel .= '<div class="carousel-caption">';
    $carousel .= '<a class="lienSlider" href="ouvrage-'. htmlspecialchars($bookTitre) . '-' . htmlspecialchars($dataBook['OUV_ID']) . '.html" >';
    $carousel .= '<h3>' . htmlspecialchars($bookTitre);
    $carousel .= '<br />' . htmlspecialchars($dataBook['OUV_SOUSTITRE']) . '</h3></a>';
    $carousel .= '</div></div>';


///////////////////
    $iteration = $iteration + 1;


?>   
<div class='resume col-sm-5'>
<!--</div>-->
<div class="  text-center ">
    <h1>  <?= $bookTitre ?> </h1>
<img class="imgOuvrage" src="uploads/<?= htmlspecialchars($dataBook['OUV_IMAGE'])?>" alt="illustration Ouvrage" >
</div>

<div class="  text-center ">
    <h3>PREFACE</h3>

    <?= $bookPreface ?>

</div>

<a style="float:right" href="index.php?action=listPosts&amp;ouv_id=<?= $dataBook['OUV_ID']?>" title="Liste des chapitres"><i class="fa fa-arrow-right  fa-2x "></i></a>  
</div>
<?php

}
$listeCarousel .= '</ol>';
$carousel .= '</div>';
$books->closeCursor();
?>

<?php $content = ob_get_clean(); ?>
<?php ob_start(); ?>



<?= $listeCarousel ?>
<?= $carousel ?>

<!-- Left and right controls -->
<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
</a>
<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
</a>

</div>



<?php $slider = ob_get_clean(); ?>

<?php require('view/frontend/template.php'); ?>

