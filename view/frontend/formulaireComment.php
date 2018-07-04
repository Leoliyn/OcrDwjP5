<?php
/**
 * formulaire saise du commentaire
 */
?>


<form action="index.php?action=addComment" method="post">
       
            <input class="form-control"type="hidden" id="postId" name="postId" value="<?= htmlspecialchars($_GET['id']) ?>" />

            <input class="form-control" type="hidden" id="author" name="author" value="<?= $_SESSION['user'] ?> "readOnly/>
            <input class="form-control" type="hidden" id="authorId" name="authorId" value="<?= $_SESSION['userId'] ?> "readOnly/>
       
            <input class="form-control" type="hidden" id="precedent" name="precedent" value="0"readOnly/>
  <input class="form-control" type="hidden" id="ouvId" name="ouvId" value="<?= $ouvId ?> "readOnly/>
            <label for="comment">Saisissez votre commentaire</label><br />
            <textarea class="form-control" id="comment" name="comment"></textarea><br />
         <input class="btn btn-primary" type="submit" name="envoyerComm" value="Envoyez" /> 
</form>
  

