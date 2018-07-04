<?php
/**
 * Section footer backend
 */
?>
<footer class="text-center">
  
     <?php if(isset($_SESSION['userId'])): ?>
   
  <a class="up-arrow" href="#myCarousel" data-toggle="tooltip" title="HAUT">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a><br><br>
<p><a href="indexadmin.php?action=deconnexion" data-toggle="tooltip" title="Administration ">Déconnexion</a></p>
    <?php endif;?>
</footer>