<?php
// Modal  integration suite
echo '<div class="modal fade" id="integrationModal'.$donneesVotes['VOTE_ID'].'" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4><span class="fa fa-id-card"></span> Intégration d\'une suite<br /> Etes-vous sur ? <br /> Les votes et les scores seront supprimés!</h4>
           
            </div>
            <div class="modal-body">
                <form role="form" action="indexadmin.php" method="get">
                    <div class="form-group">
                        '.$donneesVotes['OUVRAGE_OUV_ID'].'<br />
            '.$donneesVotes['USER_PSEUDO'].' <br /> 
                '.$donneesVotes['VOTE_ID'].'<br />
                
               '.$donneesVotes['p5_posts_ART_ID'].' <br />
                <br />
                        <input type="hidden" name="action" value="concatSuite" id="action" >
                        <input type="hidden" name="suiteId"  value="'.$donneesVotes['p5_posts_ART_ID'].'" id="suiteId" >
                        <input type="hidden" name="auteur" value="'.$donneesVotes['USER_PSEUDO'].'" id="auteur" >
                        <input type="hidden" name="ouv_id" value="'. $donneesVotes['OUVRAGE_OUV_ID'].'" id="ouv_id" >   
                        <input type="hidden" name="vote_id" value="'.$donneesVotes['VOTE_ID'].'" id="vote_id" > ';
      
                echo'    </div>
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
