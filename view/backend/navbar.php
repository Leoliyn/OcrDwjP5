<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>                        
            </button>
            <a class="navbar-brand" href="indexadmin.php">
                ADMINISTRATION  
                <i style ="font-size:8px">(Blog de Jean FORTEROCHE)</i>

            </a>

        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
               <?php
               if((isset($_SESSION['superAdmin']))AND($_SESSION['superAdmin']==1)){
               ?>
                <li><a href='indexadmin.php?action=dashboard'title="DashBoard"><i class="fa fa-gears fa-3x"></i></a></li>
                
                <?php
               }
               if(isset($_SESSION['userId'])){
                   
               
               
               echo' <li><a href="indexadmin.php?action=message"title="Messagerie interne"><i class="fa fa-envelope   fa-3x "></i><span class="badge">'.$_SESSION['nbMess'].'</span></a></li>';
            }   
            ?>
                <li><a href='indexadmin.php?action=changePsswd'title="Changement Password"><i class="fa fa-user   fa-3x "></i><span class="badge"><?php if(isset($_SESSION['user'])){ echo $_SESSION['user'];} ?></span></a></li>
    <?php 
        if(isset($_GET['ouv_id'])){
            $ouvId = $_GET['ouv_id'];
       echo"<li><a href='indexadmin.php?action=listPosts&amp;ouv_id=".$ouvId."'title ='Gestion des chapitres'><i class='fa fa-file-o   fa-3x '></i></a></li>";
            }
   
    ?>
                
                <li><a href='indexadmin.php?action=listBooks'title ="Gestion des ouvrages"><i class="fa fa-envira   fa-3x "></i></a></li>
                <li><a href='index.php'title ="Retour Frontend"><i class="fa fa-home   fa-3x "></i></a></li>



            </ul>
        </div>

    </div>
</nav>
