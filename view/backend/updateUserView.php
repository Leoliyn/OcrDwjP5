<?php
/**
 *  Formulaire Modification utilisateur
 */
?>
<?php ob_start(); ?>
<?php $data = $user; ?>

<div class='resume'>
    <h3>Modification Utilisateur</h3>
    <form action='indexadmin.php?action=majUser' method="post">

        <p><label></label></p><p><input type="hidden" id="user_id" name="user_id" value="<?= htmlspecialchars($data['USER_ID']) ?>"  >

            <label>NOM</label><input type="texte" class="form-control" id="nom" name = "nom" value="<?= htmlspecialchars($data['USER_NAME']) ?>">
            <label> PRENOM</label><input type="texte" class="form-control" id="prenom" name = "prenom" value="<?= htmlspecialchars($data['USER_LASTNAME']) ?>">
            <label> PSEUDO</label><input type="texte" class="form-control" id="pseudo" name = "pseudo" value="<?= htmlspecialchars($data['USER_PSEUDO']) ?>">
            <label> E-MAIL</label><input type="email" class="form-control" id="email" name = "email" value="<?= htmlspecialchars($data['USER_MAIL']) ?>">
            <label> ROOT</label><input type="number" class="form-control"  name = "superviseur" value="<?= htmlspecialchars($data['ROOT']) ?>">
        </p>  
        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php?action=dashboard"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>
    <?php $content = ob_get_clean(); ?>
    <?php require('view/backend/template.php'); ?>
