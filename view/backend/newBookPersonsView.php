<?php $title = 'Jean FORTEROCHE'; ?>


<?php ob_start(); ?>


<?php
$data = $book;
?>
<div class='resume'>

    <h3>
        Personnages de l'ouvrage

    </h3>

    <form action='indexadmin.php?action=majPersons' method="post">

        <p><label></label></p><p><input type="hidden" id="ouv_id" name="ouv_id" value="<?= htmlspecialchars($data['OUV_ID']) ?>"  ></p>
      
       <label> Titre</label><input type="texte" class="form-control" id="person1_titre" name = "person1_titre" value="">
       
        <label> Description</label><input style="width: 100%;" name="person1_description" id="person1_description" value="" /><br />
       
           <br />
        
            <br />
            Pour changer ou charger une image cliquez sur Parcourir (1600x550 ou de ratio 2.909)</label> <input type="file" name="uploaded_file" /> 
        <br />

        
        
        
        
        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php?action=book&amp;id=<?= $data['OUV_ID'] ?>"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>





<?php $content = ob_get_clean(); ?>

    <?php require('view/backend/template.php'); ?>
