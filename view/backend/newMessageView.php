<?php
/**
 * formulaire nouveau message
 */
?>
<?php ob_start(); ?>


<div class='resume'>
    <h3>Nouveau Message</h3>
    <form method="post" action='indexadmin.php'>
        <p>
            <label for="Utilisateurs">Sélectionnez un destinataire </label><br />
            <select class=form-control name="destinataire" id="destinataire">
                <?php while ($userList = $users->fetch()) : ?>
                    <option
                    <?php if ((!empty($_GET['destinataire']))AND ( $_GET['destinataire'] == $userList['USER_ID'])): ?>
                            selected
                        <?php endif; ?>
                        value="<?= $userList['USER_ID'] ?>"><?= $userList['USER_PSEUDO'] ?>

                    </option>
                <?php endwhile; ?>

            </select>  
        </p> 
        <input type="hidden"  name="action" value="addMessage" id="addMessage"/>
        <input type="hidden"  name="expediteur" value="<?= $_SESSION['userId'] ?>" id="objet"/>
        <label>Objet :  </label><br /><input type="text" class=form-control name="objet" id="objet"/>
        <label> Corps du message : </label><textarea style="width: 100%;" name="contenu"></textarea>
        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php?action=message"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php require('view/backend/template.php'); ?>
