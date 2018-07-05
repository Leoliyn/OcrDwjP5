<?php
/**
 *  Vue liste des ouvrages 
 */
?>
<?php ob_start(); ?>
<?php
$title = $_SESSION['title'];
$page = 'listBookView.php';
$contentMenu = "";
$listeCarousel = '<div id="myCarousel" class="carousel slide" data-ride="carousel"><ol class="carousel-indicators">';
$carousel = '<div class="carousel-inner" role="listbox">';
$iteration = 0;

while ($dataBook = $books->fetch()) {
    $ouvId = htmlspecialchars($dataBook['OUV_ID']);
    $description = htmlspecialchars($dataBook['OUV_DESCRIPTION']);
    $bookPreface = $dataBook['OUV_PREFACE'];
    $bookTitre = htmlspecialchars($dataBook['OUV_TITRE']);

    setlocale(LC_CTYPE, 'fr_FR.UTF-8');
    $titre = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $bookTitre);
    $titre = strtr($titre, "'@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ", "-aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy");
    $titre = strtr($titre, " ", "_");


    $bookSoustitre = htmlspecialchars($dataBook['OUV_SOUSTITRE']);

    $keywords = htmlspecialchars($dataBook['OUV_KEYWORDS']);
    $contentMenu .= "<li><a href='";
    $contentMenu .= "ouvrage-";
    $contentMenu .= htmlspecialchars($titre);
    $contentMenu .= "-";
    $contentMenu .= htmlspecialchars($ouvId);

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
    $carousel .= '<a class="lienSlider" href="ouvrage-' . htmlspecialchars($bookTitre) . '-' . htmlspecialchars($dataBook['OUV_ID']) . '.html" >';
    $carousel .= '<h3>' . htmlspecialchars($bookTitre);
    $carousel .= '</h3></a>';
    $carousel .= '</div></div>';


///////////////////
    $iteration = $iteration + 1;
    ?>  
    <div class='resumeFrontend'>
        <div class='text-center'>
  
            <h6>  <?= $bookTitre ?> </h6>
            <img class="imgOuvrage" src="uploads/<?= htmlspecialchars($dataBook['OUV_IMAGE']) ?>" alt="illustration Ouvrage" >
            <h6>PREFACE</h6>
        </div>

        <div class="  text-justify ">


    <?= $bookPreface ?>



            <a class="right" href="ouvrage-<?= htmlspecialchars($titre) ?>-<?= htmlspecialchars($dataBook['OUV_ID']) ?>.html"><i class="fa fa-arrow-right circle  fa-2x "></i></a>  
        </div></div>

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

