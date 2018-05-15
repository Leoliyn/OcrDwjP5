<?php $title = 'Jean FORTEROCHE'; ?>


<?php ob_start(); ?>


<?php
$data = $article;
$ouvrageTitre = $ouvrage['OUV_TITRE'];
$ouvId = $_GET['ouv_id'];
?>
<div class='resume'>

    <h3>
        Modification Article

    </h3>



    <form enctype="multipart/form-data"  action='indexadmin.php?action=majPost' method="post">
        <p><label></label></p><p><input type="hidden" id="date" name="art_date" value="<?= htmlspecialchars($data['DATE_fr']) ?>"  ></p>
        <p><label></label></p><p><input type="hidden" id="art_id" name="art_id" value="<?= htmlspecialchars($data['ART_ID']) ?>"  ></p>
       <!-- <p><label> Publier en ligne</label></p><p><input type="checkbox" id="art_desactive" name="art_desactive" checked></p>-->
         <input type ="hidden" id ="ouv_id"name="ouv_id" value ="<?= $ouvId ?>">
        <label> Ouvrage</label><input type="texte" class="form-control" id="ouvrage" name = "ouvrage" value="<?= $ouvrageTitre ?>" readOnly >
        <label> Chapitre</label><input type="texte" class="form-control" id="art_chapter" name = "art_chapter" value="<?= htmlspecialchars($data['ART_CHAPTER']) ?>">
        <label> Auteur</label><input type="texte" class="form-control" id="art_auteur" name = "art_auteur" value="<?= htmlspecialchars($data['USER_PSEUDO'])  ?>" readOnly>
        <input type="hidden" class="form-control" id="auteur" name = "auteur" value="<?= htmlspecialchars($data['ART_AUTEUR']) ?>">
        <label> Statut</label><input type="texte" class="form-control" id="statut_post" name = "statut_post" value="<?= htmlspecialchars($data['STATUT_POST_LIBELLE']) ?>"readOnly >
        <label> Titre</label><input type="texte" class="form-control" id="art_title" name = "art_title" value="<?= htmlspecialchars($data['ART_TITLE']) ?>">
        <label> Sous-titre</label><input type="texte" class="form-control" id="art_subtitle" name = "art_subtitle" value="<?= htmlspecialchars($data['ART_SUBTITLE']) ?>">
        <label> Article</label><textarea style="width: 100%;" name="art_content"><?= $data['ART_CONTENT'] ?> </textarea>
        <label> Description</label><input style="width: 100%;" name="art_description" id="art_description" value="<?= htmlspecialchars($data['ART_DESCRIPTION']) ?>" /><br />
        <label> Mots clés (séparés par une virgule)</label><input style="width: 100%;" name="art_keywords" id="art_keywords"  value="<?= htmlspecialchars($data['ART_KEYWORDS']) ?>"/><br />   
        <br />
         <?php
    $file="./uploads/".htmlspecialchars($data['ART_IMAGE']);
    if(is_file($file))
  {
    ?>
        <img src='./uploads/<?= htmlspecialchars($data['ART_IMAGE']) ?>' class="miniature" />
        <br />
        <label> Image du chapitre :<?= htmlspecialchars($data['ART_IMAGE']) ?>
            <?php
  }
            ?>
            <br />
            Pour changer ou charger une image cliquez sur Parcourir (1600x550 ou de ratio 2.909)</label> <input type="file" name="uploaded_file" /> 
        <br />


        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php?action=post&amp;id=<?= htmlspecialchars($data['ART_ID']) ?>&amp;ouv_id=<?= htmlspecialchars($ouvId) ?>"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form> 

</div>

<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
