<?php ob_start(); ?>


<!--    <div class="col-sm-3 col-xs-12">
         <img  class= 'couverture' src='public/images/couverture2.jpg' title='couverture ouvrage' />
-->
<?php

$page='listPostsView.php';
$dataBook = $book[0];

$ouvId =$_GET['ouv_id'];
//$auteur = htmlspecialchars($dataBook['OUV_AUTEUR']);
$description =htmlspecialchars($dataBook['OUV_DESCRIPTION']);
$bookPreface =$dataBook['OUV_PREFACE'];
$bookTitre =htmlspecialchars($dataBook['OUV_TITRE']);
$bookSoustitre =htmlspecialchars($dataBook['OUV_SOUSTITRE']); 
$keywords=htmlspecialchars($dataBook['OUV_KEYWORDS']);
$image ='public/images/couverture2.jpg';
$titleOuv=$bookTitre;
//$book->closeCursor();

?>   

<!--</div>-->
<div class="  text-center ">
    <h1>  <?= $bookTitre ?>  </h1>

</div>

<div class="  text-center ">
    <h3>PREFACE</h3>

    <?= $bookPreface ?>

</div>
<div class="" >
<!--    <ul>-->
        <?php
        $contentMenu = "";
        $contentMenuResume="";
        $iteration = 0;
        $listeCarousel = '<div id="myCarousel" class="carousel slide" data-ride="carousel"><ol class="carousel-indicators">';
        $carousel = '<div class="carousel-inner" role="listbox">';

        while ($data = $posts->fetch()) {

            setlocale(LC_CTYPE, 'fr_FR.UTF-8');
            $titre = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $data['ART_TITLE']);
            $titre = strtr($titre, "'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ", "-aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy");
            $titre = strtr($titre, " ", "_");
            ?>

    <?php
    $contenuChapitre= $data['ART_CONTENT_RESUME'];
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
    $contentMenu .= "</span><span>"; 
    $contentMenu .= htmlspecialchars($data['ART_TITLE']);
    $contentMenu.="</a></span></li>";
    //
    $contentMenuResume .= "<li><a href='";
    $contentMenuResume .= "chapitre-";
    $contentMenuResume .= htmlspecialchars($data['ART_CHAPTER']);
    $contentMenuResume .= "-".$titre ."-";
    $contentMenuResume .= htmlspecialchars($data['ART_ID']);
    $contentMenuResume .= "-".$ouvId;
    $contentMenuResume .= ".html'>";
    $contentMenuResume .= "<span class='icochap  fa-1x'>            
          </span><span class='numeroChapitre'>N°: "; 
    $contentMenuResume .= htmlspecialchars($data['ART_CHAPTER']);
    $contentMenuResume .= "</span><span>"; 
    $contentMenuResume .= htmlspecialchars($data['ART_TITLE']);
    $contentMenuResume.="</a></span></li>";
//
    $contentMenuResume .= "</a></span> <div class='item resumeFrontend'>".$contenuChapitre."(...)</div></li>";
   
////////////////////////////////////creation liste ol slider///////////////////////
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

    $carousel .= '<img class="imgCarousel" src="uploads/' . htmlspecialchars($data['ART_IMAGE']) . '" alt="illustration chapitre" >';
    $carousel .= '<div class="carousel-caption">';
    $carousel .= '<a class="lienSlider" href="chapitre-' . htmlspecialchars($data['ART_CHAPTER']) . '-' . htmlspecialchars($titre) . '-' . htmlspecialchars($data['ART_ID']) . '.html" ><h3>' . $data['ART_TITLE'] . " Chapitre " . $data['ART_CHAPTER'];
    $carousel .= '<br />' . htmlspecialchars($data['ART_SUBTITLE']) . '</h3></a>';
    $carousel .= '</div></div>';


///////////////////
    $iteration = $iteration + 1;
}
$listeCarousel .= '</ol>';
$carousel .= '</div>';
$posts->closeCursor();
?>
    <!--</ul>-->



</div>


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

