<?php $title = 'Jean FORTEROCHE Billet simple pour l\'ALASKA '; ?>
<?php ob_start(); ?>

<div class="modal fade" id="mailModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">

                <h4><span class="fa fa-trash"></span> <?= $info ?> </h4>
            </div>
            <div class="modal-body">
                <form role="form" action="indexadmin.php" method="get">
                    <input type="hidden" class="form-control" id="action" name="action"value="delPost">

                    <button type="submit" class="btn btn-block">Retour
                        <span class="glyphicon glyphicon-ok"></span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
