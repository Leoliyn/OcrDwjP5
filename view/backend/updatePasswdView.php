<?php $title = 'Jean FORTEROCHE'; ?>


<?php ob_start(); ?>

<?php
if (isset($message)) {
    echo $message;
}
?>

<div class='resume'>

    <h3>
        Modification Mot de passe 

    </h3>

    <form action='indexadmin.php?action=changePsswd' method="post">

        <p><label></label></p><p><input type="hidden" id="ouv_id" name="ouv_id" value="<?= htmlspecialchars($data['OUV_ID']) ?>"  ></p>
        <label>Mot de passe actuel</label><input type="password" class="form-control" id="oldmdp" name = "oldmdp">
        <label> Nouveau Mot de passe </label><input type="password" class="form-control" id="mdp" name = "mdp">
        <label>Resaisir Nouveau Mot de passe </label><input type="password" class="form-control" id="remdp" name = "remdp">
        <input class="btn btn-primary" type="submit" name="send" value="Envoyer" />
        <input class="btn btn-primary" type="reset" name="reset" value="Reset" />
        <a href="indexadmin.php"><input class="btn btn-primary" type="button" name="retour" value="Retour" /></a>
    </form>
<div><button class="btn2" data-toggle="modal" data-target="#myModal2">Mot de passe oublié ?</button></div>

<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">

        <!-- Modal2 content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4><span class="fa fa-id-card"></span> Mot de passe oublié</h4>
                Un mail vous parviendra avec la marche à suivre ...  
            </div>
            <div class="modal-body">
                <form role="form" action="indexadmin.php" method="post">
                    <div class="form-group">
                        <label for="usrname"><span class="fa fa-user fa2x"></span> Votre adresse mail</label>
                        <input type="email" class="form-control" id="email" name="emailForget"placeholder="Votre mail xxxx@xx.xx" required>
                    </div>
                    
                    <button type="submit" class="btn btn-block">Envoyez! 
                        <span class="glyphicon glyphicon-ok"></span>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove"></span> Annulation
                </button>

            </div>
        </div>
    </div>
</div>


    <?php $content = ob_get_clean(); ?>

    <?php require('view/backend/template.php'); ?>
