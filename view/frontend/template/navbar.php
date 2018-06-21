<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
            </button>
            <a class="navbar-brand" href="index.php">
             Entreprise truc
<!--                <i style ="font-size:12px"><?= $bookTitre ?></i>-->
            </a>

        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
<?php if($page=='listBookView.php'){?>
                <li class="dropdown">
                    <a class="dropdown-toggle  animate slideInUp" data-toggle="dropdown" href="#">LES OUVRAGES
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">

                        <?= $contentMenu ?>
                    </ul></li>
                <?php
                echo '<li><a href="index.php">ACCUEIL</a></li>';
                echo '<li><a href="#contact">CONTACT</a></li>';
}
                ?>  
<?php if($page=='listPostsView.php'){?>
                <li class="dropdown">
                    <a class="dropdown-toggle  animate slideInUp" data-toggle="dropdown" href="#">LES CHAPITRES
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">

                        <?= $contentMenu ?>
                    </ul></li>
                <?php
                echo '<li><a href="./index.php">ACCUEIL</a></li>';
              
                echo '<li><a href="#contact">CONTACT</a></li>';
}
                ?>                     
            </ul>
        </div>
    </div>
</nav>
