<?php $title = 'Jean FORTEROCHE Billet simple pour l\'ALASKA '; ?>
<?php ob_start(); ?>
<?php
while ($dataBook = $books->fetch())
{
$auteur = htmlspecialchars($dataBook['OUV_AUTEUR']);
$description =htmlspecialchars($dataBook['OUV_DESCRIPTION']);
$bookPreface =htmlspecialchars($dataBook['OUV_PREFACE']);
$bookTitre =htmlspecialchars($dataBook['OUV_TITRE']);
$bookSoustitre =htmlspecialchars($dataBook['OUV_SOUSTITRE']); 
$title= $auteur." ".$bookTitre;

}
$books->closeCursor();
// EN RAISON DUN PB DE NB CONNEXION ONLINE
//$auteur = "Jean FORTEROCHE";
//$description = "En modifiant lgrement ses documents.David avait d sasseoir lorsquil avait entendu le prnom Florence. ";
//$bookPreface = "<p>Un long silence se fit dans la voiture. Le chauffeur regardait droit devant. David jeta un &oelig;il sur le compteur qui affichait 210km/h. L&rsquo;autoroute &eacute;tait d&eacute;serte. Depuis la construction de la Ligne Grande Vitesse, les gens pr&eacute;f&eacute;raient prendre les transports en communs, plus rapides et moins chers. La LGV traversait la France d'un bout &agrave; l'autre avec un arr&ecirc;t &agrave; Paris. C&rsquo;est lui aussi qui &eacute;tait &agrave; la base du dernier processeur, le sph&eacute;ro. Un processeur ayant une architecture en forme de sph&egrave;re et capable de traiter les informations &agrave; une vitesse jamais atteinte. Tous les ordinateurs en &eacute;taient &eacute;quip&eacute;s. Le cr&eacute;ateur officiel, le Dr.</p>";
//$bookTitre = "Billet simple pour l'ALASKA";
//$bookSoustitre = "Inuits inouïs";
////$title= $auteur." ".$bookTitre;
//$keywords = "écrivain, livre, Alaska, chiens, Jefferson, Galimède,Joe CASH";
//$image = 'public/images/couverture2.jpg';

while ($data = $posts->fetch()) {

    setlocale(LC_CTYPE, 'fr_FR.UTF-8');
    $titre0 = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', htmlspecialchars($data['ART_TITLE']));
    $titre = strtr($titre0, " '@ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ", "--aAAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy");
    ?>
    <li><a href="chapitre-<?= htmlspecialchars($data['ART_CHAPTER']) ?>-<?= htmlspecialchars($titre) ?>-<?= htmlspecialchars($data['ART_ID']) ?>.html">chapitre<?= htmlspecialchars($data['ART_CHAPTER']) ?>-<?= htmlspecialchars($data['ART_TITLE']) ?> </a>
    </li>
   
    <?php 
$tabPagination[]=$data['ART_CHAPTER'];
}

$posts->closeCursor();
?>



<?php $contentMenu = ob_get_clean(); ?>


<?php ob_start(); ?>


<?php
$data = $article;
$title = "Chapitre " . htmlspecialchars($data['ART_CHAPTER']) . ',' . htmlspecialchars($data['ART_TITLE']) . "- Blog de Jean FORTEROCHE";
$description = htmlspecialchars($data['ART_DESCRIPTION']);
$keywords = htmlspecialchars($data['ART_KEYWORDS']);
$image = 'uploads/' . htmlspecialchars($data['ART_IMAGE']);
?> 
    
<div class=''>

    <h3>
<?= htmlspecialchars($data['ART_TITLE']) ?>

    </h3>

    <p><em>le <?= htmlspecialchars($data['DATE_fr']) ?></em></p>
  
<?= $data['ART_CONTENT'] ?>

  
</div>
    
<div class='text-center'>
    <h2>Votre commentaire</h2>

    <form action="commentaire<?= htmlspecialchars($data['ART_ID']) ?>" method="post">
        <div>
            <input type="hidden" id="postId" name="postId" value="<?= htmlspecialchars($data['ART_ID']) ?>" />
            <label for="author">Auteur</label><br />
            <input type="text" id="author" name="author" />
        </div>
        <div>
            <label for="comment">Commentaire</label><br />
            <textarea id="comment" name="comment"></textarea>
        </div>
        <div>
            <input class="btn btn-primary" type="submit" name="envoyerComm" value="Envoyer" />
        </div>
    </form>
    <div class='text-center'>
        <a class="updown up-arrow " href="#article" data-toggle="tooltip" title="section précédente">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </a>
        
          <a class="updown down-arrow " href="#band" data-toggle="tooltip" title="section suivante">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </a>
    </div>
</div> 
<?php
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
