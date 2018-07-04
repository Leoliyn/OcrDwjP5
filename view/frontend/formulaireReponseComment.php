<?php
/**
 * formulaire reponse commentaire  frontend
 */
?>

<form action="index.php?action=addComment" method="post">
    <input class="form-control"type="hidden" id="postId" name="postId" value="<?= htmlspecialchars($_GET['id']) ?>" />

    <input class="form-control" type="hidden" id="author" name="author" value="<?= $_SESSION['user'] ?> "readOnly/>
    <input class="form-control" type="hidden" id="authorId" name="authorId" value="<?= $_SESSION['userId'] ?> "readOnly/>
    <?php
    $idcom = null;
    if ($tableau[$cle]['COMM_ID']) {
        $idcom = htmlspecialchars($tableau[$cle]['COMM_ID']);
    } else {
        $idcom = 0;
    }
    ?>
     <input class="form-control" type="hidden" id="precedent" name="precedent" value="<?= $idcom ?> "readOnly/>
    <input class="form-control" type="hidden" id="ouvId" name="ouvId" value="<?= $ouvId ?> "readOnly/>

       
   
    <label for="comment">Saisissez votre commentaire</label><br />
    <textarea class="form-control" id="comment" name="comment"></textarea><br />
             
     
    <input class="btn btn-xs" type="submit" name="envoyerComm" value="Envoyez" /> 
      
    
</form>
       
 
           
           

