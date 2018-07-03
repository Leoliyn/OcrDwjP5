<?php 
echo"<div class='resume'>

    <h3 >   Visites & Stats   </h3>";
echo'<div class=resumeFrontend>';
echo'<h5> Visites depuis le début</h5>';
echo '<table class="table">';
echo'<tr><td>OUVRAGE</td><td>TITRE</td><td>Visites</td></tr>';

while ($visiteTotale = $totalVisit->fetch())
        
{      
  echo'<tr><td>'.$visiteTotale['OUV_TITRE'].'</td><td>'.$visiteTotale['ART_TITLE'].'</td><td>'.$visiteTotale['COMPTEUR'].'</td></tr>';

 
}
echo'</table></div>';
echo'<div class=resumeFrontend>';
echo'<h5> Visites depuis 24 Heures</h5>';
echo '<table class="table">';
echo'<tr><td>OUVRAGE</td><td>TITRE</td><td>Visites</td></tr>';


while ($visiteH24 = $visit24H->fetch())
      
{      
  echo'<tr><td>'.$visiteH24['OUV_TITRE'].'</td><td>'.$visiteH24['ART_TITLE'].'</td><td>'.$visiteH24['COMPTEUR'].'</td></tr>';
}
echo'</table></div>';

echo'<div class=resumeFrontend>';
echo'<h5> Visites depuis 7 jours</h5>';
echo '<table class="table">';
echo'<tr><td>OUVRAGE</td><td>TITRE</td><td>Visites</td></tr>';


while ($visiteJ7 = $visit7J->fetch())
{      
  echo'<tr><td>'.$visiteJ7['OUV_TITRE'].'</td><td>'.$visiteJ7['ART_TITLE'].'</td><td>'.$visiteJ7['COMPTEUR'].'</td></tr>';
}
echo'</table></div>';
echo'<div class=resumeFrontend>';
echo'<h5> Visites depuis 1 mois</h5>';
echo '<table class="table">';
echo'<tr><td>OUVRAGE</td><td>TITRE</td><td>Visites</td></tr>';


while ($visiteM1 = $visit1M->fetch())
{      
  echo'<tr><td>'.$visiteM1['OUV_TITRE'].'</td><td>'.$visiteM1['ART_TITLE'].'</td><td>'.$visiteM1['COMPTEUR'].'</td></tr>';
}
echo'</table></div>';
echo'<div class=resumeFrontend>';
echo'<h5> Visites depuis 6 mois</h5>';
echo '<table class="table">';
echo'<tr><td>OUVRAGE</td><td>TITRE</td><td>Visites</td></tr>';


while ($visiteM6 = $visit6M->fetch())
{      
  echo'<tr><td>'.$visiteM6['OUV_TITRE'].'</td><td>'.$visiteM6['ART_TITLE'].'</td><td>'.$visiteM6['COMPTEUR'].'</td></tr>';
}
echo'</table></div>';

echo '</div>';


    



    




