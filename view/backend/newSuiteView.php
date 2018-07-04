<?php
/**
 * Formulaire nouvel suite 
 */
?>
<?php ob_start(); ?> 
 <?php 
  $chapitre= 0;
  $ouvrageTitre = $ouvrage['OUV_TITRE'];
  $ouvId = $_GET['ouv_id'];
  $idPrecedent=$_GET['id']
?>

<div class='resume'>

    <h3>
        Nouvelle suite

    </h3>



    <form enctype="multipart/form-data"  action='indexadmin.php?action=addSuite' method='post'>
        <input type ="hidden" id ="ouv_id"name="ouv_id" value ="<?= $ouvId ?>">
        <label> Ouvrage</label><input type="texte" class="form-control" id="ouvrage" name = "ouvrage" value="<?= $ouvrageTitre ?>" readOnly >
         <label> Chapitre</label><input type="texte" class="form-control" id="art_chapter" name = "art_chapter" value="<?= $chapitre ?>">
         <label> Auteur</label><input type="texte" class="form-control" id="art_auteur" name = "art_auteur" value="<?= $_SESSION['user'] ?>" readOnly>
         <input type="hidden" class="form-control" id="auteur" name = "auteur" value="<?= $_SESSION['userId'] ?>">
         <input type="hidden" class="form-control" id="precedent" name = "precedent" value="<?= $idPrecedent ?>">
         <label> Statut du post</label><input type="texte" class="form-control" id="statut_post" name = "statut_post" value="REDACTION" readOnly>
         <label> Article</label><textarea style="width: 100%;" name="art_content"><br /></textarea>
    <br />
        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>

</div>
<?php $content = ob_get_clean(); ?>
<?php require('view/backend/template.php'); ?>
