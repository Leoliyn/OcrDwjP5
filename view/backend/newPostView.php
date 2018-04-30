<?php $title = 'Jean FORTEROCHE'; ?>


<?php ob_start(); 
  
  $chapitre= $chapter[0] +1;  
?>

<div class='resume'>

    <h3>
        Nouvel Article

    </h3>



    <form enctype="multipart/form-data"  action='indexadmin.php?action=addPost' method='post'>

        <label> Chapitre</label><input type="texte" class="form-control" id="art_chapter" name = "art_chapter" value="<?= $chapitre ?>">
        <label> Titre</label><input type="texte" class="form-control" id="art_title" name = "art_title" value="">
        <label> Sous-titre</label><input type="texte" class="form-control" id="art_subtitle" name = "art_subtitle" value="">
        <label> Article</label><textarea style="width: 100%;" name="art_content"><br /></textarea>
        <label> Description</label><input style="width: 100%;" name="art_description" id="art_description" /><br />
        <label> Mots clés (séparés par une virgule)</label><input style="width: 100%;" name="art_keywords" id="art_keywords" /><br />   
        <label> Pour charger l'image cliquez sur Parcourir (1600x550 ou de ratio 2.909) </label> <input type="file" name="uploaded_file" /> 
        <br />
        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>

</div>




<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
