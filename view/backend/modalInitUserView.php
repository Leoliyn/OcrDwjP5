<?php
/**
 * Modal confirmation  init user
 */

echo '<div class="modal fade" id="initUserModal'.$donnees['USER_ID'].'" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4><span class="fa fa-id-card"></span> initialisation des accès  de : ' . $donnees['USER_PSEUDO'] . '<br /> Etes-vous sur ? <br /></h4>
           
            </div>
            <div class="modal-body">
                <form role="form" action="indexadmin.php" method="get">
                    <div class="form-group">
                        
                        <input type="hidden" name="action" value="initUser" id="action" >
                        <input type="hidden" name="id"  value="'. $donnees['USER_ID'] . '" id="id" >
                        
                   </div>
                    <div class="form-group">
                        
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
</div>';
