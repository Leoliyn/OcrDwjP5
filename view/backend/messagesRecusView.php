<?php
/**
 * container message reçus
 * 
 */
?>

<div id='recus'class="resume">

    <h3 >   Message(s) reçu(s)  </h3>
<div class="row">
<div class="col-sm-2">

</div>  
        <div class="col-sm-1">


     </div>
<form method="post" action="indexadmin.php">
 <input type="hidden" name="action" value="ordreMessagerieRecus"/>

 
<div class="col-sm-1">

<button  class="btn-message" type="submit" name="lu" value="lu"><i class="fa fa-envelope-open-o  fa-2x" title="Marquer comme lu"> </i></button>
    
  </div>
<div class="col-sm-1">

<button  class="btn-message" type="submit" name="nonlu" value="nonlu"><i class="fa fa-envelope-o  fa-2x " title="Marquer comme non lu"></i></button>


     </div>
 
     
<div class="col-sm-1">

<button  class="btn-message" type="submit" name="corbeille" value="corbeille"><i class="fa fa-trash-o fa-2x " title="Mettre à la corbeille"></i></button>
     </div>
<div class="col-sm-6">

      </div></div>

<?php while ($boiteReception = $messagesReçus->fetch()): ?>
 <span ><ul class="list-group">
      
       <li class="list-group-item "><input type="checkbox" name="listeId[]"  id="<?= htmlspecialchars($boiteReception['MESS_ID']) ?>" value="<?= htmlspecialchars($boiteReception['MESS_ID']) ?>"/>
       <span>
 <?php if(htmlspecialchars($boiteReception['MESS_LU'])): ?>
<i class="fa fa-envelope-open-o  fa-1x" title="lu"></i>
     <?php $lu= 'lu'; ?>
 <?php else: ?>
 <?php $lu='nonlu'; ?>
 <i class="fa fa-envelope  fa-1x" title="non lu"></i>
<?php endif; ?>       
     </span>
      <span><a href="indexadmin.php?action=newMessage&destinataire=<?= htmlspecialchars($boiteReception['EXPEDITEUR']) ?>"title ="Repondre à <?= htmlspecialchars($boiteReception['USER_PSEUDO']) ?>"> De: <?= htmlspecialchars($boiteReception['USER_PSEUDO'])?></a>
      </span> 
       <span class ="<?= $lu ?>"><strong>[Obj: <?= htmlspecialchars($boiteReception['MESS_OBJET']) ?>]</strong>
       </span><br />
       <span class ="<?= $lu ?>"> Mess : <?= $boiteReception['MESS_CONTENT'] ?> </span>
</li>

     
  </ul></span>

  <?php endwhile; ?>  
</form>


</div>

