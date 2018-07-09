<div id='books' class='resume'>
    <h3 >   Ouvrages   </h3>
    <table class="table">
        <tr><td>OUVRAGE ID</td><td>TITRE</td><td>SOUS TITRE</td><td>PREFACE</td><td>ENABLE</td><td>Droits d'accès</td></tr>
        <?php while ($donneesBook = $listBooks->fetch()) { ?>    
            <tr>
                <td><?= $donneesBook['OUV_ID'] ?></td>
                <td><?= $donneesBook['OUV_TITRE'] ?></td>
                <td><?= $donneesBook['OUV_SOUSTITRE'] ?></td>
                <td><?= $donneesBook['OUV_PREFACE'] ?></td>
                <td><?= $donneesBook['OUV_ENABLE'] ?></td>
                <td><a href="indexadmin.php?action=rightsBook&amp;ouv_id=<?= $donneesBook['OUV_ID'] ?>"><i class="fa fa-vcard-o  fa-2x"></i></td>
            </tr>
        <?php } ?>
    </table></div>






    




