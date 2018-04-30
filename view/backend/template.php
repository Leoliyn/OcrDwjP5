<!DOCTYPE html>


<html>

    <?php require_once ('view/backend/head.php'); ?>

    <body>
        <?php require_once ('view/backend/navbar.php'); ?>


        <div id="article" class="resume text-justify">

            <?= $content ?>

        </div>
        <br>

        <?php require_once ('view/backend/sectionFooter.php'); ?>
    </body>

</html>
