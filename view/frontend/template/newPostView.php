<?php $title = 'Jean FORTEROCHE'; ?>


<?php ob_start(); ?>


<div class='resume'>

    <h3>
        Nouvel Article

    </h3>



    <form action='indexadmin.php?action=addPost' method="post">


        <p><!--<label> Publier en ligne</label></p><p><input type="checkbox" id="art_desactive" name="art_desactive" value=1 ></p>-->
            <label> Chapitre</label><input type="texte" class="form-control" id="art_chapter" name = "art_chapter" value="">
            <label> Titre</label><input type="texte" class="form-control" id="art_title" name = "art_title" value="">
            <label> Sous-titre</label><input type="texte" class="form-control" id="art_subtitle" name = "art_subtitle" value="">
            <label> Article</label><textarea style="width: 100%;" name="art_content"><br /></textarea>

            <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
            <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
            <a href="indexadmin.php"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>
</div>




<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
