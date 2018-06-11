<?php 
echo'<div class="resume">

    <h3 >   Message(s) reçu(s)  </h3>
<div class="row">
<div class="col-sm-2">

</div>  
        <div class="col-sm-1">


     </div>';
 echo'<form method="post" action="indexadmin.php">
 <input type="hidden" name="action" value="ordreMessagerieRecus"/>

 
<div class="col-sm-1">

<button  class="btn-message" type="submit" name="lu" value="lu"><i class="fa fa-envelope-open-o  fa-2x" title="Marquer comme lu""> </i></button>

    
  </div>
<div class="col-sm-1">

<button  class="btn-message" type="submit" name="nonlu" value="nonlu"><i class="fa fa-envelope-o  fa-2x " title="Marquer comme non lu"></i></button>


     </div>
 
     
<div class="col-sm-1">

<button  class="btn-message" type="submit" name="corbeille" value="corbeille"><i class="fa fa-trash-o fa-2x " title="Mettre à la corbeille""></i></button>
     </div>
<div class="col-sm-6">

      </div></div>
';
while ($boiteReception = $messagesReçus->fetch())
 { 

 echo'  <span ><ul class="list-group">
      
       <li class="list-group-item "><input type="checkbox" name="listeId[]"  id="'.htmlspecialchars($boiteReception['MESS_ID']).'" value="'.htmlspecialchars($boiteReception['MESS_ID']).'"/>'
         . '<span>  ';
 if(htmlspecialchars($boiteReception['MESS_LU'])){
     echo ' <i class="fa fa-envelope-open-o  fa-1x" title="lu"></i>';
     $lu= 'lu';
 }else {
$lu='nonlu';
 echo '<i class="fa fa-envelope  fa-1x" title="non lu"></i>';
 }       
         echo '</span>'
         . '<span><a href="indexadmin.php?action=newMessage&destinataire='.htmlspecialchars($boiteReception['EXPEDITEUR']).
    '"title ="Repondre à '.htmlspecialchars($boiteReception['USER_PSEUDO']).
    '"> De: '.htmlspecialchars($boiteReception['USER_PSEUDO']).
    '</a> </span> 
       <span class ="'.$lu.'"><strong>[Obj: '.htmlspecialchars($boiteReception['MESS_OBJET']).']</strong></span><br /><span class ="'.$lu.'"> Mess : '.$boiteReception['MESS_CONTENT'].
    '</li></span>
      
  </ul></span>
';
  }   
  echo'</form>';


echo'</div>';


    



    




