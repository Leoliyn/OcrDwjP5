
<footer class="text-center">
    <?php
    if(isset($_SESSION['userId'])){
    
    echo'    <a class="up-arrow" href="#myCarousel" data-toggle="tooltip" title="HAUT">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a><br><br>
<p><a href="indexadmin.php?action=deconnexion" data-toggle="tooltip" title="Administration ">Déconnexion</a></p>';
    }
    ?>
</footer>