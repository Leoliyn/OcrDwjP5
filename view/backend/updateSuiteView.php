<?php ob_start(); ?>


<?php
$data = $suite;
$ouvrageTitre = $ouvrage['OUV_TITRE'];
$ouvId = $_GET['ouv_id'];
?>
<div class='resume'>

    <h3>
        Modification Suite

    </h3>



    <form enctype="multipart/form-data"  action='indexadmin.php?action=majSuite' method="post">
        <p><label></label></p><p><input type="hidden" id="date" name="art_date" value="<?= htmlspecialchars($data['DATE_fr']) ?>"  ></p>
        <p><label></label></p><p><input type="hidden" id="art_id" name="art_id" value="<?= htmlspecialchars($data['ART_ID']) ?>"  ></p>
       <!-- <p><label> Publier en ligne</label></p><p><input type="checkbox" id="art_desactive" name="art_desactive" checked></p>-->
        <input type ="hidden" id ="precedent" name="precedent" value ="<?= htmlspecialchars($data['ART_PRECEDENT']) ?>">
        <input type ="hidden" id ="ouv_id" name="ouv_id" value ="<?= $ouvId ?>">
        <label> Ouvrage</label><input type="texte" class="form-control" id="ouvrage" name = "ouvrage" value="<?= $ouvrageTitre ?>" readOnly >
    
        <label> Auteur</label><input type="texte" class="form-control" id="art_auteur" name = "art_auteur" value="<?= htmlspecialchars($data['USER_PSEUDO'])  ?>" readOnly>
        <input type="hidden" class="form-control" id="auteur" name = "auteur" value="<?= htmlspecialchars($data['ART_AUTEUR']) ?>">
        <label> Statut</label><input type="texte" class="form-control" id="statut_post" name = "statut_post" value="<?= htmlspecialchars($data['STATUT_POST_LIBELLE']) ?>"readOnly >
        <label> Article</label><textarea style="width: 100%;" name="art_content"><?= $data['ART_CONTENT'] ?> </textarea>
        <br />
   
            <br />
          


        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php?action=post&amp;id=<?= htmlspecialchars($data['ART_PRECEDENT']) ?>&amp;ouv_id=<?= htmlspecialchars($ouvId) ?>"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form> 

</div>

<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
