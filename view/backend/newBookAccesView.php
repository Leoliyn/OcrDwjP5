<?php $title = 'Jean FORTEROCHE'; ?>


<?php ob_start(); ?>


<div class='resume'>

    <h3>
        Nouvel Accès à l'Ouvrage </h3>

<form method="post" action='indexadmin.php?action=addAccesBook&amp;ouv_id=<?= $_GET['ouv_id'] ?>'>
   <p>
       <label for="Utilisateurs">Un accès pour qui? </label><br />
       <select class=form-control name="user" id="user">
           <?php
           while ($userList= $users->fetch()) {
         echo'  <option value="'.$userList['USER_ID'].'">'.$userList['USER_PSEUDO'].'</option>';
           }
           ?>
         </select>  <br />
       <label for="Statut">En tant que ?</label><br />
       <select class=form-control name="statut" id="statut">
            <?php
           while ($statutList= $statuts->fetch()) {
         echo'  <option value="'.$statutList['p5_STATUT_ID'].'">'.$statutList['STATUT'].'</option>';
           }
           ?>
       </select>
   </p> 
   <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
            <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
            <a href="indexadmin.php?action=dashboard"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
</form>

    

</div>




<?php $content = ob_get_clean(); ?>

<?php require('view/backend/template.php'); ?>
