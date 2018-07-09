<?php
/**
 * container visites dans  dashboard
 */
?>

<div id ='visites'class="resume">
    
    <h3>   Visites & Stats   </h3>

    <div class= resumeFrontend >
        <h5> Visites depuis le début</h5>
        <table class="table">
            <tr>
                <td>OUVRAGE</td>
                <td>TITRE</td>
                <td>Visites</td>
            </tr>

            <?php while ($visiteTotale = $totalVisit->fetch()) { ?>  
                <tr>
                    <td><?= $visiteTotale['OUV_TITRE'] ?></td>
                    <td><?= $visiteTotale['ART_TITLE'] ?></td>
                    <td><?= $visiteTotale['COMPTEUR'] ?></td>
                </tr>
            <?php } ?>
        </table></div>

    <div class='resumeFrontend'>
        <h5> Visites depuis 24 Heures</h5>
        <table class="table">
            <tr>
                <td>OUVRAGE</td>
                <td>TITRE</td>
                <td>Visites</td>
            </tr>


            <?php while ($visiteH24 = $visit24H->fetch()) { ?>

                <tr>
                    <td><?= $visiteH24['OUV_TITRE'] ?></td>
                    <td><?= $visiteH24['ART_TITLE'] ?></td>
                    <td><?= $visiteH24['COMPTEUR'] ?></td>
                </tr>
            <?php } ?>
        </table></div>


    <div class='resumeFrontend'>
        <h5> Visites depuis 7 jours</h5>
        <table class="table">
            <tr>
                <td>OUVRAGE</td>
                <td>TITRE</td>
                <td>Visites</td>
            </tr>


            <?php while ($visiteJ7 = $visit7J->fetch()) { ?>

                <tr>
                    <td><?= $visiteJ7['OUV_TITRE'] ?></td>
                    <td><?= $visiteJ7['ART_TITLE'] ?></td>
                    <td><?= $visiteJ7['COMPTEUR'] ?></td>
                </tr>
            <?php } ?>
        </table></div>

    <div class='resumeFrontend'>
        <h5> Visites depuis 1 mois</h5>
        <table class="table">
            <tr>
                <td>OUVRAGE</td><
                <td>TITRE</td>
                <td>Visites</td>
            </tr>


            <?php while ($visiteM1 = $visit1M->fetch()) { ?>

                <tr>
                    <td><?= $visiteM1['OUV_TITRE'] ?></td>
                    <td><?= $visiteM1['ART_TITLE'] ?></td>
                    <td><?= $visiteM1['COMPTEUR'] ?></td>
                </tr>
            <?php } ?>
        </table></div>
    <div class="resumeFrontend">
        <h5> Visites depuis 6 mois</h5>
        <table class="table">
            <tr>
                <td>OUVRAGE</td>
                <td>TITRE</td>
                <td>Visites</td>
            </tr>


            <?php while ($visiteM6 = $visit6M->fetch()) { ?>

                <tr>
                    <td><?= $visiteM6['OUV_TITRE'] ?></td>
                    <td><?= $visiteM6['ART_TITLE'] ?></td>
                    <td><?= $visiteM6['COMPTEUR'] ?></td>
                </tr>
            <?php } ?>
        </table></div></div>
 



    




