<!DOCTYPE html>


<html lang="fr">

    <?php require_once 'view/frontend/template/head.php'; ?>

    <body>
        <?php require_once 'view/frontend/template/navbar.php'; ?>
        <?php require_once 'view/frontend/template/slider.php'; ?>

        <?php require_once 'view/frontend/template/sectionArticle.php'; ?>
        <?php
        if ($page == 'listPostsView.php') {
            require_once 'view/frontend/template/sectionChapitreListe.php';
        }
        ?>
        <?php require_once 'view/frontend/template/sectionContact.php'; ?>
        <?php require_once 'view/frontend/template/sectionFooter.php'; ?>
    </body>

</html>



