<?php
/**
 * Barre de navigation frontend
 */
?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
            </button>
            <a class="navbar-brand" href="index.php">
                <?= $_SESSION['title'] ?>
                <i style ="font-size:12px"><?php if (isset($titleOuv)) echo $titleOuv; ?></i>
            </a>

        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <?php if ($page == 'listBookView.php'): ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle  animate slideInUp" data-toggle="dropdown" href="#">LES OUVRAGES
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?= $contentMenu ?>
                        </ul></li>
                    <li><a href="index.php">ACCUEIL</a></li>
                    <li><a href="#contact">CONTACT</a></li>
                <?php endif; ?>  
                <?php if (($page == 'listPostsView.php') || ($page == 'postView.php')): ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle  animate slideInUp" data-toggle="dropdown" href="#">LES CHAPITRES
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu">

                            <?= $contentMenu ?>
                        </ul></li>

                    <li><a href="./index.php">ACCUEIL</a></li>

                    <li><a href="#contact">CONTACT</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="indexadmin.php"><i class="fa fa-industry fa-2x"></i></a></li>

                <?php endif; ?>                     
            </ul>
        </div>
    </div>
</nav>
