<?php ob_start(); ?>


<?php
$data = $book;
?>
<div class='resume'>

    <h3>
        Modification Ouvrage

    </h3>

    <form <form enctype="multipart/form-data"   action='indexadmin.php?action=majBook' method="post">

        <p><label></label></p><p><input type="hidden" id="ouv_id" name="ouv_id" value="<?= htmlspecialchars($data['OUV_ID']) ?>"  ></p>
       <!-- <label> Auteur</label><input type="texte" class="form-control" id="ouv_auteur" name = "ouv_auteur" value="<?= htmlspecialchars($data['OUV_AUTEUR']) ?>">
       --> <label> Titre</label><input type="texte" class="form-control" id="ouv_titre" name = "ouv_titre" value="<?= htmlspecialchars($data['OUV_TITRE']) ?>">
        <label> Sous-titre</label><input type="texte" class="form-control" id="ouv_soustitre" name = "ouv_soustitre" value="<?= htmlspecialchars($data['OUV_SOUSTITRE']) ?>">
        <label> Préface</label><textarea style="width: 100%;" name="ouv_preface"><?= htmlspecialchars($data['OUV_PREFACE']) ?> </textarea>
        <label> Description</label><input style="width: 100%;" name="ouv_description" id="ouv_description" value="<?= htmlspecialchars($data['OUV_DESCRIPTION']) ?>" /><br />
        <label> Mots clés (séparés par une virgule)</label><input style="width: 100%;" name="ouv_keywords" id="ouv_keywords"  value="<?= htmlspecialchars($data['OUV_KEYWORDS']) ?>"/><br />   
           <br />
         <?php
    $file="./uploads/".htmlspecialchars($data['OUV_IMAGE']);
    if(is_file($file))
  {
    ?>
        <img src='./uploads/<?= htmlspecialchars($data['OUV_IMAGE']) ?>' class="miniature" />
        <br />
       Image du chapitre :<?= htmlspecialchars($data['OUV_IMAGE']) ?>
            <?php
  }else{
      echo'<label>';
  }
            ?>
            <br />
             <label> Pour changer ou charger une image cliquez sur Parcourir (1600x550 ou de ratio 2.909)</label>
             <input type="file" name="uploaded_imageBook" /> 
        <br />

        
        
        
        
        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php?action=book&amp;id=<?= $data['OUV_ID'] ?>"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>





<?php $content = ob_get_clean(); ?>

    <?php require('view/backend/template.php'); ?>
