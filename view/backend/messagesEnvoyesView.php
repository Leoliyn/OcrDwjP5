<?php 
echo'<div class="resume">

    <h3 >   Message(s) envoyé(s)  </h3>
<div class="row">
<div class="col-sm-3">

</div>  
     ';
 echo'<form method="post" action="indexadmin.php">
 <input type="hidden" name="action" value="ordreMessagerieEnvoyes"/>

 

     
<div class="col-sm-1">

<button  class="btn-message" type="submit" name="corbeille" value="corbeille"><i class="fa fa-trash-o fa-2x " title="Mettre à la corbeille""></i></button>
     </div>
<div class="col-sm-8">

      </div></div>
';
while ($boiteEnvoi = $messagesEnvoyes->fetch())
 { 

 echo'  <span ><ul class="list-group">
      
       <li class="list-group-item "><input type="checkbox" name="listeId[]"  id="'.htmlspecialchars($boiteEnvoi['MESS_ID']).'" value="'.htmlspecialchars($boiteEnvoi['MESS_ID']).'"/>'
         . '<span>  ';
      
         echo '</span>'
         . '<span><a href="indexadmin.php?action=newMessage&destinataire='.htmlspecialchars($boiteEnvoi['DESTINATAIRE']).
    '" title = "Ecrire à '.htmlspecialchars($boiteEnvoi['DESTINATAIRE_PSEUDO']).'"> A: '.htmlspecialchars($boiteEnvoi['DESTINATAIRE_PSEUDO']).
    '  </a>  </span>
       <span ><strong>[Obj: '.htmlspecialchars($boiteEnvoi['MESS_OBJET']).']</strong></span><br /><span > Mess : '.$boiteEnvoi['MESS_CONTENT'].
    '</li></span>
      
  </ul></span>
';
  }   
  echo'</form>';


echo'</div>';


    



    




