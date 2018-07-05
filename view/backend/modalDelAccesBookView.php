<?php
/**
 * fenetre modale confirmation de suppression accès ouvrage
 */


echo '<div class="modal fade" id="delAccesBookModal'.$donnees['USER_ID'].'" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4><span class="fa fa-id-card"></span> Suppression desaccès pour : ' . $donnees['USER_PSEUDO'] . '<br /> Etes-vous sur ? <br /></h4>
           
            </div>
            <div class="modal-body">
                <form role="form" action="indexadmin.php" method="get">
                    <div class="form-group">
                        
                        <input type="hidden" name="action" value="delAcces" id="action" >
                        <input type="hidden" name="user_id"  value="'. $donnees['USER_ID'] . '" id="user_id" >
                        <input type="hidden" name="ouv_id"  value="'. $donnees['OUV_ID'] . '" id="ouv_id" >
                        <input type="hidden" name="statut_id"  value="'. $donnees['p5_statut_liste_p5_STATUT_ID'] . '" id="statut_id" >
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
?>