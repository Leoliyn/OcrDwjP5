<?php ob_start(); ?>



<div class='resume'>

    <h3>
        Nouvel Utilisateur

    </h3>

    <form action='indexadmin.php?action=addUser' method="post">

        <p>
            <label>NOM</label><input type="texte" class="form-control" id="nom" name = "nom" value="" required>
            <label> PRENOM</label><input type="texte" class="form-control" id="prenom" name = "prenom" value="" required>
            <label> PSEUDO</label><input type="texte" class="form-control" id="pseudo" name = "pseudo" value="" required>
            <label> E-MAIL</label><input type="email" class="form-control" id="email" name = "email" value="wwww@xx.xx" required>
            <label> PASSWD</label><input type="password" class="form-control" id="passwd" name = "passwd" value="" required>
            <label> ROOT</label><input type="number" class="form-control"  name = "superviseur" value="0">
        </p>  



        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php?action=dashBoard"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>





<?php $content = ob_get_clean(); ?>

    <?php require('view/backend/template.php'); ?>
