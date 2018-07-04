<?php
/**
 * Containier formulaire inscription  frontend
 */
?>


<div class="row">
    <form action='index.php?action=inscription&amp;ouv_id=<?= $ouvId ?>&amp;id=<?= $data['ART_ID'] ?>' method="post">

        <p>

        <div class="col-sm-6 form-group">
            <label>Nom</label><input type="texte" class="form-control" id="nom" name = "nom" value="" required>
            <label> Prénom</label><input type="texte" class="form-control" id="prenom" name = "prenom" value="" required>
            <label> Passwd</label><input type="password" class="form-control" id="password" name = "password" value="" required>
        </div>
        <div class="col-sm-6 form-group">
            <label> Pseudo</label><input type="texte" class="form-control" id="pseudo" name = "pseudo" value="" required>
            <label> E-mail</label><input type="email" class="form-control" id="email" name = "email" value="Votre e mail (wwww@xx.xx)" required>

            </br>
            <input class="btn btn-primary" type="submit" name="send" value="Je m'inscris" />
            <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
            <a href="indexadmin.php?action=dashBoard"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a> 
        </div>
        </p>  



    </form>
</div>