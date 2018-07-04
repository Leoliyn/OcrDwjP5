<?php
/**
 * Modal confirmation suppression vote
 */


echo '<div class="modal fade" id="delVoteModal'.$donneesVotes['VOTE_ID'].'" role="dialog">
    <div class="modal-dialog">



        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4><span class="fa fa-id-card"></span> Suppression de scrutin<br /> Etes-vous sur ? <br /> Les votes et les scores seront supprimés!</h4>
           
            </div>
            <div class="modal-body">
                <form role="form" action="indexadmin.php" method="get">
                    <div class="form-group">
                       
                        <input type="hidden" name="action" value="delVote" id="action" >
                        <input type="hidden" name="art_id"  value="'.$donneesVotes['p5_posts_ART_ID'].'" id="art_id" >
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
